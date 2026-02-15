<?php
/**
 * Reseller Profile Model
 */

class ResellerProfile extends Model {
    protected $table = 'reseller_profiles';
    
    /**
     * Get all resellers with user details
     */
    public function getAllWithUserDetails() {
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.status as user_status, u.created_at as registered_at
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.user_id
                ORDER BY r.reseller_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get resellers by status
     */
    public function getByStatus($status) {
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.status as user_status, u.created_at as registered_at
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.user_id
                WHERE r.approval_status = :status
                ORDER BY r.reseller_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll();
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
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.status as user_status, 
                       u.profile_image, u.created_at as registered_at, u.last_login,
                       admin.full_name as approved_by_name
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.user_id
                LEFT JOIN users admin ON r.approved_by = admin.user_id
                WHERE r.reseller_id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get reseller statistics
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total_resellers,
                    SUM(CASE WHEN approval_status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN approval_status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN approval_status = 'rejected' THEN 1 ELSE 0 END) as rejected_count,
                    SUM(total_sales) as total_sales,
                    SUM(total_commission) as total_commission
                FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Update reseller
     */
    public function update($id, $data) {
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE reseller_id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Count pending resellers
     */
    public function countPending() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE approval_status = 'pending'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    /**
     * Get pending applications with user details
     */
    public function getPendingApplications() {
        return $this->getByStatus('pending');
    }
    
    /**
     * Get top performing resellers
     */
    public function getTopResellers($limit = 20) {
        $sql = "SELECT r.*, u.full_name, u.email, u.phone, u.status as user_status
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.user_id
                WHERE r.approval_status = 'approved'
                ORDER BY r.total_sales DESC, r.total_commission DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
