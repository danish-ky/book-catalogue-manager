<?php
session_start();

// Only allow admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

// DB & helpers
include_once "db_conn.php";
include_once "php/func-book.php";
include_once "php/func-category.php";
include_once "php/func-author.php";

// Validate book_id
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$book_id = $_GET['id'];
$book = get_book_by_id($conn, $book_id);
if (!$book) {
    header("Location: admin.php");
    exit;
}

$categories = get_all_categories($conn);
$authors    = get_all_author($conn);

// Messages from backend
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Book | Admin Panel</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
:root{
    --primary:#4f46e5;
    --primary-dark:#4338ca;
    --accent:#10b981;
    --text:#333;
    --border:#e5e7eb;
}

body {
    font-family: 'Montserrat', sans-serif;
    background:#f5f6fa;
    color:var(--text);
}

.navbar-brand { color: var(--primary); font-weight:700; }
.navbar-nav .nav-link { color:#555; font-weight:500; }
.navbar-nav .nav-link:hover { color:var(--primary-dark); }

.container { padding: 3rem 1rem; }
.card { border-radius: 12px; padding: 2rem; max-width: 960px; margin:auto; box-shadow: 0 6px 20px rgba(0,0,0,.05); }
.card h1 { font-family: 'Playfair Display', serif; color:var(--primary); margin-bottom: 1rem; }
.badge-book-id { font-weight:700; font-size:.95rem; background:rgba(79,70,229,.08); color:var(--primary); border:1px solid rgba(79,70,229,.25); padding:.5rem 1rem; border-radius:8px; }
.form-label { font-weight:600; color:#1f2937; }
.form-control, .form-select { border-radius:8px; border:1px solid var(--border); padding:.65rem 1rem; }
.form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(79,70,229,.15); }
.section-title { font-weight:700; color:#111827; margin-top:1.5rem; margin-bottom:.75rem; border-bottom:1px solid #e5e7eb; padding-bottom:.3rem; display:inline-block; }
.thumb { width: 100px; height:140px; object-fit:cover; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.1); }
.link-small { text-decoration:none; color:var(--primary); }
.link-small:hover { text-decoration:underline; color:var(--primary-dark); }
.btn-primary { background:var(--primary); border:none; border-radius:10px; padding:.75rem 1.2rem; font-weight:700; }
.btn-primary:hover { background:var(--primary-dark); transform:translateY(-1px); }
.alert { border-radius:10px; }
</style>
</head>
<body>

<div class="container">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 rounded-3 px-3">
        <a class="navbar-brand" href="admin.php"><i class="fas fa-book-open me-2"></i>Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-store me-1"></i> Store</a></li>
                <li class="nav-item"><a class="nav-link" href="add-book.php"><i class="fas fa-book-medical me-1"></i> Add Book</a></li>
                <li class="nav-item"><a class="nav-link" href="add-category.php"><i class="fas fa-tags me-1"></i> Add Category</a></li>
                <li class="nav-item"><a class="nav-link" href="add-author.php"><i class="fas fa-user-edit me-1"></i> Add Author</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Card -->
    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><i class="fas fa-book-open me-2"></i>Edit Book</h1>
            <span class="badge-book-id"><i class="fas fa-hashtag me-1"></i><?= htmlspecialchars($book['book_id']) ?></span>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="php/edit-book.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">
            <input type="hidden" name="current_cover" value="<?= htmlspecialchars($book['cover']) ?>">
            <input type="hidden" name="current_file" value="<?= htmlspecialchars($book['file']) ?>">

            <!-- Book Details -->
            <div class="mb-3">
                <label class="form-label">Book Title *</label>
                <input type="text" name="book_title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea name="book_description" rows="4" class="form-control" required><?= htmlspecialchars($book['description']) ?></textarea>
            </div>

            <h6 class="section-title">Details</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pages</label>
                    <input type="number" name="book_pages" class="form-control" value="<?= htmlspecialchars($book['pages'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Published Year</label>
                    <input type="number" name="published_year" class="form-control" value="<?= htmlspecialchars($book['published_year'] ?? '') ?>" min="1800" max="<?= date('Y') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Publisher</label>
                    <input type="text" name="book_publisher" class="form-control" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Author *</label>
                    <select name="book_author" class="form-select" required>
                        <option value="0">Select author</option>
                        <?php foreach($authors as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $book['author_id'] == $a['id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select name="book_category" class="form-select" required>
                        <option value="0">Select category</option>
                        <?php foreach($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $book['category_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h6 class="section-title">Identifiers & Files</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="book_isbn" class="form-control" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Book Cover</label>
                    <input type="file" name="book_cover" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
                    <?php if(!empty($book['cover'])): ?>
                        <div class="mt-2 d-flex align-items-center gap-3">
                            <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" class="thumb" alt="Cover">
                            <a href="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" target="_blank" class="link-small"><i class="fas fa-image me-1"></i>View</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Book File</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.epub,.mobi">
                    <?php if(!empty($book['file'])): ?>
                        <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" target="_blank" class="link-small mt-1 d-block"><i class="fas fa-file me-1"></i>View</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Update Book</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
