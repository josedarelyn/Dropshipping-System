-- ============================================
-- E-BENTA DATABASE SCHEMA
-- Dhendhen Beauty Products & Boutique
-- Dropshipping Management System
-- Version: 1.0.0
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS ebenta_database 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE ebenta_database;

-- ============================================
-- TABLE: users
-- Stores all system users (Admin, Reseller, Customer)
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255) NULL DEFAULT NULL,
    role ENUM('admin', 'reseller', 'customer') DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: categories
-- Product categories
-- ============================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    parent_id INT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: categories
-- Product categories
-- ============================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    parent_id INT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: products
-- Product inventory management
-- ============================================
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category_id INT,
    sku VARCHAR(50) UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2),
    stock_quantity INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 10,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_sku (sku),
    INDEX idx_status (status),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: resellers
-- Reseller management
-- ============================================
CREATE TABLE resellers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    commission_rate DECIMAL(5,2) DEFAULT 15.00,
    total_sales DECIMAL(10,2) DEFAULT 0.00,
    total_commission DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at DATETIME NULL,
    rejection_reason TEXT NULL,
    gcash_number VARCHAR(20),
    gcash_name VARCHAR(100),
    business_name VARCHAR(100),
    business_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: orders
-- Order management
-- ============================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    reseller_id INT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    total_items INT DEFAULT 0,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('cod', 'gcash', 'bank_transfer', 'credit_card') DEFAULT 'cod',
    payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    delivery_method ENUM('door-to-door', 'courier', 'pick-up') DEFAULT 'courier',
    delivery_address TEXT,
    delivery_fee DECIMAL(10,2) DEFAULT 0.00,
    notes TEXT,
    tracking_number VARCHAR(100),
    cancelled_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reseller_id) REFERENCES resellers(id) ON DELETE SET NULL,
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_customer (customer_id),
    INDEX idx_reseller (reseller_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: order_items
-- Individual items in orders
-- ============================================
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: order_tracking
-- Order status tracking history
-- ============================================
CREATE TABLE order_tracking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: commissions
-- Commission tracking and payouts
-- ============================================
CREATE TABLE commissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reseller_id INT NOT NULL,
    order_id INT NULL,
    sale_amount DECIMAL(10,2) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at DATETIME NULL,
    transaction_id VARCHAR(100),
    payment_method VARCHAR(50),
    paid_at DATETIME NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reseller_id) REFERENCES resellers(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_reseller (reseller_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: transactions
-- Financial transaction history
-- ============================================
CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transaction_id VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    type ENUM('sale', 'commission', 'refund', 'withdrawal') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    reference_id INT NULL,
    reference_type VARCHAR(50),
    payment_method VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_transaction_id (transaction_id),
    INDEX idx_user (user_id),
    INDEX idx_type (type),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: notifications
-- System notifications
-- ============================================
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: activity_logs
-- System activity logging
-- ============================================
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: settings
-- System settings and configurations
-- ============================================
CREATE TABLE system_settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(50) DEFAULT 'string',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: product_reviews
-- Customer product reviews
-- ============================================
CREATE TABLE product_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    INDEX idx_product (product_id),
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: e_wallet
-- Reseller e-wallet management
-- ============================================
CREATE TABLE e_wallet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reseller_id INT UNIQUE NOT NULL, 
    balance DECIMAL(10,2) DEFAULT 0.00,
    total_earned DECIMAL(10,2) DEFAULT 0.00,
    total_withdrawn DECIMAL(10,2) DEFAULT 0.00,
    gcash_number VARCHAR(20),
    gcash_name VARCHAR(100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reseller_id) REFERENCES resellers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEFAULT DATA
-- ============================================

-- Insert default admin user
INSERT INTO users (full_name, email, password, phone, role, status) VALUES
('System Administrator', 'admin@ebenta.com', '$2y$10$D7z5ZpGqQqKqQqKqvKqKqeQqKqKqKqKqKqKqKqKqKqKqKqKqKqKqm', '09123456789', 'admin', 'active');
-- Password: admin123 (use fix_password.php if login fails)

-- Insert default categories
INSERT INTO categories (name, description, status) VALUES
('Skincare', 'Beauty and skincare products', 'active'),
('Makeup', 'Cosmetics and makeup items', 'active'),
('Haircare', 'Hair care and styling products', 'active'),
('Fragrances', 'Perfumes and body mists', 'active'),
('Accessories', 'Beauty accessories and tools', 'active');

-- Insert system settings
INSERT INTO system_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'E-Benta - Dhendhen Beauty Products', 'string', 'Website name'),
('site_email', 'info@ebenta.com', 'string', 'Contact email'),
('site_phone', '09123456789', 'string', 'Contact phone'),
('default_commission_rate', '15', 'number', 'Default commission rate percentage'),
('low_stock_threshold', '10', 'number', 'Low stock alert threshold'),
('minimum_order_amount', '100', 'number', 'Minimum order amount'),
('delivery_fee', '50', 'number', 'Standard delivery fee'),
('gcash_enabled', '1', 'boolean', 'Enable GCash payments'),
('withdrawal_day', 'Friday', 'string', 'Weekly withdrawal day'),
('site_logo', '', 'string', 'Site logo path'),
('maintenance_mode', '0', 'boolean', 'Enable maintenance mode'),
('user_photo_max_size', '2097152', 'number', 'Maximum user photo size in bytes (2MB)'),
('user_photo_allowed_types', 'jpeg,jpg,png,gif', 'string', 'Allowed user photo file types'),
('user_photo_max_width', '800', 'number', 'Maximum user photo width in pixels'),
('user_photo_max_height', '800', 'number', 'Maximum user photo height in pixels'),
('default_avatar_path', 'public/images/default-avatar.png', 'string', 'Default user avatar image path');

-- ============================================
-- CREATE VIEWS FOR REPORTING
-- ============================================

-- Sales summary view
CREATE OR REPLACE VIEW v_sales_summary AS
SELECT 
    DATE(o.created_at) as sale_date,
    COUNT(DISTINCT o.id) as total_orders,
    SUM(o.total_amount) as total_sales,
    AVG(o.total_amount) as average_order_value,
    SUM(o.total_items) as total_items_sold
FROM orders o
WHERE o.status != 'cancelled'
GROUP BY DATE(o.created_at);

-- Reseller performance view
CREATE OR REPLACE VIEW v_reseller_performance AS
SELECT 
    r.id,
    u.full_name as reseller_name,
    u.email,
    r.commission_rate,
    r.total_sales,
    r.total_commission,
    COUNT(DISTINCT o.id) as total_orders,
    r.status,
    r.created_at
FROM resellers r
LEFT JOIN users u ON r.user_id = u.id
LEFT JOIN orders o ON r.id = o.reseller_id
GROUP BY r.id;

-- Product performance view
CREATE OR REPLACE VIEW v_product_performance AS
SELECT 
    p.id,
    p.name,
    p.sku,
    p.price,
    p.stock_quantity,
    COUNT(DISTINCT oi.order_id) as times_ordered,
    SUM(oi.quantity) as total_sold,
    SUM(oi.subtotal) as total_revenue,
    p.status
FROM products p
LEFT JOIN order_items oi ON p.id = oi.product_id
GROUP BY p.id;

-- ============================================
-- CREATE STORED PROCEDURES
-- ============================================

DELIMITER //

-- Calculate commission for an order
CREATE PROCEDURE sp_calculate_commission(
    IN p_order_id INT,
    IN p_reseller_id INT
)
BEGIN
    DECLARE v_sale_amount DECIMAL(10,2);
    DECLARE v_commission_rate DECIMAL(5,2);
    DECLARE v_commission_amount DECIMAL(10,2);
    
    -- Get order amount
    SELECT total_amount INTO v_sale_amount
    FROM orders
    WHERE id = p_order_id;
    
    -- Get commission rate
    SELECT commission_rate INTO v_commission_rate
    FROM resellers
    WHERE id = p_reseller_id;
    
    -- Calculate commission
    SET v_commission_amount = (v_sale_amount * v_commission_rate) / 100;
    
    -- Insert commission record
    INSERT INTO commissions (reseller_id, order_id, sale_amount, commission_rate, amount, status)
    VALUES (p_reseller_id, p_order_id, v_sale_amount, v_commission_rate, v_commission_amount, 'pending');
    
    -- Update reseller totals
    UPDATE resellers
    SET total_sales = total_sales + v_sale_amount,
        total_commission = total_commission + v_commission_amount
    WHERE id = p_reseller_id;
END //

-- Update product stock
CREATE PROCEDURE sp_update_stock(
    IN p_product_id INT,
    IN p_quantity INT,
    IN p_operation VARCHAR(10)
)
BEGIN
    IF p_operation = 'add' THEN
        UPDATE products
        SET stock_quantity = stock_quantity + p_quantity
        WHERE id = p_product_id;
    ELSEIF p_operation = 'subtract' THEN
        UPDATE products
        SET stock_quantity = stock_quantity - p_quantity
        WHERE id = p_product_id AND stock_quantity >= p_quantity;
    END IF;
END //

DELIMITER ;

-- ============================================
-- CREATE TRIGGERS
-- ============================================

DELIMITER //

-- Trigger: Auto-generate order number
CREATE TRIGGER tr_orders_before_insert
BEFORE INSERT ON orders
FOR EACH ROW
BEGIN
    IF NEW.order_number IS NULL OR NEW.order_number = '' THEN
        SET NEW.order_number = CONCAT('ORD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 9999), 4, '0'));
    END IF;
END //

-- Trigger: Log order status changes
CREATE TRIGGER tr_orders_after_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO order_tracking (order_id, status, notes, updated_by)
        VALUES (NEW.id, NEW.status, CONCAT('Status changed from ', OLD.status, ' to ', NEW.status), NULL);
    END IF;
END //

-- Trigger: Update product stock on order
CREATE TRIGGER tr_order_items_after_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock_quantity = stock_quantity - NEW.quantity
    WHERE id = NEW.product_id;
END //

DELIMITER ;

-- ============================================
-- GRANT PERMISSIONS (Optional)
-- ============================================
-- GRANT ALL PRIVILEGES ON ebenta_database.* TO 'ebenta_user'@'localhost' IDENTIFIED BY 'secure_password';
-- FLUSH PRIVILEGES;

-- ============================================
-- END OF SCHEMA
-- ============================================

-- Display success message
SELECT 'E-Benta database schema created successfully!' as Status;
SELECT COUNT(*) as 'Total Tables' FROM information_schema.tables WHERE table_schema = 'ebenta_database';
