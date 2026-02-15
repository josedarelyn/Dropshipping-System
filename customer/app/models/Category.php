<?php
/**
 * Category Model
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
    
    public function getWithProductCount() {
        $sql = "SELECT c.*, COUNT(p.product_id) as product_count
                FROM {$this->table} c
                LEFT JOIN products p ON c.category_id = p.category_id AND p.is_active = 1
                WHERE c.is_active = 1
                GROUP BY c.category_id
                ORDER BY c.category_name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
