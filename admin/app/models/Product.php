<?php
/**
 * Product Model
 */

class Product extends Model {
    protected $table = 'products';
    private $columns = null;
    
    // Detect and cache table columns
    private function getColumns() {
        if ($this->columns === null) {
            if (!$this->tableExists()) {
                $this->columns = [];
                return $this->columns;
            }
            try {
                $stmt = $this->db->query("DESCRIBE {$this->table}");
                $this->columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $this->columns = [];
            }
        }
        return $this->columns;
    }
    
    // Check if table exists
    private function tableExists() {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Check if column exists
    private function hasColumn($columnName) {
        return in_array($columnName, $this->getColumns());
    }
    
    // Get the appropriate ID column
    private function getIdColumn() {
        $columns = $this->getColumns();
        if (in_array('product_id', $columns)) return 'product_id';
        return 'id';
    }
    
    // Override getAll to join with categories
    public function getAll($orderBy = null, $order = 'DESC') {
        $idCol = $this->getIdColumn();
        $orderCol = $this->hasColumn('created_at') ? 'p.created_at' : 'p.' . $idCol;
        
        try {
            $sql = "SELECT p.*, c.category_name 
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.category_id
                    ORDER BY {$orderCol} DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Fallback without join
            $sql = "SELECT * FROM {$this->table} ORDER BY {$idCol} DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }
    
    // Get active products
    public function getActiveProducts() {
        if (!$this->tableExists()) {
            return [];
        }
        
        $idCol = $this->getIdColumn();
        $orderBy = $this->hasColumn('created_at') ? 'created_at' : $idCol;
        
        if ($this->hasColumn('status')) {
            $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY {$orderBy} DESC";
        } elseif ($this->hasColumn('is_active')) {
            $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY {$orderBy} DESC";
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} DESC";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get products by category
    public function getByCategory($categoryId) {
        $nameCol = $this->hasColumn('product_name') ? 'product_name' : 'name';
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :category_id ORDER BY {$nameCol} ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Search products
    public function search($keyword) {
        $nameCol = $this->hasColumn('product_name') ? 'product_name' : 'name';
        $sql = "SELECT * FROM {$this->table} 
                WHERE {$nameCol} LIKE :keyword OR description LIKE :keyword 
                ORDER BY {$nameCol} ASC";
        $stmt = $this->db->prepare($sql);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Update stock
    public function updateStock($productId, $quantity, $operation = 'add') {
        $idCol = $this->getIdColumn();
        
        if ($operation === 'add') {
            $sql = "UPDATE {$this->table} SET stock_quantity = stock_quantity + :quantity WHERE {$idCol} = :id";
        } else {
            $sql = "UPDATE {$this->table} SET stock_quantity = stock_quantity - :quantity WHERE {$idCol} = :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Get low stock products
    public function getLowStockProducts($threshold = 10) {
        if (!$this->tableExists()) {
            return [];
        }
        
        $statusCondition = '';
        if ($this->hasColumn('status')) {
            $statusCondition = " AND status = 'active'";
        } elseif ($this->hasColumn('is_active')) {
            $statusCondition = " AND is_active = 1";
        }
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE stock_quantity <= :threshold{$statusCondition}
                ORDER BY stock_quantity ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get product statistics
    public function getStatistics() {
        if (!$this->tableExists()) {
            return [
                'total_products' => 0,
                'total_stock' => 0,
                'low_stock_count' => 0,
                'active_products' => 0
            ];
        }
        
        $activeProductsCalc = $this->hasColumn('status') 
            ? "SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_products"
            : ($this->hasColumn('is_active')
                ? "SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_products"
                : "COUNT(*) as active_products");
        
        $sql = "SELECT 
                    COUNT(*) as total_products,
                    SUM(stock_quantity) as total_stock,
                    SUM(CASE WHEN stock_quantity <= 10 THEN 1 ELSE 0 END) as low_stock_count,
                    {$activeProductsCalc}
                FROM {$this->table}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Get most sold products
    public function getMostSold($limit = 10) {
        if (!$this->tableExists()) {
            return [];
        }
        
        $idCol = $this->getIdColumn();
        
        $sql = "SELECT p.*, COALESCE(SUM(oi.quantity), 0) as total_sold
                FROM {$this->table} p
                LEFT JOIN order_items oi ON p.{$idCol} = oi.product_id
                GROUP BY p.{$idCol}
                ORDER BY total_sold DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
