<?php
session_start();
include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$book = get_book_by_id($conn, $id);

if(!$book) {
    header("Location: index.php?error=Book not found");
    exit();
}

$author = get_author($conn, $book['author_id']);
$category = get_category($conn, $book['category_id']);

if(!$author || !$category) {
    header("Location: index.php?error=Associated data not found");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=htmlspecialchars($book['title'])?> | BookVault</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        /* ---- your existing CSS unchanged ---- */
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3a0ca3;
            --secondary: #7209b7;
            --accent: #4cc9f0;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --dark-gray: #495057;
            --success: #38b000;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: var(--dark);
            line-height: 1.7;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark);
        }
        /* Navigation */
        .navbar { background-color: white; box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08); padding: 1rem 2rem; position: sticky; top: 0; z-index: 1000; }
        .navbar-brand { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.8rem; color: var(--primary-dark); display: flex; align-items: center; gap: 0.5rem; }
        .navbar-brand i { color: var(--primary); }
        .nav-link { font-weight: 500; color: var(--dark-gray); padding: 0.5rem 1rem; transition: all 0.3s ease; }
        .nav-link:hover, .nav-link.active { color: var(--primary); }

        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3a0ca3;
            --secondary: #7209b7;
            --accent: #4cc9f0;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --dark-gray: #495057;
            --success: #38b000;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: var(--dark);
            line-height: 1.7;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark);
        }
        
        /* Navigation */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand i {
            color: var(--primary);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-gray);
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
        }
        
        /* Book Header */
        .book-hero {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 5rem 0 3rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .book-hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        
        .book-hero::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        
        .book-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }
        
        .book-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .book-author-link {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px dashed rgba(255,255,255,0.3);
        }
        
        .book-author-link:hover {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }
        
        /* Main Content */
        .book-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .book-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 3rem;
            transition: all 0.3s ease;
        }
        
        .book-cover-container {
            position: relative;
            overflow: hidden;
            height: 100%;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }
        
        .book-cover {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }
        
        .book-cover-container:hover .book-cover {
            transform: scale(1.05);
        }
        
        .book-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 2;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .badge-new {
            background-color: var(--secondary);
            color: white;
        }
        
        .badge-popular {
            background-color: var(--success);
            color: white;
        }
        
        .book-details {
            padding: 2.5rem;
        }
        
        .book-meta {
            margin-bottom: 2rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .meta-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .meta-label {
            font-weight: 500;
            color: var(--gray);
            margin-bottom: 0.2rem;
            font-size: 0.9rem;
        }
        
        .meta-value {
            font-weight: 600;
            color: var(--dark);
        }
        
        .book-description {
            margin-bottom: 2.5rem;
        }
        
        .description-title {
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .description-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-outline {
            background-color: white;
            color: var(--primary);
            border: 1px solid var(--light-gray);
        }
        
        .btn-outline:hover {
            background-color: var(--light-gray);
            color: var(--primary-dark);
            transform: translateY(-3px);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #5a189a;
            color: white;
            transform: translateY(-3px);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            margin-bottom: 2rem;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .back-link:hover {
            color: var(--primary-dark);
            transform: translateX(-5px);
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark), #16213e);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 5rem;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .social-links a {
            color: white;
            background-color: rgba(255,255,255,0.1);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .book-title {
                font-size: 2rem;
            }
            
            .book-cover-container {
                min-height: 350px;
            }
        }
        
        @media (max-width: 768px) {
            .book-hero {
                padding: 4rem 0 2.5rem;
            }
            
            .book-title {
                font-size: 1.8rem;
            }
            
            .book-subtitle {
                font-size: 1.1rem;
            }
            
            .book-details {
                padding: 1.5rem;
            }
            
            .action-btn {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .book-hero {
                padding: 3rem 0 2rem;
            }
            
            .book-title {
                font-size: 1.6rem;
            }
            
            .book-cover-container {
                min-height: 300px;
            }
            
            .action-btns {
                flex-direction: column;
                gap: 1rem;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book-open"></i> BookVault
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="authors.php">Authors</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Book Hero Section -->
    <section class="book-hero text-center animate__animated animate__fadeIn">
        <div class="container">
            <h1 class="book-title"><?=htmlspecialchars($book['title'])?></h1>
            <p class="book-subtitle">By <a href="author.php?id=<?=$author['id']?>" class="book-author-link"><?=htmlspecialchars($author['name'])?></a></p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="book-container">
        <a href="javascript:history.back()" class="back-link animate__animated animate__fadeIn">
            <i class="fas fa-arrow-left me-2"></i> Back to Previous Page
        </a>
        
        <div class="book-card animate__animated animate__fadeInUp">
            <div class="row g-0">
                <!-- Book Cover Column -->
                <div class="col-lg-5">
                    <div class="book-cover-container">
                        <?php if (!empty($book['cover'])): ?>
                            <img src="uploads/cover/<?=$book['cover']?>" class="book-cover" alt="<?=htmlspecialchars($book['title'])?>">
                        <?php else: ?>
                            <i class="fas fa-book-open fa-5x text-muted"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Book Details Column -->
                <div class="col-lg-7">
                    <div class="book-details">
                        <!-- Book Meta Information -->
                        <div class="book-meta">
                            <div class="meta-item">
                                <div class="meta-icon"><i class="fas fa-user-tie"></i></div>
                                <div>
                                    <div class="meta-label">Author</div>
                                    <div class="meta-value"><a href="author.php?id=<?=$author['id']?>" class="text-decoration-none"><?=htmlspecialchars($author['name'])?></a></div>
                                </div>
                            </div>
                            
                            <div class="meta-item">
                                <div class="meta-icon"><i class="fas fa-tag"></i></div>
                                <div>
                                    <div class="meta-label">Category</div>
                                    <div class="meta-value"><a href="category.php?id=<?=$category['id']?>" class="text-decoration-none"><?=htmlspecialchars($category['name'])?></a></div>
                                </div>
                            </div>
                            
                            <div class="meta-item">
                                <div class="meta-icon"><i class="fas fa-file-alt"></i></div>
                                <div>
                                    <div class="meta-label">Pages</div>
                                    <div class="meta-value"><?=htmlspecialchars($book['pages'] ?? 'N/A')?></div>
                                </div>
                            </div>
                            
                            <div class="meta-item">
                                <div class="meta-icon"><i class="fas fa-calendar-alt"></i></div>
                                <div>
                                    <div class="meta-label">Published</div>
                                    <div class="meta-value"><?=htmlspecialchars($book['published_year'] ?? 'N/A')?></div>
                                </div>
                            </div>

                            <!-- ✅ Publisher Section Added -->
                            <?php if (!empty($book['publisher'])): ?>
                            <div class="meta-item">
                                <div class="meta-icon"><i class="fas fa-building"></i></div>
                                <div>
                                    <div class="meta-label">Publisher</div>
                                    <div class="meta-value"><?=htmlspecialchars($book['publisher'])?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($book['isbn'])): ?>
                            <div class="meta-item">
                                <div class="meta-icon"><i class="fas fa-barcode"></i></div>
                                <div>
                                    <div class="meta-label">ISBN</div>
                                    <div class="meta-value"><?=htmlspecialchars($book['isbn'])?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Book Description -->
                        <div class="book-description">
                            <h4 class="description-title">About This Book</h4>
                            <p><?=nl2br(htmlspecialchars($book['description'] ?? 'No description available'))?></p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-3 action-btns">
                            <a href="uploads/files/<?=$book['file']?>" class="action-btn btn-primary" target="_blank">
                                <i class="fas fa-eye me-1"></i> Read Online
                            </a>
                            <a href="uploads/files/<?=$book['file']?>" class="action-btn btn-secondary" download="<?=htmlspecialchars($book['title'])?>">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                            <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="edit-book.php?id=<?=$book['id']?>" class="action-btn btn-outline">
                                <i class="fas fa-edit me-1"></i> Edit Book
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
