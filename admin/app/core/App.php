<?php
/**
 * Main Application Class - Router
 */

class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
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
}
