<style>
    .product-detail-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin: 30px 0;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .product-image-main {
        width: 100%;
        border-radius: 12px;
        object-fit: cover;
    }
    
    .product-detail-info h1 {
        font-size: 32px;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .product-category {
        display: inline-block;
        padding: 5px 15px;
        background: var(--light-gray);
        border-radius: 20px;
        font-size: 14px;
        color: var(--gray);
        margin-bottom: 15px;
    }
    
    .product-price-large {
        font-size: 36px;
        font-weight: bold;
        color: var(--primary);
        margin: 20px 0;
    }
    
    .product-description {
        line-height: 1.8;
        color: var(--gray);
        margin: 20px 0;
        padding: 20px;
        background: var(--light-gray);
        border-radius: 8px;
    }
    
    .stock-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
        font-size: 16px;
    }
    
    .stock-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .stock-available {
        background: #d4edda;
        color: #155724;
    }
    
    .stock-low {
        background: #fff3cd;
        color: #856404;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 25px 0;
    }
    
    .qty-btn {
        width: 40px;
        height: 40px;
        border: 2px solid var(--primary);
        background: white;
        color: var(--primary);
        border-radius: 8px;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .qty-btn:hover {
        background: var(--primary);
        color: white;
    }
    
    .qty-input {
        width: 80px;
        text-align: center;
        font-size: 18px;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin: 30px 0;
    }
    
    .related-products {
        margin-top: 60px;
    }
    
    .related-products h2 {
        margin-bottom: 30px;
        font-size: 28px;
    }
    
    @media (max-width: 768px) {
        .product-detail-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="product-detail-container">
    <div>
        <?php if ($product['product_image']): ?>
            <img src="<?php echo BASE_URL . '../admin/' . $product['product_image']; ?>" 
                 alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                 class="product-image-main">
        <?php else: ?>
            <div class="product-image-main" style="height: 500px; display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                <i class="fas fa-image" style="font-size: 100px; color: #ccc;"></i>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="product-detail-info">
        <?php if ($product['category_name']): ?>
            <span class="product-category">
                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['category_name']); ?>
            </span>
        <?php endif; ?>
        
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
        
        <div class="product-price-large">₱<?php echo number_format($product['price'], 2); ?></div>
        
        <div class="stock-info">
            <i class="fas fa-box"></i>
            <span class="stock-badge <?php echo $product['stock_quantity'] > 10 ? 'stock-available' : 'stock-low'; ?>">
                <?php echo $product['stock_quantity']; ?> Available
            </span>
        </div>
        
        <?php if ($product['description']): ?>
            <div class="product-description">
                <h3 style="margin-bottom: 10px;">Description</h3>
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($product['stock_quantity'] > 0): ?>
            <?php if (isset($_SESSION['customer_id'])): ?>
            <form method="POST" action="<?php echo BASE_URL; ?>cart/add">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                
                <div class="quantity-selector">
                    <label style="font-weight: 600;">Quantity:</label>
                    <button type="button" class="qty-btn" onclick="decreaseQty()">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" class="qty-input">
                    <button type="button" class="qty-btn" onclick="increaseQty()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn" style="flex: 1; padding: 15px; font-size: 16px;">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <a href="<?php echo BASE_URL; ?>shop" class="btn btn-outline" style="padding: 15px 30px;">
                        <i class="fas fa-arrow-left"></i> Back to Shop
                    </a>
                </div>
            </form>
            <?php else: ?>
            <div class="action-buttons" style="margin-top: 25px;">
                <a href="<?php echo BASE_URL; ?>auth/login" class="btn" style="flex: 1; padding: 15px; font-size: 16px; text-align: center;">
                    <i class="fas fa-sign-in-alt"></i> Login to Add to Cart
                </a>
                <a href="<?php echo BASE_URL; ?>shop" class="btn btn-outline" style="padding: 15px 30px;">
                    <i class="fas fa-arrow-left"></i> Back to Shop
                </a>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                This product is currently out of stock
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #f0f0f0;">
            <h4 style="margin-bottom: 15px;">Product Details</h4>
            <ul style="line-height: 2;">
                <li><strong>SKU:</strong> <?php echo $product['sku'] ?? 'N/A'; ?></li>
                <li><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></li>
                <li><strong>Stock:</strong> <?php echo $product['stock_quantity']; ?> units</li>
            </ul>
        </div>
    </div>
</div>

<?php if (!empty($relatedProducts)): ?>
    <div class="related-products">
        <h2><i class="fas fa-heart"></i> You Might Also Like</h2>
        <div class="products-grid">
            <?php foreach ($relatedProducts as $related): ?>
                <div class="product-card" onclick="location.href='<?php echo BASE_URL; ?>shop/product/<?php echo $related['product_id']; ?>'">
                    <?php if ($related['product_image']): ?>
                        <img src="<?php echo BASE_URL . '../admin/' . $related['product_image']; ?>" 
                             alt="<?php echo htmlspecialchars($related['product_name']); ?>" 
                             class="product-image">
                    <?php else: ?>
                        <div class="product-image" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                            <i class="fas fa-image" style="font-size: 60px; color: #ccc;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-info">
                        <div class="product-name"><?php echo htmlspecialchars($related['product_name']); ?></div>
                        <div class="product-price">₱<?php echo number_format($related['price'], 2); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}
</script>
