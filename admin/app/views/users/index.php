<!-- User Management -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-cog"></i> User Management
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                        <a href="<?php echo BASE_URL; ?>user/add" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Statistics by Role -->
    <div class="row">
        <?php if (!empty($user_stats)): ?>
            <?php foreach ($user_stats as $stat): ?>
                <div class="col col-3">
                    <div class="card stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, 
                            <?php 
                            echo $stat['role'] == 'admin' ? '#ff69b4, #ee82ee' : 
                                 ($stat['role'] == 'reseller' ? '#ee82ee, #9370db' : 
                                 ($stat['role'] == 'customer' ? '#9370db, #ff69b4' : '#ffb6c1, #db7093')); 
                            ?>);">
                            <i class="fas fa-<?php 
                                echo $stat['role'] == 'admin' ? 'user-shield' : 
                                     ($stat['role'] == 'reseller' ? 'user-tie' : 'user'); 
                            ?>"></i>
                        </div>
                        <div class="stat-value">
                            <?php echo number_format($stat['count'] ?? 0); ?>
                        </div>
                        <div class="stat-label"><?php echo ucfirst($stat['role'] ?? 'N/A'); ?>s</div>
                        <div class="stat-change positive">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo $stat['active_count'] ?? 0; ?> active</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Users Table -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Users</h3>
                    <div class="d-flex gap-2">
                        <select class="form-control" style="width: auto;" id="filterRole">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="reseller">Reseller</option>
                            <option value="customer">Customer</option>
                        </select>
                        <select class="form-control" style="width: auto;" id="filterUserStatus">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usersTable">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAllUsers">
                                    </th>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): 
                                        // Detect ID column (user_id or id)
                                        $userId = isset($user['user_id']) ? $user['user_id'] : ($user['id'] ?? 0);
                                    ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="user-checkbox" value="<?php echo $userId; ?>">
                                            </td>
                                            <td><strong>#<?php echo str_pad($userId, 4, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        <?php echo strtoupper(substr($user['full_name'] ?? 'U', 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></strong>
                                                        <?php if ($userId == $_SESSION['admin_id']): ?>
                                                            <span class="badge badge-info" style="font-size: 10px; margin-left: 5px;">YOU</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php
                                                $role = $user['role'] ?? 'customer';
                                                $roleBadgeClass = $role == 'admin' ? 'badge-danger' : 
                                                                 ($role == 'reseller' ? 'badge-primary' : 'badge-info');
                                                $roleIcon = $role == 'admin' ? 'user-shield' : 
                                                           ($role == 'reseller' ? 'user-tie' : 'user');
                                                ?>
                                                <span class="badge <?php echo $roleBadgeClass; ?>">
                                                    <i class="fas fa-<?php echo $roleIcon; ?>"></i>
                                                    <?php echo ucfirst($role); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <label style="position: relative; display: inline-block; width: 50px; height: 24px; margin: 0;">
                                                    <input type="checkbox" 
                                                           <?php echo ($user['status'] == 'active') ? 'checked' : ''; ?>
                                                           onchange="toggleUserStatus(<?php echo $userId; ?>, this.checked ? 'active' : 'inactive')"
                                                           style="opacity: 0; width: 0; height: 0;">
                                                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px;"></span>
                                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                                                </label>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></td>
                                            <td>
                                                <?php if (!empty($user['last_login'])): ?>
                                                    <span style="font-size: 12px; color: var(--gray);">
                                                        <?php echo date('M d, Y H:i', strtotime($user['last_login'])); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Never</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo BASE_URL; ?>user/details/<?php echo $userId; ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>user/edit/<?php echo $userId; ?>" 
                                                       class="btn btn-sm btn-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($userId != $_SESSION['admin_id']): ?>
                                                        <button onclick="deleteUserModal(<?php echo $userId; ?>, '<?php echo htmlspecialchars($user['full_name'] ?? '', ENT_QUOTES); ?>')" 
                                                           class="btn btn-sm btn-danger"
                                                           title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No users found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                            <span id="userSelectedCount" style="font-size: 14px; color: var(--gray); display: none;">
                                <strong id="userSelectedNum">0</strong> user(s) selected
                            </span>
                            <button class="btn btn-sm btn-danger" onclick="bulkDeleteUsers()">
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                            <button class="btn btn-sm btn-success" onclick="bulkActivateUsers()">
                                <i class="fas fa-check"></i> Activate Selected
                            </button>
                        </div>
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
</div>

