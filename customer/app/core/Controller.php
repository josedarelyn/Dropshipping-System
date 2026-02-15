<?php
/**
 * Base Controller Class
 */

class Controller {
    /**
     * Load model
     */
    protected function model($model) {
        $modelPath = BASE_PATH . '/app/models/' . $model . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        }
        
        die("Model not found: {$model}");
    }
    
    /**
     * Load view with layout
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: {$view}");
        }
    }
    
    /**
     * Load view with template (header/footer)
     */
    protected function template($view, $data = []) {
        extract($data);
        
        require_once BASE_PATH . '/app/views/layouts/header.php';
        
        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: {$view}");
        }
        
        require_once BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function requireAuth() {
        if (!isset($_SESSION['customer_id']) || ($_SESSION['role'] ?? '') !== 'customer') {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Get cart count for header
     */
    protected function getCartCount() {
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            return array_sum(array_column($_SESSION['cart'], 'quantity'));
        }
        return 0;
    }
}
