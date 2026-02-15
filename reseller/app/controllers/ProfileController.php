<?php
/**
 * Profile Controller
 */

class ProfileController extends Controller {
    private $userModel;
    private $resellerModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->userModel = $this->model('User');
        $this->resellerModel = $this->model('Reseller');
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $resellerId = $_SESSION['reseller_id'];
        
        $user = $this->userModel->getById($userId);
        $reseller = $this->resellerModel->getById($resellerId);
        
        // Update session with latest reseller status
        if ($reseller) {
            $_SESSION['reseller_status'] = $reseller['approval_status'];
            $_SESSION['commission_rate'] = $reseller['commission_rate'];
        }
        
        $data = [
            'pageTitle' => 'My Profile',
            'user' => $user,
            'reseller' => $reseller
        ];
        
        $this->template('profile/index', $data);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'profile');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $resellerId = $_SESSION['reseller_id'];
        
        // Update user info
        $userData = [
            'full_name' => trim($_POST['full_name']),
            'phone' => trim($_POST['phone'])
        ];
        
        // Update reseller info
        $resellerData = [
            'business_name' => trim($_POST['business_name']),
            'business_address' => trim($_POST['business_address']),
            'gcash_number' => trim($_POST['gcash_number']),
            'gcash_name' => trim($_POST['gcash_name'])
        ];
        
        $userUpdated = $this->userModel->update($userId, $userData);
        $resellerUpdated = $this->resellerModel->update($resellerId, $resellerData);
        
        if ($userUpdated && $resellerUpdated) {
            $_SESSION['success'] = 'Profile updated successfully';
            $_SESSION['full_name'] = $userData['full_name'];
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }
        
        header('Location: ' . BASE_URL . 'profile');
        exit();
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'profile');
            exit();
        }
        
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New passwords do not match';
            header('Location: ' . BASE_URL . 'profile');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        
        if (!password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['error'] = 'Current password is incorrect';
            header('Location: ' . BASE_URL . 'profile');
            exit();
        }
        
        $updated = $this->userModel->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
        
        if ($updated) {
            $_SESSION['success'] = 'Password changed successfully';
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }
        
        header('Location: ' . BASE_URL . 'profile');
        exit();
    }
}
