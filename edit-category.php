<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    # If category ID is not set
    if (!isset($_GET['id'])) {
        #Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];

    # Database Connection File
    include "db_conn.php";

    # Category helper function
    include "php/func-category.php";
    $category = get_category($conn, $id);
    
    # If the ID is invalid
    if ($category == 0) {
        #Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category | Literary</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary: #2b6777;
            --secondary: #c8d8e4;
            --accent: #52ab98;
            --light: #f2f2f2;
            --dark: #1a1a1a;
            --text: #333333;
            --text-light: #6c757d;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: var(--text);
        }
        
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-top: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .form-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 700;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(43, 103, 119, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 6px;
            padding: 1rem;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--primary);
        }
        
        .nav-link {
            color: var(--text);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary);
        }
        
        .container {
            padding-top: 2rem;
            padding-bottom: 4rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin.php">Literary Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="fas fa-store me-1"></i> Store
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-book.php">
                                <i class="fas fa-book-medical me-1"></i> Add Book
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-category.php">
                                <i class="fas fa-tags me-1"></i> Add Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-author.php">
                                <i class="fas fa-user-edit me-1"></i> Add Author
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="form-container shadow">
            <h1 class="form-title">
                <i class="fas fa-tag me-2"></i>Edit Category
            </h1>
            
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?=htmlspecialchars($_GET['error']); ?>
                </div>
            <?php } ?>
            
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?=htmlspecialchars($_GET['success']); ?>
                </div>
            <?php } ?>
            
            <form action="php/edit-category.php" method="post">
                <input type="text" hidden value="<?=$category['id']?>" name="category_id">
                
                <div class="mb-4">
                    <label class="form-label">Category Name</label>
                    <input type="text" 
                           class="form-control" 
                           value="<?=htmlspecialchars($category['name'])?>" 
                           name="category_name"
                           required>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php } else {
    header("Location: login.php");
    exit;
} ?>