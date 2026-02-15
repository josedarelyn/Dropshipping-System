<?php
/**
 * Cart Controller
 */

class CartController extends Controller {
    private $productModel;
    
    public function __construct() {
        $this->productModel = $this->model('Product');
        
        // Initialize cart in session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    public function index() {
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->productModel->getById($productId);
            if ($product && $product['is_active'] == 1) {
                $product['quantity'] = $quantity;
                $product['item_total'] = $product['price'] * $quantity;
                $cartItems[] = $product;
                $subtotal += $product['item_total'];
            }
        }
        
        $data = [
            'pageTitle' => 'Shopping Cart',
            'cartItems' => $cartItems,
            'subtotal' => $subtotal
        ];
        
        $this->template('cart/index', $data);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if ($productId > 0 && $quantity > 0) {
                $product = $this->productModel->getById($productId);
                
                if ($product && $product['is_active'] == 1) {
                    // Check stock
                    $currentQty = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId] : 0;
                    $newQty = $currentQty + $quantity;
                    
                    if ($newQty <= $product['stock_quantity']) {
                        $_SESSION['cart'][$productId] = $newQty;
                        $_SESSION['success'] = 'Product added to cart';
                    } else {
                        $_SESSION['error'] = 'Not enough stock available';
                    }
                } else {
                    $_SESSION['error'] = 'Product not available';
                }
            }
        }
        
        // Return JSON for AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => isset($_SESSION['success']),
                'message' => $_SESSION['success'] ?? $_SESSION['error'] ?? '',
                'cartCount' => array_sum($_SESSION['cart'])
            ]);
            exit;
        }
        
        $this->redirect('cart');
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
            
            if ($productId > 0) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $product = $this->productModel->getById($productId);
                    if ($quantity <= $product['stock_quantity']) {
                        $_SESSION['cart'][$productId] = $quantity;
                    } else {
                        $_SESSION['error'] = 'Not enough stock available';
                    }
                }
            }
        }
        
        $this->redirect('cart');
    }
    
    public function remove($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = 'Item removed from cart';
        }
        
        $this->redirect('cart');
    }
    
    public function clear() {
        $_SESSION['cart'] = [];
        $_SESSION['success'] = 'Cart cleared';
        $this->redirect('cart');
    }
    
    public function count() {
        header('Content-Type: application/json');
        echo json_encode(['count' => array_sum($_SESSION['cart'])]);
        exit;
    }
}
