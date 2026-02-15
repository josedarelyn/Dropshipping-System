<?php
/**
 * Checkout Controller
 */

class CheckoutController extends Controller {
    private $productModel;
    private $orderModel;
    private $notificationModel;
    private $userModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->notificationModel = $this->model('Notification');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Your cart is empty';
            $this->redirect('cart');
        }
        
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->productModel->getById($productId);
            if ($product && $product['is_active'] == 1) {
                $product['quantity'] = $quantity;
                $product['item_total'] = $product['price'] * $quantity;
                $cartItems[] = $product;
                $subtotal += $product['item_total'];
            }
        }
        
        // Get customer details for pre-filling address
        $customer = $this->userModel->getById($_SESSION['customer_id']);
        
        // Get customer's default address
        $addressSql = "SELECT * FROM customer_addresses WHERE user_id = :user_id AND is_default = 1 LIMIT 1";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($addressSql);
        $stmt->execute(['user_id' => $_SESSION['customer_id']]);
        $defaultAddress = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If no default, get any address
        if (!$defaultAddress) {
            $addressSql = "SELECT * FROM customer_addresses WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
            $stmt = $db->prepare($addressSql);
            $stmt->execute(['user_id' => $_SESSION['customer_id']]);
            $defaultAddress = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $data = [
            'pageTitle' => 'Checkout',
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'customer' => $customer,
            'defaultAddress' => $defaultAddress
        ];
        
        $this->template('cart/checkout', $data);
    }
    
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('checkout');
        }
        
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Your cart is empty';
            $this->redirect('cart');
        }
        
        // Validate input
        $deliveryType = $_POST['delivery_type'] ?? '';
        $deliveryAddress = $_POST['delivery_address'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';
        $notes = $_POST['order_notes'] ?? '';
        $gcashReference = $_POST['gcash_reference'] ?? '';
        $gcashSenderNumber = $_POST['gcash_sender_number'] ?? '';
        
        if (empty($deliveryType) || empty($deliveryAddress) || empty($paymentMethod)) {
            $_SESSION['error'] = 'Please fill all required fields';
            $this->redirect('checkout');
        }
        
        // GCash validation
        $proofOfPaymentPath = null;
        if ($paymentMethod === 'gcash') {
            if (empty($gcashReference)) {
                $_SESSION['error'] = 'Please enter your GCash reference number';
                $this->redirect('checkout');
            }
            if (empty($gcashSenderNumber)) {
                $_SESSION['error'] = 'Please enter the GCash number you used';
                $this->redirect('checkout');
            }
            
            // Handle proof of payment upload
            if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['proof_of_payment'];
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($file['type'], $allowedTypes)) {
                    $_SESSION['error'] = 'Invalid file type. Only JPG and PNG are allowed.';
                    $this->redirect('checkout');
                }
                
                if ($file['size'] > $maxSize) {
                    $_SESSION['error'] = 'File size must be less than 5MB';
                    $this->redirect('checkout');
                }
                
                // Create uploads directory if not exists
                $uploadDir = BASE_PATH . '/uploads/payments/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'gcash_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $proofOfPaymentPath = 'uploads/payments/' . $filename;
                } else {
                    $_SESSION['error'] = 'Failed to upload proof of payment. Please try again.';
                    $this->redirect('checkout');
                }
            } else {
                $_SESSION['error'] = 'Please upload your proof of payment screenshot';
                $this->redirect('checkout');
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $items = [];
        $productResellerId = null; // Track if products belong to a reseller
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->productModel->getById($productId);
            if ($product && $product['is_active'] == 1) {
                // Check stock
                if ($product['stock_quantity'] < $quantity) {
                    $_SESSION['error'] = "Not enough stock for {$product['product_name']}";
                    $this->redirect('checkout');
                }
                
                // Track reseller_id from products
                if (!empty($product['reseller_id']) && $productResellerId === null) {
                    $productResellerId = $product['reseller_id'];
                }
                
                $itemTotal = $product['price'] * $quantity;
                $subtotal += $itemTotal;
                
                $items[] = [
                    'product_id' => $productId,
                    'product_name' => $product['product_name'],
                    'quantity' => $quantity,
                    'unit_price' => $product['price'],
                    'subtotal' => $itemTotal
                ];
            }
        }
        
        // Calculate delivery fee based on type
        $deliveryFee = match($deliveryType) {
            'door_to_door' => 50.00,
            'courier' => 100.00,
            'pickup' => 0.00,
            default => 0.00
        };
        
        $totalAmount = $subtotal + $deliveryFee;
        
        // Determine reseller_id - prioritize product reseller over referral
        $resellerId = $productResellerId ?? ($_SESSION['referral_reseller_id'] ?? null);
        $commissionAmount = 0;
        
        if ($resellerId) {
            $resellerModel = $this->model('Reseller');
            $reseller = $resellerModel->getById($resellerId);
            if ($reseller && ($reseller['approval_status'] ?? $reseller['status'] ?? '') === 'approved') {
                $commissionRate = $reseller['commission_rate'] / 100;
                $commissionAmount = $subtotal * $commissionRate;
            }
        }
        
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Create order
        $orderData = [
            'order_number' => $orderNumber,
            'customer_id' => $_SESSION['customer_id'],
            'reseller_id' => $resellerId,
            'total_amount' => $totalAmount,
            'commission_amount' => $commissionAmount,
            'delivery_type' => $deliveryType,
            'delivery_address' => $deliveryAddress,
            'delivery_fee' => $deliveryFee,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'notes' => $notes
        ];
        
        try {
            $orderId = $this->orderModel->createOrder($orderData, $items);
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            // Create payment transaction
            $paymentData = [
                'order_id' => $orderId,
                'payment_method' => $paymentMethod,
                'amount' => $totalAmount,
                'reference_number' => $gcashReference ?: null,
                'proof_of_payment' => $proofOfPaymentPath,
                'gcash_number' => $gcashSenderNumber ?: null,
                'status' => ($paymentMethod === 'gcash') ? 'pending' : 'pending'
            ];
            $this->orderModel->createPaymentWithDetails($paymentData);
            
            // If GCash, update order payment status to 'pending' (awaiting verification)
            if ($paymentMethod === 'gcash') {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("UPDATE orders SET payment_status = 'pending' WHERE order_id = :order_id");
                $stmt->execute(['order_id' => $orderId]);
            }
            
            // Get customer name for notifications
            $customerName = $_SESSION['full_name'] ?? 'Customer';
            
            // === NOTIFY ADMIN (SELLER/OWNER) ===
            $this->notificationModel->notifyAdminNewOrder(
                $orderId,
                $orderNumber,
                $customerName,
                $totalAmount
            );
            
            // === NOTIFY RESELLER (if order is through a reseller) ===
            if ($resellerId) {
                $this->notificationModel->notifyResellerNewOrder(
                    $resellerId,
                    $orderId,
                    $orderNumber,
                    $customerName,
                    $totalAmount,
                    $commissionAmount
                );
            }
            
            $_SESSION['success'] = 'Order placed successfully! The seller has been notified.';
            $this->redirect('orders/details/' . $orderId);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to create order: ' . $e->getMessage();
            $this->redirect('checkout');
        }
    }
}
