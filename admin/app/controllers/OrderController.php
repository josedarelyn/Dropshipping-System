<?php
/**
 * Order Controller
 */

class OrderController extends Controller {
    private $orderModel;
    private $productModel;
    private $notificationModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->orderModel = $this->model('Order');
        $this->productModel = $this->model('Product');
        $this->notificationModel = $this->model('Notification');
    }
    
    // List all orders
    public function index() {
        $data['page_title'] = 'Order Management';
        $data['orders'] = $this->orderModel->getOrdersWithCustomer();
        $data['order_stats'] = $this->orderModel->getStatistics();
        
        $this->template('orders/index', $data);
    }
    
    // View order details
    public function details($id) {
        $data['order'] = $this->orderModel->getOrderDetails($id);
        
        if (!$data['order']) {
            $this->setFlash('error', 'Order not found');
            $this->redirect('order');
        }
        
        $data['order_items'] = $this->orderModel->getOrderItems($id);
        $data['payment'] = $this->orderModel->getPaymentTransaction($id);
        $data['page_title'] = 'Order Details - #' . $data['order']['order_number'];
        
        $this->template('orders/view', $data);
    }
    
    // Update order status
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            
            if ($this->orderModel->updateStatus($orderId, $status)) {
                // Get order details for notification
                $order = $this->orderModel->getOrderDetails($orderId);
                
                if ($order) {
                    $orderNumber = $order['order_number'] ?? 'N/A';
                    $statusLabel = ucfirst($status);
                    
                    $statusMessages = [
                        'pending' => "Your order #{$orderNumber} is now pending.",
                        'processing' => "Great news! Your order #{$orderNumber} is now being processed.",
                        'shipped' => "Your order #{$orderNumber} has been shipped! It's on its way.",
                        'delivered' => "Your order #{$orderNumber} has been delivered. Thank you for shopping!",
                        'cancelled' => "Your order #{$orderNumber} has been cancelled."
                    ];
                    $message = $statusMessages[$status] ?? "Your order #{$orderNumber} status changed to {$statusLabel}.";
                    
                    // Notify customer
                    $customerId = $order['customer_id'] ?? null;
                    if ($customerId) {
                        $this->notificationModel->createNotification([
                            'user_id' => $customerId,
                            'title' => "Order {$statusLabel}",
                            'message' => $message,
                            'type' => 'order',
                            'related_id' => $orderId
                        ]);
                    }
                    
                    // Notify reseller (if order has a reseller)
                    $resellerId = $order['reseller_id'] ?? null;
                    if ($resellerId) {
                        // Get reseller's user_id from reseller_profiles
                        try {
                            $db = Database::getInstance()->getConnection();
                            $stmt = $db->prepare(
                                "SELECT user_id FROM reseller_profiles WHERE reseller_id = :rid LIMIT 1"
                            );
                            $stmt->execute(['rid' => $resellerId]);
                            $reseller = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($reseller) {
                                $this->notificationModel->createNotification([
                                    'user_id' => $reseller['user_id'],
                                    'title' => "Referred Order {$statusLabel}",
                                    'message' => "Order #{$orderNumber} from your referral has been updated to {$statusLabel}.",
                                    'type' => 'order',
                                    'related_id' => $orderId
                                ]);
                            }
                        } catch (Exception $e) {
                            // Silently fail notification to reseller
                        }
                    }
                }
                
                $this->json(['success' => true, 'message' => 'Order status updated successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update order status'], 400);
            }
        }
    }
    
    // Orders by status
    public function status($status = 'pending') {
        $data['page_title'] = ucfirst($status) . ' Orders';
        $data['orders'] = $this->orderModel->getByStatus($status);
        $data['current_status'] = $status;
        
        $this->template('orders/by_status', $data);
    }
    
    // Verify/confirm GCash payment
    public function verifyPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? 0;
            $action = $_POST['action'] ?? ''; // 'approve' or 'reject'
            
            if (!$orderId || !in_array($action, ['approve', 'reject'])) {
                $this->json(['success' => false, 'message' => 'Invalid request'], 400);
                return;
            }
            
            try {
                $db = Database::getInstance()->getConnection();
                
                if ($action === 'approve') {
                    // Update payment_transactions status to completed
                    $stmt = $db->prepare("UPDATE payment_transactions SET status = 'completed', payment_date = NOW() WHERE order_id = :oid");
                    $stmt->execute(['oid' => $orderId]);
                    
                    // Update order payment_status to paid
                    $stmt = $db->prepare("UPDATE orders SET payment_status = 'paid' WHERE order_id = :oid");
                    $stmt->execute(['oid' => $orderId]);
                    
                    // Notify customer
                    $order = $this->orderModel->getOrderDetails($orderId);
                    if ($order) {
                        $orderNumber = $order['order_number'] ?? 'N/A';
                        $customerId = $order['customer_id'] ?? null;
                        if ($customerId) {
                            $this->notificationModel->createNotification([
                                'user_id' => $customerId,
                                'title' => 'Payment Confirmed',
                                'message' => "Your GCash payment for order #{$orderNumber} has been verified and confirmed. Thank you!",
                                'type' => 'payment',
                                'related_id' => $orderId
                            ]);
                        }
                    }
                    
                    $this->json(['success' => true, 'message' => 'Payment has been verified and approved']);
                    
                } else {
                    // Reject payment
                    $stmt = $db->prepare("UPDATE payment_transactions SET status = 'failed' WHERE order_id = :oid");
                    $stmt->execute(['oid' => $orderId]);
                    
                    // Update order payment_status to failed
                    $stmt = $db->prepare("UPDATE orders SET payment_status = 'failed' WHERE order_id = :oid");
                    $stmt->execute(['oid' => $orderId]);
                    
                    // Notify customer
                    $order = $this->orderModel->getOrderDetails($orderId);
                    if ($order) {
                        $orderNumber = $order['order_number'] ?? 'N/A';
                        $customerId = $order['customer_id'] ?? null;
                        if ($customerId) {
                            $this->notificationModel->createNotification([
                                'user_id' => $customerId,
                                'title' => 'Payment Rejected',
                                'message' => "Your GCash payment for order #{$orderNumber} could not be verified. Please contact us or submit a valid proof of payment.",
                                'type' => 'payment',
                                'related_id' => $orderId
                            ]);
                        }
                    }
                    
                    $this->json(['success' => true, 'message' => 'Payment has been rejected']);
                }
                
            } catch (Exception $e) {
                $this->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
        }
    }
    
    // Order tracking
    public function tracking($orderNumber) {
        $data['page_title'] = 'Order Tracking';
        // Implementation for order tracking
        $this->template('orders/tracking', $data);
    }
}
