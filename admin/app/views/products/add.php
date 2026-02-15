<!-- Add Product -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> Add New Product
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
                    <form method="POST" action="<?php echo BASE_URL; ?>product/add" enctype="multipart/form-data" id="productForm">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name">Product Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter product name">
                        </div>

                        <!-- SKU -->
                        <div class="form-group">
                            <label for="sku">SKU (Stock Keeping Unit) <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="sku" name="sku" required placeholder="e.g., PROD-001">
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter product description"></textarea>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['category_id']; ?>">
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
                                               step="0.01" min="0" required placeholder="0.00">
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
                                               step="0.01" min="0" required placeholder="0.00">
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
                                   min="0" required placeholder="0" value="0">
                        </div>

                        <!-- Product Image -->
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this)" style="display: none;">
                            
                            <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                                <!-- Preview Image -->
                                <div id="imagePreview" style="width: 200px; height: 200px; border: 3px dashed #ddd; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <img id="preview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <i class="fas fa-image" id="defaultProductIcon" style="font-size: 60px; color: #ccc;"></i>
                                </div>
                                
                                <!-- Upload Actions -->
                                <div style="flex: 1;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('image').click();" style="margin-bottom: 10px;">
                                        <i class="fas fa-upload"></i> Import Image
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="clearProductImage()" id="clearImageBtn" style="margin-bottom: 10px; margin-left: 10px; display: none;">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                    <div id="imageFileName" style="font-size: 14px; color: #666; margin-top: 5px;"></div>
                                    <small class="text-muted" style="display: block; margin-top: 5px;">
                                        Recommended size: 800x800px. Max 2MB.<br>
                                        Supported formats: JPG, PNG, GIF
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status <span style="color: red;">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">Active (Available for sale)</option>
                                <option value="inactive">Inactive (Hidden from customers)</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Add Product
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
            <!-- Quick Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i> Quick Tips
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 20px; margin: 0;">
                        <li style="margin-bottom: 10px;"><strong>SKU:</strong> Use a unique identifier for easy tracking</li>
                        <li style="margin-bottom: 10px;"><strong>Pricing:</strong> Set competitive prices to maximize sales</li>
                        <li style="margin-bottom: 10px;"><strong>Images:</strong> High-quality images increase conversion</li>
                        <li style="margin-bottom: 10px;"><strong>Description:</strong> Be detailed and highlight key features</li>
                        <li><strong>Stock:</strong> Keep inventory updated to avoid overselling</li>
                    </ul>
                </div>
            </div>

            <!-- Commission Info -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hand-holding-usd"></i> Reseller Commission
                    </h3>
                </div>
                <div class="card-body">
                    <p><strong>Default Rate:</strong> 10%</p>
                    <p style="margin: 0;">Resellers earn commission on each sale. You can adjust rates per reseller in the Resellers section.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Calculate profit margin
function calculateProfitMargin() {
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('price').value) || 0;
    
    if (costPrice > 0 && sellingPrice > 0) {
        const profit = sellingPrice - costPrice;
        const margin = ((profit / sellingPrice) * 100).toFixed(2);
        const profitDisplay = `₱${profit.toFixed(2)} (${margin}%)`;
        
        document.getElementById('profitMargin').textContent = profitDisplay;
        
        // Change color based on margin
        const marginContainer = document.getElementById('profitMargin').parentElement;
        if (profit < 0) {
            marginContainer.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
        } else if (margin < 20) {
            marginContainer.style.background = 'linear-gradient(135deg, #ffc107 0%, #e0a800 100%)';
        } else {
            marginContainer.style.background = 'linear-gradient(135deg, #28a745 0%, #218838 100%)';
        }
    }
}

// Preview image
function previewImage(input) {
    const preview = document.getElementById('preview');
    const defaultIcon = document.getElementById('defaultProductIcon');
    const fileName = document.getElementById('imageFileName');
    const clearBtn = document.getElementById('clearImageBtn');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        // Check file size (2MB max)
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            clearProductImage();
            return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Please select a valid image file (JPG, PNG, or GIF)');
            input.value = '';
            clearProductImage();
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            defaultIcon.style.display = 'none';
            previewContainer.style.border = '3px solid var(--primary-pink)';
            fileName.innerHTML = '<i class="fas fa-check-circle" style="color: green;"></i> ' + input.files[0].name;
            clearBtn.style.display = 'inline-block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Clear product image
function clearProductImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    const defaultIcon = document.getElementById('defaultProductIcon');
    const fileName = document.getElementById('imageFileName');
    const clearBtn = document.getElementById('clearImageBtn');
    const previewContainer = document.getElementById('imagePreview');
    
    input.value = '';
    preview.src = '';
    preview.style.display = 'none';
    defaultIcon.style.display = 'block';
    previewContainer.style.border = '3px dashed #ddd';
    fileName.innerHTML = '';
    clearBtn.style.display = 'none';
}

// Add event listeners
document.getElementById('cost_price').addEventListener('input', calculateProfitMargin);
document.getElementById('price').addEventListener('input', calculateProfitMargin);

// Form validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('price').value) || 0;
    
    if (sellingPrice < costPrice) {
        if (!confirm('Warning: Selling price is lower than cost price. You will lose money on this product. Continue anyway?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
