<?php
/**
 * Base Controller Class
 */

class Controller {
    
    // Load model
    protected function model($model) {
        $modelPath = BASE_PATH . '/app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        }
        return null;
    }
    
    // Load view
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: " . $view);
        }
    }
    
    // Load template with header and footer
    protected function template($view, $data = []) {
        extract($data);
        
        require_once BASE_PATH . '/app/views/layouts/header.php';
        require_once BASE_PATH . '/app/views/layouts/sidebar.php';
        
        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
        
        require_once BASE_PATH . '/app/views/layouts/footer.php';
    }
    
    // Check if user is logged in
    protected function isLoggedIn() {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }
    
    // Require login
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit();
        }
    }
    
    // JSON response
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    // Redirect
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }
    
    // Flash message
    protected function setFlash($type, $message) {
        $_SESSION['flash_type'] = $type;
        $_SESSION['flash_message'] = $message;
    }
    
    // Get flash message
    protected function getFlash() {
        if (isset($_SESSION['flash_message'])) {
            $flash = [
                'type' => $_SESSION['flash_type'],
                'message' => $_SESSION['flash_message']
            ];
            unset($_SESSION['flash_type']);
            unset($_SESSION['flash_message']);
            return $flash;
        }
        return null;
    }
    
    // Sanitize input
    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}
