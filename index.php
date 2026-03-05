<?php
/**
 * Login Page
 * Admin authentication entry point
 */

// Include configuration
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Include utilities
require_once __DIR__ . '/app/utils/Security.php';
require_once __DIR__ . '/app/utils/Functions.php';

// Include models
require_once __DIR__ . '/app/models/Auth.php';

// Check if already logged in
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = postParam('email', '', FILTER_SANITIZE_EMAIL);
    $password = postParam('password', '', false);
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        $auth = new Auth($conn);
        $result = $auth->login($email, $password);
        
        if ($result['success']) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Admin Panel</title>
    <link rel="stylesheet" href="app/assets/css/main.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-dark) 0%, #1a2847 100%);
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: var(--spacing-lg);
        }
        
        .login-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
        }
        
        .login-header h1 {
            margin: 0 0 var(--spacing-sm) 0;
            font-size: 28px;
            color: var(--text-primary);
        }
        
        .login-header p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: var(--spacing-lg);
        }
        
        label {
            display: block;
            margin-bottom: var(--spacing-sm);
            color: var(--text-primary);
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--bg-dark);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .login-btn:hover {
            background: var(--primary-dark);
            box-shadow: var(--shadow-md);
        }
        
        .login-btn:active {
            transform: scale(0.98);
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #F87171;
            padding: var(--spacing-md);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            font-size: 14px;
        }
        
        .login-footer {
            text-align: center;
            margin-top: var(--spacing-lg);
            color: var(--text-muted);
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Admin Panel</h1>
                <p>Professional Admin Dashboard</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo Security::sanitize($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="admin@example.com"
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    >
                </div>
                
                <button type="submit" class="login-btn">Sign In</button>
            </form>
            
            <div class="login-footer">
                <p>© 2026 Admin Panel. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
