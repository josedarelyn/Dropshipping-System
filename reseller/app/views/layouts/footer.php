            </main>
        </div>
    </div>

    <script>
        // Set active menu item based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const menuLinks = document.querySelectorAll('.sidebar-menu a');
            
            menuLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add('active');
                }
            });

            // Auto-hide alerts after 5 seconds
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
