<div class="card">
    <div class="card-header">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <h3><i class="fas fa-edit"></i> Edit Product</h3>
            <a href="<?php echo BASE_URL; ?>inventory" class="btn-link">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>inventory/edit/<?php echo $product['product_id']; ?>" enctype="multipart/form-data" class="product-form">
            <div class="form-section">
                <h4 class="section-title"><i class="fas fa-info-circle"></i> Product Information</h4>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="product_name">
                            Product Name <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="product_name" 
                               name="product_name" 
                               value="<?php echo htmlspecialchars($product['product_name']); ?>"
                               required 
                               placeholder="Enter product name">
                        <small class="form-hint">Choose a clear, descriptive name</small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="category_id">
                            Category <span class="required">*</span>
                        </label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"
                                        <?php echo $category['category_id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-hint">Select the most relevant category</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">
                        Description
                    </label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Describe your product, its features, benefits, and specifications..."><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    <small class="form-hint">Provide detailed information to help customers make a decision</small>
                </div>
            </div>
            
            <div class="form-section">
                <h4 class="section-title"><i class="fas fa-dollar-sign"></i> Pricing & Stock</h4>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="price">
                            Price (₱) <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-prefix">₱</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="price" 
                                   name="price" 
                                   value="<?php echo $product['price']; ?>"
                                   step="0.01" 
                                   min="0" 
                                   required 
                                   placeholder="0.00">
                        </div>
                        <small class="form-hint">Set a competitive price</small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="stock_quantity">
                            Stock Quantity <span class="required">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="stock_quantity" 
                               name="stock_quantity" 
                               value="<?php echo $product['stock_quantity']; ?>"
                               min="0" 
                               required 
                               placeholder="0">
                        <small class="form-hint">How many units do you have?</small>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4 class="section-title"><i class="fas fa-image"></i> Product Image</h4>
                
                <div class="form-group">
                    <label for="product_image">
                        Upload Image
                    </label>
                    
                    <?php if ($product['product_image']): ?>
                        <div class="current-image-container">
                            <p class="text-muted"><strong>Current Image:</strong></p>
                            <img src="<?php echo BASE_URL . '../admin/' . $product['product_image']; ?>" 
                                 alt="Current Image"
                                 id="currentImage"
                                 style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1rem;">
                            <p class="text-muted" style="font-size: 0.9rem;">Upload a new image to replace the current one</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="image-upload-area">
                        <input type="file" 
                               class="form-control-file" 
                               id="product_image" 
                               name="product_image" 
                               accept="image/*"
                               onchange="previewImage(event)">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to browse or drag and drop</p>
                            <small>JPG, PNG, GIF • Max 5MB</small>
                        </div>
                        <img id="imagePreview" style="display: none; max-width: 100%; height: auto; border-radius: 8px; margin-top: 1rem;">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4 class="section-title"><i class="fas fa-toggle-on"></i> Product Status</h4>
                
                <div class="form-group">
                    <div class="toggle-switch">
                        <input type="checkbox" 
                               class="toggle-input" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               <?php echo $product['is_active'] ? 'checked' : ''; ?>>
                        <label class="toggle-label" for="is_active">
                            <span class="toggle-slider"></span>
                            <span class="toggle-text">Product is Active and Visible to Customers</span>
                        </label>
                    </div>
                    <small class="form-hint">Toggle to make this product visible or hidden in the shop</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Product
                </button>
                <a href="<?php echo BASE_URL; ?>inventory" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.card-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.5rem;
}

.card-header i {
    color: var(--primary-pink);
    margin-right: 0.5rem;
}

.btn-link {
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.2s;
}

.btn-link:hover {
    color: var(--primary-pink);
}

.card-body {
    padding: 2rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-danger {
    background: #fee;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.product-form {
    max-width: 900px;
}

.form-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.section-title {
    color: #2c3e50;
    font-size: 1.1rem;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e0e0e0;
}

.section-title i {
    color: var(--primary-pink);
    margin-right: 0.5rem;
}

.form-row {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
}

.required {
    color: #dc3545;
    font-weight: bold;
}

.form-control, .form-control-file {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.input-group {
    display: flex;
    align-items: center;
}

.input-prefix {
    background: #f0f0f0;
    padding: 0.75rem 1rem;
    border: 2px solid #e0e0e0;
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-weight: 600;
    color: #666;
}

.input-group .form-control {
    border-radius: 0 8px 8px 0;
}

.form-hint {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.85rem;
    color: #666;
    font-style: italic;
}

.current-image-container {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
}

.image-upload-area {
    position: relative;
}

.form-control-file {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.upload-placeholder {
    border: 3px dashed #d0d0d0;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    background: #fafafa;
    transition: all 0.3s;
    cursor: pointer;
}

.upload-placeholder:hover {
    border-color: var(--primary-pink);
    background: #fff;
}

.upload-placeholder i {
    font-size: 3rem;
    color: var(--primary-pink);
    margin-bottom: 1rem;
    display: block;
}

.upload-placeholder p {
    margin: 0.5rem 0;
    color: #2c3e50;
    font-weight: 500;
}

.upload-placeholder small {
    color: #666;
}

.toggle-switch {
    display: flex;
    align-items: center;
}

.toggle-input {
    display: none;
}

.toggle-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    gap: 1rem;
}

.toggle-slider {
    position: relative;
    width: 60px;
    height: 30px;
    background: #ccc;
    border-radius: 30px;
    transition: background 0.3s;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    width: 24px;
    height: 24px;
    background: white;
    border-radius: 50%;
    top: 3px;
    left: 3px;
    transition: transform 0.3s;
}

.toggle-input:checked + .toggle-label .toggle-slider {
    background: var(--primary-pink);
}

.toggle-input:checked + .toggle-label .toggle-slider::before {
    transform: translateX(30px);
}

.toggle-text {
    font-weight: 500;
    color: #2c3e50;
}

.form-actions {
    margin-top: 2rem;
    display: flex;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 2px solid #f0f0f0;
}

.btn-lg {
    padding: 0.875rem 2rem;
    font-size: 1.05rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .form-section {
        padding: 1rem;
    }
}
</style>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const currentImage = document.getElementById('currentImage');
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            
            if (currentImage) {
                currentImage.style.opacity = '0.5';
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>
