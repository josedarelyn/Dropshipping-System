<?php
/**
 * Withdrawal Model - For Reseller Portal
 */

class Withdrawal extends Model {
    protected $table = 'withdrawal_requests';
    
    public function getResellerWithdrawals($resellerId) {
        $sql = "SELECT * FROM {$this->table}
                WHERE reseller_id = :reseller_id
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        return $stmt->fetchAll();
    }
    
    public function createRequest($resellerId, $amount) {
        try {
            $this->db->beginTransaction();
            
            // Insert withdrawal request
            $sql = "INSERT INTO {$this->table} (reseller_id, amount, status, created_at)
                    VALUES (:reseller_id, :amount, 'pending', NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'reseller_id' => $resellerId,
                'amount' => $amount
            ]);
            
            // Deduct from wallet balance
            $sql = "UPDATE reseller_profiles 
                    SET wallet_balance = wallet_balance - :amount
                    WHERE reseller_id = :reseller_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'amount' => $amount,
                'reseller_id' => $resellerId
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
