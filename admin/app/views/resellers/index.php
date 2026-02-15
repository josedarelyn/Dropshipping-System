<!-- Reseller Management -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Reseller Management
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>reseller/pending" class="btn btn-warning">
                            <i class="fas fa-clock"></i> Pending Applications 
                            <?php if(isset($reseller_stats['pending_count']) && $reseller_stats['pending_count'] > 0): ?>
                                <span class="badge" style="background: white; color: var(--warning);">
                                    <?php echo $reseller_stats['pending_count']; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <button class="btn btn-outline" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                        <a href="<?php echo BASE_URL; ?>reseller/add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Reseller
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reseller Statistics -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($reseller_stats['total_resellers'] ?? 0); ?>
                </div>
                <div class="stat-label">Total Resellers</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($reseller_stats['approved_count'] ?? 0); ?>
                </div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($reseller_stats['pending_count'] ?? 0); ?>
                </div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9370db 0%, #ff69b4 100%);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    ₱<?php echo number_format($reseller_stats['total_sales'] ?? 0, 0); ?>
                </div>
                <div class="stat-label">Total Sales by Resellers</div>
            </div>
        </div>
    </div>
    
    <!-- Commission Overview -->
    <div class="row">
        <div class="col col-12">
            <div class="card" style="background: var(--gradient-card);">
                <div class="card-body" style="display: flex; justify-content: space-around; align-items: center;">
                    <div style="text-align: center;">
                        <div style="font-size: 32px; font-weight: 700; color: var(--primary-pink);">
                            ₱<?php echo number_format($reseller_stats['total_commission'] ?? 0, 2); ?>
                        </div>
                        <div style="font-size: 14px; color: var(--gray); margin-top: 5px;">Total Commissions Earned</div>
                    </div>
                    <div style="height: 50px; width: 2px; background: var(--secondary-lavender);"></div>
                    <div style="text-align: center;">
                        <div style="font-size: 32px; font-weight: 700; color: var(--secondary-violet);">
                            <?php echo number_format(($reseller_stats['approved_count'] ?? 0) > 0 ? ($reseller_stats['total_sales'] ?? 0) / ($reseller_stats['approved_count']) : 0, 2); ?>
                        </div>
                        <div style="font-size: 14px; color: var(--gray); margin-top: 5px;">Average Sales per Reseller</div>
                    </div>
                    <div style="height: 50px; width: 2px; background: var(--secondary-lavender);"></div>
                    <div style="text-align: center;">
                        <div style="font-size: 32px; font-weight: 700; color: var(--secondary-purple);">
                            <?php echo DEFAULT_COMMISSION_RATE; ?>%
                        </div>
                        <div style="font-size: 14px; color: var(--gray); margin-top: 5px;">Default Commission Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resellers Table -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Resellers</h3>
                    <div class="d-flex gap-2">
                        <select class="form-control" style="width: auto;" id="filterResellerStatus">
                            <option value="">All Status</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <select class="form-control" style="width: auto;" id="sortBy">
                            <option value="recent">Most Recent</option>
                            <option value="sales">Highest Sales</option>
                            <option value="commission">Highest Commission</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="resellersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reseller Name</th>
                                    <th>Contact</th>
                                    <th>Total Sales</th>
                                    <th>Commission Rate</th>
                                    <th>Total Commission</th>
                                    <th>Status</th>
                                    <th>Joined Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($resellers)): ?>
                                    <?php foreach ($resellers as $reseller): 
                                        // Detect ID column (reseller_id or id)
                                        $resellerId = isset($reseller['reseller_id']) ? $reseller['reseller_id'] : ($reseller['id'] ?? 0);
                                    ?>
                                        <tr>
                                            <td><strong>#<?php echo str_pad($resellerId, 4, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        <?php echo strtoupper(substr($reseller['full_name'] ?? 'N', 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($reseller['full_name'] ?? 'N/A'); ?></strong>
                                                        <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                                            <?php echo htmlspecialchars($reseller['email'] ?? ''); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($reseller['phone'] ?? 'N/A'); ?></td>
                                            <td>
                                                <strong style="color: var(--primary-pink); font-size: 16px;">
                                                    ₱<?php echo number_format($reseller['total_sales'] ?? 0, 2); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo ($reseller['commission_rate'] ?? DEFAULT_COMMISSION_RATE); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <strong style="color: var(--secondary-purple);">
                                                    ₱<?php echo number_format($reseller['total_commission'] ?? 0, 2); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $reseller['status'] ?? 'pending';
                                                $badgeClass = $status == 'approved' ? 'badge-success' : 
                                                             ($status == 'pending' ? 'badge-warning' : 'badge-danger');
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($reseller['created_at'] ?? 'now')); ?></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo BASE_URL; ?>reseller/details/<?php echo $resellerId; ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($status == 'pending'): ?>
                                                        <button onclick="approveResellerAction(<?php echo $resellerId; ?>)" 
                                                                class="btn btn-sm btn-success"
                                                                title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button onclick="rejectResellerAction(<?php echo $resellerId; ?>)" 
                                                                class="btn btn-sm btn-danger"
                                                                title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button onclick="updateCommissionRateModal(<?php echo $resellerId; ?>, '<?php echo htmlspecialchars($reseller['full_name']); ?>', <?php echo $reseller['commission_rate'] ?? DEFAULT_COMMISSION_RATE; ?>)" 
                                                                class="btn btn-sm btn-primary"
                                                                title="Update Commission Rate">
                                                            <i class="fas fa-percentage"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No resellers found</td>
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

