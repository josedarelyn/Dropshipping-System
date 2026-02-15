        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-store"></i>
                </div>
                <div class="sidebar-brand">
                    <h2>E-Benta</h2>
                    <p>Dhendhen Beauty</p>
                </div>
            </div>
            
            <nav>
                <ul class="sidebar-nav">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>dashboard" class="nav-link <?php echo (isset($page_title) && $page_title == 'Dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Inventory Management -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>product" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Inventory') !== false) ? 'active' : ''; ?>">
                            <i class="fas fa-boxes"></i>
                            <span>Inventory</span>
                            <?php if(isset($low_stock_products) && count($low_stock_products) > 0): ?>
                                <span class="badge"><?php echo count($low_stock_products); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- Order Management -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>order" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Order') !== false) ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    
                    <!-- Reseller Management -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>reseller" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Reseller') !== false) ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i>
                            <span>Resellers</span>
                            <?php if(isset($pending_resellers) && $pending_resellers > 0): ?>
                                <span class="badge"><?php echo $pending_resellers; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- Commission & Payouts -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>commission" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Commission') !== false) ? 'active' : ''; ?>">
                            <i class="fas fa-wallet"></i>
                            <span>Commissions</span>
                        </a>
                    </li>
                    
                    <!-- User Management -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>user" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'User') !== false) ? 'active' : ''; ?>">
                            <i class="fas fa-user-cog"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    
                    <!-- Analytics -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>dashboard/analytics" class="nav-link <?php echo (isset($page_title) && $page_title == 'Analytics') ? 'active' : ''; ?>">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    
                    <!-- Reports -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>dashboard/reports" class="nav-link <?php echo (isset($page_title) && $page_title == 'Reports') ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    
                    <!-- Settings -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>settings" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    
                    <!-- Logout -->
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>auth/logout" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <?php
            // Display flash messages
            if (isset($_SESSION['flash_message'])):
                $flashType = $_SESSION['flash_type'];
                $flashMessage = $_SESSION['flash_message'];
                unset($_SESSION['flash_type']);
                unset($_SESSION['flash_message']);
            ?>
                <div class="alert alert-<?php echo $flashType; ?> fade-in">
                    <i class="fas fa-<?php 
                        echo $flashType == 'success' ? 'check-circle' : 
                             ($flashType == 'danger' ? 'exclamation-circle' : 
                             ($flashType == 'warning' ? 'exclamation-triangle' : 'info-circle')); 
                    ?>"></i>
                    <span><?php echo $flashMessage; ?></span>
                </div>
            <?php endif; ?>
