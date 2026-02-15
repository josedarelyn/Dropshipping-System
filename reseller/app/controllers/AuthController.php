<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {
    private $userModel;
    private $resellerModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->resellerModel = new Reseller();
    }
    
    /**
     * Login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['reseller_id'])) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Please fill in all fields';
                $this->redirect('auth/login');
            }
            
            // Verify login
            $user = $this->userModel->verifyLogin($email, $password);
            
            if ($user && $user['role'] === 'reseller') {
                // Get reseller details
                $reseller = $this->resellerModel->getByUserId($user['user_id']);
                
                if (!$reseller) {
                    $_SESSION['error'] = 'Reseller account not found';
                    $this->redirect('auth/login');
                }
                
                // Clear any conflicting session data from other portals
                unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email']);
                unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email'], $_SESSION['admin_role']);
                
                // Set session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['reseller_id'] = $reseller['reseller_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['reseller_status'] = $reseller['approval_status'];
                $_SESSION['commission_rate'] = $reseller['commission_rate'];
                
                // Update last login
                $this->userModel->updateLastLogin($user['user_id']);
                
                $_SESSION['success'] = 'Welcome back, ' . $user['full_name'] . '!';
                $this->redirect('dashboard');
            } else {
                $_SESSION['error'] = 'Invalid credentials or not a reseller account';
                $this->redirect('auth/login');
            }
        }
        
        $this->view('auth/login');
    }
    
    /**
     * Register page
     */
    public function register() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['reseller_id'])) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $userData = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? '')
            ];
            
            $resellerData = [
                'business_name' => trim($_POST['business_name'] ?? ''),
                'business_address' => trim($_POST['business_address'] ?? ''),
                'gcash_number' => trim($_POST['gcash_number'] ?? ''),
                'gcash_name' => trim($_POST['gcash_name'] ?? '')
            ];
            
            // Validate
            $errors = [];
            
            if (empty($userData['full_name'])) {
                $errors[] = 'Full name is required';
            }
            
            if (empty($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required';
            }
            
            if (empty($userData['password']) || strlen($userData['password']) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
            
            if ($userData['password'] !== $userData['confirm_password']) {
                $errors[] = 'Passwords do not match';
            }
            
            if (empty($userData['phone'])) {
                $errors[] = 'Phone number is required';
            }
            
            if (empty($resellerData['business_name'])) {
                $errors[] = 'Business name is required';
            }
            
            if (empty($resellerData['gcash_number'])) {
                $errors[] = 'GCash number is required';
            }
            
            // Check if email exists
            if ($this->userModel->getByEmail($userData['email'])) {
                $errors[] = 'Email already exists';
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $_POST;
                $this->redirect('auth/register');
            }
            
            // Create reseller account
            try {
                $userId = $this->userModel->createReseller($userData, $resellerData);
                $_SESSION['success'] = 'Registration successful! Your account is pending approval. You will be notified once approved.';
                $this->redirect('auth/login');
            } catch (Exception $e) {
                $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                $this->redirect('auth/register');
            }
        }
        
        $this->view('auth/register');
    }
    
    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
