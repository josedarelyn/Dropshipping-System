<!-- Order Management -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart"></i> Order Management
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel"></i> Export Orders
                        </button>
                        <button class="btn btn-primary" onclick="printContent('ordersTable')">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Statistics -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($order_stats['pending_orders'] ?? 0); ?>
                </div>
                <div class="stat-label">Pending Orders</div>
                <a href="<?php echo BASE_URL; ?>order/status/pending" style="font-size: 12px; color: var(--primary-pink); margin-top: 10px; display: block;">View All →</a>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <i class="fas fa-cog fa-spin"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($order_stats['processing_orders'] ?? 0); ?>
                </div>
                <div class="stat-label">Processing</div>
                <a href="<?php echo BASE_URL; ?>order/status/processing" style="font-size: 12px; color: var(--primary-pink); margin-top: 10px; display: block;">View All →</a>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($order_stats['shipped_orders'] ?? 0); ?>
                </div>
                <div class="stat-label">Shipped</div>
                <a href="<?php echo BASE_URL; ?>order/status/shipped" style="font-size: 12px; color: var(--primary-pink); margin-top: 10px; display: block;">View All →</a>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($order_stats['delivered_orders'] ?? 0); ?>
                </div>
                <div class="stat-label">Delivered</div>
                <a href="<?php echo BASE_URL; ?>order/status/delivered" style="font-size: 12px; color: var(--primary-pink); margin-top: 10px; display: block;">View All →</a>
            </div>
        </div>
    </div>
    
    <!-- Total Sales Card -->
    <div class="row">
        <div class="col col-12">
            <div class="card" style="background: var(--gradient-card);">
                <div class="card-body" style="display: flex; justify-content: space-around; align-items: center;">
                    <div style="text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: var(--primary-pink);">
                            ₱<?php echo number_format($order_stats['total_sales'] ?? 0, 2); ?>
                        </div>
                        <div style="font-size: 14px; color: var(--gray); margin-top: 5px;">Total Sales</div>
                    </div>
                    <div style="height: 50px; width: 2px; background: var(--secondary-lavender);"></div>
                    <div style="text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: var(--secondary-violet);">
                            <?php echo number_format($order_stats['total_orders'] ?? 0); ?>
                        </div>
                        <div style="font-size: 14px; color: var(--gray); margin-top: 5px;">Total Orders</div>
                    </div>
                    <div style="height: 50px; width: 2px; background: var(--secondary-lavender);"></div>
                    <div style="text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: var(--secondary-purple);">
                            ₱<?php echo number_format($order_stats['average_order_value'] ?? 0, 2); ?>
                        </div>
                        <div style="font-size: 14px; color: var(--gray); margin-top: 5px;">Average Order Value</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Orders</h3>
                    <div class="d-flex gap-2">
                        <select class="form-control" style="width: auto;" id="filterOrderStatus">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <input type="date" class="form-control" style="width: auto;" id="filterDate">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="ordersTable">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): 
                                        // Detect ID column (order_id or id)
                                        $orderId = isset($order['order_id']) ? $order['order_id'] : ($order['id'] ?? 0);
                                    ?>
                                        <tr>
                                            <td>
                                                <strong style="color: var(--primary-pink);">
                                                    #<?php echo $order['order_number'] ?? 'N/A'; ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></strong>
                                                <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                                    <?php echo htmlspecialchars($order['customer_email'] ?? ''); ?>
                                                </p>
                                            </td>
                                            <td><?php echo htmlspecialchars($order['customer_phone'] ?? 'N/A'); ?></td>
                                            <td><?php echo number_format($order['total_items'] ?? 0); ?> items</td>
                                            <td>
                                                <strong style="color: var(--primary-pink); font-size: 16px;">
                                                    ₱<?php echo number_format($order['total_amount'] ?? 0, 2); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php
                                                    $pm = $order['payment_method'] ?? 'N/A';
                                                    $ptStatus = $order['payment_tx_status'] ?? $order['payment_status'] ?? 'pending';
                                                    if ($pm === 'gcash') {
                                                        $badgeColor = $ptStatus === 'completed' ? '#28a745' : ($ptStatus === 'failed' ? '#dc3545' : '#007DFF');
                                                        $badgeIcon = $ptStatus === 'completed' ? 'fa-check-circle' : ($ptStatus === 'failed' ? 'fa-times-circle' : 'fa-clock');
                                                        $badgeLabel = $ptStatus === 'completed' ? 'Verified' : ($ptStatus === 'failed' ? 'Rejected' : 'Pending');
                                                        echo '<span style="display:inline-flex; align-items:center; gap:4px; background:' . $badgeColor . '15; color:' . $badgeColor . '; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:600;">';
                                                        echo '<i class="fas fa-mobile-alt"></i> GCash';
                                                        echo '</span>';
                                                        echo '<br><span style="font-size:10px; color:' . $badgeColor . ';"><i class="fas ' . $badgeIcon . '"></i> ' . $badgeLabel . '</span>';
                                                    } elseif ($pm === 'cod') {
                                                        echo '<span style="display:inline-flex; align-items:center; gap:4px; background:#4caf5015; color:#4caf50; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:600;">';
                                                        echo '<i class="fas fa-money-bill-wave"></i> COD';
                                                        echo '</span>';
                                                    } elseif ($pm === 'bank_transfer') {
                                                        echo '<span style="display:inline-flex; align-items:center; gap:4px; background:#ff980015; color:#ff9800; padding:4px 10px; border-radius:15px; font-size:11px; font-weight:600;">';
                                                        echo '<i class="fas fa-university"></i> Bank';
                                                        echo '</span>';
                                                    } else {
                                                        echo '<span style="color:#888;">N/A</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <select class="form-control" style="width: auto; font-size: 12px;" 
                                                        onchange="updateOrderStatus(<?php echo $orderId; ?>, this.value)">
                                                    <option value="pending" <?php echo (($order['order_status'] ?? '') == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="processing" <?php echo (($order['order_status'] ?? '') == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                                    <option value="shipped" <?php echo (($order['order_status'] ?? '') == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                                    <option value="delivered" <?php echo (($order['order_status'] ?? '') == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                                    <option value="cancelled" <?php echo (($order['order_status'] ?? '') == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </td>
                                            <td><?php echo date('M d, Y H:i', strtotime($order['created_at'] ?? 'now')); ?></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo BASE_URL; ?>order/details/<?php echo $orderId; ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-primary"
                                                            onclick="printInvoice(<?php echo $orderId; ?>)"
                                                            title="Print Invoice">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="pagination">
                        <a href="#" class="page-link"><i class="fas fa-chevron-left"></i></a>
                        <a href="#" class="page-link active">1</a>
                        <a href="#" class="page-link">2</a>
                        <a href="#" class="page-link">3</a>
                        <a href="#" class="page-link"><i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Update Modal -->
<div class="modal" id="orderStatusModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-exchange-alt"></i> Update Order Status
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('orderStatusModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i class="fas fa-shipping-fast" style="font-size: 40px; color: #17a2b8; margin-bottom: 15px;"></i>
            <p id="orderStatusMessage" style="font-size: 16px; margin: 10px 0;"></p>
            <p style="font-size: 13px; color: var(--gray);">The customer will be notified of this change.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="cancelOrderStatusUpdate()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn btn-primary" id="confirmStatusBtn" onclick="confirmOrderStatusUpdate()">
                <i class="fas fa-check"></i> Confirm
            </button>
        </div>
    </div>
</div>

<script>
let pendingOrderId = null;
let pendingOrderStatus = null;
let previousStatusSelect = null;

function updateOrderStatus(orderId, status) {
    pendingOrderId = orderId;
    pendingOrderStatus = status;
    
    // Save reference to select element to revert if cancelled
    const selects = document.querySelectorAll('select.form-control');
    selects.forEach(s => {
        if (s.onchange && s.closest('tr')) {
            previousStatusSelect = s;
        }
    });
    
    const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
    document.getElementById('orderStatusMessage').innerHTML = 
        'Update order status to <strong>"' + statusLabel + '"</strong>?';
    openModal('orderStatusModal');
}

function confirmOrderStatusUpdate() {
    if (!pendingOrderId || !pendingOrderStatus) return;
    closeModal(document.getElementById('orderStatusModal'));
    
    fetch('<?php echo BASE_URL; ?>order/updateStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'order_id=' + pendingOrderId + '&status=' + pendingOrderStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast('Error: ' + (data.message || 'Failed to update'), 'danger');
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        showToast('Failed to update status', 'danger');
        setTimeout(() => location.reload(), 1500);
    });
    
    pendingOrderId = null;
    pendingOrderStatus = null;
}

function cancelOrderStatusUpdate() {
    closeModal(document.getElementById('orderStatusModal'));
    location.reload(); // Revert select to original value
    pendingOrderId = null;
    pendingOrderStatus = null;
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification toast-' + type;
    toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}

function printInvoice(orderId) {
    window.open('<?php echo BASE_URL; ?>order/invoice/' + orderId, '_blank');
}

// Filter by status
document.getElementById('filterOrderStatus').addEventListener('change', function() {
    const status = this.value;
    if (status) {
        window.location.href = '<?php echo BASE_URL; ?>order/status/' + status;
    } else {
        window.location.href = '<?php echo BASE_URL; ?>order';
    }
});

// Filter by date - client-side filtering
document.getElementById('filterDate').addEventListener('change', function() {
    const selectedDate = this.value;
    const rows = document.querySelectorAll('#ordersTable table tbody tr');
    
    if (!selectedDate) {
        rows.forEach(row => row.style.display = '');
        return;
    }
    
    rows.forEach(row => {
        const dateCell = row.querySelector('td:nth-child(8)');
        if (dateCell) {
            const rowDate = new Date(dateCell.textContent.trim());
            const filterDate = new Date(selectedDate);
            if (rowDate.toDateString() === filterDate.toDateString()) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});
</script>
