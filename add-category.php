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
    <title>Add Category | Admin Dashboard</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --secondary: #f9fafb;
            --accent: #10b981;
            --danger: #ef4444;
            --light: #ffffff;
            --dark: #111827;
            --text: #374151;
            --text-light: #6b7280;
            --border: #e5e7eb;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text);
            overflow-x: hidden;
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
            color: var(--text);
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
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            background: var(--light);
            margin-bottom: 2rem;
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
            color: var(--text);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--secondary);
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
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
                <span>Literary Admin</span>
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
                        <i class="fas fa-book-medical"></i>
                        <span>Add Book</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-author.php">
                        <i class="fas fa-user-edit"></i>
                        <span>Add Author</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="add-category.php">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Add New Category</h1>
            <button type="button" class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tags"></i>
                            Category Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="php/add-category.php" method="post">
                            <?php if (isset($_GET['error'])) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <?=htmlspecialchars($_GET['error']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?=htmlspecialchars($_GET['success']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            
                            <div class="mb-4">
                                <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="categoryName" name="category_name" required>
                                <small class="text-muted">Enter a descriptive name for the category (e.g. Fiction, Science, History)</small>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Add Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lightbulb"></i>
                            Quick Tips
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                    <div>
                                        <h6 class="mb-1">Keep names concise</h6>
                                        <p class="text-muted small mb-0">Use 1-3 words that clearly describe the category</p>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                    <div>
                                        <h6 class="mb-1">Avoid special characters</h6>
                                        <p class="text-muted small mb-0">Stick to letters and numbers for best compatibility</p>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                    <div>
                                        <h6 class="mb-1">Check for duplicates</h6>
                                        <p class="text-muted small mb-0">Ensure the category doesn't already exist</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                    <div>
                                        <h6 class="mb-1">Be specific</h6>
                                        <p class="text-muted small mb-0">"Historical Fiction" is better than just "Fiction"</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
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
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Set active nav link
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>

<?php } else {
    header("Location: login.php");
    exit;
} ?>