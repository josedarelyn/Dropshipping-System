<?php
/**
 * Wallet Controller
 */

class WalletController extends Controller {
    private $resellerModel;
    private $withdrawalModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->resellerModel = $this->model('Reseller');
        $this->withdrawalModel = $this->model('Withdrawal');
    }
    
    public function index() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Get wallet balance and withdrawal history
        $reseller = $this->resellerModel->getById($resellerId);
        $withdrawals = $this->withdrawalModel->getResellerWithdrawals($resellerId);
        
        $data = [
            'pageTitle' => 'E-Wallet',
            'reseller' => $reseller,
            'withdrawals' => $withdrawals
        ];
        
        $this->template('wallet/index', $data);
    }
    
    public function withdraw() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'wallet');
            exit();
        }
        
        $amount = floatval($_POST['amount'] ?? 0);
        $resellerId = $_SESSION['reseller_id'];
        
        if ($amount < 100) {
            $_SESSION['error'] = 'Minimum withdrawal amount is ₱100.00';
            header('Location: ' . BASE_URL . 'wallet');
            exit();
        }
        
        $reseller = $this->resellerModel->getById($resellerId);
        
        if ($amount > $reseller['wallet_balance']) {
            $_SESSION['error'] = 'Insufficient balance';
            header('Location: ' . BASE_URL . 'wallet');
            exit();
        }
        
        // Create withdrawal request
        $result = $this->withdrawalModel->createRequest($resellerId, $amount);
        
        if ($result) {
            $_SESSION['success'] = 'Withdrawal request submitted successfully';
        } else {
            $_SESSION['error'] = 'Failed to create withdrawal request';
        }
        
        header('Location: ' . BASE_URL . 'wallet');
        exit();
    }
}
