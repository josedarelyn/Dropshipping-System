<?php
/**
 * Main Application Class - Router
 */

class App {
    protected $controller = 'ShopController';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        // Check authentication for protected pages
        $this->checkAuth();
        
        $url = $this->parseUrl();
        
        // Check for controller
        if (isset($url[0]) && file_exists(BASE_PATH . '/app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }
        
        require_once BASE_PATH . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Check for method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }
        
        // Get params
        $this->params = $url ? array_values($url) : [];
        
        // Call controller method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
    
    private function checkAuth() {
        $publicPages = ['auth', 'shop', 'product', ''];
        $currentPage = isset($_GET['url']) ? explode('/', $_GET['url'])[0] : '';
        
        // Allow public pages
        if (in_array($currentPage, $publicPages) || empty($currentPage)) {
            return;
        }
        
        // Require authentication for protected pages
        $protectedPages = ['account', 'cart', 'checkout', 'orders'];
        if (in_array($currentPage, $protectedPages)) {
            if (!isset($_SESSION['customer_id']) || ($_SESSION['role'] ?? '') !== 'customer') {
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
            }
        }
    }
}
