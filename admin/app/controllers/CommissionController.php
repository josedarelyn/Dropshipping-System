<?php
/**
 * Commission Controller
 */

class CommissionController extends Controller {
    private $commissionModel;
    private $resellerModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->commissionModel = $this->model('Commission');
        $this->resellerModel = $this->model('Reseller');
    }
    
    // List all commissions
    public function index() {
        $data['page_title'] = 'Commission Management';
        $data['commissions'] = $this->commissionModel->getCommissionsWithReseller();
        $data['commission_stats'] = $this->commissionModel->getStatistics();
        
        $this->template('commissions/index', $data);
    }
    
    // Add new commission payout
    public function add() {
        // Get all approved resellers for dropdown
        $data['resellers'] = $this->resellerModel->getApprovedResellers();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commissionData = [
                'reseller_id' => $_POST['reseller_id'],
                'order_id' => $_POST['order_id'] ?? null,
                'amount' => $_POST['amount'],
                'commission_rate' => $_POST['commission_rate'],
                'status' => $_POST['status'] ?? 'pending',
                'payment_method' => $this->sanitize($_POST['payment_method'] ?? ''),
                'payment_details' => $this->sanitize($_POST['payment_details'] ?? ''),
                'notes' => $this->sanitize($_POST['notes'] ?? ''),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->commissionModel->create($commissionData)) {
                $this->setFlash('success', 'Commission payout added successfully');
                $this->redirect('commission');
            } else {
                $this->setFlash('error', 'Failed to add commission payout');
            }
        }
        
        $data['page_title'] = 'Add Commission Payout';
        $this->template('commissions/add', $data);
    }
    
    // Pending payouts
    public function pending() {
        $data['page_title'] = 'Pending Commission Payouts';
        $data['commissions'] = $this->commissionModel->getPendingPayouts();
        
        $this->template('commissions/pending', $data);
    }
    
    // Request OTP for payout approval
    public function requestOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commissionId = $_POST['commission_id'];
            
            // Generate OTP
            $otp = $this->generateOTP();
            
            // Store OTP in session with expiry
            $_SESSION['otp_' . $commissionId] = [
                'code' => $otp,
                'expires_at' => time() + OTP_EXPIRY
            ];
            
            // In production, send OTP via SMS/Email
            // For now, we'll just return it in the response
            
            $this->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp // Remove this in production
            ]);
        }
    }
    
    // Verify OTP and approve payout
    public function approvePayout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commissionId = $_POST['commission_id'];
            $otpInput = $_POST['otp'];
            
            // Verify OTP
            if (!isset($_SESSION['otp_' . $commissionId])) {
                $this->json(['success' => false, 'message' => 'OTP not found or expired'], 400);
                return;
            }
            
            $otpData = $_SESSION['otp_' . $commissionId];
            
            // Check expiry
            if (time() > $otpData['expires_at']) {
                unset($_SESSION['otp_' . $commissionId]);
                $this->json(['success' => false, 'message' => 'OTP has expired'], 400);
                return;
            }
            
            // Verify OTP code
            if ($otpInput != $otpData['code']) {
                $this->json(['success' => false, 'message' => 'Invalid OTP'], 400);
                return;
            }
            
            // OTP verified, approve the payout
            $approvedBy = $_SESSION['admin_id'];
            
            if ($this->commissionModel->approvePayout($commissionId, $approvedBy, true)) {
                unset($_SESSION['otp_' . $commissionId]);
                $this->json(['success' => true, 'message' => 'Payout approved successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to approve payout'], 400);
            }
        }
    }
    
    // Process payout (after GCash integration)
    public function processPayout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commissionId = $_POST['commission_id'];
            
            // Get commission details
            $commission = $this->commissionModel->getById($commissionId);
            
            if (!$commission || $commission['status'] !== 'approved') {
                $this->json(['success' => false, 'message' => 'Invalid commission or not approved'], 400);
                return;
            }
            
            // Process GCash payment
            // This is a placeholder - integrate with actual GCash API
            $transactionId = 'GCASH_' . uniqid();
            
            if ($this->commissionModel->processPayout($commissionId, $transactionId)) {
                $this->json(['success' => true, 'message' => 'Payout processed successfully', 'transaction_id' => $transactionId]);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to process payout'], 400);
            }
        }
    }
    
    // Withdrawal schedule settings
    public function withdrawalSchedule() {
        $data['page_title'] = 'Withdrawal Schedule Settings';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save withdrawal schedule settings
            // Implementation here
            $this->setFlash('success', 'Withdrawal schedule updated successfully');
        }
        
        $this->template('commissions/withdrawal_schedule', $data);
    }
    
    // Generate OTP
    private function generateOTP() {
        return str_pad(rand(0, pow(10, OTP_LENGTH) - 1), OTP_LENGTH, '0', STR_PAD_LEFT);
    }
    
    // View reseller commission history
    public function resellerHistory($resellerId) {
        $data['page_title'] = 'Reseller Commission History';
        $data['reseller'] = $this->resellerModel->getById($resellerId);
        $data['commissions'] = $this->commissionModel->getResellerCommissions($resellerId);
        
        $this->template('commissions/reseller_history', $data);
    }
}
