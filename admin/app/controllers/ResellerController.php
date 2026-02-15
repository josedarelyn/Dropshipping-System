<?php
/**
 * Reseller Controller
 */

class ResellerController extends Controller {
    private $resellerModel;
    private $userModel;
    private $notificationModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->resellerModel = new ResellerProfile();
        $this->userModel = new User();
        $this->notificationModel = new Notification();
    }
    
    // List all resellers
    public function index() {
        $status = $_GET['status'] ?? 'all';
        
        if ($status === 'all') {
            $resellers = $this->resellerModel->getAllWithUserDetails();
        } else {
            $resellers = $this->resellerModel->getByStatus($status);
        }
        
        $stats = $this->resellerModel->getStats();
        
        $data = [
            'resellers' => $resellers,
            'reseller_stats' => $stats,
            'currentStatus' => $status,
            'page_title' => 'Reseller Management'
        ];
        
        $this->template('resellers/index', $data);
    }
    
    /**
     * View reseller details
     */
    public function details($id) {
        $reseller = $this->resellerModel->getWithUserDetails($id);
        
        if (!$reseller) {
            $_SESSION['error'] = 'Reseller not found';
            $this->redirect('reseller');
        }
        
        $data = [
            'reseller' => $reseller,
            'page_title' => 'Reseller Details'
        ];
        
        $this->template('resellers/view', $data);
    }
    
    /**
     * Approve reseller
     */
    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reseller');
        }
        
        $reseller = $this->resellerModel->getById($id);
        
        if (!$reseller) {
            $_SESSION['error'] = 'Reseller not found';
            $this->redirect('reseller');
        }
        
        // Update approval status
        $updated = $this->resellerModel->update($id, [
            'approval_status' => 'approved',
            'approved_by' => $_SESSION['admin_id'],
            'approved_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($updated) {
            // Send notification to reseller
            $this->notificationModel->createNotification([
                'user_id' => $reseller['user_id'],
                'title' => 'Reseller Account Approved',
                'message' => 'Congratulations! Your reseller account has been approved. You can now start selling products.',
                'type' => 'system'
            ]);
            
            $_SESSION['success'] = 'Reseller approved successfully';
        } else {
            $_SESSION['error'] = 'Failed to approve reseller';
        }
        
        $this->redirect('reseller/details/' . $id);
    }
    
    /**
     * Reject reseller
     */
    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reseller');
        }
        
        $reason = trim($_POST['reason'] ?? '');
        
        if (empty($reason)) {
            $_SESSION['error'] = 'Rejection reason is required';
            $this->redirect('reseller/details/' . $id);
        }
        
        $reseller = $this->resellerModel->getById($id);
        
        if (!$reseller) {
            $_SESSION['error'] = 'Reseller not found';
            $this->redirect('reseller');
        }
        
        // Update approval status
        $updated = $this->resellerModel->update($id, [
            'approval_status' => 'rejected',
            'approved_by' => $_SESSION['admin_id'],
            'approved_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($updated) {
            // Send notification to reseller
            $this->notificationModel->createNotification([
                'user_id' => $reseller['user_id'],
                'title' => 'Reseller Account Rejected',
                'message' => "Unfortunately, your reseller application has been rejected. Reason: {$reason}",
                'type' => 'system'
            ]);
            
            $_SESSION['success'] = 'Reseller rejected';
        } else {
            $_SESSION['error'] = 'Failed to reject reseller';
        }
        
        $this->redirect('reseller/details/' . $id);
    }
    
    /**
     * Suspend reseller
     */
    public function suspend($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reseller');
        }
        
        $reseller = $this->resellerModel->getById($id);
        
        if (!$reseller) {
            $_SESSION['error'] = 'Reseller not found';
            $this->redirect('reseller');
        }
        
        // Update user status to suspended
        $updated = $this->userModel->update($reseller['user_id'], [
            'status' => 'suspended'
        ]);
        
        if ($updated) {
            // Send notification
            $this->notificationModel->createNotification([
                'user_id' => $reseller['user_id'],
                'title' => 'Account Suspended',
                'message' => 'Your reseller account has been suspended. Please contact support for more information.',
                'type' => 'system'
            ]);
            
            $_SESSION['success'] = 'Reseller suspended';
        } else {
            $_SESSION['error'] = 'Failed to suspend reseller';
        }
        
        $this->redirect('reseller/details/' . $id);
    }
    
    /**
     * Reactivate reseller
     */
    public function reactivate($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reseller');
        }
        
        $reseller = $this->resellerModel->getById($id);
        
        if (!$reseller) {
            $_SESSION['error'] = 'Reseller not found';
            $this->redirect('reseller');
        }
        
        // Update user status to active
        $updated = $this->userModel->update($reseller['user_id'], [
            'status' => 'active'
        ]);
        
        if ($updated) {
            // Send notification
            $this->notificationModel->createNotification([
                'user_id' => $reseller['user_id'],
                'title' => 'Account Reactivated',
                'message' => 'Your reseller account has been reactivated. You can now access your dashboard.',
                'type' => 'system'
            ]);
            
            $_SESSION['success'] = 'Reseller reactivated';
        } else {
            $_SESSION['error'] = 'Failed to reactivate reseller';
        }
        
        $this->redirect('reseller/details/' . $id);
    }
    
    // Add new reseller application
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // First, create user account
            $userData = [
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'phone' => $this->sanitize($_POST['phone']),
                'address' => $this->sanitize($_POST['address']),
                'role' => 'reseller',
                'password' => $_POST['password'],
                'status' => 'active'
            ];
            
            // Check if email already exists
            if ($this->userModel->getByEmail($userData['email'])) {
                $this->setFlash('error', 'Email already exists');
                $data['reseller'] = $_POST;
                $data['page_title'] = 'Add Reseller Application';
                $this->template('resellers/add', $data);
                return;
            }
            
            // Create user
            $userId = $this->userModel->register($userData);
            
            if ($userId) {
                // Create reseller application
                $resellerData = [
                    'user_id' => $userId,
                    'commission_rate' => $_POST['commission_rate'] ?? 10,
                    'status' => $_POST['status'] ?? 'pending',
                    'notes' => $this->sanitize($_POST['notes'] ?? ''),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                if ($this->resellerModel->create($resellerData)) {
                    $this->setFlash('success', 'Reseller application added successfully');
                    $this->redirect('reseller');
                } else {
                    $this->setFlash('error', 'Failed to create reseller application');
                }
            } else {
                $this->setFlash('error', 'Failed to create user account');
            }
        }
        
        $data['page_title'] = 'Add Reseller Application';
        $this->template('resellers/add', $data);
    }
    
    // Pending applications
    public function pending() {
        // Redirect to index with pending status filter
        $this->redirect('reseller?status=pending');
    }
    
    // Top resellers
    public function topPerformers() {
        $data['page_title'] = 'Top Performing Resellers';
        $data['resellers'] = $this->resellerModel->getTopResellers(20);
        $data['reseller_stats'] = $this->resellerModel->getStats();
        $data['currentStatus'] = 'all';
        
        $this->template('resellers/index', $data);
    }
    
    // Update commission rate
    public function updateCommissionRate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resellerId = $_POST['reseller_id'];
            $commissionRate = $_POST['commission_rate'];
            
            $updateData = ['commission_rate' => $commissionRate];
            
            if ($this->resellerModel->update($resellerId, $updateData)) {
                $this->json(['success' => true, 'message' => 'Commission rate updated successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update commission rate'], 400);
            }
        }
    }
}
