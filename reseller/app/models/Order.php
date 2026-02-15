<?php
/**
 * Order Model - For Reseller Portal
 */

class Order extends Model {
    protected $table = 'orders';
    
    public function getResellerOrders($resellerId) {
        $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email
                FROM {$this->table} o
                INNER JOIN users u ON o.customer_id = u.user_id
                WHERE o.reseller_id = :reseller_id
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        return $stmt->fetchAll();
    }
    
    public function getOrderDetails($orderId, $resellerId) {
        $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email, u.phone as customer_phone
                FROM {$this->table} o
                INNER JOIN users u ON o.customer_id = u.user_id
                WHERE o.order_id = :order_id AND o.reseller_id = :reseller_id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['order_id' => $orderId, 'reseller_id' => $resellerId]);
        $order = $stmt->fetch();
        
        if ($order) {
            // Get order items
            $sql = "SELECT oi.*, p.product_name, p.product_image
                    FROM order_items oi
                    LEFT JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = :order_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['order_id' => $orderId]);
            $order['items'] = $stmt->fetchAll();
        }
        
        return $order;
    }
}
