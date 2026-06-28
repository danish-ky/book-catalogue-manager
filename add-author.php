<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author | LiteraryHub Admin</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --secondary: #f9fafb;
            --dark: #111827;
            --light: #ffffff;
            --gray: #6b7280;
            --light-gray: #f3f4f6;
            --border: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--secondary);
            color: var(--dark);
            line-height: 1.5;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--light);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .sidebar-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--gray);
            font-weight: 500;
            transition: all 0.2s ease;
            margin: 0.25rem 0;
        }
        
        .nav-link:hover, 
        .nav-link.active {
            color: var(--primary);
            background-color: rgba(79, 70, 229, 0.05);
            border-left: 4px solid var(--primary);
        }
        
        .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        /* Logout button style */
        .nav-link.logout {
            color: var(--danger);
        }
        
        .nav-link.logout:hover {
            color: #fff;
            background-color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
            padding: 2rem;
        }
        
        /* Top Header */
        .top-header {
            background: var(--light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .page-title {
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--dark);
            margin: 0;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            background: var(--light);
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
        }
        
        .card-title {
            font-weight: 600;
            margin-bottom: 0;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Form Styles */
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-size: 0.9375rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            font-size: 0.9375rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        textarea.form-control {
            min-height: 150px;
        }
        
        .form-text {
            font-size: 0.8125rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-secondary {
            border-color: var(--border);
            color: var(--gray);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--light-gray);
            border-color: var(--border);
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            border: 1px solid transparent;
        }
        
        .alert i {
            margin-right: 0.75rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 250px;
            }
            .main-content {
                margin-left: 250px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <i class="fas fa-book-open"></i>
                <span>LiteraryHub</span>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-book.php">
                        <i class="fas fa-book"></i>
                        <span>Add Book</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="add-author.php">
                        <i class="fas fa-user-edit"></i>
                        <span>Add Author</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-category.php">
                        <i class="fas fa-tags"></i>
                        <span>Add Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-store"></i>
                        <span>Store Front</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link logout" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <h1 class="page-title">Add New Author</h1>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-question-circle"></i>
                <span class="d-none d-md-inline"> Help</span>
            </button>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-user-edit"></i>
                            Author Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="php/add-author.php" method="post">
                            <?php if (isset($_GET['error'])) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <?=htmlspecialchars($_GET['error']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i>
                                    <?=htmlspecialchars($_GET['success']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            
                            <div class="mb-4">
                                <label for="authorName" class="form-label">Author Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="authorName" 
                                       name="author_name" required placeholder="e.g. J.K. Rowling">
                                <small class="form-text">Include the author's complete name as it should appear in publications</small>
                            </div>
                            
                            <div class="mb-4">
                                <label for="authorBio" class="form-label">Biography</label>
                                <textarea class="form-control" id="authorBio" name="author_bio" 
                                          rows="6" placeholder="Provide details about the author's background, notable works, and achievements"></textarea>
                                <small class="form-text">This will be displayed on author profile pages (supports basic HTML formatting)</small>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                    <span class="d-none d-md-inline"> Reset</span>
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i>
                                    <span class="d-none d-md-inline"> Add Author</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="pt-4 mt-4 text-muted border-top">
            &copy; <?php echo date("Y"); ?> LiteraryHub Admin Dashboard
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set active nav link
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
            
            // Toggle sidebar on mobile (would need a button in header)
            // document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            //     document.querySelector('.sidebar').classList.toggle('active');
            // });
        });
    </script>
</body>
</html>

<?php } else {
    header("Location: login.php");
    exit;
} ?>