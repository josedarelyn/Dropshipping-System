<?php
/**
 * Sales Controller
 */

class SalesController extends Controller {
    private $resellerModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->resellerModel = $this->model('Reseller');
    }
    
    public function index() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Get sales reports
        $salesData = $this->resellerModel->getSalesReport($resellerId);
        
        $data = [
            'pageTitle' => 'Sales Report',
            'salesData' => $salesData
        ];
        
        $this->template('sales/index', $data);
    }
}
