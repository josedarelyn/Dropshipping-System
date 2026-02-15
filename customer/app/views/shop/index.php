<style>
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #f0f0f0;
    }
    
    .product-info {
        padding: 15px;
    }
    
    .product-name {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        font-size: 22px;
        font-weight: bold;
        color: var(--primary);
        margin-bottom: 10px;
    }
    
    .product-stock {
        font-size: 13px;
        color: var(--gray);
        margin-bottom: 15px;
    }
    
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .category-filter {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .category-btn {
        padding: 8px 20px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 25px;
        color: var(--dark);
        text-decoration: none;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .category-btn:hover, .category-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 40px;
    }
    
    .page-btn {
        padding: 8px 15px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 8px;
        color: var(--dark);
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .page-btn:hover, .page-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }
    
    .empty-state i {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
</style>

<div class="filter-section">
    <h3 style="margin-bottom: 15px;">
        <i class="fas fa-filter"></i> Filter by Category
    </h3>
    <div class="category-filter">
        <a href="<?php echo BASE_URL; ?>shop" class="category-btn <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
            All Products
        </a>
        <?php foreach ($categories as $category): ?>
            <a href="<?php echo BASE_URL; ?>shop?category=<?php echo $category['category_id']; ?>" 
               class="category-btn <?php echo (isset($_GET['category']) && $_GET['category'] == $category['category_id']) ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($category['category_name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($searchQuery): ?>
<div style="background: #e3f2fd; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
    <i class="fas fa-search"></i> Search results for: <strong><?php echo htmlspecialchars($searchQuery); ?></strong>
    <a href="<?php echo BASE_URL; ?>shop" style="margin-left: 15px; color: var(--primary);">Clear search</a>
</div>
<?php endif; ?>

<h2 style="margin-bottom: 20px;">
    <i class="fas fa-shopping-bag"></i> 
    <?php echo $searchQuery ? 'Search Results' : ($currentCategory ? 'Products' : 'Featured Products'); ?>
</h2>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>No products found</h3>
        <p>Try adjusting your search or filter to find what you're looking for</p>
        <a href="<?php echo BASE_URL; ?>shop" class="btn" style="margin-top: 20px;">Browse All Products</a>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card" onclick="location.href='<?php echo BASE_URL; ?>shop/product/<?php echo $product['product_id']; ?>'">
                <?php if ($product['product_image']): ?>
                    <img src="<?php echo BASE_URL . '../admin/' . $product['product_image']; ?>" 
                         alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                         class="product-image"
                         onerror="this.src='<?php echo BASE_URL; ?>public/images/no-image.jpg'">
                <?php else: ?>
                    <div class="product-image" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                        <i class="fas fa-image" style="font-size: 60px; color: #ccc;"></i>
                    </div>
                <?php endif; ?>
                
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                    <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                    <div class="product-stock">
                        <i class="fas fa-box"></i> 
                        <?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] . ' in stock' : 'Out of stock'; ?>
                    </div>
                    <button class="btn" style="width: 100%;" onclick="event.stopPropagation(); addToCart(<?php echo $product['product_id']; ?>)">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?><?php echo $currentCategory ? '&category=' . $currentCategory : ''; ?>" class="page-btn">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $currentPage): ?>
                    <span class="page-btn active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?><?php echo $currentCategory ? '&category=' . $currentCategory : ''; ?>" class="page-btn">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?><?php echo $currentCategory ? '&category=' . $currentCategory : ''; ?>" class="page-btn">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script>
function addToCart(productId) {
    <?php if (!isset($_SESSION['customer_id'])): ?>
        showToast('Please login to add items to cart', 'error');
        setTimeout(() => { window.location.href = '<?php echo BASE_URL; ?>auth/login'; }, 1500);
        return;
    <?php endif; ?>
    
    fetch('<?php echo BASE_URL; ?>cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'product_id=' + productId + '&quantity=1'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Product added to cart!', 'success');
            updateCartCount();
        } else {
            showToast(data.message || 'Failed to add product', 'error');
        }
    })
    .catch(() => showToast('Something went wrong', 'error'));
}

function showToast(message, type) {
    const existing = document.querySelector('.cart-toast');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = 'cart-toast cart-toast-' + type;
    toast.innerHTML = '<i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + '"></i> ' + message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
.cart-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 10px;
    color: white;
    font-weight: 500;
    font-size: 14px;
    z-index: 9999;
    transform: translateX(120%);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 10px;
}
.cart-toast.show { transform: translateX(0); }
.cart-toast-success { background: #28a745; }
.cart-toast-error { background: #dc3545; }
</style>
