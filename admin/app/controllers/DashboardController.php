<?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {
    private $userModel;
    private $productModel;
    private $orderModel;
    private $resellerModel;
    private $commissionModel;
    
    public function __construct() {
        $this->requireLogin();
        
        $this->userModel = $this->model('User');
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->resellerModel = $this->model('Reseller');
        $this->commissionModel = $this->model('Commission');
    }
    
    // Main dashboard
    public function index() {
        // Get statistics
        $data['page_title'] = 'Dashboard';
        $data['user_stats'] = $this->userModel->getStatistics();
        $data['product_stats'] = $this->productModel->getStatistics();
        $data['order_stats'] = $this->orderModel->getStatistics();
        $data['reseller_stats'] = $this->resellerModel->getStatistics();
        $data['commission_stats'] = $this->commissionModel->getStatistics();
        
        // Get recent orders
        $data['recent_orders'] = array_slice($this->orderModel->getOrdersWithCustomer(), 0, 10);
        
        // Get pending resellers
        $data['pending_resellers'] = $this->resellerModel->getPendingApplications();
        
        // Get low stock products
        $data['low_stock_products'] = $this->productModel->getLowStockProducts();
        
        // Get daily sales for chart
        $data['daily_sales'] = $this->orderModel->getDailySales(30);
        
        // Get monthly sales for chart
        $data['monthly_sales'] = $this->orderModel->getMonthlySales();
        
        // Get top products
        $data['top_products'] = $this->productModel->getMostSold(5);
        
        // Get top resellers
        $data['top_resellers'] = $this->resellerModel->getTopResellers(5);
        
        $this->template('dashboard/index', $data);
    }
    
    // Analytics view
    public function analytics() {
        $data['page_title'] = 'Analytics';
        
        // Get date range from request or default to last 30 days
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        
        // Get statistics for date range
        $data['order_stats'] = $this->orderModel->getStatistics($startDate, $endDate);
        $data['daily_sales'] = $this->orderModel->getDailySales(30);
        $data['monthly_sales'] = $this->orderModel->getMonthlySales();
        
        // Get product performance
        $data['top_products'] = $this->productModel->getMostSold(10);
        
        // Get reseller performance
        $data['top_resellers'] = $this->resellerModel->getTopResellers(10);
        
        $this->template('dashboard/analytics', $data);
    }
    
    // Reports
    public function reports() {
        $data['page_title'] = 'Reports';
        $this->template('dashboard/reports', $data);
    }
}
