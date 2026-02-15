<?php
/**
 * Order Model - Customer Portal
 */

class Order extends Model {
    protected $table = 'orders';
    
    public function createOrder($orderData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Insert order
            $this->create($orderData);
            $orderId = $this->lastInsertId();
            
            // Insert order items
            foreach ($items as $item) {
                $item['order_id'] = $orderId;
                
                $sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal) 
                        VALUES (:order_id, :product_id, :product_name, :quantity, :unit_price, :subtotal)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($item);
                
                // Update product stock
                $sql = "UPDATE products SET stock_quantity = stock_quantity - :quantity 
                        WHERE product_id = :product_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id']
                ]);
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function createPaymentTransaction($orderId, $paymentMethod, $amount) {
        $sql = "INSERT INTO payment_transactions (order_id, payment_method, amount, status) 
                VALUES (:order_id, :payment_method, :amount, 'pending')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'order_id' => $orderId,
            'payment_method' => $paymentMethod,
            'amount' => $amount
        ]);
    }
    
    public function createPaymentWithDetails($data) {
        $sql = "INSERT INTO payment_transactions (order_id, payment_method, amount, reference_number, proof_of_payment, gcash_number, status) 
                VALUES (:order_id, :payment_method, :amount, :reference_number, :proof_of_payment, :gcash_number, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'order_id' => $data['order_id'],
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'reference_number' => $data['reference_number'],
            'proof_of_payment' => $data['proof_of_payment'],
            'gcash_number' => $data['gcash_number'],
            'status' => $data['status']
        ]);
    }
    
    public function getCustomerOrders($customerId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE customer_id = :customer_id 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll();
    }
    
    public function getOrderDetails($orderId, $customerId) {
        $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email, u.phone as customer_phone
                FROM {$this->table} o
                INNER JOIN users u ON o.customer_id = u.user_id
                WHERE o.order_id = :order_id AND o.customer_id = :customer_id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['order_id' => $orderId, 'customer_id' => $customerId]);
        $order = $stmt->fetch();
        
        if ($order) {
            // Get order items
            $sql = "SELECT oi.*, p.product_image
                    FROM order_items oi
                    LEFT JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = :order_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['order_id' => $orderId]);
            $order['items'] = $stmt->fetchAll();
            
            // Get payment transaction
            $sql = "SELECT * FROM payment_transactions WHERE order_id = :order_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['order_id' => $orderId]);
            $order['payment'] = $stmt->fetch();
        }
        
        return $order;
    }
    
    public function getByIdAndCustomer($orderId, $customerId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE order_id = :order_id AND customer_id = :customer_id 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['order_id' => $orderId, 'customer_id' => $customerId]);
        return $stmt->fetch();
    }
    
    public function updateStatus($orderId, $status) {
        $sql = "UPDATE {$this->table} SET order_status = :status WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'order_id' => $orderId]);
    }
}
