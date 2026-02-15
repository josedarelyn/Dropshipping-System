    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>
                    <i class="fas fa-heart"></i> <?php echo SITE_NAME; ?>
                </h4>
                <p>
                    Your trusted online beauty products and boutique shop. 
                    Quality products at affordable prices.
                </p>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="<?php echo BASE_URL; ?>shop"><i class="fas fa-chevron-right"></i> Shop</a>
                <a href="<?php echo BASE_URL; ?>cart"><i class="fas fa-chevron-right"></i> Cart</a>
                <?php if (isset($_SESSION['customer_id']) && (($_SESSION['role'] ?? '') === 'customer')): ?>
                    <a href="<?php echo BASE_URL; ?>orders"><i class="fas fa-chevron-right"></i> My Orders</a>
                    <a href="<?php echo BASE_URL; ?>account"><i class="fas fa-chevron-right"></i> My Account</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>auth/login"><i class="fas fa-chevron-right"></i> Login</a>
                    <a href="<?php echo BASE_URL; ?>auth/register"><i class="fas fa-chevron-right"></i> Register</a>
                <?php endif; ?>
            </div>
            
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p><i class="fas fa-phone"></i> +63 123 456 7890</p>
                <p><i class="fas fa-envelope"></i> info@dhendhen.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Manila, Philippines</p>
            </div>
            
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Made with <span class="heart"><i class="fas fa-heart"></i></span> All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        function toggleMobileMenu() {
            document.querySelector('.nav-content').classList.toggle('active');
        }
        
        // Update cart count dynamically
        function updateCartCount() {
            fetch('<?php echo BASE_URL; ?>cart/count')
                .then(res => res.json())
                .then(data => {
                    const badge = document.querySelector('.cart-badge');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            const cartBtn = document.querySelector('.header-btn[href*="cart"]');
                            if (cartBtn) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'cart-badge';
                                newBadge.textContent = data.count;
                                cartBtn.appendChild(newBadge);
                            }
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                });
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
