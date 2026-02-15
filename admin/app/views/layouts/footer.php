        </main>
    </div>
    
    <!-- User Dropdown Menu (Hidden by default) -->
    <div id="userDropdown" style="display: none; position: absolute; top: 60px; right: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow-lg); padding: 10px; min-width: 200px; z-index: 9999;">
        <a href="<?php echo BASE_URL; ?>profile" style="display: block; padding: 10px 15px; color: var(--dark-gray); text-decoration: none; border-radius: 8px; transition: var(--transition-base);">
            <i class="fas fa-user"></i> Profile
        </a>
        <a href="<?php echo BASE_URL; ?>commission/withdrawalSchedule" style="display: block; padding: 10px 15px; color: var(--dark-gray); text-decoration: none; border-radius: 8px; transition: var(--transition-base);">
            <i class="fas fa-cog"></i> Settings
        </a>
        <hr style="margin: 8px 0; border: none; border-top: 1px solid var(--secondary-lavender);">
        <a href="<?php echo BASE_URL; ?>auth/logout" style="display: block; padding: 10px 15px; color: var(--danger); text-decoration: none; border-radius: 8px; transition: var(--transition-base);">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
    
    <!-- Custom JavaScript -->
    <script>
        // Sidebar Toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
        });
        
        // User Menu Toggle
        document.getElementById('userMenuToggle').addEventListener('click', function() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenuToggle');
            const dropdown = document.getElementById('userDropdown');
            
            if (!userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
        
        // Global Search
        document.getElementById('globalSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // Implement global search functionality
            console.log('Searching for:', searchTerm);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
        
        // Responsive sidebar for mobile
        if (window.innerWidth <= 1024) {
            document.body.classList.add('sidebar-collapsed');
        }
        
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 1024) {
                document.body.classList.add('sidebar-collapsed');
            }
        });
    </script>
    
    <!-- Define BASE_URL for JavaScript -->
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    
    <script src="<?php echo BASE_URL; ?>public/js/admin-scripts.js?v=<?php echo time(); ?>"></script>
</body>
</html>
