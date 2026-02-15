<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    // Login page
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            $password = $_POST['password'];
            
            // Validate input
            if (empty($email) || empty($password)) {
                $data['error'] = 'Please fill in all fields';
                $this->view('auth/login', $data);
                return;
            }
            
            // Verify credentials
            $user = $this->userModel->verifyLogin($email, $password);
            
            if ($user && $user['role'] === 'admin') {
                // Set session
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_role'] = $user['role'];
                $_SESSION['admin_photo'] = $user['profile_image'] ?? '';
                $_SESSION['logged_in_at'] = time();
                
                // Update last login
                $this->userModel->updateLastLogin($user['id']);
                
                // Redirect to dashboard
                $this->redirect('dashboard');
            } else {
                $data['error'] = 'Invalid email or password';
                $this->view('auth/login', $data);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    // Logout
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth/login');
    }
    
    // Check session timeout
    private function checkSessionTimeout() {
        if (isset($_SESSION['logged_in_at'])) {
            $elapsed = time() - $_SESSION['logged_in_at'];
            
            if ($elapsed > SESSION_TIMEOUT) {
                $this->logout();
            }
            
            // Update last activity time
            $_SESSION['logged_in_at'] = time();
        }
    }
}
