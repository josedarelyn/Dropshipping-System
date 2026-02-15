<?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {
    private $resellerModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->resellerModel = new Reseller();
    }
    
    /**
     * Dashboard home page
     */
    public function index() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Get reseller details first
        $reseller = $this->resellerModel->getById($resellerId);
        
        // Update session with latest approval status
        if ($reseller) {
            $_SESSION['reseller_status'] = $reseller['approval_status'];
            $_SESSION['commission_rate'] = $reseller['commission_rate'];
        }
        
        // Get dashboard stats
        $stats = $this->resellerModel->getDashboardStats($resellerId);
        
        // Get recent orders
        $recentOrders = $this->resellerModel->getRecentOrders($resellerId, 5);
        
        $data = [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'reseller' => $reseller,
            'pageTitle' => 'Dashboard'
        ];
        
        $this->template('dashboard/index', $data);
    }
}
