<!-- Notifications Page -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-bell"></i> Notifications
                </h1>
                <p class="page-description">Stay updated with system activities and alerts</p>
            </div>
            <div class="col" style="text-align: right;">
                <?php if ($unread_count > 0): ?>
                <a href="<?php echo BASE_URL; ?>notification/markAllRead" class="btn btn-primary">
                    <i class="fas fa-check-double"></i> Mark All as Read
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Notification Stats -->
    <div class="row" style="margin-bottom: 25px;">
        <div class="col col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo count($notifications); ?></h3>
                    <p>Total Notifications</p>
                </div>
            </div>
        </div>
        <div class="col col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo $unread_count; ?></h3>
                    <p>Unread</p>
                </div>
            </div>
        </div>
        <div class="col col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo count($notifications) - $unread_count; ?></h3>
                    <p>Read</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-list"></i> All Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($notifications)): ?>
                    <div class="text-center" style="padding: 40px 20px;">
                        <i class="fas fa-bell-slash" style="font-size: 60px; color: #ccc; margin-bottom: 20px;"></i>
                        <h4 style="color: #666;">No Notifications</h4>
                        <p style="color: #999;">You're all caught up! Check back later for updates.</p>
                    </div>
                    <?php else: ?>
                    <div class="notification-list">
                        <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>" 
                             data-id="<?php echo $notification['notification_id']; ?>">
                            <div class="notification-icon">
                                <?php
                                $iconMap = [
                                    'order' => 'fa-shopping-cart',
                                    'payment' => 'fa-credit-card',
                                    'commission' => 'fa-dollar-sign',
                                    'withdrawal' => 'fa-money-bill-wave',
                                    'system' => 'fa-info-circle'
                                ];
                                $icon = $iconMap[$notification['type']] ?? 'fa-bell';
                                ?>
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <div class="notification-content">
                                <h5><?php echo htmlspecialchars($notification['title']); ?></h5>
                                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                <span class="notification-time">
                                    <i class="far fa-clock"></i>
                                    <?php 
                                    $time = strtotime($notification['created_at']);
                                    $diff = time() - $time;
                                    if ($diff < 60) echo 'Just now';
                                    elseif ($diff < 3600) echo floor($diff/60) . ' minutes ago';
                                    elseif ($diff < 86400) echo floor($diff/3600) . ' hours ago';
                                    else echo date('M d, Y h:i A', $time);
                                    ?>
                                </span>
                            </div>
                            <div class="notification-actions">
                                <?php if (!$notification['is_read']): ?>
                                <a href="<?php echo BASE_URL; ?>notification/markRead/<?php echo $notification['notification_id']; ?>" 
                                   class="btn btn-sm btn-primary" title="Mark as read">
                                    <i class="fas fa-check"></i>
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>notification/delete/<?php echo $notification['notification_id']; ?>" 
                                   class="btn btn-sm btn-outline" 
                                   onclick="return confirm('Delete this notification?');" 
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
    gap: 15px;
}

.notification-item.unread {
    background: #fff8f0;
    border-left: 4px solid var(--primary-pink);
}

.notification-item.read {
    background: #f8f9fa;
    opacity: 0.8;
}

.notification-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-pink);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
}

.notification-content h5 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #333;
}

.notification-content p {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 14px;
    line-height: 1.5;
}

.notification-time {
    font-size: 12px;
    color: #999;
}

.notification-actions {
    display: flex;
    gap: 5px;
    flex-shrink: 0;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

.stat-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-right: 20px;
}

.stat-details h3 {
    margin: 0;
    font-size: 32px;
    font-weight: bold;
}

.stat-details p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}
</style>
