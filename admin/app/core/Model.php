<?php
/**
 * Base Model Class
 */

class Model {
    protected $db;
    protected $table;
    private $primaryKey = null;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Detect primary key column for the table
    protected function getPrimaryKey() {
        if ($this->primaryKey !== null) {
            return $this->primaryKey;
        }
        
        try {
            // Check if table exists first
            $stmt = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            if ($stmt->rowCount() === 0) {
                $this->primaryKey = 'id'; // Default fallback
                return $this->primaryKey;
            }
            
            // Get primary key from table structure
            $stmt = $this->db->query("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['Column_name'])) {
                $this->primaryKey = $result['Column_name'];
            } else {
                // Fallback: check common patterns
                $stmt = $this->db->query("DESCRIBE {$this->table}");
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                // Common ID column patterns based on table name
                $tableName = rtrim($this->table, 's'); // Remove trailing 's'
                $possibleIds = [
                    $tableName . '_id',  // e.g., product_id for products table
                    'id',
                    $this->table . '_id'
                ];
                
                foreach ($possibleIds as $possibleId) {
                    if (in_array($possibleId, $columns)) {
                        $this->primaryKey = $possibleId;
                        break;
                    }
                }
                
                if ($this->primaryKey === null) {
                    $this->primaryKey = 'id'; // Ultimate fallback
                }
            }
        } catch (Exception $e) {
            $this->primaryKey = 'id'; // Error fallback
        }
        
        return $this->primaryKey;
    }
    
    // Get all records
    public function getAll($orderBy = null, $order = 'DESC') {
        if ($orderBy === null) {
            $orderBy = $this->getPrimaryKey();
        }
        
        // Validate order direction
        $order = strtoupper($order);
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'DESC';
        }
        
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // If ordering fails, try without ORDER BY
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }
    
    // Get record by ID
    public function getById($id) {
        $pk = $this->getPrimaryKey();
        
        $sql = "SELECT * FROM {$this->table} WHERE {$pk} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Create record
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    // Update record
    public function update($id, $data) {
        $pk = $this->getPrimaryKey();
        
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "{$key} = :{$key}, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$pk} = :id";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Delete record
    public function delete($id) {
        $pk = $this->getPrimaryKey();
        
        $sql = "DELETE FROM {$this->table} WHERE {$pk} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Custom query
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    // Count records
    public function count($where = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}
