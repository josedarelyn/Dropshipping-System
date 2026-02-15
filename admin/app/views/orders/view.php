<div class="content-wrapper">
    <div class="page-header">
        <h2><i class="fas fa-file-invoice"></i> <?php echo $page_title; ?></h2>
        <a href="<?php echo BASE_URL; ?>order" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <!-- Order Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Order Information</h3>
            </div>
            <div class="card-body">
                <table style="width:100%; border-collapse:collapse;">
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Order Number:</td>
                        <td style="padding:10px; font-weight:700; color:var(--primary-pink);">#<?php echo $order['order_number']; ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Date:</td>
                        <td style="padding:10px;"><?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Status:</td>
                        <td style="padding:10px;">
                            <select id="orderStatusSelect" class="form-control" style="width:auto; font-weight:600;"
                                    onchange="updateOrderStatusDetail(<?php echo $order['order_id'] ?? $order['id'] ?? 0; ?>, this.value)">
                                <?php $currentStatus = $order['order_status'] ?? $order['status'] ?? 'pending'; ?>
                                <option value="pending" <?php echo $currentStatus == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $currentStatus == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $currentStatus == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $currentStatus == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $currentStatus == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Payment Status:</td>
                        <td style="padding:10px;">
                            <span class="badge badge-<?php echo ($order['payment_status'] ?? '') == 'paid' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($order['payment_status'] ?? 'pending'); ?>
                            </span>
                        </td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Delivery Type:</td>
                        <td style="padding:10px;"><?php echo ucwords(str_replace('_', ' ', $order['delivery_type'] ?? 'N/A')); ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Delivery Fee:</td>
                        <td style="padding:10px;">₱<?php echo number_format($order['delivery_fee'] ?? 0, 2); ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Total Amount:</td>
                        <td style="padding:10px; font-size:20px; font-weight:700; color:var(--primary-pink);">₱<?php echo number_format($order['total_amount'] ?? 0, 2); ?></td></tr>
                </table>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Customer Information</h3>
            </div>
            <div class="card-body">
                <table style="width:100%; border-collapse:collapse;">
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Name:</td>
                        <td style="padding:10px;"><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Email:</td>
                        <td style="padding:10px;"><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Phone:</td>
                        <td style="padding:10px;"><?php echo htmlspecialchars($order['customer_phone'] ?? 'N/A'); ?></td></tr>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Delivery Address:</td>
                        <td style="padding:10px;"><?php echo nl2br(htmlspecialchars($order['delivery_address'] ?? 'N/A')); ?></td></tr>
                    <?php if (!empty($order['notes'])): ?>
                    <tr><td style="padding:10px; font-weight:600; color:#666;">Notes:</td>
                        <td style="padding:10px;"><?php echo nl2br(htmlspecialchars($order['notes'])); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <?php if (!empty($payment) && ($payment['payment_method'] ?? '') === 'gcash'): ?>
    <!-- GCash Payment Verification -->
    <div class="card" style="margin-bottom:20px; border: 2px solid <?php echo ($payment['status'] ?? '') === 'completed' ? '#28a745' : (($payment['status'] ?? '') === 'failed' ? '#dc3545' : '#007DFF'); ?>;">
        <div class="card-header" style="background: <?php echo ($payment['status'] ?? '') === 'completed' ? '#28a74510' : (($payment['status'] ?? '') === 'failed' ? '#dc354510' : '#007DFF10'); ?>;">
            <h3 class="card-title" style="color: <?php echo ($payment['status'] ?? '') === 'completed' ? '#28a745' : (($payment['status'] ?? '') === 'failed' ? '#dc3545' : '#007DFF'); ?>;">
                <i class="fas fa-mobile-alt"></i> GCash Payment Details
                <?php if (($payment['status'] ?? '') === 'completed'): ?>
                    <span style="margin-left:10px; background:#28a745; color:white; padding:3px 12px; border-radius:20px; font-size:12px;"><i class="fas fa-check"></i> Verified</span>
                <?php elseif (($payment['status'] ?? '') === 'failed'): ?>
                    <span style="margin-left:10px; background:#dc3545; color:white; padding:3px 12px; border-radius:20px; font-size:12px;"><i class="fas fa-times"></i> Rejected</span>
                <?php else: ?>
                    <span style="margin-left:10px; background:#ffc107; color:#333; padding:3px 12px; border-radius:20px; font-size:12px;"><i class="fas fa-clock"></i> Pending Verification</span>
                <?php endif; ?>
            </h3>
        </div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                <div>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr><td style="padding:10px; font-weight:600; color:#666;">Reference Number:</td>
                            <td style="padding:10px; font-weight:700; color:#007DFF; font-size:16px;"><?php echo htmlspecialchars($payment['reference_number'] ?? 'N/A'); ?></td></tr>
                        <tr><td style="padding:10px; font-weight:600; color:#666;">Sender GCash No.:</td>
                            <td style="padding:10px; font-weight:600;"><?php echo htmlspecialchars($payment['gcash_number'] ?? 'N/A'); ?></td></tr>
                        <tr><td style="padding:10px; font-weight:600; color:#666;">Amount:</td>
                            <td style="padding:10px; font-weight:700; font-size:18px; color:var(--primary-pink);">₱<?php echo number_format($payment['amount'] ?? 0, 2); ?></td></tr>
                        <tr><td style="padding:10px; font-weight:600; color:#666;">Date Submitted:</td>
                            <td style="padding:10px;"><?php echo date('M d, Y h:i A', strtotime($payment['created_at'] ?? 'now')); ?></td></tr>
                        <?php if (!empty($payment['payment_date'])): ?>
                        <tr><td style="padding:10px; font-weight:600; color:#666;">Verified Date:</td>
                            <td style="padding:10px;"><?php echo date('M d, Y h:i A', strtotime($payment['payment_date'])); ?></td></tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if (($payment['status'] ?? '') === 'pending'): ?>
                    <div style="margin-top:20px; display:flex; gap:10px;">
                        <button onclick="verifyGcashPayment(<?php echo $order['order_id'] ?? $order['id'] ?? 0; ?>, 'approve')" 
                                class="btn btn-success" style="padding:12px 25px; font-size:14px; font-weight:600;">
                            <i class="fas fa-check-circle"></i> Approve Payment
                        </button>
                        <button onclick="verifyGcashPayment(<?php echo $order['order_id'] ?? $order['id'] ?? 0; ?>, 'reject')" 
                                class="btn btn-danger" style="padding:12px 25px; font-size:14px; font-weight:600;">
                            <i class="fas fa-times-circle"></i> Reject Payment
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div style="text-align:center;">
                    <?php if (!empty($payment['proof_of_payment'])): ?>
                        <p style="font-weight:600; color:#666; margin-bottom:10px;">Proof of Payment:</p>
                        <a href="<?php echo BASE_URL . '../customer/' . $payment['proof_of_payment']; ?>" target="_blank">
                            <img src="<?php echo BASE_URL . '../customer/' . $payment['proof_of_payment']; ?>" 
                                 style="max-width:300px; max-height:400px; border-radius:10px; border:3px solid #007DFF; cursor:pointer; box-shadow:0 4px 15px rgba(0,0,0,0.1);" 
                                 alt="GCash Proof of Payment">
                        </a>
                        <p style="font-size:12px; color:#888; margin-top:8px;">Click image to view full size</p>
                    <?php else: ?>
                        <div style="padding:40px; background:#f8f9fa; border-radius:10px;">
                            <i class="fas fa-image" style="font-size:50px; color:#ddd;"></i>
                            <p style="color:#888; margin-top:10px;">No proof of payment uploaded</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php elseif (!empty($payment)): ?>
    <!-- Non-GCash Payment Info -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-credit-card"></i> Payment Information</h3>
        </div>
        <div class="card-body">
            <table style="width:100%; border-collapse:collapse;">
                <tr><td style="padding:10px; font-weight:600; color:#666;">Method:</td>
                    <td style="padding:10px; font-weight:600;"><?php echo ucwords(str_replace('_', ' ', $payment['payment_method'] ?? 'N/A')); ?></td></tr>
                <tr><td style="padding:10px; font-weight:600; color:#666;">Amount:</td>
                    <td style="padding:10px; font-weight:700;">₱<?php echo number_format($payment['amount'] ?? 0, 2); ?></td></tr>
                <tr><td style="padding:10px; font-weight:600; color:#666;">Status:</td>
                    <td style="padding:10px;">
                        <span class="badge badge-<?php echo ($payment['status'] ?? '') == 'completed' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($payment['status'] ?? 'pending'); ?>
                        </span>
                    </td></tr>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Order Items -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-box"></i> Order Items</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th style="text-align:center;">Quantity</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($order_items)): ?>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <?php if (!empty($item['product_image'])): ?>
                                                <img src="<?php echo BASE_URL . $item['product_image']; ?>" 
                                                     alt="" style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                            <?php else: ?>
                                                <div style="width:50px; height:50px; background:#f0f0f0; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                                    <i class="fas fa-image" style="color:#ccc;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <strong><?php echo htmlspecialchars($item['product_name'] ?? 'N/A'); ?></strong>
                                        </div>
                                    </td>
                                    <td>₱<?php echo number_format($item['unit_price'] ?? 0, 2); ?></td>
                                    <td style="text-align:center;"><?php echo $item['quantity'] ?? 0; ?></td>
                                    <td style="text-align:right; font-weight:600;">₱<?php echo number_format($item['subtotal'] ?? 0, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No items found</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:600;">Subtotal:</td>
                            <td style="text-align:right; font-weight:600;">₱<?php echo number_format(($order['total_amount'] ?? 0) - ($order['delivery_fee'] ?? 0), 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:600;">Delivery Fee:</td>
                            <td style="text-align:right; font-weight:600;">₱<?php echo number_format($order['delivery_fee'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:right; font-size:18px; font-weight:700; color:var(--primary-pink);">Total:</td>
                            <td style="text-align:right; font-size:18px; font-weight:700; color:var(--primary-pink);">₱<?php echo number_format($order['total_amount'] ?? 0, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Update Modal -->
<div class="modal" id="orderStatusDetailModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-exchange-alt"></i> Update Order Status
            </h3>
            <button class="modal-close" onclick="cancelStatusChange()" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i class="fas fa-shipping-fast" style="font-size: 40px; color: #17a2b8; margin-bottom: 15px;"></i>
            <p id="statusDetailMessage" style="font-size: 16px; margin: 10px 0;"></p>
            <p style="font-size: 13px; color: var(--gray);">The customer will be notified of this change.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="cancelStatusChange()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn btn-primary" onclick="confirmStatusChange()">
                <i class="fas fa-check"></i> Confirm
            </button>
        </div>
    </div>
</div>

<!-- Payment Verification Modal -->
<div class="modal" id="paymentVerifyModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" id="paymentVerifyHeader">
            <h3 class="modal-title" id="paymentVerifyTitle"></h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('paymentVerifyModal'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i id="paymentVerifyIcon" style="font-size: 48px; margin-bottom: 15px;"></i>
            <p id="paymentVerifyMessage" style="font-size: 16px; margin: 10px 0;"></p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('paymentVerifyModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn" id="paymentVerifyBtn" onclick="confirmPaymentVerification()">
                <i class="fas fa-check"></i> <span id="paymentVerifyBtnText">Confirm</span>
            </button>
        </div>
    </div>
</div>

<script>
let pendingStatusOrderId = null;
let pendingStatusValue = null;
let pendingPaymentOrderId = null;
let pendingPaymentAction = null;

function updateOrderStatusDetail(orderId, status) {
    pendingStatusOrderId = orderId;
    pendingStatusValue = status;
    const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
    document.getElementById('statusDetailMessage').innerHTML = 
        'Update order status to <strong>"' + statusLabel + '"</strong>?';
    openModal('orderStatusDetailModal');
}

function confirmStatusChange() {
    if (!pendingStatusOrderId || !pendingStatusValue) return;
    closeModal(document.getElementById('orderStatusDetailModal'));
    
    fetch('<?php echo BASE_URL; ?>order/updateStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'order_id=' + pendingStatusOrderId + '&status=' + pendingStatusValue
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
    
    pendingStatusOrderId = null;
    pendingStatusValue = null;
}

function cancelStatusChange() {
    closeModal(document.getElementById('orderStatusDetailModal'));
    location.reload();
    pendingStatusOrderId = null;
    pendingStatusValue = null;
}

function verifyGcashPayment(orderId, action) {
    pendingPaymentOrderId = orderId;
    pendingPaymentAction = action;
    
    const header = document.getElementById('paymentVerifyHeader');
    const title = document.getElementById('paymentVerifyTitle');
    const icon = document.getElementById('paymentVerifyIcon');
    const message = document.getElementById('paymentVerifyMessage');
    const btn = document.getElementById('paymentVerifyBtn');
    const btnText = document.getElementById('paymentVerifyBtnText');
    
    if (action === 'approve') {
        header.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
        title.innerHTML = '<i class="fas fa-check-circle"></i> Approve Payment';
        title.style.color = '#fff';
        icon.className = 'fas fa-check-circle';
        icon.style.color = '#28a745';
        message.innerHTML = 'Are you sure you want to <strong>APPROVE</strong> this GCash payment?<br><small style="color: var(--gray);">This will mark the payment as confirmed.</small>';
        btn.className = 'btn btn-success';
        btnText.textContent = 'Approve Payment';
    } else {
        header.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
        title.innerHTML = '<i class="fas fa-times-circle"></i> Reject Payment';
        title.style.color = '#fff';
        icon.className = 'fas fa-times-circle';
        icon.style.color = '#dc3545';
        message.innerHTML = 'Are you sure you want to <strong>REJECT</strong> this GCash payment?<br><small style="color: var(--gray);">The customer will be notified.</small>';
        btn.className = 'btn btn-danger';
        btnText.textContent = 'Reject Payment';
    }
    
    openModal('paymentVerifyModal');
}

function confirmPaymentVerification() {
    if (!pendingPaymentOrderId || !pendingPaymentAction) return;
    closeModal(document.getElementById('paymentVerifyModal'));
    
    fetch('<?php echo BASE_URL; ?>order/verifyPayment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'order_id=' + pendingPaymentOrderId + '&action=' + pendingPaymentAction
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Error: ' + (data.message || 'Failed to verify'), 'danger');
        }
    })
    .catch(error => {
        showToast('Failed to process payment verification', 'danger');
    });
    
    pendingPaymentOrderId = null;
    pendingPaymentAction = null;
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification toast-' + type;
    toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
