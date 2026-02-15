<!-- Low Stock / Manage Stock -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i> Manage Stock - Low Stock Products
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>product" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Inventory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <div class="col col-4">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">
                    <?php 
                        $outOfStock = 0;
                        $lowStock = 0;
                        $criticalStock = 0;
                        if (!empty($products)) {
                            foreach ($products as $p) {
                                $qty = $p['stock_quantity'] ?? 0;
                                if ($qty == 0) $outOfStock++;
                                elseif ($qty <= 5) $criticalStock++;
                                else $lowStock++;
                            }
                        }
                        echo $outOfStock;
                    ?>
                </div>
                <div class="stat-label">Out of Stock</div>
            </div>
        </div>
        <div class="col col-4">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-value"><?php echo $criticalStock; ?></div>
                <div class="stat-label">Critical (1-5 units)</div>
            </div>
        </div>
        <div class="col col-4">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value"><?php echo $lowStock; ?></div>
                <div class="stat-label">Low Stock (6-10 units)</div>
            </div>
        </div>
    </div>

    <!-- Bulk Restock Section -->
    <div class="row">
        <div class="col col-12">
            <div class="card" style="border-left: 4px solid var(--primary-pink);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> Bulk Restock
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" onclick="bulkRestockSelected()">
                            <i class="fas fa-layer-group"></i> Restock Selected
                        </button>
                        <button class="btn btn-sm btn-outline" onclick="selectAllLowStock()">
                            <i class="fas fa-check-double"></i> Select All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Products Table -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products Needing Restock</h3>
                    <div class="d-flex gap-2">
                        <select class="form-control" style="width: auto;" id="filterStockLevel" onchange="filterByStockLevel(this.value)">
                            <option value="">All Low Stock</option>
                            <option value="out">Out of Stock (0)</option>
                            <option value="critical">Critical (1-5)</option>
                            <option value="low">Low (6-10)</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="lowStockTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAllCheck" onchange="toggleSelectAll(this)"></th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Current Stock</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Quick Restock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): 
                                        $productId = isset($product['product_id']) ? $product['product_id'] : ($product['id'] ?? 0);
                                        $stock = $product['stock_quantity'] ?? 0;
                                        $stockLevel = $stock == 0 ? 'out' : ($stock <= 5 ? 'critical' : 'low');
                                    ?>
                                        <tr data-stock-level="<?php echo $stockLevel; ?>" data-product-id="<?php echo $productId; ?>">
                                            <td>
                                                <input type="checkbox" class="restock-checkbox" value="<?php echo $productId; ?>">
                                            </td>
                                            <td>
                                                <?php $imgPath = $product['product_image'] ?? $product['image'] ?? ''; ?>
                                                <?php if (!empty($imgPath)): ?>
                                                    <img src="<?php echo BASE_URL . $imgPath; ?>" 
                                                         alt="<?php echo htmlspecialchars($product['product_name'] ?? $product['name'] ?? ''); ?>" 
                                                         class="table-avatar" style="border-radius: 8px;">
                                                <?php else: ?>
                                                    <div style="width: 40px; height: 40px; background: var(--secondary-lavender); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image" style="color: var(--primary-pink);"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($product['product_name'] ?? $product['name'] ?? 'N/A'); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php
                                                    if ($stock == 0) {
                                                        $badgeClass = 'badge-danger';
                                                        $statusText = 'Out of Stock';
                                                    } elseif ($stock <= 5) {
                                                        $badgeClass = 'badge-danger';
                                                        $statusText = $stock . ' units';
                                                    } else {
                                                        $badgeClass = 'badge-warning';
                                                        $statusText = $stock . ' units';
                                                    }
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
                                            </td>
                                            <td>
                                                <?php if ($stock == 0): ?>
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle"></i> Out of Stock
                                                    </span>
                                                <?php elseif ($stock <= 5): ?>
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-exclamation-circle"></i> Critical
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Low
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong style="color: var(--primary-pink);">₱<?php echo number_format($product['price'] ?? 0, 2); ?></strong>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1" style="align-items: center;">
                                                    <input type="number" class="form-control restock-qty" 
                                                           id="restockQty_<?php echo $productId; ?>"
                                                           min="1" value="10" style="width: 80px; padding: 5px 8px; font-size: 13px;">
                                                    <button onclick="quickRestockProduct(<?php echo $productId; ?>)" 
                                                            class="btn btn-sm btn-primary" title="Restock">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo BASE_URL; ?>product/edit/<?php echo $productId; ?>" 
                                                       class="btn btn-sm btn-info" title="Edit Product">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div style="padding: 40px;">
                                                <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px; display: block;"></i>
                                                <h3 style="color: #28a745;">All Stocked Up!</h3>
                                                <p style="color: var(--gray);">No products are currently low on stock.</p>
                                            </div>
                                        </td>
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

