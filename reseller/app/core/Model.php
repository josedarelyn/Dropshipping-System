<?php
/**
 * Base Model Class
 */

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all records
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get record by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Create new record
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Count records
     */
    public function count($where = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach (array_keys($where) as $key) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($where);
        $result = $stmt->fetch();
        
        return $result['count'];
    }
}
