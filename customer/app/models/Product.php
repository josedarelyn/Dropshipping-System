<?php
/**
 * Product Model - Customer Portal
 */

class Product extends Model {
    protected $table = 'products';
    
    public function getAllActive($limit = 12, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_active = 1 AND stock_quantity > 0
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getActiveCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE is_active = 1 AND stock_quantity > 0";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByIdWithDetails($id) {
        $sql = "SELECT p.*, c.category_name
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.product_id = :id AND p.is_active = 1
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByCategory($categoryId, $limit = 12, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE category_id = :category_id AND is_active = 1 AND stock_quantity > 0
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCategoryCount($categoryId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE category_id = :category_id AND is_active = 1 AND stock_quantity > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetch()['count'];
    }
    
    public function search($query, $limit = 12, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (product_name LIKE :query OR description LIKE :query) 
                AND is_active = 1 AND stock_quantity > 0
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function searchCount($query) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE (product_name LIKE :query OR description LIKE :query) 
                AND is_active = 1 AND stock_quantity > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetch()['count'];
    }
    
    public function getRelated($categoryId, $excludeId, $limit = 4) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE category_id = :category_id AND product_id != :exclude_id 
                AND is_active = 1 AND stock_quantity > 0
                ORDER BY RAND()
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getFeatured($limit = 8) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_featured = 1 AND is_active = 1 AND stock_quantity > 0
                ORDER BY created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
