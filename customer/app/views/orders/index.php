<style>
    .orders-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-top: 30px;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .orders-table thead {
        background: var(--light-gray);
    }
    
    .orders-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark);
        border-bottom: 2px solid #ddd;
    }
    
    .orders-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    
    .orders-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .order-number {
        font-weight: 600;
        color: var(--primary);
        font-size: 15px;
    }
    
    .order-date {
        color: var(--gray);
        font-size: 14px;
    }
    
    .order-amount {
        font-size: 18px;
        font-weight: bold;
        color: var(--dark);
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background: #cfe2ff;
        color: #084298;
    }
    
    .status-shipped {
        background: #e7d6ff;
        color: #6f42c1;
    }
    
    .status-delivered {
        background: #d1e7dd;
        color: #0f5132;
    }
    
    .status-cancelled {
        background: #f8d7da;
        color: #842029;
    }
    
    .action-btn {
        padding: 8px 20px;
        background: var(--primary);
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        transition: background 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .action-btn:hover {
        background: var(--primary-dark);
    }
    
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 10px 20px;
        border: 2px solid #ddd;
        border-radius: 25px;
        background: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: var(--gray);
        transition: all 0.3s;
    }
    
    .filter-tab:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .filter-tab.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }
    
    .empty-state i {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        font-size: 24px;
        color: var(--dark);
        margin-bottom: 10px;
    }
    
    @media (max-width: 768px) {
        .orders-table {
            font-size: 14px;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 12px 8px;
        }
        
        .order-amount {
            font-size: 16px;
        }
    }
</style>

<h1><i class="fas fa-receipt"></i> My Orders</h1>

<div class="orders-container">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>" 
                onclick="filterOrders('all')">
            All Orders
        </button>
        <button class="filter-tab <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'active' : ''; ?>" 
                onclick="filterOrders('pending')">
            Pending
        </button>
        <button class="filter-tab <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'active' : ''; ?>" 
                onclick="filterOrders('processing')">
            Processing
        </button>
        <button class="filter-tab <?php echo (isset($_GET['status']) && $_GET['status'] == 'shipped') ? 'active' : ''; ?>" 
                onclick="filterOrders('shipped')">
            Shipped
        </button>
        <button class="filter-tab <?php echo (isset($_GET['status']) && $_GET['status'] == 'delivered') ? 'active' : ''; ?>" 
                onclick="filterOrders('delivered')">
            Delivered
        </button>
        <button class="filter-tab <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'active' : ''; ?>" 
                onclick="filterOrders('cancelled')">
            Cancelled
        </button>
    </div>
    
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <h3>No orders found</h3>
            <p>You haven't placed any orders yet</p>
            <a href="<?php echo BASE_URL; ?>shop" class="btn" style="margin-top: 20px;">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <div class="order-number">#<?php echo $order['order_number']; ?></div>
                            </td>
                            <td>
                                <div class="order-date">
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </div>
                                <div style="font-size: 12px; color: var(--gray);">
                                    <?php echo date('h:i A', strtotime($order['created_at'])); ?>
                                </div>
                            </td>
                            <td>
                                <div class="order-amount">₱<?php echo number_format($order['total_amount'], 2); ?></div>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="<?php echo BASE_URL; ?>orders/details/<?php echo $order['order_id']; ?>" 
                                   class="action-btn">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    function filterOrders(status) {
        if (status === 'all') {
            window.location.href = '<?php echo BASE_URL; ?>orders';
        } else {
            window.location.href = '<?php echo BASE_URL; ?>orders?status=' + status;
        }
    }
</script>