<!-- Bulk Restock Modal -->
<div class="modal" id="bulkRestockModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-layer-group"></i> Bulk Restock</h3>
            <button class="modal-close" onclick="closeModal(document.getElementById('bulkRestockModal'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Selected Products: <strong id="bulkSelectedCount">0</strong></label>
            </div>
            <div class="form-group">
                <label for="bulkRestockQty">Quantity to Add to Each Product</label>
                <input type="number" id="bulkRestockQty" class="form-control" min="1" value="10" placeholder="Enter quantity">
            </div>
            <div id="bulkRestockList" style="max-height: 200px; overflow-y: auto; margin-top: 10px;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal(document.getElementById('bulkRestockModal'))">Cancel</button>
            <button class="btn btn-primary" onclick="executeBulkRestock()">
                <i class="fas fa-plus"></i> Restock All Selected
            </button>
        </div>
    </div>
</div>

<script>
const BASE_URL_JS = '<?php echo BASE_URL; ?>';

// Quick restock a single product
function quickRestockProduct(productId) {
    const qtyInput = document.getElementById('restockQty_' + productId);
    const qty = parseInt(qtyInput.value);
    
    if (!qty || qty <= 0) {
        alert('Please enter a valid quantity');
        return;
    }
    
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', qty);
    formData.append('operation', 'add');
    
    // Disable button
    const btn = qtyInput.nextElementSibling;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    fetch(BASE_URL_JS + 'product/updateStock', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success inline
            const row = btn.closest('tr');
            row.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');
            
            // Update stock badge
            const stockBadge = row.querySelector('td:nth-child(5) .badge');
            const currentStock = parseInt(stockBadge.textContent) || 0;
            const newStock = currentStock + qty;
            stockBadge.textContent = newStock + ' units';
            stockBadge.className = 'badge ' + (newStock > 10 ? 'badge-success' : 'badge-warning');
            
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
                btn.disabled = false;
                row.style.backgroundColor = '';
            }, 2000);
        } else {
            alert(data.message || 'Failed to update stock');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
}

// Filter by stock level
function filterByStockLevel(level) {
    const rows = document.querySelectorAll('#lowStockTable tbody tr');
    rows.forEach(row => {
        if (!level || row.getAttribute('data-stock-level') === level) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Select all checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.restock-checkbox');
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        if (row.style.display !== 'none') {
            cb.checked = checkbox.checked;
        }
    });
}

function selectAllLowStock() {
    const checkboxes = document.querySelectorAll('.restock-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
    document.getElementById('selectAllCheck').checked = !allChecked;
}

// Bulk restock selected products
function bulkRestockSelected() {
    const checked = document.querySelectorAll('.restock-checkbox:checked');
    if (checked.length === 0) {
        alert('Please select at least one product to restock');
        return;
    }
    
    document.getElementById('bulkSelectedCount').textContent = checked.length;
    
    // Build list of selected products
    let listHtml = '<div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden;">';
    checked.forEach(cb => {
        const row = cb.closest('tr');
        const name = row.querySelector('td:nth-child(3) strong').textContent;
        const stock = row.querySelector('td:nth-child(5) .badge').textContent;
        listHtml += `<div style="padding: 8px 12px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
            <span>${name}</span>
            <span class="badge badge-warning">${stock}</span>
        </div>`;
    });
    listHtml += '</div>';
    
    document.getElementById('bulkRestockList').innerHTML = listHtml;
    openModal('bulkRestockModal');
}

// Execute bulk restock
function executeBulkRestock() {
    const qty = parseInt(document.getElementById('bulkRestockQty').value);
    if (!qty || qty <= 0) {
        alert('Please enter a valid quantity');
        return;
    }
    
    const checked = document.querySelectorAll('.restock-checkbox:checked');
    const productIds = Array.from(checked).map(cb => cb.value);
    
    let completed = 0;
    let failed = 0;
    
    // Close modal and show progress
    closeModal(document.getElementById('bulkRestockModal'));
    
    const promises = productIds.map(productId => {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', qty);
        formData.append('operation', 'add');
        
        return fetch(BASE_URL_JS + 'product/updateStock', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                completed++;
                // Highlight row
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                if (row) row.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
            } else {
                failed++;
            }
        })
        .catch(() => { failed++; });
    });
    
    Promise.all(promises).then(() => {
        let msg = `Restock complete: ${completed} product(s) updated successfully.`;
        if (failed > 0) msg += ` ${failed} failed.`;
        alert(msg);
        if (completed > 0) {
            setTimeout(() => location.reload(), 500);
        }
    });
}
</script>
