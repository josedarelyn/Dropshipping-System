<!-- Inventory Management -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-boxes"></i> Inventory Management
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline" onclick="exportTable('excel')">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                        <a href="<?php echo BASE_URL; ?>product/add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($product_stats['total_products'] ?? 0); ?>
                </div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($product_stats['active_products'] ?? 0); ?>
                </div>
                <div class="stat-label">Active Products</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($product_stats['low_stock_count'] ?? 0); ?>
                </div>
                <div class="stat-label">Low Stock</div>
            </div>
        </div>
        
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-value">
                    <?php echo number_format($product_stats['total_stock'] ?? 0); ?>
                </div>
                <div class="stat-label">Total Stock Units</div>
            </div>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Products</h3>
                    <div class="d-flex gap-2">
                        <select class="form-control" style="width: auto;" id="filterStatus">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select class="form-control" style="width: auto;" id="filterStock">
                            <option value="">All Stock</option>
                            <option value="in-stock">In Stock</option>
                            <option value="low-stock">Low Stock</option>
                            <option value="out-of-stock">Out of Stock</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): 
                                        // Detect ID column (product_id or id)
                                        $productId = isset($product['product_id']) ? $product['product_id'] : ($product['id'] ?? 0);
                                    ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="product-checkbox" value="<?php echo $productId; ?>">
                                            </td>
                                            <td>
                                                <?php 
                                                    $imgPath = $product['product_image'] ?? $product['image'] ?? '';
                                                ?>
                                                <?php if (!empty($imgPath)): ?>
                                                    <img src="<?php echo BASE_URL . $imgPath; ?>" 
                                                         alt="<?php echo htmlspecialchars($product['product_name'] ?? $product['name'] ?? ''); ?>" 
                                                         class="table-avatar"
                                                         style="border-radius: 8px;">
                                                <?php else: ?>
                                                    <div style="width: 40px; height: 40px; background: var(--secondary-lavender); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image" style="color: var(--primary-pink);"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($product['product_name'] ?? $product['name'] ?? 'N/A'); ?></strong>
                                                <p style="margin: 0; font-size: 12px; color: var(--gray);">
                                                    <?php echo substr(htmlspecialchars($product['description'] ?? ''), 0, 50) . '...'; ?>
                                                </p>
                                            </td>
                                            <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    <?php echo htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'Uncategorized'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong style="color: var(--primary-pink);">₱<?php echo number_format($product['price'] ?? 0, 2); ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $stock = $product['stock_quantity'] ?? 0;
                                                $badgeClass = $stock > 10 ? 'badge-success' : ($stock > 0 ? 'badge-warning' : 'badge-danger');
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo $stock; ?> units
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                    // Support both 'status' (enum) and 'is_active' (tinyint) columns
                                                    if (isset($product['status'])) {
                                                        $isActive = ($product['status'] == 'active');
                                                        $statusLabel = ucfirst($product['status']);
                                                    } elseif (isset($product['is_active'])) {
                                                        $isActive = ($product['is_active'] == 1);
                                                        $statusLabel = $isActive ? 'Active' : 'Inactive';
                                                    } else {
                                                        $isActive = false;
                                                        $statusLabel = 'N/A';
                                                    }
                                                ?>
                                                <span class="badge badge-<?php echo $isActive ? 'success' : 'danger'; ?>">
                                                    <?php echo $statusLabel; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button onclick="updateStockModal(<?php echo $productId; ?>, '<?php echo htmlspecialchars($product['product_name'] ?? $product['name'] ?? '', ENT_QUOTES); ?>', <?php echo $stock; ?>)" 
                                                            class="btn btn-sm btn-success"
                                                            title="Update Stock">
                                                        <i class="fas fa-cubes"></i>
                                                    </button>
                                                    <a href="<?php echo BASE_URL; ?>product/edit/<?php echo $productId; ?>" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="deleteSingleProduct(<?php echo $productId; ?>, '<?php echo htmlspecialchars($product['product_name'] ?? $product['name'] ?? '', ENT_QUOTES); ?>')" 
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No products found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                            <span id="selectedCount" style="font-size: 14px; color: var(--gray); display: none;">
                                <strong id="selectedNum">0</strong> product(s) selected
                            </span>
                            <button class="btn btn-sm btn-danger" id="bulkDeleteBtn" onclick="bulkDelete()">
                                <i class="fas fa-trash"></i> Delete Selected
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

<!-- Update Stock Modal -->
<div class="modal" id="updateStockModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Stock</h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('updateStockModal'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Product</label>
                <input type="text" id="stockProductName" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Current Stock</label>
                <input type="number" id="currentStock" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Operation</label>
                <select id="stockOperation" class="form-control">
                    <option value="add">Add Stock</option>
                    <option value="subtract">Subtract Stock</option>
                </select>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" id="stockQuantity" class="form-control" min="1" value="1">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('updateStockModal'))">
                Cancel
            </button>
            <button class="btn btn-primary" onclick="submitStockUpdate()">
                <i class="fas fa-save"></i> Update Stock
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteConfirmModal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff;">
            <h3 class="modal-title" style="color: #fff;">
                <i class="fas fa-exclamation-triangle"></i> Confirm Delete
            </h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('deleteConfirmModal'))" style="color: #fff;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 30px 20px;">
            <i class="fas fa-trash-alt" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
            <p id="deleteModalMessage" style="font-size: 16px; margin: 10px 0;">Are you sure you want to delete this product?</p>
            <p style="font-size: 13px; color: var(--gray);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 10px;">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('deleteConfirmModal'))">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn btn-danger" id="confirmDeleteBtn" onclick="executeDelete()">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>

