<div class="content-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1><i class="fas fa-user"></i> Reseller Details</h1>
        <a href="<?php echo BASE_URL; ?>reseller" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Reseller Information -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Reseller Information</h3>
            </div>
            <div class="card-body">
                <table class="info-table">
                    <tr>
                        <th>Reseller ID:</th>
                        <td><strong>#<?php echo $reseller['reseller_id']; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Full Name:</th>
                        <td><?php echo htmlspecialchars($reseller['full_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($reseller['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?php echo htmlspecialchars($reseller['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>Business Name:</th>
                        <td><?php echo htmlspecialchars($reseller['business_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Business Address:</th>
                        <td><?php echo htmlspecialchars($reseller['business_address'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>GCash Number:</th>
                        <td><?php echo htmlspecialchars($reseller['gcash_number'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>GCash Name:</th>
                        <td><?php echo htmlspecialchars($reseller['gcash_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Commission Rate:</th>
                        <td><strong><?php echo number_format($reseller['commission_rate'] ?? 15, 2); ?>%</strong></td>
                    </tr>
                    <tr>
                        <th>Total Sales:</th>
                        <td>₱<?php echo number_format($reseller['total_sales'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Total Commission:</th>
                        <td>₱<?php echo number_format($reseller['total_commission'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Wallet Balance:</th>
                        <td>₱<?php echo number_format($reseller['wallet_balance'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Registered:</th>
                        <td><?php echo date('F d, Y h:i A', strtotime($reseller['registered_at'])); ?></td>
                    </tr>
                    <tr>
                        <th>Last Login:</th>
                        <td><?php echo $reseller['last_login'] ? date('F d, Y h:i A', strtotime($reseller['last_login'])) : 'Never'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Status & Actions -->
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-check-circle"></i> Status</h3>
            </div>
            <div class="card-body">
                <div class="status-item">
                    <label>Approval Status:</label>
                    <?php
                    $statusColors = [
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger'
                    ];
                    $statusColor = $statusColors[$reseller['approval_status']] ?? 'secondary';
                    ?>
                    <span class="badge badge-<?php echo $statusColor; ?>" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <?php echo ucfirst($reseller['approval_status']); ?>
                    </span>
                </div>

                <div class="status-item">
                    <label>User Account:</label>
                    <?php
                    $userStatusColors = [
                        'active' => 'success',
                        'inactive' => 'secondary',
                        'suspended' => 'danger',
                        'pending' => 'warning'
                    ];
                    $userStatusColor = $userStatusColors[$reseller['user_status']] ?? 'secondary';
                    ?>
                    <span class="badge badge-<?php echo $userStatusColor; ?>" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <?php echo ucfirst($reseller['user_status']); ?>
                    </span>
                </div>

                <?php if ($reseller['approved_at']): ?>
                    <div class="status-item">
                        <label>Approved/Rejected:</label>
                        <div><?php echo date('M d, Y h:i A', strtotime($reseller['approved_at'])); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($reseller['approved_by_name']): ?>
                    <div class="status-item">
                        <label>By:</label>
                        <div><?php echo htmlspecialchars($reseller['approved_by_name']); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h3><i class="fas fa-cog"></i> Actions</h3>
            </div>
            <div class="card-body">
                <?php if ($reseller['approval_status'] === 'pending'): ?>
                    <!-- Approve Button  -->
                    <button class="btn btn-success btn-block" onclick="showApproveModal()">
                        <i class="fas fa-check"></i> Approve Reseller
                    </button>

                    <!-- Reject Button -->
                    <button class="btn btn-danger btn-block" onclick="showRejectModal()">
                        <i class="fas fa-times"></i> Reject Application
                    </button>
                <?php endif; ?>

                <?php if ($reseller['user_status'] === 'active'): ?>
                    <button class="btn btn-warning btn-block" onclick="showSuspendModal()">
                        <i class="fas fa-ban"></i> Suspend Account
                    </button>
                        </button>
                    </form>
                <?php elseif ($reseller['user_status'] === 'suspended'): ?>
                    <button class="btn btn-success btn-block" onclick="showReactivateModal()">
                        <i class="fas fa-check-circle"></i> Reactivate Account
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-times-circle"></i> Reject Reseller Application
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('rejectModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="<?php echo BASE_URL; ?>reseller/reject/<?php echo $reseller['reseller_id']; ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="reason">Rejection Reason *</label>
                    <textarea id="reason" name="reason" class="form-control" rows="5" required 
                              placeholder="Please provide a detailed reason for rejection..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal(document.getElementById('rejectModal'))">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Reject Application</button>
            </div>
        </form>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal" id="approveModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-check-circle"></i> Approve Reseller
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('approveModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i class="fas fa-user-check" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
            <p style="font-size: 16px; margin: 10px 0;">Are you sure you want to approve <strong><?php echo htmlspecialchars($reseller['full_name']); ?></strong>?</p>
            <p style="font-size: 13px; color: var(--gray);">They will be able to start reselling products.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('approveModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <form method="POST" action="<?php echo BASE_URL; ?>reseller/approve/<?php echo $reseller['reseller_id']; ?>" style="display:inline;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Suspend Confirmation Modal -->
<div class="modal" id="suspendModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: #212529;">
            <h3 class="modal-title" style="color: #212529;">
                <i class="fas fa-ban"></i> Suspend Account
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('suspendModal'))" style="color: #212529;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i class="fas fa-ban" style="font-size: 48px; color: #ffc107; margin-bottom: 15px;"></i>
            <p style="font-size: 16px; margin: 10px 0;">Suspend <strong><?php echo htmlspecialchars($reseller['full_name']); ?></strong>'s account?</p>
            <p style="font-size: 13px; color: var(--gray);">They will no longer be able to access their reseller panel.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('suspendModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <form method="POST" action="<?php echo BASE_URL; ?>reseller/suspend/<?php echo $reseller['reseller_id']; ?>" style="display:inline;">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-ban"></i> Suspend
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reactivate Confirmation Modal -->
<div class="modal" id="reactivateModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-check-circle"></i> Reactivate Account
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('reactivateModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i class="fas fa-user-check" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
            <p style="font-size: 16px; margin: 10px 0;">Reactivate <strong><?php echo htmlspecialchars($reseller['full_name']); ?></strong>'s account?</p>
            <p style="font-size: 13px; color: var(--gray);">Their reseller access will be restored.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('reactivateModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <form method="POST" action="<?php echo BASE_URL; ?>reseller/reactivate/<?php echo $reseller['reseller_id']; ?>" style="display:inline;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Reactivate
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.row {
    display: flex;
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.col-md-8 {
    flex: 0 0 66.666%;
}

.col-md-4 {
    flex: 0 0 33.333%;
}

.info-table {
    width: 100%;
}

.info-table tr {
    border-bottom: 1px solid #e9ecef;
}

.info-table th,
.info-table td {
    padding: 0.75rem;
    text-align: left;
}

.info-table th {
    font-weight: 600;
    color: #495057;
    width: 40%;
}

.status-item {
    margin-bottom: 1rem;
}

.status-item label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.btn-block {
    display: block;
    width: 100%;
    margin-bottom: 0.5rem;
}

.mt-3 {
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }
    
    .col-md-8, .col-md-4 {
        flex: 0 0 100%;
    }
}
</style>

<script>
function showRejectModal() {
    openModal('rejectModal');
}

function showApproveModal() {
    openModal('approveModal');
}

function showSuspendModal() {
    openModal('suspendModal');
}

function showReactivateModal() {
    openModal('reactivateModal');
}
</script>
