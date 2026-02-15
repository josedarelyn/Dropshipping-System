<?php

class Setting extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'system_settings';
    }
    
    /**
     * Get setting by key
     */
    public function getByKey($key) {
        $query = "SELECT * FROM {$this->table} WHERE setting_key = :key LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['key' => $key]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get setting value by key
     */
    public function getValue($key, $default = null) {
        $setting = $this->getByKey($key);
        
        if (!$setting) {
            return $default;
        }
        
        // Convert value based on type
        switch ($setting['setting_type']) {
            case 'boolean':
                return (bool)$setting['setting_value'];
            case 'number':
                return (float)$setting['setting_value'];
            case 'json':
                return json_decode($setting['setting_value'], true);
            default:
                return $setting['setting_value'];
        }
    }
    
    /**
     * Update or create setting
     */
    public function updateSetting($key, $value, $type = 'string', $description = '') {
        $existing = $this->getByKey($key);
        
        if ($existing) {
            $query = "UPDATE {$this->table} 
                     SET setting_value = :value, 
                         setting_type = :type, 
                         description = :description
                     WHERE setting_key = :key";
        } else {
            $query = "INSERT INTO {$this->table} 
                     (setting_key, setting_value, setting_type, description) 
                     VALUES (:key, :value, :type, :description)";
        }
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'description' => $description
        ]);
    }
    
    /**
     * Get all settings
     */
    public function getAllSettings() {
        $query = "SELECT * FROM {$this->table} ORDER BY setting_key ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get settings by type
     */
    public function getByType($type) {
        $query = "SELECT * FROM {$this->table} WHERE setting_type = :type ORDER BY setting_key ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get settings by prefix
     */
    public function getByPrefix($prefix) {
        $query = "SELECT * FROM {$this->table} WHERE setting_key LIKE :prefix ORDER BY setting_key ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['prefix' => $prefix . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Delete setting
     */
    public function deleteSetting($key) {
        $query = "DELETE FROM {$this->table} WHERE setting_key = :key";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['key' => $key]);
    }
}
