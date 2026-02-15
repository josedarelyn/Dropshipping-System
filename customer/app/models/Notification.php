<?php
/**
 * Notification Model - Customer Portal
 * Handles creating notifications for sellers/resellers when orders are placed
 */

class Notification extends Model {
    protected $table = 'notifications';
    
    /**
     * Create a notification
     */
    public function createNotification($data) {
        $required = ['user_id', 'title', 'message', 'type'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        
        $sql = "INSERT INTO {$this->table} 
                (user_id, title, message, type, related_id, is_read, created_at) 
                VALUES (:user_id, :title, :message, :type, :related_id, 0, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'related_id' => $data['related_id'] ?? null
        ]);
    }
    
    /**
     * Get admin user IDs to notify
     */
    public function getAdminUserIds() {
        $sql = "SELECT user_id FROM users WHERE role = 'admin' AND status = 'active'";
        $stmt = $this->db->query($sql);
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($admins, 'user_id');
    }
    
    /**
     * Get reseller's user_id from reseller profile
     */
    public function getResellerUserId($resellerId) {
        $sql = "SELECT user_id FROM reseller_profiles WHERE reseller_id = :reseller_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['user_id'] : null;
    }
    
    /**
     * Notify admin(s) about a new order
     */
    public function notifyAdminNewOrder($orderId, $orderNumber, $customerName, $totalAmount) {
        $adminIds = $this->getAdminUserIds();
        
        foreach ($adminIds as $adminId) {
            $this->createNotification([
                'user_id' => $adminId,
                'title' => 'New Order Received',
                'message' => "New order #{$orderNumber} placed by {$customerName} worth ₱" . number_format($totalAmount, 2) . ". Please review and process.",
                'type' => 'order',
                'related_id' => $orderId
            ]);
        }
    }
    
    /**
     * Notify reseller about a new order through their referral
     */
    public function notifyResellerNewOrder($resellerId, $orderId, $orderNumber, $customerName, $totalAmount, $commissionAmount) {
        $resellerUserId = $this->getResellerUserId($resellerId);
        
        if ($resellerUserId) {
            $this->createNotification([
                'user_id' => $resellerUserId,
                'title' => 'New Order From Your Referral',
                'message' => "Customer {$customerName} placed order #{$orderNumber} worth ₱" . number_format($totalAmount, 2) . ". Your commission: ₱" . number_format($commissionAmount, 2) . ".",
                'type' => 'commission',
                'related_id' => $orderId
            ]);
        }
    }
}
