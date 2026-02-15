<!-- Commission & Payout Management -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-wallet"></i> Commission & Payout Management
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>commission/pending" class="btn btn-warning">
                            <i class="fas fa-clock"></i> Pending Payouts
                        </a>
                        <button class="btn btn-outline" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                        <a href="<?php echo BASE_URL; ?>commission/add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Commission
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Commission Statistics -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    ₱<?php echo number_format($commission_stats['pending_amount'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Pending Payouts</div>
                <div class="stat-change" style="background: rgba(255, 193, 7, 0.1); color: var(--warning);">
                    <i class="fas fa-list"></i>
                    <span><?php echo number_format($commission_stats['pending_count'] ?? 0); ?> requests</span>
                </div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    ₱<?php echo number_format($commission_stats['approved_amount'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    ₱<?php echo number_format($commission_stats['paid_amount'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Paid Out</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    ₱<?php echo number_format($commission_stats['total_amount'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Total Commissions</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col col-12">
            <div class="card" style="background: var(--gradient-card);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-around; align-items: center;">
                        <button class="btn btn-primary btn-lg" onclick="window.location.href='<?php echo BASE_URL; ?>commission/pending'">
                            <i class="fas fa-tasks"></i> Process Pending Payouts
                        </button>
                        <button class="btn btn-info btn-lg" onclick="window.location.href='<?php echo BASE_URL; ?>commission/withdrawalSchedule'">
                            <i class="fas fa-calendar-alt"></i> Withdrawal Schedule
                        </button>
                        <button class="btn btn-success btn-lg" onclick="generateCommissionReport()">
                            <i class="fas fa-file-pdf"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Commissions Table -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Commission Transactions</h3>
                    <div class="d-flex gap-2">
                        <select class="form-control" style="width: auto;" id="filterCommissionStatus">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <input type="month" class="form-control" style="width: auto;" id="filterMonth">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="commissionsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reseller</th>
                                    <th>Order Reference</th>
                                    <th>Sale Amount</th>
                                    <th>Commission Rate</th>
                                    <th>Commission Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($commissions)): ?>
                                    <?php foreach ($commissions as $commission): 
                                        // Detect ID column (commission_id or id)
                                        $commissionId = isset($commission['commission_id']) ? $commission['commission_id'] : ($commission['id'] ?? 0);
                                    ?>
                                        <tr>
                                            <td><strong>#<?php echo str_pad($commissionId, 6, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($commission['reseller_name'] ?? 'N/A'); ?></strong>
                                                <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                                    <?php echo htmlspecialchars($commission['email'] ?? ''); ?>
                                                </p>
                                            </td>
                                            <td>
                                                <?php if (isset($commission['order_id'])): ?>
                                                    <a href="<?php echo BASE_URL; ?>order/view/<?php echo $commission['order_id']; ?>" style="color: var(--primary-pink);">
                                                        Order #<?php echo $commission['order_number'] ?? $commission['order_id']; ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>₱<?php echo number_format($commission['sale_amount'] ?? 0, 2); ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo ($commission['commission_rate'] ?? 0); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <strong style="color: var(--primary-pink); font-size: 16px;">
                                                    ₱<?php echo number_format($commission['amount'] ?? 0, 2); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $commission['status'] ?? 'pending';
                                                $badgeClass = $status == 'paid' ? 'badge-success' : 
                                                             ($status == 'approved' ? 'badge-info' : 
                                                             ($status == 'pending' ? 'badge-warning' : 'badge-danger'));
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($commission['created_at'] ?? 'now')); ?></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <?php if ($status == 'pending'): ?>
                                                        <button onclick="requestCommissionOTP(<?php echo $commissionId; ?>)" 
                                                                class="btn btn-sm btn-success"
                                                                title="Approve with OTP">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    <?php elseif ($status == 'approved'): ?>
                                                        <button onclick="processPayoutAction(<?php echo $commissionId; ?>, <?php echo $commission['amount'] ?? 0; ?>, '<?php echo htmlspecialchars($commission['gcash_number'] ?? '', ENT_QUOTES); ?>')" 
                                                                class="btn btn-sm btn-primary"
                                                                title="Process Payout">
                                                            <i class="fas fa-money-bill-wave"></i> Pay
                                                        </button>
                                                    <?php elseif ($status == 'paid'): ?>
                                                        <button class="btn btn-sm btn-info" 
                                                                title="View Transaction"
                                                                onclick="viewTransaction('<?php echo $commission['transaction_id'] ?? ''; ?>')">
                                                            <i class="fas fa-receipt"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="<?php echo BASE_URL; ?>commission/resellerHistory/<?php echo $commission['reseller_id']; ?>" 
                                                       class="btn btn-sm btn-outline"
                                                       title="View History">
                                                        <i class="fas fa-history"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No commission transactions found</td>
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

<!-- GCash Payout Modal -->
<div class="modal" id="gcashModal">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-money-bill-wave"></i> Process GCash Payout
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('gcashModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="background: #e8f5e9; border-left: 4px solid #28a745; padding: 12px 15px; border-radius: 4px; margin-bottom: 15px;">
                <i class="fas fa-info-circle" style="color: #28a745;"></i>
                <span style="color: #2e7d32; font-size: 13px;"> Verify the GCash number before processing the payout.</span>
            </div>
            <div class="form-group">
                <label>Commission ID</label>
                <input type="text" id="gcashCommissionId" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Amount to Pay</label>
                <input type="text" id="gcashAmount" class="form-control" readonly style="font-weight: 700; color: var(--primary-pink); font-size: 18px;">
            </div>
            <div class="form-group">
                <label>GCash Number</label>
                <input type="tel" id="gcashNumber" class="form-control" placeholder="09XXXXXXXXX">
            </div>
            <div class="form-group">
                <label>Notes (Optional)</label>
                <textarea id="gcashNotes" class="form-control" rows="3" placeholder="Add any notes about this payout..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('gcashModal'))">
                Cancel
            </button>
            <button class="btn btn-success" onclick="confirmGCashPayout()">
                <i class="fas fa-paper-plane"></i> Send Payout
            </button>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal" id="transactionModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-receipt"></i> Transaction Details</h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('transactionModal'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i class="fas fa-receipt" style="font-size: 40px; color: var(--primary-pink); margin-bottom: 15px;"></i>
            <div class="form-group" style="text-align: left;">
                <label>Transaction ID</label>
                <input type="text" id="transactionIdDisplay" class="form-control" readonly>
            </div>
            <p style="font-size: 13px; color: var(--gray); margin-top: 10px;">
                <i class="fas fa-check-circle" style="color: #28a745;"></i> This commission has been paid out.
            </p>
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button class="btn btn-primary" onclick="closeModal(document.getElementById('transactionModal'))">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

<script>
let currentCommissionForPayout = null;

function processPayoutAction(commissionId, amount, gcashNum) {
    currentCommissionForPayout = commissionId;
    document.getElementById('gcashCommissionId').value = '#' + String(commissionId).padStart(6, '0');
    document.getElementById('gcashAmount').value = '₱' + parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
    document.getElementById('gcashNumber').value = gcashNum || '';
    document.getElementById('gcashNotes').value = '';
    openModal('gcashModal');
}

function confirmGCashPayout() {
    const gcashNumber = document.getElementById('gcashNumber').value.trim();
    if (!gcashNumber) {
        document.getElementById('gcashNumber').style.borderColor = '#dc3545';
        document.getElementById('gcashNumber').focus();
        return;
    }
    
    const formData = new FormData();
    formData.append('commission_id', currentCommissionForPayout);
    formData.append('gcash_number', gcashNumber);
    formData.append('notes', document.getElementById('gcashNotes').value.trim());
    
    fetch('<?php echo BASE_URL; ?>commission/processPayout', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Payout processed successfully');
            closeModal(document.getElementById('gcashModal'));
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('danger', data.message || 'Failed to process payout');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred');
    });
}

function viewTransaction(transactionId) {
    document.getElementById('transactionIdDisplay').value = transactionId || 'N/A';
    openModal('transactionModal');
}

function generateCommissionReport() {
    window.location.href = '<?php echo BASE_URL; ?>commission/export/csv';
}

// Filter by status - client-side
document.getElementById('filterCommissionStatus').addEventListener('change', function() {
    const statusFilter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#commissionsTable tbody tr');
    
    if (!statusFilter) {
        rows.forEach(row => row.style.display = '');
        return;
    }
    
    rows.forEach(row => {
        const statusBadge = row.querySelector('td:nth-child(7) .badge');
        if (statusBadge) {
            const status = statusBadge.textContent.trim().toLowerCase();
            row.style.display = (status === statusFilter) ? '' : 'none';
        }
    });
});

// Filter by month - client-side
document.getElementById('filterMonth').addEventListener('change', function() {
    const selectedMonth = this.value; // Format: YYYY-MM
    const rows = document.querySelectorAll('#commissionsTable tbody tr');
    
    if (!selectedMonth) {
        rows.forEach(row => row.style.display = '');
        return;
    }
    
    rows.forEach(row => {
        const dateCell = row.querySelector('td:nth-child(8)');
        if (dateCell) {
            const rowDate = new Date(dateCell.textContent.trim());
            const rowMonth = rowDate.getFullYear() + '-' + String(rowDate.getMonth() + 1).padStart(2, '0');
            row.style.display = (rowMonth === selectedMonth) ? '' : 'none';
        }
    });
});

// Remove red border on input focus
document.getElementById('gcashNumber').addEventListener('input', function() {
    this.style.borderColor = '';
});
</script>
