<!-- Edit Product -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Product
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>product" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        $productId = isset($product['product_id']) ? $product['product_id'] : ($product['id'] ?? 0);
        $productName = htmlspecialchars($product['product_name'] ?? $product['name'] ?? '');
        $productSku = htmlspecialchars($product['sku'] ?? '');
        $productDesc = htmlspecialchars($product['description'] ?? '');
        $productCatId = $product['category_id'] ?? '';
        $productCostPrice = $product['reseller_price'] ?? $product['cost_price'] ?? 0;
        $productPrice = $product['price'] ?? 0;
        $productStock = $product['stock_quantity'] ?? 0;
        $productImage = $product['product_image'] ?? $product['image'] ?? '';
        $productIsActive = isset($product['is_active']) ? $product['is_active'] : (isset($product['status']) ? ($product['status'] === 'active' ? 1 : 0) : 1);
        $productStatus = $productIsActive ? 'active' : 'inactive';
        $productLowThreshold = $product['low_stock_threshold'] ?? 10;
    ?>

    <!-- Product Form -->
    <div class="row">
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Product Information
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>product/edit/<?php echo $productId; ?>" enctype="multipart/form-data" id="productForm">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name">Product Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                   placeholder="Enter product name" value="<?php echo $productName; ?>">
                        </div>

                        <!-- SKU -->
                        <div class="form-group">
                            <label for="sku">SKU (Stock Keeping Unit) <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="sku" name="sku" required 
                                   placeholder="e.g., PROD-001" value="<?php echo $productSku; ?>">
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                      placeholder="Enter product description"><?php echo $productDesc; ?></textarea>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['category_id']; ?>" <?php echo $productCatId == $cat['category_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Pricing -->
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label for="cost_price">Cost Price <span style="color: red;">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" class="form-control" id="cost_price" name="cost_price" 
                                               step="0.01" min="0" required placeholder="0.00" 
                                               value="<?php echo number_format($productCostPrice, 2, '.', ''); ?>">
                                    </div>
                                    <small class="text-muted">Your purchase price</small>
                                </div>
                            </div>
                            <div class="col col-6">
                                <div class="form-group">
                                    <label for="price">Selling Price <span style="color: red;">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               step="0.01" min="0" required placeholder="0.00" 
                                               value="<?php echo number_format($productPrice, 2, '.', ''); ?>">
                                    </div>
                                    <small class="text-muted">Price shown to customers</small>
                                </div>
                            </div>
                        </div>

                        <!-- Profit Margin Display -->
                        <div class="form-group">
                            <div style="padding: 12px; background: linear-gradient(135deg, #ff69b4 0%, #ffb6c1 100%); color: white; border-radius: 8px;">
                                <strong>Estimated Profit Margin:</strong> <span id="profitMargin">₱0.00 (0%)</span>
                            </div>
                        </div>

                        <!-- Stock Quantity -->
                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity <span style="color: red;">*</span></label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                   min="0" required placeholder="0" value="<?php echo $productStock; ?>">
                            <small class="text-muted">Current stock: <?php echo $productStock; ?> units</small>
                        </div>

                        <!-- Low Stock Threshold -->
                        <div class="form-group">
                            <label for="low_stock_threshold">Low Stock Alert Threshold</label>
                            <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" 
                                   min="0" placeholder="10" value="<?php echo $productLowThreshold; ?>">
                            <small class="text-muted">You'll be alerted when stock falls below this number</small>
                        </div>

                        <!-- Product Image -->
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewEditImage(this)" style="display: none;">
                            
                            <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                                <!-- Preview Image -->
                                <div id="imagePreview" style="width: 200px; height: 200px; border: 3px dashed #ddd; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <?php if (!empty($productImage)): ?>
                                        <img id="preview" src="<?php echo BASE_URL . $productImage; ?>" alt="<?php echo $productName; ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <img id="preview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                        <i class="fas fa-image" id="defaultProductIcon" style="font-size: 60px; color: #ccc;"></i>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Upload Actions -->
                                <div style="flex: 1;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('image').click();" style="margin-bottom: 10px;">
                                        <i class="fas fa-upload"></i> Change Image
                                    </button>
                                    <small class="text-muted" style="display: block; margin-top: 5px;">
                                        Recommended size: 800x800px. Max 2MB.<br>
                                        Supported formats: JPG, PNG, GIF<br>
                                        Leave empty to keep current image.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status <span style="color: red;">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" <?php echo $productStatus === 'active' ? 'selected' : ''; ?>>Active (Available for sale)</option>
                                <option value="inactive" <?php echo $productStatus === 'inactive' ? 'selected' : ''; ?>>Inactive (Hidden from customers)</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="<?php echo BASE_URL; ?>product" class="btn btn-outline btn-lg" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col col-4">
            <!-- Quick Stock Update -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group"></i> Quick Restock
                    </h3>
                </div>
                <div class="card-body">
                    <p style="margin-bottom: 15px;">Quickly add stock without modifying other product details.</p>
                    <div class="form-group">
                        <label for="quickRestockQty">Quantity to Add</label>
                        <input type="number" class="form-control" id="quickRestockQty" min="1" value="10" placeholder="Enter quantity">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="quickRestock(<?php echo $productId; ?>)" style="width: 100%;">
                        <i class="fas fa-plus"></i> Restock Now
                    </button>
                    <div id="restockResult" style="margin-top: 10px;"></div>
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Product Stats
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span>Current Stock</span>
                        <strong><?php 
                            $stockClass = $productStock > 10 ? 'badge-success' : ($productStock > 0 ? 'badge-warning' : 'badge-danger');
                        ?>
                        <span class="badge <?php echo $stockClass; ?>"><?php echo $productStock; ?> units</span>
                        </strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span>Cost Price</span>
                        <strong>₱<?php echo number_format($productCostPrice, 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span>Selling Price</span>
                        <strong style="color: var(--primary-pink);">₱<?php echo number_format($productPrice, 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span>Profit per Unit</span>
                        <strong style="color: #28a745;">₱<?php echo number_format($productPrice - $productCostPrice, 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                        <span>Status</span>
                        <span class="badge badge-<?php echo $productStatus === 'active' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($productStatus); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i> Quick Tips
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 20px; margin: 0;">
                        <li style="margin-bottom: 10px;"><strong>Stock:</strong> Use "Quick Restock" to add inventory fast</li>
                        <li style="margin-bottom: 10px;"><strong>Pricing:</strong> Ensure selling price covers costs + commission</li>
                        <li style="margin-bottom: 10px;"><strong>Images:</strong> Update images to keep listings fresh</li>
                        <li><strong>Threshold:</strong> Set low stock alerts per product</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Calculate profit margin
function calculateMargin() {
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('price').value) || 0;
    const profit = sellingPrice - costPrice;
    const marginPercent = costPrice > 0 ? ((profit / costPrice) * 100).toFixed(1) : 0;
    
    const marginEl = document.getElementById('profitMargin');
    marginEl.innerHTML = `₱${profit.toFixed(2)} (${marginPercent}%)`;
    
    // Color coding
    const container = marginEl.closest('div');
    if (profit <= 0) {
        container.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
    } else if (marginPercent < 20) {
        container.style.background = 'linear-gradient(135deg, #ffc107 0%, #ff9800 100%)';
    } else {
        container.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
    }
}

document.getElementById('cost_price').addEventListener('input', calculateMargin);
document.getElementById('price').addEventListener('input', calculateMargin);

// Calculate on page load
calculateMargin();

// Image preview
function previewEditImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, GIF, or WebP)');
            input.value = '';
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Image file size must be less than 2MB');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const icon = document.getElementById('defaultProductIcon');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (icon) icon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

// Quick Restock function
function quickRestock(productId) {
    const qty = document.getElementById('quickRestockQty').value;
    if (!qty || qty <= 0) {
        alert('Please enter a valid quantity');
        return;
    }
    
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', qty);
    formData.append('operation', 'add');
    
    const resultDiv = document.getElementById('restockResult');
    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restocking...';
    
    fetch('<?php echo BASE_URL; ?>product/updateStock', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Stock updated! Reloading...</div>';
            setTimeout(() => location.reload(), 1000);
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Failed to update stock') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> An error occurred</div>';
    });
}
</script>
