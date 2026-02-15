<?php
/**
 * User Model - Customer Portal
 */

class User extends Model {
    protected $table = 'users';
    
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch()['count'] > 0;
    }
    
    public function emailExistsForOther($email, $userId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE email = :email AND user_id != :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'user_id' => $userId]);
        return $stmt->fetch()['count'] > 0;
    }
    
    public function update($userId, $data) {
        $sql = "UPDATE {$this->table} 
                SET full_name = :full_name, email = :email, phone = :phone
                WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $data['user_id'] = $userId;
        return $stmt->execute($data);
    }
    
    public function updatePassword($userId, $password) {
        $sql = "UPDATE {$this->table} SET password_hash = :password_hash WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'user_id' => $userId
        ]);
    }
}
