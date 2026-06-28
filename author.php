<?php 
session_start();

# If not author ID is set
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

# Get author ID from GET request
$id = $_GET['id'];

# Database Connection File
include "db_conn.php";

# Book helper function
include "php/func-book.php";
$books = get_books_by_author($conn, $id);

# author helper function
include "php/func-author.php";
$authors = get_all_author($conn);
$current_author = get_author($conn, $id);

# Category helper function
include "php/func-category.php";
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$current_author['name']?> - Online Book Store</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --secondary: #7c3aed;
            --accent: #10b981;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --gray: #94a3b8;
            --gray-light: #e2e8f0;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }
        
        /* Navbar - From Top Code */
        .navbar {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.2rem 2rem;
        }
        
        .navbar-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--darker);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand i {
            color: var(--primary);
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary);
        }
        
        .auth-btn {
            background-color: var(--primary);
            color: white;
            border-radius: 6px;
            padding: 0.6rem 1.5rem;
            transition: all 0.2s ease;
            font-weight: 600;
        }
        
        .auth-btn:hover {
            background-color: var(--primary-light);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }
        
        /* Author Header - From Bottom Code */
        .author-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .author-header h1 {
            font-weight: 700;
        }
        
        .back-arrow {
            transition: transform 0.2s;
        }
        
        .back-arrow:hover {
            transform: translateX(-5px);
        }
        
        /* Book Cards - From Top Code */
        .book-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            background-color: white;
            height: 100%;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .book-cover {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--darker);
        }
        
        .card-text {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .book-author {
            color: var(--primary);
            font-weight: 500;
        }
        
        .book-category {
            display: inline-block;
            background-color: var(--gray-light);
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }
        
        .btn-read {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-download {
            background-color: white;
            color: var(--primary);
            border: 1px solid var(--gray-light);
        }
        
        .btn-read:hover {
            background-color: var(--primary-light);
            color: white;
        }
        
        .btn-download:hover {
            background-color: var(--gray-light);
            color: var(--primary);
        }
        
        /* Sidebar - From Bottom Code */
        .sidebar {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--darker);
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary);
        }
        
        .sidebar-list {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-list a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-list a:hover,
        .sidebar-list a.active {
            background-color: var(--primary);
            color: white;
        }
        
        /* Empty State - From Bottom Code */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        
        .empty-state img {
            width: 150px;
            opacity: 0.7;
            margin-bottom: 1.5rem;
        }
        
        .empty-state p {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation - From Top Code -->
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book-open"></i> Nexus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="authors.php">Authors</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a class="nav-link auth-btn" href="admin.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        <?php } else { ?>
                            <a class="nav-link auth-btn" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content - From Bottom Code -->
    <div class="container my-5">
        <div class="author-header text-center">
            <a href="index.php" class="back-arrow d-inline-block mb-3">
                <i class="fas fa-arrow-left-circle text-white" style="font-size: 1.5rem;"></i>
            </a>
            <h1 class="display-4"><?=$current_author['name']?></h1>
            <p class="lead mb-0">Browse all books by this author</p>
        </div>
        
        <div class="row">
            <!-- Books Section -->
            <div class="col-lg-9">
                <?php if (empty($books)): ?>
                    <div class="empty-state">
                        <img src="img/empty.png" alt="Empty state">
                        <h4 class="mb-3">No Books Found</h4>
                        <p>There are currently no books available by this author.</p>
                        <a href="index.php" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Store
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach ($books as $book): ?>
                            <div class="col">
                                <div class="book-card">
                                    <img src="uploads/cover/<?=$book['cover']?>" class="book-cover" alt="<?=$book['title']?>">
                                    <div class="card-body">
                                        <span class="book-category">
                                            <?php foreach($categories as $category) { 
                                                if ($category['id'] == $book['category_id']) {
                                                    echo $category['name'];
                                                    break;
                                                }
                                            } ?>
                                        </span>
                                        <h5 class="card-title"><?=$book['title']?></h5>
                                        <p class="card-text">
                                            <span class="book-author">
                                                <i class="fas fa-user me-1"></i>
                                                <?php foreach($authors as $author) { 
                                                    if ($author['id'] == $book['author_id']) {
                                                        echo $author['name'];
                                                        break;
                                                    }
                                                } ?>
                                            </span>
                                            <br>
                                            <?=substr($book['description'], 0, 100)?>...
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="uploads/files/<?=$book['file']?>" class="action-btn btn-read">
                                                <i class="fas fa-book-open me-1"></i> Read
                                            </a>
                                            <a href="uploads/files/<?=$book['file']?>" class="action-btn btn-download" download="<?=$book['title']?>">
                                                <i class="fas fa-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar mb-4">
                    <h3 class="sidebar-title">Categories</h3>
                    <ul class="sidebar-list">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="category.php?id=<?=$category['id']?>">
                                        <i class="fas fa-bookmark me-2"></i><?=$category['name']?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No categories available</p>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="sidebar">
                    <h3 class="sidebar-title">Authors</h3>
                    <ul class="sidebar-list">
                        <?php if (!empty($authors)): ?>
                            <?php foreach ($authors as $author): ?>
                                <li>
                                    <a href="author.php?id=<?=$author['id']?>" <?=($author['id'] == $id) ? 'class="active"' : ''?>>
                                        <i class="fas fa-user me-2"></i><?=$author['name']?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No authors available</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer - From Bottom Code -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?=date('Y')?> Online Book Store. All rights reserved.</p>
        </div>
    </footer>

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>