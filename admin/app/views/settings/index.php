<!-- System Settings -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-cog"></i> System Settings
                </h1>
                <p class="page-description">Configure system-wide settings and preferences</p>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_tab == 'general') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>settings?tab=general">
                                <i class="fas fa-info-circle"></i> General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_tab == 'photo') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>settings?tab=photo">
                                <i class="fas fa-camera"></i> Photo Upload
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_tab == 'order') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>settings?tab=order">
                                <i class="fas fa-shopping-cart"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_tab == 'payment') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>settings?tab=payment">
                                <i class="fas fa-credit-card"></i> Payment
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        
                        <!-- General Settings Tab -->
                        <?php if ($active_tab == 'general'): ?>
                        <div class="tab-pane active">
                            <form method="POST" action="<?php echo BASE_URL; ?>settings/updateGeneral">
                                <div class="row">
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="site_name">
                                                <i class="fas fa-store"></i> Site Name
                                            </label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                                   value="<?php echo $general_settings[0]['setting_value'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="site_email">
                                                <i class="fas fa-envelope"></i> Contact Email
                                            </label>
                                            <input type="email" class="form-control" id="site_email" name="site_email" 
                                                   value="<?php echo $general_settings[1]['setting_value'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="site_phone">
                                                <i class="fas fa-phone"></i> Contact Phone
                                            </label>
                                            <input type="text" class="form-control" id="site_phone" name="site_phone" 
                                                   value="<?php echo $general_settings[2]['setting_value'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label>
                                                <i class="fas fa-wrench"></i> Maintenance Mode
                                            </label>
                                            <div style="padding-top: 10px;">
                                                <label class="switch">
                                                    <input type="checkbox" name="maintenance_mode" 
                                                           <?php echo (isset($maintenance['setting_value']) && $maintenance['setting_value'] == '1') ? 'checked' : ''; ?>>
                                                    <span class="slider"></span>
                                                </label>
                                                <span style="margin-left: 10px;">Enable maintenance mode</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save General Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Photo Upload Settings Tab -->
                        <?php if ($active_tab == 'photo'): ?>
                        <div class="tab-pane active">
                            <form method="POST" action="<?php echo BASE_URL; ?>settings/updatePhoto">
                                <div class="row">
                                    <div class="col col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            Configure photo upload settings for user profile pictures
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="max_size">
                                                <i class="fas fa-file-image"></i> Maximum File Size (bytes)
                                            </label>
                                            <input type="number" class="form-control" id="max_size" name="max_size" 
                                                   value="<?php echo $photo_settings[1]['setting_value'] ?? '2097152'; ?>" required>
                                            <small class="text-muted">2097152 bytes = 2MB</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="allowed_types">
                                                <i class="fas fa-file-alt"></i> Allowed File Types
                                            </label>
                                            <input type="text" class="form-control" id="allowed_types" name="allowed_types" 
                                                   value="<?php echo $photo_settings[0]['setting_value'] ?? 'jpeg,jpg,png,gif'; ?>" required>
                                            <small class="text-muted">Comma-separated (e.g., jpeg,jpg,png,gif)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="max_width">
                                                <i class="fas fa-arrows-alt-h"></i> Maximum Width (pixels)
                                            </label>
                                            <input type="number" class="form-control" id="max_width" name="max_width" 
                                                   value="<?php echo $photo_settings[3]['setting_value'] ?? '800'; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="max_height">
                                                <i class="fas fa-arrows-alt-v"></i> Maximum Height (pixels)
                                            </label>
                                            <input type="number" class="form-control" id="max_height" name="max_height" 
                                                   value="<?php echo $photo_settings[2]['setting_value'] ?? '800'; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-12">
                                        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                            <div class="card-body">
                                                <h5 style="color: white; margin-bottom: 10px;">
                                                    <i class="fas fa-lightbulb"></i> Current Configuration
                                                </h5>
                                                <p style="margin: 5px 0;">
                                                    Max Size: <strong><?php echo number_format($photo_settings[1]['setting_value'] ?? 2097152); ?> bytes 
                                                    (<?php echo round(($photo_settings[1]['setting_value'] ?? 2097152) / 1048576, 2); ?> MB)</strong>
                                                </p>
                                                <p style="margin: 5px 0;">
                                                    Allowed Types: <strong><?php echo $photo_settings[0]['setting_value'] ?? 'jpeg,jpg,png,gif'; ?></strong>
                                                </p>
                                                <p style="margin: 5px 0;">
                                                    Max Dimensions: <strong><?php echo $photo_settings[3]['setting_value'] ?? '800'; ?> × 
                                                    <?php echo $photo_settings[2]['setting_value'] ?? '800'; ?> pixels</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Photo Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Order Settings Tab -->
                        <?php if ($active_tab == 'order'): ?>
                        <div class="tab-pane active">
                            <form method="POST" action="<?php echo BASE_URL; ?>settings/updateOrder">
                                <div class="row">
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="minimum_order_amount">
                                                <i class="fas fa-dollar-sign"></i> Minimum Order Amount (₱)
                                            </label>
                                            <input type="number" class="form-control" id="minimum_order_amount" 
                                                   name="minimum_order_amount" 
                                                   value="<?php echo $order_settings[0]['setting_value'] ?? '100'; ?>" 
                                                   required step="0.01">
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="delivery_fee">
                                                <i class="fas fa-truck"></i> Standard Delivery Fee (₱)
                                            </label>
                                            <input type="number" class="form-control" id="delivery_fee" 
                                                   name="delivery_fee" 
                                                   value="<?php echo $order_settings[1]['setting_value'] ?? '50'; ?>" 
                                                   required step="0.01">
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="low_stock_threshold">
                                                <i class="fas fa-exclamation-triangle"></i> Low Stock Threshold
                                            </label>
                                            <input type="number" class="form-control" id="low_stock_threshold" 
                                                   name="low_stock_threshold" 
                                                   value="<?php echo $order_settings[2]['setting_value'] ?? '10'; ?>" 
                                                   required>
                                            <small class="text-muted">Alert when stock falls below this number</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Order Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Payment Settings Tab -->
                        <?php if ($active_tab == 'payment'): ?>
                        <div class="tab-pane active">
                            <form method="POST" action="<?php echo BASE_URL; ?>settings/updatePayment">
                                <div class="row">
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="commission_rate">
                                                <i class="fas fa-percent"></i> Default Commission Rate (%)
                                            </label>
                                            <input type="number" class="form-control" id="commission_rate" 
                                                   name="commission_rate" 
                                                   value="<?php echo $commission_settings[0]['setting_value'] ?? '15'; ?>" 
                                                   required step="0.01" min="0" max="100">
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="withdrawal_day">
                                                <i class="fas fa-calendar-alt"></i> Withdrawal Day
                                            </label>
                                            <select class="form-control" id="withdrawal_day" name="withdrawal_day" required>
                                                <?php $withdrawal_day = $payment_settings[1]['setting_value'] ?? 'Friday'; ?>
                                                <option value="Monday" <?php echo ($withdrawal_day == 'Monday') ? 'selected' : ''; ?>>Monday</option>
                                                <option value="Tuesday" <?php echo ($withdrawal_day == 'Tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                                                <option value="Wednesday" <?php echo ($withdrawal_day == 'Wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                                                <option value="Thursday" <?php echo ($withdrawal_day == 'Thursday') ? 'selected' : ''; ?>>Thursday</option>
                                                <option value="Friday" <?php echo ($withdrawal_day == 'Friday') ? 'selected' : ''; ?>>Friday</option>
                                                <option value="Saturday" <?php echo ($withdrawal_day == 'Saturday') ? 'selected' : ''; ?>>Saturday</option>
                                                <option value="Sunday" <?php echo ($withdrawal_day == 'Sunday') ? 'selected' : ''; ?>>Sunday</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label>
                                                <i class="fas fa-mobile-alt"></i> GCash Payment
                                            </label>
                                            <div style="padding-top: 10px;">
                                                <label class="switch">
                                                    <input type="checkbox" name="gcash_enabled" 
                                                           <?php echo (isset($payment_settings[0]['setting_value']) && ($payment_settings[0]['setting_value'] == '1' || $payment_settings[0]['setting_value'] == 'true')) ? 'checked' : ''; ?>>
                                                    <span class="slider"></span>
                                                </label>
                                                <span style="margin-left: 10px;">Enable GCash payments</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 30px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Payment Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Toggle Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--primary-pink);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Tab Styling */
.nav-tabs .nav-link {
    color: #666;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 12px 20px;
    font-weight: 500;
    transition: all 0.3s;
}

.nav-tabs .nav-link:hover {
    color: var(--primary-pink);
    border-bottom-color: var(--primary-pink);
}

.nav-tabs .nav-link.active {
    color: var(--primary-pink);
    border-bottom-color: var(--primary-pink);
    background: transparent;
}

.tab-content {
    padding: 20px 0;
}
</style>
