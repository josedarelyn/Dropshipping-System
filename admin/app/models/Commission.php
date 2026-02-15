<?php
/**
 * Commission Model
 */

class Commission extends Model {
    protected $table = 'commissions';
    private $columns = null;
    
    // Get the appropriate ID columns
    private function getUserIdColumn() {
        try {
            $stmt = $this->db->query("DESCRIBE users");
            $userColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return in_array('user_id', $userColumns) ? 'user_id' : 'id';
        } catch (Exception $e) {
            return 'id';
        }
    }
    
    private function getResellerIdColumn() {
        try {
            $stmt = $this->db->query("DESCRIBE resellers");
            $resellerColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return in_array('reseller_id', $resellerColumns) ? 'reseller_id' : 'id';
        } catch (Exception $e) {
            return 'id';
        }
    }
    
    private function getCommissionIdColumn() {
        if ($this->columns === null) {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $this->columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return in_array('commission_id', $this->columns) ? 'commission_id' : 'id';
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
        if (!$this->tableExists()) {
            return false;
        }
        if ($this->columns === null) {
            try {
                $stmt = $this->db->query("DESCRIBE {$this->table}");
                $this->columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $this->columns = [];
                return false;
            }
        }
        return in_array($columnName, $this->columns);
    }
    
    // Get commissions with reseller info
    public function getCommissionsWithReseller() {
        if (!$this->tableExists()) {
            return [];
        }
        
        $userIdCol = $this->getUserIdColumn();
        $resellerIdCol = $this->getResellerIdColumn();
        
        $sql = "SELECT c.*, r.user_id, u.full_name as reseller_name, u.email
                FROM {$this->table} c
                LEFT JOIN resellers r ON c.reseller_id = r.{$resellerIdCol}
                LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get commissions by status
    public function getByStatus($status) {
        $userIdCol = $this->getUserIdColumn();
        $resellerIdCol = $this->getResellerIdColumn();
        
        if ($this->hasColumn('status')) {
            $sql = "SELECT c.*, r.user_id, u.full_name as reseller_name, u.email
                    FROM {$this->table} c
                    LEFT JOIN resellers r ON c.reseller_id = r.{$resellerIdCol}
                    LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                    WHERE c.status = :status
                    ORDER BY c.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
        } else {
            $sql = "SELECT c.*, r.user_id, u.full_name as reseller_name, u.email
                    FROM {$this->table} c
                    LEFT JOIN resellers r ON c.reseller_id = r.{$resellerIdCol}
                    LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                    ORDER BY c.created_at DESC";
            $stmt = $this->db->prepare($sql);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get pending payouts
    public function getPendingPayouts() {
        if (!$this->tableExists()) {
            return [];
        }
        return $this->getByStatus('pending');
    }
    
    // Approve commission payout
    public function approvePayout($commissionId, $approvedBy, $otpVerified = false) {
        if (!$otpVerified) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} 
                SET status = 'approved',
                    approved_by = :approved_by,
                    approved_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':approved_by', $approvedBy, PDO::PARAM_INT);
        $stmt->bindParam(':id', $commissionId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Process payout
    public function processPayout($commissionId, $transactionId) {
        $sql = "UPDATE {$this->table} 
                SET status = 'paid',
                    transaction_id = :transaction_id,
                    paid_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->bindParam(':id', $commissionId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Get commission statistics
    public function getStatistics() {
        if (!$this->tableExists()) {
            return [
                'total_commissions' => 0,
                'total_amount' => 0,
                'pending_amount' => 0,
                'approved_amount' => 0,
                'paid_amount' => 0,
                'cancelled_amount' => 0
            ];
        }
        
        if ($this->hasColumn('status')) {
            $statusCases = ",
                    SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending_amount,
                    SUM(CASE WHEN status = 'approved' THEN amount ELSE 0 END) as approved_amount,
                    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as paid_amount,
                    SUM(CASE WHEN status = 'cancelled' THEN amount ELSE 0 END) as cancelled_amount";
        } else {
            $statusCases = ",
                    0 as pending_amount,
                    0 as approved_amount,
                    0 as paid_amount,
                    0 as cancelled_amount";
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_commissions,
                    SUM(amount) as total_amount{$statusCases}
                FROM {$this->table}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get reseller commission history
    public function getResellerCommissions($resellerId) {
        $sql = "SELECT c.*, o.order_number
                FROM {$this->table} c
                LEFT JOIN orders o ON c.order_id = o.id
                WHERE c.reseller_id = :reseller_id
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':reseller_id', $resellerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
