<style>
    .account-container {
        margin-top: 30px;
    }
    
    .account-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .account-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }
    
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="password"],
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .btn-update {
        width: 100%;
        padding: 15px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }
    
    .btn-update:hover {
        background: var(--primary-dark);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }
    
    .stat-card:nth-child(2) {
        background: linear-gradient(135deg, #ff9800, #f57c00);
    }
    
    .stat-card:nth-child(3) {
        background: linear-gradient(135deg, #4caf50, #388e3c);
    }
    
    .stat-value {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }
    
    .password-section {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .divider {
        height: 2px;
        background: var(--light-gray);
        margin: 30px 0;
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }
    
    @media (max-width: 968px) {
        .account-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<h1><i class="fas fa-user-circle"></i> My Account</h1>

<div class="account-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="account-grid">
        <!-- Profile Information -->
        <div class="account-card">
            <h2 class="card-title">
                <i class="fas fa-user-edit"></i> Profile Information
            </h2>
            
            <form method="POST" action="<?php echo BASE_URL; ?>account/update-profile">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                           placeholder="+63 XXX XXX XXXX">
                </div>
                
                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>
        
        <!-- Account Statistics -->
        <div>
            <div class="account-card">
                <h2 class="card-title">
                    <i class="fas fa-chart-bar"></i> Statistics
                </h2>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $stats['pending_orders'] ?? 0; ?></div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $stats['completed_orders'] ?? 0; ?></div>
                        <div class="stat-label">Completed Orders</div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="account-card" style="margin-top: 20px;">
                <h2 class="card-title">
                    <i class="fas fa-link"></i> Quick Links
                </h2>
                
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="<?php echo BASE_URL; ?>orders" class="btn btn-outline">
                        <i class="fas fa-receipt"></i> View Orders
                    </a>
                    <a href="<?php echo BASE_URL; ?>shop" class="btn btn-outline">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Change Password -->
    <div class="password-section">
        <h2 class="card-title">
            <i class="fas fa-lock"></i> Change Password
        </h2>
        
        <form method="POST" action="<?php echo BASE_URL; ?>account/change-password">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label for="current_password">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password *</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
            </div>
            
            <button type="submit" class="btn-update">
                <i class="fas fa-key"></i> Change Password
            </button>
        </form>
    </div>
</div>

<script>
    // Form validation for password change
    document.querySelector('form[action*="change-password"]').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New password and confirm password do not match!');
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long!');
            return false;
        }
    });
    
    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        e.target.value = value;
    });
</script>
