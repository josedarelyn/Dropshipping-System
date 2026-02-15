<?php
/**
 * Profile Controller
 */

class ProfileController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->userModel = $this->model('User');
    }
    
    /**
     * Display user profile
     */
    public function index() {
        $userId = $_SESSION['admin_id'];
        $data['user'] = $this->userModel->getById($userId);
        
        if (!$data['user']) {
            $this->setFlash('error', 'User not found');
            $this->redirect('dashboard');
            return;
        }
        
        $data['page_title'] = 'My Profile';
        $this->template('profile/index', $data);
    }
    
    /**
     * Update profile information
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['admin_id'];
            
            $userData = [
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'phone' => $this->sanitize($_POST['phone'])
            ];
            
            // Check if email already exists for other users
            $existingUser = $this->userModel->getByEmail($userData['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                $this->setFlash('error', 'Email already exists');
                $this->redirect('profile');
                return;
            }
            
            // Update password if provided
            if (!empty($_POST['new_password'])) {
                if (strlen($_POST['new_password']) < 6) {
                    $this->setFlash('error', 'Password must be at least 6 characters');
                    $this->redirect('profile');
                    return;
                }
                
                if ($_POST['new_password'] !== $_POST['confirm_password']) {
                    $this->setFlash('error', 'Passwords do not match');
                    $this->redirect('profile');
                    return;
                }
                
                $userData['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            }
            
            if ($this->userModel->update($userId, $userData)) {
                // Update session
                $_SESSION['admin_name'] = $userData['full_name'];
                $_SESSION['admin_email'] = $userData['email'];
                
                $this->setFlash('success', 'Profile updated successfully');
            } else {
                $this->setFlash('error', 'Failed to update profile');
            }
        }
        
        $this->redirect('profile');
    }
    
    /**
     * Update profile photo
     */
    public function updatePhoto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['admin_id'];
            
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $targetDir = UPLOAD_PATH . 'users/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                    $this->setFlash('error', 'Invalid file type. Only JPG, PNG, and GIF are allowed');
                    $this->redirect('profile');
                    return;
                }
                
                // Validate file size (2MB max)
                if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
                    $this->setFlash('error', 'File size must be less than 2MB');
                    $this->redirect('profile');
                    return;
                }
                
                // Get old photo to delete
                $user = $this->userModel->getById($userId);
                $oldPhoto = $user['profile_image'] ?? '';
                
                // Generate unique filename
                $imageExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $imageName = 'user_' . $userId . '_' . uniqid() . '.' . $imageExtension;
                $targetFile = $targetDir . $imageName;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                    $photoPath = 'public/uploads/users/' . $imageName;
                    
                    // Update database
                    if ($this->userModel->update($userId, ['profile_image' => $photoPath])) {
                        // Delete old photo if exists
                        if (!empty($oldPhoto) && file_exists($oldPhoto)) {
                            unlink($oldPhoto);
                        }
                        
                        // Update session
                        $_SESSION['admin_photo'] = $photoPath;
                        
                        $this->setFlash('success', 'Profile photo updated successfully');
                    } else {
                        $this->setFlash('error', 'Failed to update photo in database');
                    }
                } else {
                    $this->setFlash('error', 'Failed to upload photo');
                }
            } else {
                $this->setFlash('error', 'No photo selected');
            }
        }
        
        $this->redirect('profile');
    }
    
    /**
     * Delete profile photo
     */
    public function deletePhoto() {
        $userId = $_SESSION['admin_id'];
        $user = $this->userModel->getById($userId);
        $photoPath = $user['profile_image'] ?? '';
        
        if (!empty($photoPath)) {
            // Update database
            if ($this->userModel->update($userId, ['profile_image' => null])) {
                // Delete file
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
                
                // Update session
                $_SESSION['admin_photo'] = '';
                
                $this->setFlash('success', 'Profile photo removed successfully');
            } else {
                $this->setFlash('error', 'Failed to remove photo');
            }
        }
        
        $this->redirect('profile');
    }
}
