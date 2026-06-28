<?php 
session_start();

// If search key is not set or empty
if (!isset($_GET['key']) || empty($_GET['key'])) {
    header("Location: index.php");
    exit;
}
$key = $_GET['key'];

// Database Connection
include "db_conn.php";

// Book helper function
include "php/func-book.php";
$books = search_books($conn, $key);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | BookVault</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --dark: #1a1a2e;
            --light-gray: #e9ecef;
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f5f7fa; color: var(--dark); }
        h1,h2,h3,h4,h5 { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .navbar { background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 1rem 0; }
        .navbar-brand { font-weight: 700; color: var(--primary); font-size: 1.8rem; display: flex; align-items: center; gap: 0.5rem; }
        .nav-link { font-weight: 500; color: var(--dark); padding: 0.5rem 1rem; }
        .nav-link.active, .nav-link:hover { color: var(--primary); }
        .auth-btn { background-color: var(--primary); color: white; border-radius: 8px; padding: 0.5rem 1.5rem; font-weight: 600; }
        .auth-btn:hover { background-color: var(--secondary); }
        .search-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 3rem 0; margin-bottom: 2rem; border-radius: 0 0 20px 20px; text-align: center; }
        .search-keyword { background-color: rgba(255,255,255,0.2); padding: 0.2rem 0.8rem; border-radius: 50px; font-weight: 600; }
        .book-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background-color: white; transition: 0.3s; margin-bottom: 1.5rem; height: 100%; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .book-cover { height: 250px; width: 100%; object-fit: cover; border-bottom: 1px solid var(--light-gray); }
        .card-body { padding: 1.5rem; display: flex; flex-direction: column; }
        .card-title { font-weight: 700; margin-bottom: 1rem; font-size: 1.2rem; }

        /* Book Meta Styling */
        .book-meta-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .book-meta {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 500;
        }
        .book-meta i { width: 18px; }

        .book-meta.author { color: #3b82f6; }     /* Blue */
        .book-meta.category { color: #6366f1; }   /* Indigo/Purple */
        .book-meta.pages { color: #22c55e; }      /* Green */
        .book-meta.year { color: #f97316; }       /* Orange */
        .book-meta.publisher { color: #06b6d4; }  /* Cyan */
        .book-meta.isbn { color: #6b7280; }       /* Gray */

        .action-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: 0.2s; font-size: 0.9rem; }
        .btn-view { background-color: var(--primary); color: white; }
        .btn-view:hover { background-color: var(--secondary); }
        .btn-download { background-color: white; color: var(--primary); border: 1px solid var(--light-gray); }
        .btn-download:hover { background-color: var(--light-gray); }
        .empty-state { text-align: center; padding: 4rem 2rem; background-color: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin: 2rem auto; max-width: 600px; }
        .empty-icon { font-size: 5rem; color: var(--light-gray); margin-bottom: 1.5rem; }
        .footer { background-color: var(--dark); color: white; padding: 2rem 0; margin-top: 4rem; text-align: center; }
        @media (max-width: 768px) { .book-cover { height: 200px; } }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-book"></i> BookVault</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="books.php">Books</a></li>
                <li class="nav-item"><a class="nav-link" href="authors.php">Authors</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                <li class="nav-item ms-lg-2">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="nav-link auth-btn" href="admin.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                    <?php else: ?>
                        <a class="nav-link auth-btn" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Header -->
<header class="search-header">
    <div class="container">
        <h1 class="display-5 mb-3">Search Results</h1>
        <p class="lead mb-0">You searched for: <span class="search-keyword">"<?=htmlspecialchars($key)?>"</span></p>
    </div>
</header>

<!-- Main Content -->
<main class="container mb-5">
    <div class="row">
        <div class="col-12">
            <?php if (empty($books)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-search"></i></div>
                    <h4>No Books Found</h4>
                    <p>We couldn't find any books matching your search.</p>
                    <a href="books.php" class="btn btn-primary px-4"><i class="fas fa-book-open me-2"></i> Browse All Books</a>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($books as $book): ?>
                        <div class="col">
                            <div class="book-card h-100">
                                <?php if (!empty($book['cover'])): ?>
                                    <img src="uploads/cover/<?=$book['cover']?>" class="book-cover" alt="<?=htmlspecialchars($book['title'])?>">
                                <?php else: ?>
                                    <div class="book-cover d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-book-open fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body">
                                    <h5 class="card-title"><?=htmlspecialchars($book['title'])?></h5>

                                    <!-- Vertical Metadata with Colors -->
                                    <div class="book-meta-group">
                                        <div class="book-meta author"><i class="fas fa-user-pen"></i> <?=htmlspecialchars($book['author_name'] ?? 'Unknown')?></div>
                                        <div class="book-meta category"><i class="fas fa-tag"></i> <?=htmlspecialchars($book['category_name'] ?? 'Uncategorized')?></div>
                                        <div class="book-meta pages"><i class="fas fa-file-alt"></i> <?=htmlspecialchars($book['pages'] ?? 'N/A')?> pages</div>
                                        <div class="book-meta year"><i class="fas fa-calendar-alt"></i> <?=htmlspecialchars($book['published_year'] ?? 'N/A')?></div>
                                        <div class="book-meta publisher"><i class="fas fa-building"></i> <?=htmlspecialchars($book['publisher'] ?? 'Unknown')?></div>
                                        <div class="book-meta isbn"><i class="fas fa-barcode"></i> <?=htmlspecialchars($book['isbn'] ?? 'N/A')?></div>
                                    </div>

                                    <p class="card-text flex-grow-1"><?=htmlspecialchars(substr($book['description'], 0, 120))?>...</p>
                                    <div class="d-flex mt-3">
                                        <a href="uploads/files/<?=$book['file']?>" class="action-btn btn-view me-2"><i class="fas fa-eye me-1"></i> View</a>
                                        <a href="uploads/files/<?=$book['file']?>" class="action-btn btn-download" download="<?=htmlspecialchars($book['title'])?>"><i class="fas fa-download me-1"></i> Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">&copy; <?=date('Y')?> BookVault. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
