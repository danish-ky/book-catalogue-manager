<?php  
session_start();

# If the admin is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Literary Haven Admin</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #7c3aed;
            --accent: #8b5cf6;
            --light: #f8fafc;
            --dark: #0f172a;
            --text: #334155;
            --text-light: #64748b;
            --border: #e2e8f0;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1590&q=80');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(248, 250, 252, 0.9);
        }
        
        .login-container {
            max-width: 440px;
            width: 100%;
            margin: 2rem auto;
        }
        
        .login-card {
            padding: 3rem;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
                        0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-logo {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .login-logo i {
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .login-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }
        
        .login-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .form-control {
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
            font-size: 0.95rem;
            height: auto;
        }
        
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            display: block;
        }
        
        .input-group-text {
            background-color: white;
            border: 1px solid var(--border);
            color: var(--text-light);
            padding: 0 1.25rem;
        }
        
        .password-toggle {
            cursor: pointer;
            background-color: white;
            border: 1px solid var(--border);
            color: var(--text-light);
            padding: 0 1.25rem;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .btn-login {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .login-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert-error {
            border-radius: 8px;
            padding: 0.875rem 1.25rem;
            font-size: 0.9rem;
            margin-bottom: 1.75rem;
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        
        .alert-error i {
            margin-right: 0.5rem;
        }
        
        @media (max-width: 576px) {
            .login-container {
                margin: 1rem;
            }
            
            .login-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="login-logo">
                                <i class="fas fa-book-open"></i>
                                <span>Literary Haven</span>
                            </div>
                            <h2 class="login-title">Admin Dashboard</h2>
                            <p class="login-subtitle">Sign in to manage your bookstore inventory and content</p>
                        </div>
                        
                        <?php if (isset($_GET['error'])) { ?>
                        <div class="alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?=htmlspecialchars($_GET['error']); ?>
                        </div>
                        <?php } ?>
                        
                        <form method="POST" action="php/auth.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control" 
                                           name="email" 
                                           id="email" 
                                           placeholder="admin@example.com"
                                           required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password" 
                                           id="password"
                                           placeholder="••••••••"
                                           required>
                                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign In
                            </button>
                            
                            <div class="login-footer">
                                <a href="index.php" class="login-link">
                                    <i class="fas fa-arrow-left me-1"></i> Back to store
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

<?php } else {
    header("Location: admin.php");
    exit;
} ?>