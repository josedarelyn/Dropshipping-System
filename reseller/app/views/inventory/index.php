<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<!-- Page Header -->
<div class="page-header-section">
    <div>
        <h1 class="page-title"><i class="fas fa-box"></i> Inventory Management</h1>
        <p class="page-subtitle">Manage your products and view available items</p>
    </div>
    <a href="<?php echo BASE_URL; ?>inventory/add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Product
    </a>
</div>

<!-- My Products Section -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store"></i> My Products</h3>
        <span class="badge badge-primary"><?php echo count($myProducts); ?> Products</span>
    </div>
    <div class="card-body">
        <?php if (empty($myProducts)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Products Yet</h3>
                <p>You haven't added any products yet. Start by adding your first product!</p>
                <a href="<?php echo BASE_URL; ?>inventory/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Your First Product
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myProducts as $product): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <?php if ($product['product_image']): ?>
                                            <img src="<?php echo BASE_URL . '../admin/' . $product['product_image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                                 class="product-thumb">
                                        <?php else: ?>
                                            <div class="product-thumb-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                                            <?php if (!empty($product['description'])): ?>
                                                <small><?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                                <td><strong>₱<?php echo number_format($product['price'], 2); ?></strong></td>
                                <td>
                                    <span class="badge badge-<?php echo $product['stock_quantity'] > 10 ? 'success' : ($product['stock_quantity'] > 0 ? 'warning' : 'danger'); ?>">
                                        <?php echo $product['stock_quantity']; ?> units
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $product['is_active'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>inventory/edit/<?php echo $product['product_id']; ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Edit Product">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>inventory/delete/<?php echo $product['product_id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this product?')"
                                           title="Delete Product">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Available Products (Admin) Section -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-shopping-bag"></i> Available Products from Admin</h3>
        <span class="badge badge-info">For Reference</span>
    </div>
    <div class="card-body">
        <?php 
        $adminProducts = array_filter($allProducts, function($p) { 
            return empty($p['reseller_id']); 
        });
        ?>
        
        <?php if (empty($adminProducts)): ?>
            <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No admin products available</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($adminProducts as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($product['product_image']): ?>
                                <img src="<?php echo BASE_URL . '../admin/' . $product['product_image']; ?>" 
                                     alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <?php else: ?>
                                <div class="product-image-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                            <div class="product-badge">
                                <span class="badge badge-<?php echo $product['stock_quantity'] > 10 ? 'success' : ($product['stock_quantity'] > 0 ? 'warning' : 'danger'); ?>">
                                    <?php echo $product['stock_quantity']; ?> in stock
                                </span>
                            </div>
                        </div>
                        <div class="product-details">
                            <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                            <p class="product-description">
                                <?php echo htmlspecialchars(substr($product['description'] ?? 'No description available', 0, 80)); ?>...
                            </p>
                            <div class="product-pricing">
                                <div>
                                    <small>Customer Price</small>
                                    <strong class="price-customer">₱<?php echo number_format($product['price'], 2); ?></strong>
                                </div>
                                <div>
                                    <small>Your Price</small>
                                    <strong class="price-reseller">₱<?php echo number_format($product['reseller_price'], 2); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
}

.alert-success {
    background: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.alert i {
    font-size: 1.25rem;
}

.page-header-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 1.75rem;
    color: var(--gray-800);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-title i {
    color: var(--primary-pink);
}

.page-subtitle {
    color: var(--gray-600);
    margin: 0;
    font-size: 0.95rem;
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: var(--gray-800);
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-header h3 i {
    color: var(--primary-pink);
}

.card-body {
    padding: 1.5rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-500);
}

.empty-state i {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin-bottom: 2rem;
    font-size: 1rem;
}

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table thead {
    background: var(--gray-50);
}

table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--gray-200);
}

table td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
}

table tbody tr:hover {
    background: var(--gray-50);
}

.product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid var(--gray-200);
}

.product-thumb-placeholder {
    width: 60px;
    height: 60px;
    background: var(--gray-100);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-400);
    font-size: 1.5rem;
}

.product-info div {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-info strong {
    color: var(--gray-800);
    font-size: 0.95rem;
}

.product-info small {
    color: var(--gray-500);
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    font-size: 0.9rem;
}

.btn-primary {
    background: var(--primary-pink);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-pink-dark);
    transform: translateY(-1px);
}

.btn-sm {
    padding: 0.4rem 0.75rem;
    font-size: 0.85rem;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.badge-secondary {
    background: var(--gray-200);
    color: var(--gray-700);
}

.badge-primary {
    background: var(--primary-pink);
    color: white;
}

.badge-info {
    background: #d1ecf1;
    color: #0c5460;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.product-card {
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
    background: white;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: var(--gray-100);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-400);
    font-size: 3rem;
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.product-details {
    padding: 1.25rem;
}

.product-details h4 {
    margin: 0 0 0.75rem 0;
    font-size: 1rem;
    color: var(--gray-800);
    line-height: 1.4;
}

.product-description {
    color: var(--gray-600);
    font-size: 0.85rem;
    margin: 0 0 1rem 0;
    line-height: 1.5;
}

.product-pricing {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

.product-pricing > div {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-pricing small {
    color: var(--gray-500);
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
}

.price-customer {
    color: var(--gray-700);
    font-size: 1.1rem;
}

.price-reseller {
    color: var(--primary-pink);
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .page-header-section {
        flex-direction: column;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    table {
        font-size: 0.85rem;
    }
    
    .product-thumb {
        width: 50px;
        height: 50px;
    }
}
</style>
