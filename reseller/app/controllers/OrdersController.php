<?php
/**
 * Orders Controller
 */

class OrdersController extends Controller {
    private $orderModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->orderModel = $this->model('Order');
    }
    
    public function index() {
        $resellerId = $_SESSION['reseller_id'];
        
        // Get orders for this reseller
        $orders = $this->orderModel->getResellerOrders($resellerId);
        
        $data = [
            'pageTitle' => 'My Orders',
            'orders' => $orders
        ];
        
        $this->template('orders/index', $data);
    }
    
    public function details($id) {
        $order = $this->orderModel->getOrderDetails($id, $_SESSION['reseller_id']);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            header('Location: ' . BASE_URL . 'orders');
            exit();
        }
        
        $data = [
            'pageTitle' => 'Order Details',
            'order' => $order
        ];
        
        $this->template('orders/view', $data);
    }
}
