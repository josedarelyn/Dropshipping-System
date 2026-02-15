<!-- Dashboard Overview -->
<div class="fade-in">
    <!-- Statistics Cards Row -->
    <div class="row">
        <!-- Total Sales -->
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">
                    ₱<?php echo number_format($order_stats['total_sales'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Total Sales</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>12.5% from last month</span>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ee82ee 0%, #9370db 100%);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($order_stats['total_orders'] ?? 0); ?>
                </div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>8.3% increase</span>
                </div>
            </div>
        </div>
        
        <!-- Active Resellers -->
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9370db 0%, #ff69b4 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($reseller_stats['approved_count'] ?? 0); ?>
                </div>
                <div class="stat-label">Active Resellers</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span><?php echo ($reseller_stats['pending_count'] ?? 0); ?> pending</span>
                </div>
            </div>
        </div>
        
        <!-- Total Products -->
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffb6c1 0%, #db7093 100%);">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($product_stats['total_products'] ?? 0); ?>
                </div>
                <div class="stat-label">Total Products</div>
                <div class="stat-change <?php echo ($product_stats['low_stock_count'] ?? 0) > 0 ? 'negative' : 'positive'; ?>">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo ($product_stats['low_stock_count'] ?? 0); ?> low stock</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Status Cards -->
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
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area"></i> Sales Overview
                    </h3>
                    <div>
                        <select id="salesPeriod" class="form-control" style="width: auto; display: inline-block;">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                            <option value="365">Last Year</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Commission Statistics -->
        <div class="col col-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-wallet"></i> Commission Stats
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="commissionChart"></canvas>
                    </div>
                    <div style="margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--secondary-lavender);">
                            <span><i class="fas fa-circle" style="color: #ff69b4;"></i> Pending</span>
                            <strong>₱<?php echo number_format($commission_stats['pending_amount'] ?? 0, 2); ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--secondary-lavender);">
                            <span><i class="fas fa-circle" style="color: #ee82ee;"></i> Approved</span>
                            <strong>₱<?php echo number_format($commission_stats['approved_amount'] ?? 0, 2); ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                            <span><i class="fas fa-circle" style="color: #9370db;"></i> Paid</span>
                            <strong>₱<?php echo number_format($commission_stats['paid_amount'] ?? 0, 2); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Tables Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-bag"></i> Recent Orders
                    </h3>
                    <a href="<?php echo BASE_URL; ?>order" class="btn btn-sm btn-primary">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_orders)): ?>
                                    <?php foreach (array_slice($recent_orders, 0, 5) as $order): ?>
                                        <tr>
                                            <td><strong>#<?php echo $order['order_number'] ?? 'N/A'; ?></strong></td>
                                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td>
                                            <td><strong>₱<?php echo number_format($order['total_amount'] ?? 0, 2); ?></strong></td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    $os = $order['order_status'] ?? $order['status'] ?? '';
                                                    echo ($os == 'delivered') ? 'success' : 
                                                         (($os == 'pending') ? 'warning' : 
                                                         (($os == 'cancelled') ? 'danger' : 'info')); 
                                                ?>">
                                                    <?php echo ucfirst($os ?: 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'] ?? 'now')); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No recent orders</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Reseller Applications -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-check"></i> Pending Reseller Applications
                    </h3>
                    <a href="<?php echo BASE_URL; ?>reseller/pending" class="btn btn-sm btn-primary">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pending_resellers)): ?>
                                    <?php foreach (array_slice($pending_resellers, 0, 5) as $reseller): 
                                        $resellerId = isset($reseller['reseller_id']) ? $reseller['reseller_id'] : ($reseller['id'] ?? 0);
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($reseller['full_name'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($reseller['email'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($reseller['phone'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($reseller['created_at'] ?? 'now')); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="dashboardApproveReseller(<?php echo $resellerId; ?>, '<?php echo htmlspecialchars($reseller['full_name'] ?? 'N/A', ENT_QUOTES); ?>')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="dashboardRejectReseller(<?php echo $resellerId; ?>, '<?php echo htmlspecialchars($reseller['full_name'] ?? 'N/A', ENT_QUOTES); ?>')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No pending applications</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Products and Resellers -->
    <div class="row">
        <!-- Top Products -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-fire"></i> Top Selling Products
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_products)): ?>
                        <?php foreach ($top_products as $index => $product): ?>
                            <div style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--secondary-lavender);">
                                <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="margin: 0; font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($product['name'] ?? 'N/A'); ?></h4>
                                    <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                        <?php echo $product['total_sold'] ?? 0; ?> units sold
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: var(--primary-pink);">₱<?php echo number_format($product['price'] ?? 0, 2); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No products data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Top Resellers -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy"></i> Top Performing Resellers
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_resellers)): ?>
                        <?php foreach ($top_resellers as $index => $reseller): ?>
                            <div style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--secondary-lavender);">
                                <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="margin: 0; font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($reseller['full_name'] ?? 'N/A'); ?></h4>
                                    <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                        <?php echo htmlspecialchars($reseller['email'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: var(--primary-pink); font-size: 16px;">₱<?php echo number_format($reseller['total_sales'] ?? 0, 2); ?></strong>
                                    <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                        Commission: ₱<?php echo number_format($reseller['total_commission'] ?? 0, 2); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No reseller data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    <?php if (!empty($low_stock_products) && count($low_stock_products) > 0): ?>
        <div class="row">
            <div class="col col-12">
                <div class="card" style="border-left: 4px solid var(--warning);">
                    <div class="card-header" style="background: rgba(255, 193, 7, 0.1);">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i> 
                            Low Stock Alert
                        </h3>
                        <a href="<?php echo BASE_URL; ?>product/lowStock" class="btn btn-sm btn-warning">
                            Manage Stock <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Current Stock</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($low_stock_products, 0, 5) as $product): 
                                        $productId = isset($product['product_id']) ? $product['product_id'] : ($product['id'] ?? 0);
                                        $pName = htmlspecialchars($product['product_name'] ?? $product['name'] ?? 'N/A');
                                        $pStock = $product['stock_quantity'] ?? 0;
                                    ?>
                                        <tr>
                                            <td><strong><?php echo $pName; ?></strong></td>
                                            <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    <?php echo $pStock; ?> units
                                                </span>
                                            </td>
                                            <td>₱<?php echo number_format($product['price'] ?? 0, 2); ?></td>
                                            <td><span class="badge badge-warning">Low Stock</span></td>
                                            <td>
                                                <div class="d-flex gap-1" style="align-items: center;">
                                                    <input type="number" class="form-control" 
                                                           id="dashRestock_<?php echo $productId; ?>"
                                                           min="1" value="10" 
                                                           style="width: 70px; padding: 4px 6px; font-size: 12px;">
                                                    <button onclick="dashboardRestock(<?php echo $productId; ?>, '<?php echo $pName; ?>')" 
                                                            class="btn btn-sm btn-primary" title="Add Stock">
                                                        <i class="fas fa-plus"></i> Restock
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript for Charts -->
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = <?php echo json_encode($daily_sales ?? []); ?>;
    
    const salesLabels = salesData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });
    const salesValues = salesData.map(item => parseFloat(item.sales));
    
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Sales (₱)',
                data: salesValues,
                borderColor: '#ff69b4',
                backgroundColor: 'rgba(255, 105, 180, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ff69b4',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 105, 180, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#ff69b4',
                    borderWidth: 2,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(230, 230, 250, 0.5)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Commission Chart (Doughnut)
    const commissionCtx = document.getElementById('commissionChart').getContext('2d');
    const commissionStats = <?php echo json_encode($commission_stats ?? []); ?>;
    
    new Chart(commissionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Paid'],
            datasets: [{
                data: [
                    parseFloat(commissionStats.pending_amount || 0),
                    parseFloat(commissionStats.approved_amount || 0),
                    parseFloat(commissionStats.paid_amount || 0)
                ],
                backgroundColor: [
                    '#ff69b4',
                    '#ee82ee',
                    '#9370db'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 105, 180, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#ff69b4',
                    borderWidth: 2,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ₱' + context.parsed.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });
    
    // Dashboard Restock Function
    function dashboardRestock(productId, productName) {
        const qtyInput = document.getElementById('dashRestock_' + productId);
        const qty = parseInt(qtyInput.value);
        
        if (!qty || qty <= 0) {
            alert('Please enter a valid quantity');
            return;
        }
        
        const btn = qtyInput.nextElementSibling;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', qty);
        formData.append('operation', 'add');
        
        fetch('<?php echo BASE_URL; ?>product/updateStock', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="fas fa-check"></i> Done';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                
                // Update stock badge
                const row = btn.closest('tr');
                const stockBadge = row.querySelector('.badge-danger');
                if (stockBadge) {
                    const currentStock = parseInt(stockBadge.textContent) || 0;
                    const newStock = currentStock + qty;
                    stockBadge.textContent = newStock + ' units';
                    if (newStock > 10) {
                        stockBadge.className = 'badge badge-success';
                    }
                }
                row.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
                
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                    btn.disabled = false;
                }, 3000);
            } else {
                alert(data.message || 'Failed to restock');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while restocking');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }
    
    // Reseller Actions
    let pendingDashResellerId = null;
    let pendingDashAction = null;

    function dashboardApproveReseller(id, name) {
        pendingDashResellerId = id;
        pendingDashAction = 'approve';
        const header = document.querySelector('#dashResellerActionModal .modal-header');
        header.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
        document.getElementById('dashResellerIcon').className = 'fas fa-user-check';
        document.getElementById('dashResellerTitle').textContent = 'Approve Reseller';
        document.getElementById('dashResellerMessage').innerHTML = 'Are you sure you want to approve <strong>' + name + '</strong> as a reseller?';
        document.getElementById('dashRejectReasonGroup').style.display = 'none';
        const confirmBtn = document.getElementById('dashResellerConfirmBtn');
        confirmBtn.className = 'btn btn-success';
        confirmBtn.innerHTML = '<i class="fas fa-check"></i> Approve';
        openModal('dashResellerActionModal');
    }

    function dashboardRejectReseller(id, name) {
        pendingDashResellerId = id;
        pendingDashAction = 'reject';
        const header = document.querySelector('#dashResellerActionModal .modal-header');
        header.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
        document.getElementById('dashResellerIcon').className = 'fas fa-user-times';
        document.getElementById('dashResellerTitle').textContent = 'Reject Reseller';
        document.getElementById('dashResellerMessage').innerHTML = 'Are you sure you want to reject <strong>' + name + '</strong>\'s application?';
        document.getElementById('dashRejectReasonGroup').style.display = 'block';
        document.getElementById('dashRejectReason').value = '';
        const confirmBtn = document.getElementById('dashResellerConfirmBtn');
        confirmBtn.className = 'btn btn-danger';
        confirmBtn.innerHTML = '<i class="fas fa-times"></i> Reject';
        openModal('dashResellerActionModal');
    }

    function confirmDashResellerAction() {
        if (pendingDashAction === 'reject') {
            const reason = document.getElementById('dashRejectReason').value.trim();
            if (!reason) {
                document.getElementById('dashRejectReason').style.borderColor = '#dc3545';
                document.getElementById('dashRejectReason').focus();
                return;
            }
            const formData = new FormData();
            formData.append('rejection_reason', reason);
            fetch('<?php echo BASE_URL; ?>reseller/reject/' + pendingDashResellerId, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal(document.getElementById('dashResellerActionModal'));
                    showAlert('success', 'Reseller rejected successfully');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message || 'Failed to reject reseller');
                }
            })
            .catch(() => showAlert('danger', 'An error occurred'));
        } else {
            fetch('<?php echo BASE_URL; ?>reseller/approve/' + pendingDashResellerId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal(document.getElementById('dashResellerActionModal'));
                    showAlert('success', 'Reseller approved successfully');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message || 'Failed to approve reseller');
                }
            })
            .catch(() => showAlert('danger', 'An error occurred'));
        }
    }
</script>

<!-- Dashboard Reseller Action Modal -->
<div class="modal" id="dashResellerActionModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-user-check" id="dashResellerIcon"></i> <span id="dashResellerTitle">Approve Reseller</span>
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('dashResellerActionModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 25px 20px;">
            <p id="dashResellerMessage" style="font-size: 15px; color: var(--dark-text); margin-bottom: 15px;"></p>
            <div class="form-group" id="dashRejectReasonGroup" style="display: none; text-align: left;">
                <label>Reason for Rejection</label>
                <textarea id="dashRejectReason" class="form-control" rows="3" placeholder="Please provide a reason..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('dashResellerActionModal'))">Cancel</button>
            <button class="btn btn-success" id="dashResellerConfirmBtn" onclick="confirmDashResellerAction()">
                <i class="fas fa-check"></i> Approve
            </button>
        </div>
    </div>
</div>