<!-- Update Commission Rate Modal -->
<div class="modal" id="commissionRateModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-percentage"></i> Update Commission Rate</h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('commissionRateModal'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Reseller</label>
                <input type="text" id="commissionResellerName" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Current Commission Rate</label>
                <input type="number" id="currentCommissionRate" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>New Commission Rate (%)</label>
                <input type="number" id="newCommissionRate" class="form-control" min="0" max="100" step="0.1">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('commissionRateModal'))">
                Cancel
            </button>
            <button class="btn btn-primary" onclick="submitCommissionRate()">
                <i class="fas fa-save"></i> Update Rate
            </button>
        </div>
    </div>
</div>

<!-- Reseller Approve/Reject Confirmation Modal -->
<div class="modal" id="resellerActionModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" id="resellerActionHeader">
            <h3 class="modal-title" id="resellerActionTitle"></h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('resellerActionModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i id="resellerActionIcon" style="font-size: 48px; margin-bottom: 15px;"></i>
            <p id="resellerActionMessage" style="font-size: 16px; margin: 10px 0;"></p>
            <div id="rejectReasonGroup" class="form-group" style="display: none; text-align: left; margin-top: 15px;">
                <label>Rejection Reason *</label>
                <textarea id="rejectReasonInput" class="form-control" rows="3" placeholder="Provide a reason for rejection..."></textarea>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('resellerActionModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn" id="resellerActionBtn" onclick="confirmResellerAction()">
                <i class="fas fa-check"></i> <span id="resellerActionBtnText">Confirm</span>
            </button>
        </div>
    </div>
</div>

<script>
let currentResellerId = null;
let pendingResellerAction = null;
let pendingResellerActionId = null;

function approveResellerAction(id) {
    pendingResellerAction = 'approve';
    pendingResellerActionId = id;
    
    document.getElementById('resellerActionHeader').style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
    document.getElementById('resellerActionTitle').innerHTML = '<i class="fas fa-check-circle"></i> Approve Reseller';
    document.getElementById('resellerActionTitle').style.color = '#fff';
    document.getElementById('resellerActionIcon').className = 'fas fa-user-check';
    document.getElementById('resellerActionIcon').style.color = '#28a745';
    document.getElementById('resellerActionMessage').innerHTML = 'Are you sure you want to <strong>approve</strong> this reseller?';
    document.getElementById('rejectReasonGroup').style.display = 'none';
    document.getElementById('resellerActionBtn').className = 'btn btn-success';
    document.getElementById('resellerActionBtnText').textContent = 'Approve';
    
    openModal('resellerActionModal');
}

function rejectResellerAction(id) {
    pendingResellerAction = 'reject';
    pendingResellerActionId = id;
    
    document.getElementById('resellerActionHeader').style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
    document.getElementById('resellerActionTitle').innerHTML = '<i class="fas fa-times-circle"></i> Reject Reseller';
    document.getElementById('resellerActionTitle').style.color = '#fff';
    document.getElementById('resellerActionIcon').className = 'fas fa-user-times';
    document.getElementById('resellerActionIcon').style.color = '#dc3545';
    document.getElementById('resellerActionMessage').innerHTML = 'Are you sure you want to <strong>reject</strong> this reseller?';
    document.getElementById('rejectReasonGroup').style.display = 'block';
    document.getElementById('rejectReasonInput').value = '';
    document.getElementById('resellerActionBtn').className = 'btn btn-danger';
    document.getElementById('resellerActionBtnText').textContent = 'Reject';
    
    openModal('resellerActionModal');
}

function confirmResellerAction() {
    if (!pendingResellerActionId) return;
    
    if (pendingResellerAction === 'approve') {
        fetch('<?php echo BASE_URL; ?>reseller/approve/' + pendingResellerActionId, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            closeModal(document.getElementById('resellerActionModal'));
            if (data.success) {
                showAlert('success', 'Reseller approved successfully');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message || 'Failed to approve reseller');
            }
        })
        .catch(() => {
            showAlert('danger', 'An error occurred');
        });
    } else if (pendingResellerAction === 'reject') {
        const reason = document.getElementById('rejectReasonInput').value.trim();
        if (!reason) {
            document.getElementById('rejectReasonInput').style.borderColor = '#dc3545';
            return;
        }
        
        const formData = new FormData();
        formData.append('rejection_reason', reason);
        
        fetch('<?php echo BASE_URL; ?>reseller/reject/' + pendingResellerActionId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            closeModal(document.getElementById('resellerActionModal'));
            if (data.success) {
                showAlert('success', 'Reseller rejected');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message || 'Failed to reject reseller');
            }
        })
        .catch(() => {
            showAlert('danger', 'An error occurred');
        });
    }
    
    pendingResellerAction = null;
    pendingResellerActionId = null;
}

