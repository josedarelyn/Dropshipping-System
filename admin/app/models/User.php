<?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';
    
    // Detect database structure
    private function detectStructure() {
        $stmt = $this->db->query("DESCRIBE {$this->table}");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        return [
            'id_column' => in_array('user_id', $columns) ? 'user_id' : 'id',
            'password_column' => in_array('password_hash', $columns) ? 'password_hash' : 'password'
        ];
    }
    
    // Get user by email
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        
        // Normalize to 'id' and 'password' keys
        if ($user) {
            if (isset($user['user_id'])) {
                $user['id'] = $user['user_id'];
            }
            if (isset($user['password_hash'])) {
                $user['password'] = $user['password_hash'];
            }
        }
        
        return $user;
    }
    
    // Get user by ID (override parent to normalize)
    public function getById($id) {
        $structure = $this->detectStructure();
        $idColumn = $structure['id_column'];
        
        $sql = "SELECT * FROM {$this->table} WHERE {$idColumn} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();
        
        // Normalize to 'id' and 'password' keys
        if ($user) {
            if (isset($user['user_id'])) {
                $user['id'] = $user['user_id'];
            }
            if (isset($user['password_hash'])) {
                $user['password'] = $user['password_hash'];
            }
        }
        
        return $user;
    }
    
    // Get users by role
    public function getByRole($role) {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Register new user
    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = $data['status'] ?? 'active';
        
        return $this->create($data);
    }
    
    // Verify login
    public function verifyLogin($email, $password) {
        $user = $this->getByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    // Update password
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Update user (override parent to handle user_id)
    public function update($id, $data) {
        $structure = $this->detectStructure();
        $idColumn = $structure['id_column'];
        $passwordColumn = $structure['password_column'];
        
        // If password is being updated and it's not already hashed, hash it
        if (isset($data['password']) && strlen($data['password']) < 60) {
            $data[$passwordColumn] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "{$key} = :{$key}, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Update last login
    public function updateLastLogin($userId) {
        $structure = $this->detectStructure();
        $idColumn = $structure['id_column'];
        
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE {$idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Get user statistics
    public function getStatistics() {
        $structure = $this->detectStructure();
        
        // Check if status column exists
        $stmt = $this->db->query("DESCRIBE {$this->table}");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $hasStatus = in_array('status', $columns);
        
        if ($hasStatus) {
            $statusCases = ",\n                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,\n                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_count";
        } else {
            $statusCases = ",\n                    COUNT(*) as active_count,\n                    0 as inactive_count";
        }
        
        $sql = "SELECT 
                    role,
                    COUNT(*) as count{$statusCases}
                FROM {$this->table}
                GROUP BY role";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
