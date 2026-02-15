<?php
/**
 * Shop Controller - Main product browsing
 */

class ShopController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $offset = ($page - 1) * PRODUCTS_PER_PAGE;
        
        // Get products
        if ($search) {
            $products = $this->productModel->search($search, PRODUCTS_PER_PAGE, $offset);
            $totalProducts = $this->productModel->searchCount($search);
        } elseif ($categoryId) {
            $products = $this->productModel->getByCategory($categoryId, PRODUCTS_PER_PAGE, $offset);
            $totalProducts = $this->productModel->getCategoryCount($categoryId);
        } else {
            $products = $this->productModel->getAllActive(PRODUCTS_PER_PAGE, $offset);
            $totalProducts = $this->productModel->getActiveCount();
        }
        
        $totalPages = ceil($totalProducts / PRODUCTS_PER_PAGE);
        $categories = $this->categoryModel->getAllActive();
        
        $data = [
            'pageTitle' => 'Shop',
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentCategory' => $categoryId,
            'searchQuery' => $search
        ];
        
        $this->template('shop/index', $data);
    }
    
    public function product($id) {
        $product = $this->productModel->getByIdWithDetails($id);
        
        if (!$product) {
            $_SESSION['error'] = 'Product not found';
            $this->redirect('shop');
        }
        
        // Get related products
        $relatedProducts = $this->productModel->getRelated($product['category_id'], $id, 4);
        
        $data = [
            'pageTitle' => $product['product_name'],
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];
        
        $this->template('shop/product', $data);
    }
}
