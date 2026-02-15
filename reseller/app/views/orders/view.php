<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Order Details'; ?> - E-Benta Reseller</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .order-details-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .order-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .order-date {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .order-status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-processing {
            background-color: #17a2b8;
            color: white;
        }
        
        .status-shipped {
            background-color: #6f42c1;
            color: white;
        }
        
        .status-delivered {
            background-color: #28a745;
            color: white;
        }
        
        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .detail-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .detail-card h3 i {
            color: #667eea;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #666;
            font-size: 14px;
        }
        
        .detail-value {
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .items-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .items-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .order-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .item-meta {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .item-total {
            font-weight: 700;
            color: #667eea;
            font-size: 16px;
            text-align: right;
        }
        
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .summary-row.total {
            border-bottom: none;
            border-top: 2px solid #333;
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-top: 10px;
            padding-top: 15px;
        }
        
        .summary-row.commission {
            background: linear-gradient(135deg, #667eea1a 0%, #764ba21a 100%);
            margin: 10px -25px 10px -25px;
            padding: 15px 25px;
            border: none;
            color: #667eea;
            font-weight: 600;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .back-button:hover {
            background-color: #5a6268;
        }
        
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .order-item {
                flex-direction: column;
            }
            
            .item-total {
                text-align: left;
            }
            
            .item-meta {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="order-details-container">
        <div style="margin-bottom: 20px;">
            <a href="<?php echo BASE_URL; ?>orders" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
        
        <div class="order-header">
            <div>
                <div class="order-number">#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></div>
                <div class="order-date">Ordered on <?php echo date('F d, Y \a\t h:i A', strtotime($order['created_at'])); ?></div>
            </div>
            <div class="order-status-badge status-<?php echo $order['order_status']; ?>">
                <?php echo ucfirst($order['order_status']); ?>
            </div>
        </div>
        
        <div class="details-grid">
            <div class="detail-card">
                <h3><i class="fas fa-user"></i> Customer Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['customer_email']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['customer_phone'] ?? 'N/A'); ?></span>
                </div>
            </div>
            
            <div class="detail-card">
                <h3><i class="fas fa-map-marker-alt"></i> Delivery Address</h3>
                <div class="detail-item">
                    <span class="detail-value">
                        <?php echo nl2br(htmlspecialchars($order['delivery_address'] ?? 'N/A')); ?>
                    </span>
                </div>
            </div>
            
            <div class="detail-card">
                <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Method:</span>
                    <span class="detail-value"><?php echo strtoupper($order['payment_method'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span style="color: <?php echo ($order['payment_status'] === 'paid') ? '#28a745' : '#ffc107'; ?>;">
                            <?php echo ucfirst($order['payment_status'] ?? 'pending'); ?>
                        </span>
                    </span>
                </div>
                <?php if (!empty($order['payment_proof'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Proof:</span>
                    <span class="detail-value">
                        <a href="<?php echo BASE_URL . 'uploads/payments/' . $order['payment_proof']; ?>" target="_blank" style="color: #667eea;">
                            <i class="fas fa-external-link-alt"></i> View
                        </a>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="items-card">
            <h3><i class="fas fa-shopping-cart"></i> Order Items</h3>
            <?php if (!empty($order['items'])): ?>
                <?php foreach ($order['items'] as $item): ?>
                    <div class="order-item">
                        <img src="<?php echo BASE_URL . 'uploads/products/' . ($item['product_image'] ?? 'no-image.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                             class="item-image"
                             onerror="this.src='<?php echo BASE_URL; ?>public/images/no-image.jpg'">
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="item-meta">
                                <span><strong>Price:</strong> ₱<?php echo number_format($item['unit_price'], 2); ?></span>
                                <span><strong>Quantity:</strong> <?php echo $item['quantity']; ?></span>
                            </div>
                        </div>
                        <div class="item-total">
                            ₱<?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 20px;">No items found.</p>
            <?php endif; ?>
        </div>
        
        <div class="summary-card">
            <h3 style="margin-bottom: 15px;"><i class="fas fa-receipt"></i> Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₱<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Delivery Fee:</span>
                <span>₱<?php echo number_format($order['delivery_fee'] ?? 0, 2); ?></span>
            </div>
            <div class="summary-row commission">
                <span><i class="fas fa-award"></i> Your Commission:</span>
                <span>₱<?php echo number_format($order['commission_amount'] ?? 0, 2); ?></span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span>₱<?php echo number_format($order['total_amount'] + ($order['delivery_fee'] ?? 0), 2); ?></span>
            </div>
        </div>
    </div>
</body>
</html>
