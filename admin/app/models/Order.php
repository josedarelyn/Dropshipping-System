<?php
/**
 * Order Model
 */

class Order extends Model {
    protected $table = 'orders';
    private $columns = null;
    
    // Detect and cache table columns
    private function getColumns() {
        if ($this->columns === null) {
            if (!$this->tableExists()) {
                $this->columns = [];
                return $this->columns;
            }
            try {
                $stmt = $this->db->query("DESCRIBE {$this->table}");
                $this->columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $this->columns = [];
            }
        }
        return $this->columns;
    }
    
    // Check if table exists
    private function tableExists() {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Check if column exists
    private function hasColumn($columnName) {
        return in_array($columnName, $this->getColumns());
    }
    
    // Get the appropriate ID column
    private function getIdColumn() {
        $columns = $this->getColumns();
        if (in_array('order_id', $columns)) return 'order_id';
        return 'id';
    }
    
    // Get the status column name (could be 'status', 'order_status', etc.)
    private function getStatusColumn() {
        $columns = $this->getColumns();
        if (in_array('order_status', $columns)) return 'order_status';
        if (in_array('status', $columns)) return 'status';
        return null; // No status column
    }
    
    // Get the appropriate user ID column from users table
    private function getUserIdColumn() {
        try {
            $stmt = $this->db->query("DESCRIBE users");
            $userColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return in_array('user_id', $userColumns) ? 'user_id' : 'id';
        } catch (Exception $e) {
            return 'id';
        }
    }
    
    // Get orders with customer info
    public function getOrdersWithCustomer() {
        if (!$this->tableExists()) {
            return [];
        }
        
        $orderIdCol = $this->getIdColumn();
        $userIdCol = $this->getUserIdColumn();
        
        $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email, u.phone as customer_phone,
                       pt.payment_method, pt.status as payment_tx_status, pt.reference_number
                FROM {$this->table} o
                LEFT JOIN users u ON o.customer_id = u.{$userIdCol}
                LEFT JOIN payment_transactions pt ON o.{$orderIdCol} = pt.order_id
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get order by ID with details
    public function getOrderDetails($orderId) {
        $orderIdCol = $this->getIdColumn();
        $userIdCol = $this->getUserIdColumn();
        
        $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email, 
                       u.phone as customer_phone
                FROM {$this->table} o
                LEFT JOIN users u ON o.customer_id = u.{$userIdCol}
                WHERE o.{$orderIdCol} = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get payment transaction for an order
    public function getPaymentTransaction($orderId) {
        $sql = "SELECT * FROM payment_transactions WHERE order_id = :order_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get order items
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.product_name, p.product_image
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = :order_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Update order status
    public function updateStatus($orderId, $status) {
        $orderIdCol = $this->getIdColumn();
        $statusCol = $this->getStatusColumn();
        
        if (!$statusCol) {
            return false; // Can't update status if column doesn't exist
        }
        
        $sql = "UPDATE {$this->table} SET {$statusCol} = :status, updated_at = NOW() WHERE {$orderIdCol} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Get orders by status
    public function getByStatus($status) {
        $userIdCol = $this->getUserIdColumn();
        $statusCol = $this->getStatusColumn();
        
        if (!$statusCol) {
            // No status column, return all orders
            $sql = "SELECT o.*, u.full_name as customer_name
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.customer_id = u.{$userIdCol}
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = "SELECT o.*, u.full_name as customer_name
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.customer_id = u.{$userIdCol}
                    WHERE o.{$statusCol} = :status
                    ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get order statistics
    public function getStatistics($startDate = null, $endDate = null) {
        if (!$this->tableExists()) {
            return [
                'total_orders' => 0,
                'total_sales' => 0,
                'average_order_value' => 0,
                'pending_orders' => 0,
                'processing_orders' => 0,
                'shipped_orders' => 0,
                'delivered_orders' => 0,
                'cancelled_orders' => 0
            ];
        }
        
        $statusCol = $this->getStatusColumn();
        
        if ($statusCol) {
            $statusCases = ",
                    SUM(CASE WHEN {$statusCol} = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN {$statusCol} = 'processing' THEN 1 ELSE 0 END) as processing_orders,
                    SUM(CASE WHEN {$statusCol} = 'shipped' THEN 1 ELSE 0 END) as shipped_orders,
                    SUM(CASE WHEN {$statusCol} = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                    SUM(CASE WHEN {$statusCol} = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders";
        } else {
            $statusCases = ",
                    0 as pending_orders,
                    0 as processing_orders,
                    0 as shipped_orders,
                    0 as delivered_orders,
                    0 as cancelled_orders";
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value{$statusCases}
                FROM {$this->table}";
        
        if ($startDate && $endDate) {
            $sql .= " WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }
        
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get daily sales
    public function getDailySales($days = 30) {
        if (!$this->tableExists()) {
            return [];
        }
        
        $sql = "SELECT DATE(created_at) as date, 
                       COUNT(*) as orders,
                       SUM(total_amount) as sales
                FROM {$this->table}
                WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get monthly sales
    public function getMonthlySales($year = null) {
        if (!$year) {
            $year = date('Y');
        }
        
        $sql = "SELECT MONTH(created_at) as month, 
                       COUNT(*) as orders,
                       SUM(total_amount) as sales
                FROM {$this->table}
                WHERE YEAR(created_at) = :year
                GROUP BY MONTH(created_at)
                ORDER BY month ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
