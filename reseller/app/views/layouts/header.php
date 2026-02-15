<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard'; ?> - E-Benta Reseller Portal</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/reseller-style.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-store"></i>
                <div>
                    <h2>E-Benta</h2>
                    <p>Reseller Portal</p>
                </div>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="<?php echo BASE_URL; ?>dashboard" class="<?php echo (isset($_GET['url']) && $_GET['url'] === 'dashboard') || !isset($_GET['url']) ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <?php if (isset($_SESSION['reseller_status']) && $_SESSION['reseller_status'] === 'approved'): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>inventory" class="<?php echo (isset($_GET['url']) && strpos($_GET['url'], 'inventory') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-box"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>orders" class="<?php echo (isset($_GET['url']) && strpos($_GET['url'], 'orders') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>commission" class="<?php echo (isset($_GET['url']) && strpos($_GET['url'], 'commission') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Commissions</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>wallet" class="<?php echo (isset($_GET['url']) && strpos($_GET['url'], 'wallet') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-wallet"></i>
                        <span>E-Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>sales" class="<?php echo (isset($_GET['url']) && strpos($_GET['url'], 'sales') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Sales Report</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li>
                    <a href="<?php echo BASE_URL; ?>profile" class="<?php echo (isset($_GET['url']) && strpos($_GET['url'], 'profile') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>auth/logout" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar / Header -->
            <header class="topbar">
                <div class="topbar-left">
                    <h1><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                </div>
                <div class="topbar-right">
                    <?php if (isset($_SESSION['reseller_status']) && $_SESSION['reseller_status'] === 'pending'): ?>
                        <div class="alert alert-warning" style="margin-bottom:0; padding: 0.5rem 1rem; font-size: 0.875rem;">
                            <i class="fas fa-clock"></i> Account Pending Approval
                        </div>
                    <?php elseif (isset($_SESSION['reseller_status']) && $_SESSION['reseller_status'] === 'rejected'): ?>
                        <div class="alert alert-danger" style="margin-bottom:0; padding: 0.5rem 1rem; font-size: 0.875rem;">
                            <i class="fas fa-times-circle"></i> Account Rejected
                        </div>
                    <?php endif; ?>
                    
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['full_name'] ?? 'R', 0, 1)); ?>
                        </div>
                        <div class="user-info">
                            <h4><?php echo $_SESSION['full_name'] ?? 'Reseller'; ?></h4>
                            <p>Reseller</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content-area">
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
