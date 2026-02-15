<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    public function login() {
        // Redirect if already logged in AS CUSTOMER (check both customer_id and role)
        if (isset($_SESSION['customer_id']) && ($_SESSION['role'] ?? '') === 'customer') {
            $this->redirect('shop');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            // Validate
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Please fill all fields';
                $this->view('auth/login');
                return;
            }
            
            // Check user
            $user = $this->userModel->getByEmail($email);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Check if user is customer
                if ($user['role'] !== 'customer') {
                    $_SESSION['error'] = 'Invalid credentials';
                    $this->view('auth/login');
                    return;
                }
                
                // Clear any conflicting session data from other portals
                unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email'], $_SESSION['admin_role']);
                unset($_SESSION['reseller_id'], $_SESSION['reseller_status'], $_SESSION['commission_rate']);
                unset($_SESSION['user_id']);
                
                // Set customer session
                $_SESSION['customer_id'] = $user['user_id'];
                $_SESSION['customer_name'] = $user['full_name'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['customer_email'] = $user['email'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = 'customer';
                
                $_SESSION['success'] = 'Welcome back, ' . $user['full_name'] . '!';
                
                // Check if there's a redirect URL saved (must be within customer portal)
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    // Only redirect if URL is within customer portal
                    if (strpos($redirect, '/customer/') !== false) {
                        header('Location: ' . $redirect);
                        exit();
                    }
                }
                
                // Default redirect to shop
                $this->redirect('shop');
            } else {
                $_SESSION['error'] = 'Invalid credentials';
            }
        }
        
        $this->view('auth/login');
    }
    
    public function register() {
        // Redirect if already logged in as customer
        if (isset($_SESSION['customer_id']) && ($_SESSION['role'] ?? '') === 'customer') {
            $this->redirect('shop');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Validate
            if (empty($fullName) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Please fill all required fields';
                $this->view('auth/register');
                return;
            }
            
            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match';
                $this->view('auth/register');
                return;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters';
                $this->view('auth/register');
                return;
            }
            
            // Check if email exists
            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email already registered';
                $this->view('auth/register');
                return;
            }
            
            // Create user
            $userData = [
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'customer'
            ];
            
            if ($this->userModel->create($userData)) {
                $_SESSION['success'] = 'Registration successful! Please login';
                $this->redirect('auth/login');
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again';
            }
        }
        
        $this->view('auth/register');
    }
    
    public function logout() {
        // Only clear customer-specific session keys (preserve other portal sessions)
        unset(
            $_SESSION['customer_id'],
            $_SESSION['customer_name'],
            $_SESSION['customer_email'],
            $_SESSION['full_name'],
            $_SESSION['email'],
            $_SESSION['role'],
            $_SESSION['cart'],
            $_SESSION['redirect_after_login'],
            $_SESSION['referral_reseller_id']
        );
        header('Location: ' . BASE_URL . 'auth/login');
        exit();
    }
}
