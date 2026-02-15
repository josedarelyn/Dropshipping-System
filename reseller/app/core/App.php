<?php
/**
 * Main Application Class - Router
 */

class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        // Check authentication
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
        $publicPages = ['auth', 'login', 'register'];
        $currentPage = isset($_GET['url']) ? explode('/', $_GET['url'])[0] : 'dashboard';
        
        // Allow public pages
        if (in_array($currentPage, $publicPages)) {
            return;
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['reseller_id']) || ($_SESSION['role'] ?? '') !== 'reseller') {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        // Check reseller status
        if (isset($_SESSION['reseller_status']) && $_SESSION['reseller_status'] !== 'approved') {
            // Allow access only to dashboard and auth
            if (!in_array($currentPage, ['dashboard', 'auth', 'profile'])) {
                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            }
        }
    }
}
