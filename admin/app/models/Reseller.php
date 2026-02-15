<?php
/**
 * Reseller Model
 */

class Reseller extends Model {
    protected $table = 'resellers';
    private $columns = null;
    
    // Check if table exists
    private function tableExists() {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
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
    
    // Get the appropriate ID column for resellers
    private function getIdColumn() {
        if ($this->columns === null) {
            $stmt = $this->db->query("DESCRIBE {$this->table}");
            $this->columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return in_array('reseller_id', $this->columns) ? 'reseller_id' : 'id';
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
    
    // Get reseller with user info
    public function getResellerWithUser($resellerId) {
        $resellerIdCol = $this->getIdColumn();
        $userIdCol = $this->getUserIdColumn();
        
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.address, u.status as user_status
                FROM {$this->table} r
                LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                WHERE r.{$resellerIdCol} = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $resellerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get all resellers with user info
    public function getAllWithUserInfo() {
        if (!$this->tableExists()) {
            return [];
        }
        
        $userIdCol = $this->getUserIdColumn();
        
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.status as user_status
                FROM {$this->table} r
                LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get pending reseller applications
    public function getPendingApplications() {
        if (!$this->tableExists()) {
            return [];
        }
        
        $userIdCol = $this->getUserIdColumn();
        
        if ($this->hasColumn('status')) {
            $sql = "SELECT r.*, u.full_name, u.email, u.phone
                    FROM {$this->table} r
                    LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                    WHERE r.status = 'pending'
                    ORDER BY r.created_at ASC";
        } else {
            $sql = "SELECT r.*, u.full_name, u.email, u.phone
                    FROM {$this->table} r
                    LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                    ORDER BY r.created_at ASC";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Approve reseller
    public function approve($resellerId, $approvedBy) {
        $resellerIdCol = $this->getIdColumn();
        
        $sql = "UPDATE {$this->table} 
                SET status = 'approved', 
                    approved_by = :approved_by, 
                    approved_at = NOW() 
                WHERE {$resellerIdCol} = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':approved_by', $approvedBy, PDO::PARAM_INT);
        $stmt->bindParam(':id', $resellerId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Reject reseller
    public function reject($resellerId, $reason) {
        $sql = "UPDATE {$this->table} 
                SET status = 'rejected', 
                    rejection_reason = :reason,
                    updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':id', $resellerId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Get reseller statistics
    public function getStatistics() {
        if (!$this->tableExists()) {
            return [
                'total_resellers' => 0,
                'pending_count' => 0,
                'approved_count' => 0,
                'rejected_count' => 0,
                'total_sales' => 0,
                'total_commission' => 0
            ];
        }
        
        if ($this->hasColumn('status')) {
            $statusCases = ",
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count";
        } else {
            $statusCases = ",
                    0 as pending_count,
                    0 as approved_count,
                    0 as rejected_count";
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_resellers{$statusCases},
                    SUM(total_sales) as total_sales,
                    SUM(total_commission) as total_commission
                FROM {$this->table}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get top resellers
    public function getTopResellers($limit = 10) {
        if (!$this->tableExists()) {
            return [];
        }
        
        $userIdCol = $this->getUserIdColumn();
        
        if ($this->hasColumn('status')) {
            $sql = "SELECT r.*, u.full_name, u.email
                    FROM {$this->table} r
                    LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                    WHERE r.status = 'approved'
                    ORDER BY r.total_sales DESC
                    LIMIT :limit";
        } else {
            $sql = "SELECT r.*, u.full_name, u.email
                    FROM {$this->table} r
                    LEFT JOIN users u ON r.user_id = u.{$userIdCol}
                    ORDER BY r.total_sales DESC
                    LIMIT :limit";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Update reseller sales and commission
    public function updateSalesCommission($resellerId, $saleAmount, $commissionAmount) {
        $sql = "UPDATE {$this->table} 
                SET total_sales = total_sales + :sale_amount,
                    total_commission = total_commission + :commission_amount,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sale_amount', $saleAmount);
        $stmt->bindParam(':commission_amount', $commissionAmount);
        $stmt->bindParam(':id', $resellerId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Get all approved resellers for dropdown
    public function getApprovedResellers() {
        if (!$this->tableExists()) {
            return [];
        }
        
        $idCol = $this->getIdColumn();
        $userIdCol = $this->getUserIdColumn();
        
        $sql = "SELECT r.{$idCol} as id, r.{$idCol} as reseller_id, u.full_name, u.email, r.commission_rate
                FROM {$this->table} r
                LEFT JOIN users u ON r.user_id = u.{$userIdCol}";
        
        // Only get approved resellers if status column exists
        if ($this->hasColumn('status')) {
            $sql .= " WHERE r.status = 'approved'";
        }
        
        $sql .= " ORDER BY u.full_name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
