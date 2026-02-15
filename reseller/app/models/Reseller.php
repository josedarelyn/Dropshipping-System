<?php
/**
 * Reseller Model
 */

class Reseller extends Model {
    protected $table = 'reseller_profiles';
    
    /**
     * Get reseller by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.profile_image, u.status as user_status
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.user_id
                WHERE r.user_id = :user_id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Get reseller by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE reseller_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get reseller with user details
     */
    public function getWithUserDetails($id) {
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.profile_image, u.status as user_status,
                       u.created_at as registered_at
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.user_id
                WHERE r.reseller_id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Update reseller stats
     */
    public function updateStats($resellerId, $saleAmount, $commissionAmount) {
        $sql = "UPDATE {$this->table} 
                SET total_sales = total_sales + :sale_amount,
                    total_commission = total_commission + :commission_amount
                WHERE reseller_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'sale_amount' => $saleAmount,
            'commission_amount' => $commissionAmount,
            'id' => $resellerId
        ]);
    }
    
    /**
     * Get reseller dashboard stats
     */
    public function getDashboardStats($resellerId) {
        // Get reseller data
        $sql = "SELECT total_sales, total_commission, wallet_balance 
                FROM {$this->table} 
                WHERE reseller_id = :reseller_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $resellerData = $stmt->fetch();
        
        // Total orders
        $sql = "SELECT COUNT(*) as total_orders
                FROM orders 
                WHERE reseller_id = :reseller_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $orderData = $stmt->fetch();
        
        // Pending commissions (from commission_transactions with transaction_type='earned')
        $sql = "SELECT COALESCE(SUM(amount), 0) as pending_commission
                FROM commission_transactions 
                WHERE reseller_id = :reseller_id 
                AND transaction_type = 'earned' 
                AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $commissionData = $stmt->fetch();
        
        // Total earned (completed commission transactions)
        $sql = "SELECT COALESCE(SUM(amount), 0) as total_earned
                FROM commission_transactions 
                WHERE reseller_id = :reseller_id 
                AND transaction_type = 'earned' 
                AND status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $earnedData = $stmt->fetch();
        
        // Total withdrawn
        $sql = "SELECT COALESCE(SUM(amount), 0) as total_withdrawn
                FROM withdrawal_requests 
                WHERE reseller_id = :reseller_id AND status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $withdrawalData = $stmt->fetch();
        
        return [
            'total_orders' => $orderData['total_orders'] ?? 0,
            'total_sales' => $resellerData['total_sales'] ?? 0,
            'pending_commission' => $commissionData['pending_commission'] ?? 0,
            'wallet_balance' => $resellerData['wallet_balance'] ?? 0,
            'total_earned' => $earnedData['total_earned'] ?? 0,
            'total_withdrawn' => $withdrawalData['total_withdrawn'] ?? 0
        ];
    }
    
    /**
     * Get recent orders
     */
    public function getRecentOrders($resellerId, $limit = 5) {
        $sql = "SELECT o.*, u.full_name as customer_name
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.user_id
                WHERE o.reseller_id = :reseller_id
                ORDER BY o.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':reseller_id', $resellerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Update reseller profile
     */
    public function update($resellerId, $data) {
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE reseller_id = :reseller_id";
        $data['reseller_id'] = $resellerId;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Get sales report data
     */
    public function getSalesReport($resellerId) {
        // Monthly sales for the last 6 months
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_orders,
                    SUM(total_amount) as total_sales,
                    SUM(commission_amount) as total_commission
                FROM orders
                WHERE reseller_id = :reseller_id
                AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $monthlySales = $stmt->fetchAll();
        
        // Sales by status
        $sql = "SELECT 
                    order_status,
                    COUNT(*) as count,
                    SUM(total_amount) as total
                FROM orders
                WHERE reseller_id = :reseller_id
                GROUP BY order_status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $salesByStatus = $stmt->fetchAll();
        
        // Top selling products
        $sql = "SELECT 
                    p.product_name,
                    p.product_image,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.subtotal) as revenue
                FROM order_items oi
                INNER JOIN products p ON oi.product_id = p.product_id
                INNER JOIN orders o ON oi.order_id = o.order_id
                WHERE o.reseller_id = :reseller_id
                GROUP BY oi.product_id
                ORDER BY total_sold DESC
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $topProducts = $stmt->fetchAll();
        
        return [
            'monthly' => $monthlySales,
            'byStatus' => $salesByStatus,
            'topProducts' => $topProducts
        ];
    }
}
