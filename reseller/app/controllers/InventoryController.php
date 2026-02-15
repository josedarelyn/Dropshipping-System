<?php
/**
 * Inventory Controller - View and manage products
 */

class InventoryController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Get all active products (admin + reseller's own)
        $allProducts = $this->productModel->getAllActive();
        $myProducts = $this->productModel->getByReseller($resellerId);
        
        $data = [
            'pageTitle' => 'Inventory',
            'allProducts' => $allProducts,
            'myProducts' => $myProducts
        ];
        
        $this->template('inventory/index', $data);
    }
    
    public function add() {
        $categories = $this->categoryModel->getAllActive();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleAdd();
        }
        
        $data = [
            'pageTitle' => 'Add Product',
            'categories' => $categories
        ];
        
        $this->template('inventory/add', $data);
    }
    
    private function handleAdd() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Validate input
        $productName = trim($_POST['product_name']);
        $categoryId = (int)$_POST['category_id'];
        $description = trim($_POST['description']);
        $price = (float)$_POST['price'];
        $stockQuantity = (int)$_POST['stock_quantity'];
        
        if (empty($productName) || empty($categoryId) || $price <= 0) {
            $_SESSION['error'] = 'Please fill all required fields';
            header('Location: ' . BASE_URL . 'inventory/add');
            exit();
        }
        
        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['product_image']);
            if (!$imagePath) {
                $_SESSION['error'] = 'Failed to upload image';
                header('Location: ' . BASE_URL . 'inventory/add');
                exit();
            }
        }
        
        // Create product
        $productData = [
            'category_id' => $categoryId,
            'reseller_id' => $resellerId,
            'product_name' => $productName,
            'description' => $description,
            'price' => $price,
            'reseller_price' => $price, // Same as price for reseller products
            'stock_quantity' => $stockQuantity,
            'product_image' => $imagePath,
            'is_active' => 1
        ];
        
        if ($this->productModel->create($productData)) {
            $_SESSION['success'] = 'Product added successfully';
            header('Location: ' . BASE_URL . 'inventory');
        } else {
            $_SESSION['error'] = 'Failed to add product';
            header('Location: ' . BASE_URL . 'inventory/add');
        }
        exit();
    }
    
    public function edit($productId) {
        $resellerId = $_SESSION['reseller_id'];
        $product = $this->productModel->getByIdAndReseller($productId, $resellerId);
        
        if (!$product) {
            $_SESSION['error'] = 'Product not found or access denied';
            header('Location: ' . BASE_URL . 'inventory');
            exit();
        }
        
        $categories = $this->categoryModel->getAllActive();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleEdit($productId);
        }
        
        $data = [
            'pageTitle' => 'Edit Product',
            'product' => $product,
            'categories' => $categories
        ];
        
        $this->template('inventory/edit', $data);
    }
    
    private function handleEdit($productId) {
        $resellerId = $_SESSION['reseller_id'];
        
        // Verify ownership
        $product = $this->productModel->getByIdAndReseller($productId, $resellerId);
        if (!$product) {
            $_SESSION['error'] = 'Access denied';
            header('Location: ' . BASE_URL . 'inventory');
            exit();
        }
        
        // Validate input
        $productName = trim($_POST['product_name']);
        $categoryId = (int)$_POST['category_id'];
        $description = trim($_POST['description']);
        $price = (float)$_POST['price'];
        $stockQuantity = (int)$_POST['stock_quantity'];
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($productName) || empty($categoryId) || $price <= 0) {
            $_SESSION['error'] = 'Please fill all required fields';
            header('Location: ' . BASE_URL . 'inventory/edit/' . $productId);
            exit();
        }
        
        // Handle image upload
        $imagePath = $product['product_image'];
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $newImagePath = $this->handleImageUpload($_FILES['product_image']);
            if ($newImagePath) {
                $imagePath = $newImagePath;
            }
        }
        
        // Update product
        $productData = [
            'category_id' => $categoryId,
            'product_name' => $productName,
            'description' => $description,
            'price' => $price,
            'reseller_price' => $price,
            'stock_quantity' => $stockQuantity,
            'product_image' => $imagePath,
            'is_active' => $isActive
        ];
        
        if ($this->productModel->update($productId, $productData)) {
            $_SESSION['success'] = 'Product updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update product';
        }
        
        header('Location: ' . BASE_URL . 'inventory');
        exit();
    }
    
    public function delete($productId) {
        $resellerId = $_SESSION['reseller_id'];
        $product = $this->productModel->getByIdAndReseller($productId, $resellerId);
        
        if (!$product) {
            $_SESSION['error'] = 'Product not found or access denied';
            header('Location: ' . BASE_URL . 'inventory');
            exit();
        }
        
        if ($this->productModel->delete($productId)) {
            $_SESSION['success'] = 'Product deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete product';
        }
        
        header('Location: ' . BASE_URL . 'inventory');
        exit();
    }
    
    private function handleImageUpload($file) {
        $uploadDir = BASE_PATH . '/../admin/uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        // Validate image
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            return false;
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/products/' . $fileName;
        }
        
        return false;
    }
}