function updateCommissionRateModal(resellerId, resellerName, currentRate) {
    currentResellerId = resellerId;
    document.getElementById('commissionResellerName').value = resellerName;
    document.getElementById('currentCommissionRate').value = currentRate;
    document.getElementById('newCommissionRate').value = currentRate;
    openModal('commissionRateModal');
}

function submitCommissionRate() {
    const newRate = document.getElementById('newCommissionRate').value;
    
    if (currentResellerId && newRate >= 0 && newRate <= 100) {
        const formData = new FormData();
        formData.append('reseller_id', currentResellerId);
        formData.append('commission_rate', newRate);
        
        fetch('<?php echo BASE_URL; ?>reseller/updateCommissionRate', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Commission rate updated successfully');
                closeModal(document.getElementById('commissionRateModal'));
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message || 'Failed to update commission rate');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred');
        });
    }
}

// Filter by status
document.getElementById('filterResellerStatus').addEventListener('change', function() {
    const status = this.value;
    if (status) {
        window.location.href = '<?php echo BASE_URL; ?>reseller?status=' + status;
    } else {
        window.location.href = '<?php echo BASE_URL; ?>reseller';
    }
});

// Set current filter value on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status') || '';
    const filterSelect = document.getElementById('filterResellerStatus');
    if (filterSelect) {
        filterSelect.value = currentStatus;
    }
});

// Sort functionality - client-side table sorting
document.getElementById('sortBy').addEventListener('change', function() {
    const sortField = this.value;
    const table = document.getElementById('resellersTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        if (sortField === 'sales') {
            const aVal = parseFloat(a.querySelector('td:nth-child(4)').textContent.replace(/[₱,]/g, '')) || 0;
            const bVal = parseFloat(b.querySelector('td:nth-child(4)').textContent.replace(/[₱,]/g, '')) || 0;
            return bVal - aVal;
        } else if (sortField === 'commission') {
            const aVal = parseFloat(a.querySelector('td:nth-child(6)').textContent.replace(/[₱,]/g, '')) || 0;
            const bVal = parseFloat(b.querySelector('td:nth-child(6)').textContent.replace(/[₱,]/g, '')) || 0;
            return bVal - aVal;
        }
        return 0; // 'recent' keeps original order
    });
    
    rows.forEach(row => tbody.appendChild(row));
});
</script>
