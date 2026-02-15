<!-- Analytics Dashboard -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Analytics Dashboard
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline" onclick="exportAnalytics('pdf')">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                        <button class="btn btn-primary" onclick="printContent('analyticsContent')">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>dashboard/analytics" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
                        <div>
                            <label for="start_date" style="display: block; margin-bottom: 5px; font-weight: 500;">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                        </div>
                        <div>
                            <label for="end_date" style="display: block; margin-bottom: 5px; font-weight: 500;">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filter
                        </button>
                        <a href="<?php echo BASE_URL; ?>dashboard/analytics" class="btn btn-outline">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($order_stats['total_orders'] ?? 0); ?>
                </div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>

        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ee82ee 0%, #9370db 100%);">
                    <i class="fas fa-peso-sign"></i>
                </div>
                <div class="stat-value">
                    ₱<?php echo number_format($order_stats['total_sales'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>

        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9370db 0%, #ff69b4 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">
                    ₱<?php echo number_format($order_stats['average_order_value'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Average Order Value</div>
            </div>
        </div>

        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffb6c1 0%, #db7093 100%);">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format((($order_stats['delivered_orders'] ?? 0) / max(1, $order_stats['total_orders'] ?? 1)) * 100, 1); ?>%
                </div>
                <div class="stat-label">Completion Rate</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Daily Sales Chart -->
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area"></i> Daily Sales Trend (Last 30 Days)
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="dailySalesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="col col-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i> Order Status
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Monthly Sales Comparison
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="monthlySalesChart" height="60"></canvas>
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
                        <i class="fas fa-trophy"></i> Top 10 Products
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Sales</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($top_products)): ?>
                                    <?php $rank = 1; ?>
                                    <?php foreach ($top_products as $product): ?>
                                        <tr>
                                            <td><strong><?php echo $rank++; ?></strong></td>
                                            <td><?php echo htmlspecialchars($product['name'] ?? 'N/A'); ?></td>
                                            <td><span class="badge badge-primary"><?php echo number_format($product['total_sold'] ?? 0); ?> units</span></td>
                                            <td><strong>₱<?php echo number_format($product['total_revenue'] ?? 0, 2); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No product data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Resellers -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Top 10 Resellers
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reseller</th>
                                    <th>Sales</th>
                                    <th>Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($top_resellers)): ?>
                                    <?php $rank = 1; ?>
                                    <?php foreach ($top_resellers as $reseller): ?>
                                        <tr>
                                            <td><strong><?php echo $rank++; ?></strong></td>
                                            <td><?php echo htmlspecialchars($reseller['full_name'] ?? 'N/A'); ?></td>
                                            <td><span class="badge badge-success">₱<?php echo number_format($reseller['total_sales'] ?? 0, 2); ?></span></td>
                                            <td><strong>₱<?php echo number_format($reseller['total_commission'] ?? 0, 2); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No reseller data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
// Daily Sales Chart
const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
const dailySalesData = <?php echo json_encode($daily_sales ?? []); ?>;

new Chart(dailySalesCtx, {
    type: 'line',
    data: {
        labels: dailySalesData.map(d => d.date),
        datasets: [{
            label: 'Daily Sales (₱)',
            data: dailySalesData.map(d => d.sales || 0),
            borderColor: '#ff69b4',
            backgroundColor: 'rgba(255, 105, 180, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Order Status Chart
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStats = <?php echo json_encode($order_stats ?? []); ?>;

new Chart(orderStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending', 'Processing', 'Cancelled'],
        datasets: [{
            data: [
                orderStats.completed_orders || 0,
                orderStats.pending_orders || 0,
                orderStats.processing_orders || 0,
                orderStats.cancelled_orders || 0
            ],
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Sales Chart
const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
const monthlySalesData = <?php echo json_encode($monthly_sales ?? []); ?>;

// Create month labels
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const monthLabels = monthlySalesData.map(d => monthNames[(d.month || 1) - 1]);
const monthData = monthlySalesData.map(d => d.sales || 0);

new Chart(monthlySalesCtx, {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [{
            label: 'Monthly Revenue (₱)',
            data: monthData,
            backgroundColor: '#ff69b4',
            borderColor: '#ff1493',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Export Analytics
function exportAnalytics(format) {
    alert('Exporting analytics to ' + format.toUpperCase() + '...');
    // In production, this would trigger actual export
}

// Print Content
function printContent(elementId) {
    window.print();
}
</script>
