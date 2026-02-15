<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fce4ec 0%, #f3e5f5 50%, #e1bee7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(255, 105, 180, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .register-left {
            width: 300px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #ff69b4 0%, #ee82ee 50%, #9370db 100%);
            padding: 60px 30px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .register-logo {
            font-size: 70px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .register-left h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .register-left > p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        
        .register-features {
            text-align: left;
            width: 100%;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .feature-item p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }
        
        .register-right {
            flex: 1;
            padding: 50px 40px;
            overflow-y: auto;
            max-height: 90vh;
        }
        
        .register-right h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .register-right > p {
            color: #666;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff69b4;
            font-size: 16px;
        }
        
        input {
            width: 100%;
            padding: 14px 14px 14px 42px;
            border: 2px solid #e6e6fa;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }
        
        input:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff69b4 0%, #ee82ee 50%, #9370db 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.4);
        }
        
        .register-btn:active {
            transform: translateY(0);
        }
        
        .register-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            color: #adb5bd;
            font-size: 14px;
            position: relative;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e6e6fa;
        }
        
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        
        .login-link {
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #ff69b4;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .back-home {
            text-align: center;
            margin-top: 15px;
        }
        
        .back-home a {
            color: #9370db;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-home a:hover {
            color: #ff69b4;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
            color: #dc3545;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
            color: #28a745;
        }
        
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }
            
            .register-left {
                width: 100%;
                padding: 40px 30px;
            }
            
            .register-right {
                padding: 30px 25px;
                max-height: none;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="register-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1><?php echo SITE_NAME; ?></h1>
            <p>Create your account today</p>
            
            <div class="register-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <strong>Easy Shopping</strong>
                        <p>Browse & buy products</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <strong>Order Tracking</strong>
                        <p>Track your deliveries</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <strong>Exclusive Deals</strong>
                        <p>Members-only discounts</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="register-right">
            <h2>Create Account ✨</h2>
            <p>Register to start shopping with us</p>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo BASE_URL; ?>auth/register" id="registerForm">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="text" id="phone" name="phone" placeholder="09XXXXXXXXX">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="register-btn" id="registerBtn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            
            <div class="divider">or</div>
            
            <div class="login-link">
                Already have an account? <a href="<?php echo BASE_URL; ?>auth/login">Sign in here</a>
            </div>
            
            <div class="back-home">
                <a href="<?php echo BASE_URL; ?>shop">
                    <i class="fas fa-arrow-left"></i> Back to Shop
                </a>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('registerBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
        });
    </script>
</body>
</html>
