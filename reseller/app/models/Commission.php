<?php
/**
 * Commission Model - For Reseller Portal
 */

class Commission extends Model {
    protected $table = 'commission_transactions';
    
    public function getResellerCommissions($resellerId) {
        $sql = "SELECT ct.*, o.order_id, o.total_amount as order_total
                FROM {$this->table} ct
                LEFT JOIN orders o ON ct.order_id = o.order_id
                WHERE ct.reseller_id = :reseller_id
                ORDER BY ct.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        return $stmt->fetchAll();
    }
    
    public function getCommissionStats($resellerId) {
        $sql = "SELECT 
                    SUM(CASE WHEN transaction_type = 'earned' AND status = 'completed' THEN amount ELSE 0 END) as total_earned,
                    SUM(CASE WHEN transaction_type = 'earned' AND status = 'pending' THEN amount ELSE 0 END) as pending,
                    SUM(CASE WHEN transaction_type = 'earned' AND status = 'completed' THEN amount ELSE 0 END) as available
                FROM {$this->table}
                WHERE reseller_id = :reseller_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        return $stmt->fetch();
    }
}
