<?php
/**
 * Orders Controller - Customer order management
 */

class OrdersController extends Controller {
    private $orderModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->orderModel = $this->model('Order');
    }
    
    public function index() {
        $customerId = $_SESSION['customer_id'];
        $orders = $this->orderModel->getCustomerOrders($customerId);
        
        $data = [
            'pageTitle' => 'My Orders',
            'orders' => $orders
        ];
        
        $this->template('orders/index', $data);
    }
    
    public function details($id) {
        $order = $this->orderModel->getOrderDetails($id, $_SESSION['customer_id']);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            $this->redirect('orders');
        }
        
        $data = [
            'pageTitle' => 'Order Details',
            'order' => $order
        ];
        
        $this->template('orders/details', $data);
    }
    
    public function cancel($id) {
        $order = $this->orderModel->getByIdAndCustomer($id, $_SESSION['customer_id']);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            $this->redirect('orders');
        }
        
        if ($order['order_status'] !== 'pending') {
            $_SESSION['error'] = 'Only pending orders can be cancelled';
            $this->redirect('orders/details/' . $id);
        }
        
        if ($this->orderModel->updateStatus($id, 'cancelled')) {
            $_SESSION['success'] = 'Order cancelled successfully';
        } else {
            $_SESSION['error'] = 'Failed to cancel order';
        }
        
        $this->redirect('orders/details/' . $id);
    }
}
