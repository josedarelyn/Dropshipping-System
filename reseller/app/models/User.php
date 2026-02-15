<?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';
    
    /**
     * Get user by email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Create new reseller user
     */
    public function createReseller($userData, $resellerData) {
        try {
            $this->db->beginTransaction();
            
            // Generate username from email
            $username = explode('@', $userData['email'])[0];
            $baseUsername = $username;
            $counter = 1;
            
            // Ensure unique username
            while ($this->usernameExists($username)) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            // Create user
            $sql = "INSERT INTO users (username, full_name, email, password_hash, phone, role, status) 
                    VALUES (:username, :full_name, :email, :password_hash, :phone, 'reseller', 'active')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'full_name' => $userData['full_name'],
                'email' => $userData['email'],
                'password_hash' => password_hash($userData['password'], PASSWORD_DEFAULT),
                'phone' => $userData['phone']
            ]);
            
            $userId = $this->db->lastInsertId();
            
            // Create reseller record
            $sql = "INSERT INTO reseller_profiles (user_id, business_name, business_address, gcash_number, gcash_name, approval_status) 
                    VALUES (:user_id, :business_name, :business_address, :gcash_number, :gcash_name, 'pending')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'business_name' => $resellerData['business_name'],
                'business_address' => $resellerData['business_address'],
                'gcash_number' => $resellerData['gcash_number'],
                'gcash_name' => $resellerData['gcash_name']
            ]);
            
            $resellerId = $this->db->lastInsertId();
            
            $this->db->commit();
            
            // Create notification for admin about new reseller
            try {
                $notificationSql = "INSERT INTO notifications (user_id, title, message, type, created_at)
                                    SELECT user_id, :title, :message, :type, NOW()
                                    FROM users WHERE role = 'admin' LIMIT 1";
                $notificationStmt = $this->db->prepare($notificationSql);
                $notificationStmt->execute([
                    'title' => 'New Reseller Registration',
                    'message' => "A new reseller '{$userData['full_name']}' has registered and is pending approval.",
                    'type' => 'system'
                ]);
            } catch (Exception $e) {
                // Notification failed but registration succeeded
            }
            
            return $userId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Verify login credentials
     */
    public function verifyLogin($email, $password) {
        $user = $this->getByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId]);
    }
    
    /**
     * Check if username exists
     */
    private function usernameExists($username) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get user by ID
     */
    public function getById($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Update user profile
     */
    public function update($userId, $data) {
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE user_id = :user_id";
        $data['user_id'] = $userId;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
