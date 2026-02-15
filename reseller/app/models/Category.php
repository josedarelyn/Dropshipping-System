<?php
/**
 * Category Model - For Reseller Portal
 */

class Category extends Model {
    protected $table = 'categories';
    
    public function getAllActive() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_active = 1
                ORDER BY category_name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