<script>
let currentProductId = null;
let deleteMode = null;       // 'single' or 'bulk'
let deleteProductId = null;
let deleteBulkIds = [];

function updateStockModal(productId, productName, currentStock) {
    currentProductId = productId;
    document.getElementById('stockProductName').value = productName;
    document.getElementById('currentStock').value = currentStock;
    document.getElementById('stockQuantity').value = 1;
    openModal('updateStockModal');
}

function submitStockUpdate() {
    const quantity = document.getElementById('stockQuantity').value;
    const operation = document.getElementById('stockOperation').value;
    
    if (currentProductId && quantity > 0) {
        updateStock(currentProductId, quantity, operation);
        closeModal(document.getElementById('updateStockModal'));
    }
}

// Update selected count display
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const count = checkboxes.length;
    const countDisplay = document.getElementById('selectedCount');
    const countNum = document.getElementById('selectedNum');
    
    if (count > 0) {
        countDisplay.style.display = 'inline';
        countNum.textContent = count;
    } else {
        countDisplay.style.display = 'none';
    }
}

// Show delete modal for single product
function deleteSingleProduct(productId, productName) {
    deleteMode = 'single';
    deleteProductId = productId;
    deleteBulkIds = [];
    document.getElementById('deleteModalMessage').innerHTML = 'Are you sure you want to delete <strong>"' + productName + '"</strong>?';
    openModal('deleteConfirmModal');
}

// Show delete modal for bulk delete
function bulkDelete() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        deleteMode = null;
        deleteBulkIds = [];
        document.getElementById('deleteModalMessage').innerHTML = '<span style="color: #dc3545;">Please check the checkbox of the product(s) you want to delete first.</span>';
        document.getElementById('confirmDeleteBtn').style.display = 'none';
        openModal('deleteConfirmModal');
        return;
    }
    
    document.getElementById('confirmDeleteBtn').style.display = '';
    deleteMode = 'bulk';
    deleteProductId = null;
    deleteBulkIds = ids;
    document.getElementById('deleteModalMessage').innerHTML = 'Are you sure you want to delete <strong>' + ids.length + ' product(s)</strong>?';
    openModal('deleteConfirmModal');
}

// Execute delete after modal confirmation
function executeDelete() {
    closeModal(document.getElementById('deleteConfirmModal'));
    
    if (deleteMode === 'single' && deleteProductId) {
        window.location.href = '<?php echo BASE_URL; ?>product/delete/' + deleteProductId;
    } else if (deleteMode === 'bulk' && deleteBulkIds.length > 0) {
        showLoading();
        
        fetch('<?php echo BASE_URL; ?>product/bulkDelete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: 'delete', ids: deleteBulkIds })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            hideLoading();
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                showAlert('danger', data.message || 'Failed to delete products');
            }
        })
        .catch(function(error) {
            hideLoading();
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while deleting products');
        });
    }
    
    // Reset
    deleteMode = null;
    deleteProductId = null;
    deleteBulkIds = [];
}

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(cb => {
        // Only toggle visible (non-filtered) rows
        const row = cb.closest('tr');
        if (row && row.style.display !== 'none') {
            cb.checked = this.checked;
        }
    });
    updateSelectedCount();
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-checkbox')) {
        updateSelectedCount();
        // Update selectAll state
        const allCheckboxes = document.querySelectorAll('.product-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length && allCheckboxes.length > 0;
    }
});

// Toggle product status
function toggleProductStatus(productId, currentStatus) {
    var newStatus = (currentStatus === 'active') ? 'inactive' : 'active';
    
    fetch('<?php echo BASE_URL; ?>product/toggleStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, status: newStatus })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            showAlert('success', 'Product status updated to ' + newStatus);
            setTimeout(function() { location.reload(); }, 1000);
        } else {
            showAlert('danger', data.message || 'Failed to update status');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred');
    });
}

// Filter functionality
function applyFilters() {
    const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
    const stockFilter = document.getElementById('filterStock').value;
    const rows = document.querySelectorAll('#productsTable tbody tr');
    
    rows.forEach(row => {
        let showByStatus = true;
        let showByStock = true;
        
        // Status filter
        if (statusFilter) {
            const statusBadge = row.querySelector('td:nth-child(8) .badge');
            if (statusBadge) {
                const status = statusBadge.textContent.trim().toLowerCase();
                showByStatus = (status === statusFilter);
            }
        }
        
        // Stock filter
        if (stockFilter) {
            const stockBadge = row.querySelector('td:nth-child(7) .badge');
            if (stockBadge) {
                const stockQty = parseInt(stockBadge.textContent) || 0;
                if (stockFilter === 'in-stock') {
                    showByStock = (stockQty > 10);
                } else if (stockFilter === 'low-stock') {
                    showByStock = (stockQty > 0 && stockQty <= 10);
                } else if (stockFilter === 'out-of-stock') {
                    showByStock = (stockQty === 0);
                }
            }
        }
        
        row.style.display = (showByStatus && showByStock) ? '' : 'none';
    });
    
    // Reset checkboxes after filtering
    document.getElementById('selectAll').checked = false;
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
    updateSelectedCount();
}

document.getElementById('filterStatus').addEventListener('change', applyFilters);
document.getElementById('filterStock').addEventListener('change', applyFilters);
</script>
