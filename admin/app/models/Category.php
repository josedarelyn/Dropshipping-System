<?php
/**
 * Category Model
 */

class Category extends Model {
    protected $table = 'categories';

    // Get all active categories
    public function getActiveCategories() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY category_name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
