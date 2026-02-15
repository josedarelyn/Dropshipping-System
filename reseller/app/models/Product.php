<?php
/**
 * Product Model - For Reseller Portal
 */

class Product extends Model {
    protected $table = 'products';
    
    public function getAllActive() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE stock_quantity > 0 AND is_active = 1
                ORDER BY product_name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByReseller($resellerId) {
        $sql = "SELECT p.*, c.category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                WHERE p.reseller_id = :reseller_id 
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reseller_id' => $resellerId]);
        return $stmt->fetchAll();
    }
    
    public function getByIdAndReseller($productId, $resellerId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE product_id = :id AND reseller_id = :reseller_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $productId,
            'reseller_id' => $resellerId
        ]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (
                    category_id, reseller_id, product_name, description, 
                    price, reseller_price, stock_quantity, product_image, is_active
                ) VALUES (
                    :category_id, :reseller_id, :product_name, :description, 
                    :price, :reseller_price, :stock_quantity, :product_image, :is_active
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($productId, $data) {
        $sql = "UPDATE {$this->table} SET 
                    category_id = :category_id,
                    product_name = :product_name,
                    description = :description,
                    price = :price,
                    reseller_price = :reseller_price,
                    stock_quantity = :stock_quantity,
                    product_image = :product_image,
                    is_active = :is_active,
                    updated_at = CURRENT_TIMESTAMP
                WHERE product_id = :id";
        
        $data['id'] = $productId;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function delete($productId) {
        $sql = "DELETE FROM {$this->table} WHERE product_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $productId]);
    }
}
