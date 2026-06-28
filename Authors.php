<?php
session_start();
include "db_conn.php";
include "php/func-author.php";

$authors = get_all_author($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Authors | Literary Haven</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #2b6777;
            --secondary: #c8d8e4;
            --accent: #52ab98;
            --light: #f8f9fa;
            --dark: #212529;
            --text: #333333;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--primary);
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--primary);
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            color: var(--text);
        }
        
        .nav-link.active {
            color: var(--accent);
        }
        
        .nav-link:hover {
            color: var(--accent);
        }
        
        .author-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background-color: white;
            height: 100%;
        }
        
        .author-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .author-img {
            height: 200px;
            object-fit: cover;
            background-color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 4rem;
        }
        
        .author-name {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .view-btn {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .view-btn:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 0;
        }
        
        .empty-icon {
            font-size: 5rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }
        
        .page-header {
            position: relative;
            padding: 4rem 0;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, rgba(43,103,119,0.1), rgba(82,171,152,0.1));
        }
        
        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--accent);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Literary Haven</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="authors.php">Authors</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a class="nav-link" href="admin.php">Dashboard</a>
                        <?php } else { ?>
                            <a class="nav-link" href="login.php">Login</a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header text-center">
        <div class="container">
            <h1 class="display-4">Our Authors</h1>
            <p class="lead">Discover the brilliant minds behind your favorite books</p>
        </div>
    </section>

    <!-- Authors Section -->
    <section class="authors-section py-5">
        <div class="container">
            <div class="row g-4">
                <?php if (empty($authors)) { ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <h3>No Authors Found</h3>
                            <p class="text-muted">We currently don't have any authors in our collection.</p>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php foreach ($authors as $author) { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="author-card">
                                <div class="author-img">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="card-body text-center p-4">
                                    <h3 class="author-name"><?=htmlspecialchars($author['name'])?></h3>
                                    <a href="author.php?id=<?=$author['id']?>" class="btn view-btn">
                                        View Works <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; <?=date('Y')?> Literary Haven. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>