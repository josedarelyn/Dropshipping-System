<?php
/**
 * Product Controller
 */

class ProductController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }
    
    // List all products
    public function index() {
        $data['page_title'] = 'Inventory Management';
        $data['products'] = $this->productModel->getAll();
        $data['product_stats'] = $this->productModel->getStatistics();
        
        $this->template('products/index', $data);
    }
    
    // Add product form
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'product_name' => $this->sanitize($_POST['name']),
                'description' => $this->sanitize($_POST['description']),
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'price' => $_POST['price'],
                'reseller_price' => $_POST['cost_price'],
                'stock_quantity' => $_POST['stock_quantity'],
                'is_active' => ($_POST['status'] === 'active') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = UPLOAD_PATH . 'products/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . '.' . $imageExtension;
                $targetFile = $targetDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $productData['product_image'] = 'public/uploads/products/' . $imageName;
                }
            }
            
            if ($this->productModel->create($productData)) {
                $this->setFlash('success', 'Product added successfully');
                $this->redirect('product');
            } else {
                $this->setFlash('error', 'Failed to add product');
            }
        }
        
        $data['page_title'] = 'Add Product';
        $data['categories'] = $this->categoryModel->getActiveCategories();
        $this->template('products/add', $data);
    }
    
    // Edit product
    public function edit($id) {
        $data['product'] = $this->productModel->getById($id);
        
        if (!$data['product']) {
            $this->setFlash('error', 'Product not found');
            $this->redirect('product');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'product_name' => $this->sanitize($_POST['name']),
                'description' => $this->sanitize($_POST['description']),
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'price' => $_POST['price'],
                'reseller_price' => $_POST['cost_price'],
                'stock_quantity' => $_POST['stock_quantity'],
                'is_active' => ($_POST['status'] === 'active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = UPLOAD_PATH . 'products/';
                $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . '.' . $imageExtension;
                $targetFile = $targetDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $productData['product_image'] = 'public/uploads/products/' . $imageName;
                }
            }
            
            if ($this->productModel->update($id, $productData)) {
                $this->setFlash('success', 'Product updated successfully');
                $this->redirect('product');
            } else {
                $this->setFlash('error', 'Failed to update product');
            }
        }
        
        $data['page_title'] = 'Edit Product';
        $data['categories'] = $this->categoryModel->getActiveCategories();
        $this->template('products/edit', $data);
    }
    
    // Delete product
    public function delete($id) {
        if ($this->productModel->delete($id)) {
            $this->setFlash('success', 'Product deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete product');
        }
        
        $this->redirect('product');
    }
    
    // Bulk delete products
    public function bulkDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];
            
            // Also support form data
            if (empty($ids) && isset($_POST['ids'])) {
                $ids = json_decode($_POST['ids'], true);
            }
            
            if (empty($ids) || !is_array($ids)) {
                $this->json(['success' => false, 'message' => 'No products selected'], 400);
                return;
            }
            
            // Sanitize ids to integers
            $ids = array_map('intval', $ids);
            
            $deleted = 0;
            $failed = 0;
            foreach ($ids as $id) {
                if ($this->productModel->delete($id)) {
                    $deleted++;
                } else {
                    $failed++;
                }
            }
            
            if ($deleted > 0) {
                $message = $deleted . ' product(s) deleted successfully';
                if ($failed > 0) {
                    $message .= ', ' . $failed . ' failed';
                }
                $this->json(['success' => true, 'message' => $message, 'deleted' => $deleted]);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to delete products'], 400);
            }
        }
    }
    
    // Toggle product status
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['product_id'] ?? ($_POST['product_id'] ?? null);
            $status = $input['status'] ?? ($_POST['status'] ?? null);
            
            if (!$id) {
                $this->json(['success' => false, 'message' => 'Product ID required'], 400);
                return;
            }
            
            $isActive = ($status === 'active') ? 1 : 0;
            $updated = $this->productModel->update($id, ['is_active' => $isActive]);
            
            if ($updated) {
                $this->json(['success' => true, 'message' => 'Product status updated']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update status'], 400);
            }
        }
    }
    
    // Update stock
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $operation = $_POST['operation']; // add or subtract
            
            if ($this->productModel->updateStock($productId, $quantity, $operation)) {
                $this->json(['success' => true, 'message' => 'Stock updated successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update stock'], 400);
            }
        }
    }
    
    // Export inventory
    public function export($format = 'excel') {
        $products = $this->productModel->getAll();
        
        if ($format === 'excel' || $format === 'csv') {
            $filename = 'inventory_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            fputcsv($output, ['Product Name', 'SKU', 'Category', 'Price', 'Stock', 'Status']);
            
            if (!empty($products)) {
                foreach ($products as $product) {
                    if (isset($product['status'])) {
                        $status = ucfirst($product['status']);
                    } elseif (isset($product['is_active'])) {
                        $status = $product['is_active'] == 1 ? 'Active' : 'Inactive';
                    } else {
                        $status = 'N/A';
                    }
                    
                    fputcsv($output, [
                        $product['product_name'] ?? $product['name'] ?? '',
                        $product['sku'] ?? '',
                        $product['category_name'] ?? 'Uncategorized',
                        number_format($product['price'] ?? 0, 2),
                        $product['stock_quantity'] ?? 0,
                        $status
                    ]);
                }
            }
            
            fclose($output);
            exit();
        }
        
        $this->setFlash('error', 'Unsupported export format');
        $this->redirect('product');
    }
    
    // Low stock alert
    public function lowStock() {
        $data['page_title'] = 'Low Stock Products';
        $data['products'] = $this->productModel->getLowStockProducts();
        
        $this->template('products/low_stock', $data);
    }
}