<!-- User Action Confirmation Modal -->
<div class="modal" id="userActionModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" id="userActionHeader" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff;">
            <h3 class="modal-title" id="userActionTitle" style="color: #fff;">
                <i class="fas fa-exclamation-triangle"></i> Confirm Action
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('userActionModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <i id="userActionIcon" class="fas fa-trash-alt" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
            <p id="userActionMessage" style="font-size: 16px; margin: 10px 0;"></p>
            <p style="font-size: 13px; color: var(--gray);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('userActionModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn" id="userActionBtn" onclick="executeUserAction()">
                <i class="fas fa-check"></i> <span id="userActionBtnText">Confirm</span>
            </button>
        </div>
    </div>
</div>

<style>
/* Toggle Switch Styling */
input:checked + span {
    background-color: var(--primary-pink) !important;
}

input:checked + span + span {
    transform: translateX(26px);
}
</style>

<script>
let userActionMode = null;
let userActionId = null;
let userActionIds = [];

function updateUserSelectedCount() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const count = checkboxes.length;
    const countDisplay = document.getElementById('userSelectedCount');
    const countNum = document.getElementById('userSelectedNum');
    
    if (count > 0) {
        countDisplay.style.display = 'inline';
        countNum.textContent = count;
    } else {
        countDisplay.style.display = 'none';
    }
}

function toggleUserStatus(userId, status) {
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('status', status);
    
    fetch('<?php echo BASE_URL; ?>user/toggleStatus', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'User status updated successfully');
        } else {
            showAlert('danger', data.message || 'Failed to update user status');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred');
        location.reload();
    });
}

function deleteUserModal(userId, userName) {
    userActionMode = 'deleteSingle';
    userActionId = userId;
    
    document.getElementById('userActionHeader').style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
    document.getElementById('userActionTitle').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Delete User';
    document.getElementById('userActionIcon').className = 'fas fa-user-times';
    document.getElementById('userActionIcon').style.color = '#dc3545';
    document.getElementById('userActionMessage').innerHTML = 'Are you sure you want to delete <strong>"' + userName + '"</strong>?';
    document.getElementById('userActionBtn').className = 'btn btn-danger';
    document.getElementById('userActionBtnText').textContent = 'Delete';
    
    openModal('userActionModal');
}

function bulkDeleteUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        userActionMode = null;
        document.getElementById('userActionHeader').style.background = 'linear-gradient(135deg, #ffc107 0%, #ff9800 100%)';
        document.getElementById('userActionTitle').innerHTML = '<i class="fas fa-info-circle"></i> No Selection';
        document.getElementById('userActionTitle').style.color = '#212529';
        document.getElementById('userActionIcon').className = 'fas fa-hand-pointer';
        document.getElementById('userActionIcon').style.color = '#ffc107';
        document.getElementById('userActionMessage').innerHTML = '<span style="color: #dc3545;">Please check the checkbox of the user(s) you want to delete first.</span>';
        document.getElementById('userActionBtn').style.display = 'none';
        openModal('userActionModal');
        return;
    }
    
    document.getElementById('userActionBtn').style.display = '';
    userActionMode = 'bulkDelete';
    userActionIds = ids;
    
    document.getElementById('userActionHeader').style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
    document.getElementById('userActionTitle').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Delete Users';
    document.getElementById('userActionTitle').style.color = '#fff';
    document.getElementById('userActionIcon').className = 'fas fa-users-slash';
    document.getElementById('userActionIcon').style.color = '#dc3545';
    document.getElementById('userActionMessage').innerHTML = 'Are you sure you want to delete <strong>' + ids.length + ' user(s)</strong>?';
    document.getElementById('userActionBtn').className = 'btn btn-danger';
    document.getElementById('userActionBtnText').textContent = 'Delete All';
    
    openModal('userActionModal');
}

function bulkActivateUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        userActionMode = null;
        document.getElementById('userActionHeader').style.background = 'linear-gradient(135deg, #ffc107 0%, #ff9800 100%)';
        document.getElementById('userActionTitle').innerHTML = '<i class="fas fa-info-circle"></i> No Selection';
        document.getElementById('userActionTitle').style.color = '#212529';
        document.getElementById('userActionIcon').className = 'fas fa-hand-pointer';
        document.getElementById('userActionIcon').style.color = '#ffc107';
        document.getElementById('userActionMessage').innerHTML = '<span style="color: #dc3545;">Please check the checkbox of the user(s) you want to activate first.</span>';
        document.getElementById('userActionBtn').style.display = 'none';
        openModal('userActionModal');
        return;
    }
    
    document.getElementById('userActionBtn').style.display = '';
    userActionMode = 'bulkActivate';
    userActionIds = ids;
    
    document.getElementById('userActionHeader').style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
    document.getElementById('userActionTitle').innerHTML = '<i class="fas fa-check-circle"></i> Activate Users';
    document.getElementById('userActionTitle').style.color = '#fff';
    document.getElementById('userActionIcon').className = 'fas fa-user-check';
    document.getElementById('userActionIcon').style.color = '#28a745';
    document.getElementById('userActionMessage').innerHTML = 'Activate <strong>' + ids.length + ' user(s)</strong>?';
    document.getElementById('userActionBtn').className = 'btn btn-success';
    document.getElementById('userActionBtnText').textContent = 'Activate All';
    
    openModal('userActionModal');
}

function executeUserAction() {
    closeModal(document.getElementById('userActionModal'));
    
    if (userActionMode === 'deleteSingle' && userActionId) {
        window.location.href = '<?php echo BASE_URL; ?>user/delete/' + userActionId;
    } else if (userActionMode === 'bulkDelete' && userActionIds.length > 0) {
        showLoading();
        fetch('<?php echo BASE_URL; ?>user/bulkDelete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete', ids: userActionIds })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showAlert('success', data.message || 'Users deleted successfully');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message || 'Failed to delete users');
            }
        })
        .catch(error => {
            hideLoading();
            showAlert('danger', 'An error occurred');
        });
    } else if (userActionMode === 'bulkActivate' && userActionIds.length > 0) {
        showLoading();
        fetch('<?php echo BASE_URL; ?>user/bulkActivate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'activate', ids: userActionIds })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showAlert('success', data.message || 'Users activated successfully');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message || 'Failed to activate users');
            }
        })
        .catch(error => {
            hideLoading();
            showAlert('danger', 'An error occurred');
        });
    }
    
    userActionMode = null;
    userActionId = null;
    userActionIds = [];
}

// Select all checkbox
document.getElementById('selectAllUsers').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateUserSelectedCount();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('user-checkbox')) {
        updateUserSelectedCount();
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        document.getElementById('selectAllUsers').checked = allCheckboxes.length === checkedCheckboxes.length && allCheckboxes.length > 0;
    }
});

// Filter by role
document.getElementById('filterRole').addEventListener('change', function() {
    const role = this.value;
    if (role) {
        window.location.href = '<?php echo BASE_URL; ?>user/byRole/' + role;
    } else {
        window.location.href = '<?php echo BASE_URL; ?>user';
    }
});

// Filter by status - client-side
document.getElementById('filterUserStatus').addEventListener('change', function() {
    const statusFilter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    if (!statusFilter) {
        rows.forEach(row => row.style.display = '');
        return;
    }
    
    rows.forEach(row => {
        const toggle = row.querySelector('input[type="checkbox"][onchange*="toggleUserStatus"]');
        if (toggle) {
            const isActive = toggle.checked;
            const matchActive = statusFilter === 'active' && isActive;
            const matchInactive = statusFilter === 'inactive' && !isActive;
            row.style.display = (matchActive || matchInactive) ? '' : 'none';
        }
    });
});
</script>
