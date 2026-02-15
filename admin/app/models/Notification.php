<?php
/**
 * Notification Model
 */

class Notification extends Model {
    protected $table = 'notifications';
    
    /**
     * Get primary key considering different database schemas
     */
    protected function detectPrimaryKey() {
        $stmt = $this->db->query("DESCRIBE {$this->table}");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            if ($column['Key'] === 'PRI') {
                return $column['Field'];
            }
        }
        
        return 'id';
    }
    
    /**
     * Get unread notifications for a user
     */
    public function getUnreadByUser($userId) {
        $pk = $this->detectPrimaryKey();
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id AND is_read = 0 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all notifications for a user
     */
    public function getByUser($userId, $limit = 50) {
        $pk = $this->detectPrimaryKey();
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count unread notifications
     */
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Count unread notifications by type
     */
    public function countUnreadByType($userId, $type) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_id = :user_id AND type = :type AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'type' => $type
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId) {
        $pk = $this->detectPrimaryKey();
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE {$pk} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $notificationId]);
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId]);
    }
    
    /**
     * Create notification
     */
    public function createNotification($data) {
        $required = ['user_id', 'title', 'message', 'type'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        
        $sql = "INSERT INTO {$this->table} 
                (user_id, title, message, type, related_id, is_read, created_at) 
                VALUES (:user_id, :title, :message, :type, :related_id, 0, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'related_id' => $data['related_id'] ?? null
        ]);
    }
    
    /**
     * Delete old read notifications
     */
    public function deleteOldRead($days = 30) {
        $sql = "DELETE FROM {$this->table} 
                WHERE is_read = 1 
                AND created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['days' => $days]);
    }
    
    /**
     * Get notifications by type
     */
    public function getByType($userId, $type) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id AND type = :type 
                ORDER BY created_at DESC 
                LIMIT 20";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'type' => $type
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
