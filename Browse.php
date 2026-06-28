<?php
session_start();
include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

// Check if browsing by author
if (isset($_GET['author_id']) && is_numeric($_GET['author_id'])) {
    $author_id = intval($_GET['author_id']);
    $books = get_books_by_author($conn, $author_id);
} else {
    $books = get_all_books($conn);
}

$authors = get_all_author($conn);
$categories = get_all_categories($conn);

// Helper function to get category name by ID
function get_category_name($categories, $category_id) {
    foreach ($categories as $category) {
        if ($category['id'] == $category_id) {
            return $category['name'];
        }
    }
    return 'Unknown';
}

// Helper function to get author name by ID
function get_author_name($authors, $author_id) {
    foreach ($authors as $author) {
        if ($author['id'] == $author_id) {
            return $author['name'];
        }
    }
    return 'Unknown';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books | Literary Haven</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
        .nav-link { font-weight: 500; padding: 0.5rem 1rem; color: var(--text); }
        .nav-link.active, .nav-link:hover { color: var(--accent); }

        /* Card Pop & Fade Effect */
        .book-card {
            position: relative;
            height: 420px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .book-card:hover {
            transform: scale(1.15); /* Pop out a little more */
            z-index: 10;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3); /* Stronger, more prominent shadow */
        }
        
        .book-card-front, .book-card-back {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: opacity 0.3s ease;
            border-radius: 12px;
        }
        .book-card-back {
            background-color: white;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            opacity: 0;
            pointer-events: none;
        }
        .book-card:hover .book-card-front {
            opacity: 0;
        }
        .book-card:hover .book-card-back {
            opacity: 1;
            pointer-events: all;
        }

        /* New Styling for the Back of the Card */
        .book-cover { 
            height: 100%; 
            object-fit: cover; 
            width: 100%; 
        }
        .book-category { 
            display: inline-block; 
            background-color: var(--accent); 
            color: white; 
            padding: 0.2rem 0.6rem; 
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: 600; 
            margin-bottom: 0.5rem; 
        }
        .book-title { 
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; 
            font-weight: 700; 
            color: var(--dark); 
            margin-bottom: 0.25rem; 
        }
        .book-author { 
            color: var(--primary); 
            font-size: 0.9rem; 
            font-style: italic;
            margin-bottom: 0.75rem; 
            display: block; 
        }
        .book-description { 
            color: var(--text); 
            font-size: 0.9rem; 
            line-height: 1.5; 
            margin-bottom: 1rem; 
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .book-meta { 
            font-size: 0.85rem; 
            margin-bottom: 0.25rem; 
            color: var(--dark); 
        }
        .book-meta strong { color: var(--primary); }

        .card-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: auto;
            align-items: center;
        }
        .btn-read {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            flex-grow: 1;
            text-align: center;
        }
        .btn-read:hover {
            background-color: var(--accent);
            color: white;
        }
        .btn-download {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            flex-grow: 1;
            text-align: center;
        }
        .btn-download:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .admin-actions { 
            position: absolute; 
            top: 15px; 
            right: 15px; 
            z-index: 10; 
        }
        .btn-delete { 
            background-color: #dc3545; 
            color: white; 
            border: none; 
            font-size: 0.9rem; 
            padding: 0.5rem 0.8rem;
            border-radius: 50%;
        }

        .empty-state { text-align: center; padding: 4rem 0; }
        .empty-icon { font-size: 5rem; color: var(--secondary); margin-bottom: 1.5rem; }
        .page-header { position: relative; padding: 4rem 0; margin-bottom: 3rem; background: linear-gradient(135deg, rgba(43,103,119,0.1), rgba(82,171,152,0.1)); }
        .page-header::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 100px; height: 3px; background-color: var(--accent); }
        .search-bar { max-width: 600px; margin: 0 auto 3rem; }
        .search-input { border-radius: 50px; padding: 1rem 1.5rem; border: 1px solid var(--secondary); }
        .search-btn { background-color: var(--accent); color: white; border: none; border-radius: 50px; padding: 0.75rem 1.5rem; }
        .search-btn:hover { background-color: var(--primary); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Literary Haven</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="browse.php">Browse</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="authors.php">Authors</a></li>
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

    <section class="page-header text-center">
        <div class="container">
            <h1 class="display-4">Browse Our Collection</h1>
            <p class="lead">Discover thousands of books across all genres</p>
        </div>
    </section>

    <div class="container search-bar">
        <form action="search.php" method="get" class="d-flex">
            <input type="text" name="key" class="form-control search-input me-2" placeholder="Search by title, author or category...">
            <button type="submit" class="btn search-btn">
                <i class="fas fa-search me-2"></i>Search
            </button>
        </form>
    </div>

    <section class="books-section pb-5">
        <div class="container">
            <div class="row g-4">
                <?php if (empty($books)) { ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-book-open"></i></div>
                            <h3>No Books Available</h3>
                            <p class="text-muted">There are currently no books in our collection.</p>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php foreach ($books as $book) { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="book-card">
                                <div class="book-card-front">
                                    <img src="uploads/cover/<?=htmlspecialchars($book['cover'])?>"
                                            class="book-cover"
                                            alt="<?=htmlspecialchars($book['title'])?>"
                                            onerror="this.onerror=null;this.src='https://via.placeholder.com/300x400?text=Cover+Not+Found'">
                                </div>
                                <div class="book-card-back">
                                    <?php if (isset($_SESSION['user_id'])) { ?>
                                        <div class="admin-actions">
                                            <form action="php/delete-book.php" method="post" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                <input type="hidden" name="id" value="<?=htmlspecialchars($book['id'])?>">
                                                <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                    <div>
                                        <span class="book-category"><?=htmlspecialchars(get_category_name($categories, $book['category_id']))?></span>
                                        <h3 class="book-title"><?=htmlspecialchars($book['title'])?></h3>
                                        <span class="book-author">By: <?=htmlspecialchars(get_author_name($authors, $book['author_id']))?></span>
                                        <p class="book-description"><?=htmlspecialchars($book['description'])?></p>
                                        
                                        <p class="book-meta"><strong>Publisher:</strong> <?=htmlspecialchars($book['publisher'])?></p>
                                        <p class="book-meta"><strong>ISBN:</strong> <?=htmlspecialchars($book['isbn'])?></p>
                                        <p class="book-meta"><strong>Pages:</strong> <?=htmlspecialchars($book['pages'])?></p>
                                        <p class="book-meta"><strong>Published Year:</strong> <?=htmlspecialchars($book['published_year'])?></p>
                                    </div>
                                    <div class="card-actions">
                                        <a href="uploads/files/<?=htmlspecialchars($book['file'])?>" class="action-btn btn-read" target="_blank">
                                            <i class="fas fa-book-open me-2"></i> Read
                                        </a>
                                        <a href="uploads/files/<?=htmlspecialchars($book['file'])?>" class="action-btn btn-download" download="<?=htmlspecialchars($book['title'])?>">
                                            <i class="fas fa-download me-2"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; <?=date('Y')?> Literary Haven. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>