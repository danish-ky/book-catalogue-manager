<?php
session_start();

include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

$books = get_all_books($conn);
$authors = get_all_author($conn);
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookVault | Books</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
        
        .navbar-brand i { color: var(--primary); }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            transition: all 0.2s ease;
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active { color: var(--primary); }
        
        .auth-btn {
            background-color: var(--primary);
            color: white;
            border-radius: 6px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .auth-btn:hover {
            background-color: var(--primary-light);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .data-table-container {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .table { color: var(--dark); }
        .table thead th {
            border-bottom: 2px solid var(--gray-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--gray);
        }
        .table-hover tbody tr:hover { background-color: var(--gray-light); }

        .book-cover-thumb {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .book-cover-thumb:hover { transform: scale(1.05); }

        .table-action-btn {
            width: 36px; height: 36px;
            display: inline-flex;
            align-items: center; justify-content: center;
            border-radius: 8px;
            margin: 0 3px;
            transition: all 0.2s;
        }
        .table-action-btn:hover { transform: translateY(-2px); }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .empty-state img { width: 150px; opacity: 0.7; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-book-open"></i> BookVault</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="books.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="authors.php">Authors</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a class="nav-link auth-btn" href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <?php } else { ?>
                            <a class="nav-link auth-btn" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="page-header text-center">
            <h1 class="display-4">Book Collection</h1>
            <p class="lead mb-0">Browse our complete collection of books</p>
        </div>
        
        <?php if (empty($books)): ?>
            <div class="empty-state">
                <img src="img/empty.png" alt="No Books">
                <h4>No Books Found</h4>
                <p>There are currently no books available in the collection.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="add-book.php" class="btn btn-primary mt-3"><i class="fas fa-plus me-2"></i>Add New Book</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="data-table-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>All Books</h3>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="add-book.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add New Book</a>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Publisher</th>
                                <th>Published</th>
                                <th>ISBN</th>
                                <th>Description</th>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><img src="uploads/cover/<?=$book['cover']?>" class="book-cover-thumb" alt="<?=$book['title']?>"></td>
                                    <td class="fw-semibold"><?=htmlspecialchars($book['title'])?></td>
                                    <td>
                                        <?php 
                                        $author_name = '';
                                        foreach ($authors as $author) {
                                            if ($author['id'] == $book['author_id']) {
                                                $author_name = htmlspecialchars($author['name']);
                                                break;
                                            }
                                        }
                                        echo $author_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $category_name = '';
                                        foreach ($categories as $category) {
                                            if ($category['id'] == $book['category_id']) {
                                                $category_name = htmlspecialchars($category['name']);
                                                break;
                                            }
                                        }
                                        ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary"><?=$category_name?></span>
                                    </td>
                                    <td><?=htmlspecialchars($book['publisher'] ?? 'N/A')?></td>
                                    <td><?=htmlspecialchars($book['published_year'] ?? 'N/A')?></td>
                                    <td><?=htmlspecialchars($book['isbn'] ?? 'N/A')?></td>
                                    <td class="text-muted"><?=htmlspecialchars(substr($book['description'], 0, 100))?>...</td>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <td>
                                            <a href="edit-book.php?id=<?=$book['id']?>" class="table-action-btn btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="delete-book.php?id=<?=$book['id']?>" class="table-action-btn btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this book?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?=date('Y')?> BookVault. All rights reserved.</p>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                language: { search: "_INPUT_", searchPlaceholder: "Search books..." },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                }
            });
        });
    </script>
</body>
</html>
