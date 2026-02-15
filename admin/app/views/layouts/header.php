<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>E-Benta Admin - Dhendhen Beauty Products</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>public/images/favicon.png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin-style.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <?php echo isset($page_title) ? $page_title : 'Dashboard'; ?>
                </h1>
            </div>
            
            <div class="header-right">
                <!-- Search -->
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." id="globalSearch">
                </div>
                
                <!-- Icons -->
                <div class="header-icons">
                    <!-- Notifications -->
                    <a href="<?php echo BASE_URL; ?>notification" class="header-icon" id="notificationIcon" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <?php
                        // Get unread notification count
                        $notifModel = new Notification();
                        $unreadCount = $notifModel->countUnread($_SESSION['admin_id']);
                        ?>
                        <span class="badge" id="notificationBadge" style="display: <?php echo $unreadCount > 0 ? 'block' : 'none'; ?>;">
                            <?php echo $unreadCount; ?>
                        </span>
                    </a>
                    
                    <!-- Messages (System Notifications) -->
                    <a href="<?php echo BASE_URL; ?>notification" class="header-icon" id="messageIcon" title="Messages">
                        <i class="fas fa-envelope"></i>
                        <?php
                        // Count 'system' type notifications as messages
                        $messageCount = $notifModel->countUnreadByType($_SESSION['admin_id'], 'system');
                        
                        // Get pending reseller count for sidebar badge
                        $resellerProfileModel = new ResellerProfile();
                        $pending_resellers = $resellerProfileModel->countPending();
                        ?>
                        <span class="badge" id="messageBadge" style="display: <?php echo $messageCount > 0 ? 'block' : 'none'; ?>;">
                            <?php echo $messageCount; ?>
                        </span>
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="user-menu" id="userMenuToggle">
                    <?php 
                        $avatarSrc = !empty($_SESSION['admin_photo']) 
                            ? BASE_URL . $_SESSION['admin_photo'] 
                            : BASE_URL . 'public/images/default-avatar.png';
                    ?>
                    <img src="<?php echo $avatarSrc; ?>" 
                         alt="Admin" class="user-avatar">
                    <div class="user-info">
                        <h4><?php echo $_SESSION['admin_name']; ?></h4>
                        <p>Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </header>
