<!-- Reports Dashboard -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i> Reports
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                        <button class="btn btn-primary" onclick="printContent('reportsContent')">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Reports Generated</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ee82ee 0%, #9370db 100%);">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Downloads</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9370db 0%, #ff69b4 100%);">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-value"><?php echo date('M Y'); ?></div>
                <div class="stat-label">Current Period</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffb6c1 0%, #db7093 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">N/A</div>
                <div class="stat-label">Last Report</div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="row">
        <!-- Sales Report -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Sales Report
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Generate detailed sales reports with date range filters</p>
                    <form id="salesReportForm" class="mt-3">
                        <div class="form-group">
                            <label>Report Period</label>
                            <select class="form-control" name="period" id="salesPeriod">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month" selected>This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_year">This Year</option>
                                <option value="custom">Custom Date Range</option>
                            </select>
                        </div>
                        <div id="customDateRange" style="display: none;">
                            <div class="form-row">
                                <div class="col">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" name="start_date">
                                </div>
                                <div class="col">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label>Export Format</label>
                            <select class="form-control" name="format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (XLSX)</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <button type="button" onclick="generateSalesReport()" class="btn btn-primary btn-block">
                            <i class="fas fa-download"></i> Generate Sales Report
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Inventory Report -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-boxes"></i> Inventory Report
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export current inventory status and stock levels</p>
                    <form id="inventoryReportForm" class="mt-3">
                        <div class="form-group">
                            <label>Stock Status</label>
                            <select class="form-control" name="stock_status">
                                <option value="all">All Products</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Include Details</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="include_price" id="includePrice" checked>
                                <label class="form-check-label" for="includePrice">Pricing Information</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="include_supplier" id="includeSupplier">
                                <label class="form-check-label" for="includeSupplier">Supplier Details</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Export Format</label>
                            <select class="form-control" name="format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (XLSX)</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <button type="button" onclick="generateInventoryReport()" class="btn btn-primary btn-block">
                            <i class="fas fa-download"></i> Generate Inventory Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Commission Report -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hand-holding-usd"></i> Commission Report
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Track reseller commissions and payouts</p>
                    <form id="commissionReportForm" class="mt-3">
                        <div class="form-group">
                            <label>Report Type</label>
                            <select class="form-control" name="report_type">
                                <option value="all">All Commissions</option>
                                <option value="pending">Pending Payouts</option>
                                <option value="paid">Paid Commissions</option>
                                <option value="by_reseller">By Reseller</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Time Period</label>
                            <select class="form-control" name="period">
                                <option value="this_month" selected>This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_quarter">This Quarter</option>
                                <option value="this_year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Export Format</label>
                            <select class="form-control" name="format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (XLSX)</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <button type="button" onclick="generateCommissionReport()" class="btn btn-primary btn-block">
                            <i class="fas fa-download"></i> Generate Commission Report
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reseller Performance Report -->
        <div class="col col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Reseller Performance
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Analyze reseller activity and performance metrics</p>
                    <form id="resellerReportForm" class="mt-3">
                        <div class="form-group">
                            <label>Status Filter</label>
                            <select class="form-control" name="status">
                                <option value="all">All Resellers</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sort By</label>
                            <select class="form-control" name="sort_by">
                                <option value="sales">Total Sales</option>
                                <option value="orders">Number of Orders</option>
                                <option value="commission">Commission Earned</option>
                                <option value="name">Reseller Name</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Export Format</label>
                            <select class="form-control" name="format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (XLSX)</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <button type="button" onclick="generateResellerReport()" class="btn btn-primary btn-block">
                            <i class="fas fa-download"></i> Generate Reseller Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> Recent Reports
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Generated</th>
                                    <th>Generated By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> No recent reports. Generate your first report above.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="stats-card pink-card">
                <div class="stats-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stats-info">
                    <h3>0</h3>
                    <p>Reports Generated</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card pink-card">
                <div class="stats-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stats-info">
                    <h3>0</h3>
                    <p>Downloads</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card pink-card">
                <div class="stats-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stats-info">
                    <h3><?php echo date('M Y'); ?></h3>
                    <p>Current Period</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card pink-card">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-info">
                    <h3>N/A</h3>
                    <p>Last Report</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Show/hide custom date range
document.getElementById('salesPeriod').addEventListener('change', function() {
    const customDateRange = document.getElementById('customDateRange');
    customDateRange.style.display = this.value === 'custom' ? 'block' : 'none';
});

// Generate Sales Report
function generateSalesReport() {
    const form = document.getElementById('salesReportForm');
    const formData = new FormData(form);
    
    // Show loading
    showLoading('Generating sales report...');
    
    // Simulate report generation (replace with actual AJAX call)
    setTimeout(() => {
        hideLoading();
        showAlert('success', 'Sales report generated successfully!');
        // In production, this would trigger a download
    }, 2000);
}

// Generate Inventory Report
function generateInventoryReport() {
    const form = document.getElementById('inventoryReportForm');
    const formData = new FormData(form);
    
    showLoading('Generating inventory report...');
    
    setTimeout(() => {
        hideLoading();
        showAlert('success', 'Inventory report generated successfully!');
    }, 2000);
}

// Generate Commission Report
function generateCommissionReport() {
    const form = document.getElementById('commissionReportForm');
    const formData = new FormData(form);
    
    showLoading('Generating commission report...');
    
    setTimeout(() => {
        hideLoading();
        showAlert('success', 'Commission report generated successfully!');
    }, 2000);
}

// Generate Reseller Report
function generateResellerReport() {
    const form = document.getElementById('resellerReportForm');
    const formData = new FormData(form);
    
    showLoading('Generating reseller report...');
    
    setTimeout(() => {
        hideLoading();
        showAlert('success', 'Reseller performance report generated successfully!');
    }, 2000);
}

// Helper Functions
function showLoading(message) {
    // Implementation would show a loading modal
    console.log('Loading:', message);
}

function hideLoading() {
    // Implementation would hide the loading modal
    console.log('Loading complete');
}

function showAlert(type, message) {
    alert(message);
}
</script>
