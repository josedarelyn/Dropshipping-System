<?php
/**
 * User Controller
 */

class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->userModel = $this->model('User');
    }
    
    // List all users
    public function index() {
        $data['page_title'] = 'User Management';
        $data['users'] = $this->userModel->getAll('created_at', 'DESC');
        $data['user_stats'] = $this->userModel->getStatistics();
        
        $this->template('users/index', $data);
    }
    
    // View user details
    public function details($id) {
        $data['user'] = $this->userModel->getById($id);
        
        if (!$data['user']) {
            $this->setFlash('error', 'User not found');
            $this->redirect('user');
        }
        
        $data['page_title'] = 'User Details - ' . $data['user']['full_name'];
        
        $this->template('users/view', $data);
    }
    
    // Add user
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'phone' => $this->sanitize($_POST['phone']),
                'address' => $this->sanitize($_POST['address']),
                'role' => $_POST['role'],
                'password' => $_POST['password'],
                'status' => $_POST['status']
            ];
            
            // Handle photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $targetDir = UPLOAD_PATH . 'users/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $imageExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid() . '_' . time() . '.' . $imageExtension;
                $targetFile = $targetDir . $imageName;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                    $userData['profile_image'] = 'public/uploads/users/' . $imageName;
                }
            }
            
            // Check if email already exists
            if ($this->userModel->getByEmail($userData['email'])) {
                $this->setFlash('error', 'Email already exists');
                $data['user'] = $userData;
                $data['page_title'] = 'Add User';
                $this->template('users/add', $data);
                return;
            }
            
            if ($this->userModel->register($userData)) {
                $this->setFlash('success', 'User added successfully');
                $this->redirect('user');
            } else {
                $this->setFlash('error', 'Failed to add user');
            }
        }
        
        $data['page_title'] = 'Add User';
        $this->template('users/add', $data);
    }
    
    // Edit user
    public function edit($id) {
        $data['user'] = $this->userModel->getById($id);
        
        if (!$data['user']) {
            $this->setFlash('error', 'User not found');
            $this->redirect('user');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'phone' => $this->sanitize($_POST['phone']),
                'address' => $this->sanitize($_POST['address']),
                'role' => $_POST['role'],
                'status' => $_POST['status'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $this->userModel->updatePassword($id, $_POST['password']);
            }
            
            if ($this->userModel->update($id, $userData)) {
                $this->setFlash('success', 'User updated successfully');
                $this->redirect('user');
            } else {
                $this->setFlash('error', 'Failed to update user');
            }
        }
        
        $data['page_title'] = 'Edit User';
        $this->template('users/edit', $data);
    }
    
    // Delete user
    public function delete($id) {
        // Prevent deleting current logged in user
        if ($id == $_SESSION['admin_id']) {
            $this->setFlash('error', 'You cannot delete your own account');
            $this->redirect('user');
        }
        
        if ($this->userModel->delete($id)) {
            $this->setFlash('success', 'User deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete user');
        }
        
        $this->redirect('user');
    }
    
    // Toggle user status
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $newStatus = $_POST['status'];
            
            if ($this->userModel->update($userId, ['status' => $newStatus])) {
                $this->json(['success' => true, 'message' => 'User status updated successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update user status'], 400);
            }
        }
    }
    
    // Users by role
    public function byRole($role) {
        $data['page_title'] = ucfirst($role) . ' Users';
        $data['users'] = $this->userModel->getByRole($role);
        $data['current_role'] = $role;
        
        $this->template('users/by_role', $data);
    }
}
