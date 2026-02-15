<?php
/**
 * Commission Controller
 */

class CommissionController extends Controller {
    private $commissionModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->commissionModel = $this->model('Commission');
    }
    
    public function index() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Get commission history
        $commissions = $this->commissionModel->getResellerCommissions($resellerId);
        $stats = $this->commissionModel->getCommissionStats($resellerId);
        
        $data = [
            'pageTitle' => 'Commissions',
            'commissions' => $commissions,
            'stats' => $stats
        ];
        
        $this->template('commission/index', $data);
    }
}
