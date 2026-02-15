<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Benta Reseller Portal</title>
    
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
        
        .login-container {
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
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #ff69b4 0%, #ee82ee 50%, #9370db 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .login-logo {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .login-left h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-left p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        
        .login-features {
            text-align: left;
            width: 100%;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .feature-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .login-right {
            flex: 1;
            padding: 60px 40px;
        }
        
        .login-right h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .login-right > p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
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
            font-size: 18px;
        }
        
        input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e6e6fa;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }
        
        input:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }
        
        .login-btn {
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
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .divider {
            text-align: center;
            margin: 25px 0;
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
        
        .register-link {
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        .register-link a {
            color: #ff69b4;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
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
        
        .alert-error, .alert-danger {
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
            .login-container {
                flex-direction: column;
            }
            
            .login-left {
                padding: 40px 30px;
            }
            
            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="login-logo">
                <i class="fas fa-store"></i>
            </div>
            <h1>E-Benta</h1>
            <p>Reseller Portal</p>
            
            <div class="login-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <strong>Sales Tracking</strong>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Monitor your earnings</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <strong>Inventory Management</strong>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Manage your products</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <strong>Commission & Wallet</strong>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Track your commissions</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="login-right">
            <h2>Welcome Back! 👋</h2>
            <p>Sign in to your reseller account</p>
            
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
            
            <form action="<?php echo BASE_URL; ?>auth/login" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required autocomplete="email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    </div>
                </div>
                
                <button type="submit" class="login-btn" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="divider">or</div>
            
            <div class="register-link">
                Don't have an account? <a href="<?php echo BASE_URL; ?>auth/register">Register here</a>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
        });
    </script>
</body>
</html>
