<?php
/**
 * Account Controller - Customer account management
 */

class AccountController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        $user = $this->userModel->getById($_SESSION['customer_id']);
        
        $data = [
            'pageTitle' => 'My Account',
            'user' => $user
        ];
        
        $this->template('account/index', $data);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('account');
        }
        
        $userId = $_SESSION['customer_id'];
        
        $userData = [
            'full_name' => trim($_POST['full_name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone'])
        ];
        
        // Validate
        if (empty($userData['full_name']) || empty($userData['email'])) {
            $_SESSION['error'] = 'Name and email are required';
            $this->redirect('account');
        }
        
        // Check if email exists for another user
        if ($this->userModel->emailExistsForOther($userData['email'], $userId)) {
            $_SESSION['error'] = 'Email already in use';
            $this->redirect('account');
        }
        
        if ($this->userModel->update($userId, $userData)) {
            $_SESSION['success'] = 'Profile updated successfully';
            $_SESSION['full_name'] = $userData['full_name'];
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }
        
        $this->redirect('account');
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('account');
        }
        
        $userId = $_SESSION['customer_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'All password fields are required';
            $this->redirect('account');
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New passwords do not match';
            $this->redirect('account');
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('account');
        }
        
        // Verify current password
        $user = $this->userModel->getById($userId);
        if (!password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['error'] = 'Current password is incorrect';
            $this->redirect('account');
        }
        
        // Update password
        if ($this->userModel->updatePassword($userId, $newPassword)) {
            $_SESSION['success'] = 'Password changed successfully';
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }
        
        $this->redirect('account');
    }
}
