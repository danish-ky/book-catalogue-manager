<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    include "db_conn.php";
    include "php/func-category.php";
    include "php/func-author.php";
    include "php/func-book.php";

    $categories = get_all_categories($conn);
    $authors = get_all_author($conn);

    # Form defaults from previous input (if redirected back)
    $title = $_GET['title'] ?? '';
    $desc = $_GET['desc'] ?? '';
    $category_id = $_GET['category_id'] ?? 0;
    $author_id = $_GET['author_id'] ?? 0;
    $pages = $_GET['pages'] ?? '';
    $published_year = $_GET['published_year'] ?? '';
    $publisher = $_GET['publisher'] ?? '';
    $isbn = $_GET['isbn'] ?? '';

    # Auto-generate Book ID for display
    $book_id = generateBookID($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Book | LiteraryHub Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* --- Root & body --- */
:root{--primary:#4f46e5;--primary-dark:#4338ca;--secondary:#f9fafb;--dark:#111827;--light:#fff;--gray:#6b7280;--border:#e5e7eb;--success:#10b981;--danger:#ef4444;}
body{font-family:'Inter',sans-serif;background-color:var(--secondary);color:var(--dark);line-height:1.5;}
.sidebar{width:280px;height:100vh;position:fixed;left:0;top:0;background:var(--light);box-shadow:0 4px 6px -1px rgba(0,0,0,.1);z-index:1000;}
.sidebar-header{padding:1.5rem;border-bottom:1px solid var(--border);}
.sidebar-title{font-weight:700;font-size:1.25rem;color:var(--primary);display:flex;align-items:center;gap:.75rem;}
.sidebar-menu{padding:1rem 0;}
.nav-link{display:flex;align-items:center;padding:.75rem 1.5rem;color:var(--gray);font-weight:500;transition:all .2s;margin:.25rem 0;}
.nav-link:hover,.nav-link.active{color:var(--primary);background-color:rgba(79,70,229,.05);border-left:4px solid var(--primary);}
.nav-link i{width:24px;margin-right:12px;font-size:1.1rem;}
.nav-link.logout{color:var(--danger)!important;}
.nav-link.logout:hover{color:#fff!important;background-color:rgba(220,53,69,.1)!important;border-left:4px solid var(--danger)!important;}
.main-content{margin-left:280px;min-height:100vh;padding:2rem;transition:all .3s;}
.top-header{background:var(--light);padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 3px rgba(0,0,0,.1);position:sticky;top:0;z-index:100;}
.page-title{font-weight:700;font-size:1.75rem;margin:0;}
.card{border:none;border-radius:.75rem;box-shadow:0 1px 3px rgba(0,0,0,.1);background:var(--light);margin-bottom:2rem;border:1px solid var(--border);}
.card-header{background:transparent;border-bottom:1px solid var(--border);padding:1.5rem;}
.card-title{font-weight:600;margin-bottom:0;font-size:1.25rem;display:flex;align-items:center;gap:.75rem;}
.card-body{padding:1.5rem;}
.form-label{font-weight:600;margin-bottom:.5rem;color:var(--dark);font-size:.9375rem;}
.form-control,.form-select{padding:.75rem 1rem;border-radius:.5rem;border:1px solid var(--border);font-size:.9375rem;transition:all .2s;}
.form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,70,229,.1);}
textarea.form-control{min-height:150px;}
.form-text{font-size:.8125rem;color:var(--gray);margin-top:.25rem;}
.btn{font-weight:500;padding:.75rem 1.5rem;border-radius:.5rem;transition:all .2s;display:inline-flex;align-items:center;gap:.5rem;}
.btn-primary{background-color:var(--primary);border-color:var(--primary);}
.btn-primary:hover{background-color:var(--primary-dark);border-color:var(--primary-dark);}
.btn-outline-secondary{border-color:var(--border);color:var(--gray);}
.btn-outline-secondary:hover{background-color:#f3f4f6;border-color:var(--border);}
.alert{border-radius:.5rem;padding:1rem 1.5rem;border:1px solid transparent;}
.alert i{margin-right:.75rem;}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
<div class="sidebar-header">
<div class="sidebar-title"><i class="fas fa-book-open"></i><span>LiteraryHub</span></div>
</div>
<div class="sidebar-menu">
<ul class="nav flex-column">
<li class="nav-item"><a class="nav-link" href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li class="nav-item"><a class="nav-link active" href="add-book.php"><i class="fas fa-book"></i> Add Book</a></li>
<li class="nav-item"><a class="nav-link" href="add-author.php"><i class="fas fa-user-edit"></i> Add Author</a></li>
<li class="nav-item"><a class="nav-link" href="add-category.php"><i class="fas fa-tags"></i> Add Category</a></li>
<li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-store"></i> Store Front</a></li>
<li class="nav-item"><a class="nav-link logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
</ul>
</div>
</div>

<!-- Main Content -->
<div class="main-content">
<div class="top-header">
<h1 class="page-title">Add New Book</h1>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
<div class="card-header">
<h5 class="card-title"><i class="fas fa-book"></i> Book Information</h5>
</div>
<div class="card-body">
<form action="php/add-book.php" method="post" enctype="multipart/form-data">
<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
<i class="fas fa-exclamation-circle"></i>
<?= htmlspecialchars($_GET['error']); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
<i class="fas fa-check-circle"></i>
<?= htmlspecialchars($_GET['success']); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Book ID -->
<div class="mb-4">
<label for="book_id" class="form-label">Book ID</label>
<input type="text" class="form-control" id="book_id" name="book_id" value="<?= htmlspecialchars($book_id) ?>" readonly>
<small class="form-text">Auto-generated unique book identifier</small>
</div>

<!-- Book Title -->
<div class="mb-4">
<label for="book_title" class="form-label">Book Title <span class="text-danger">*</span></label>
<input type="text" class="form-control" id="book_title" name="book_title" required value="<?= htmlspecialchars($title) ?>">
</div>

<!-- Publisher -->
<div class="mb-4">
<label for="book_publisher" class="form-label">Publisher</label>
<input type="text" class="form-control" id="book_publisher" name="book_publisher" value="<?= htmlspecialchars($publisher) ?>">
</div>

<!-- ISBN -->
<div class="mb-4">
<label for="book_isbn" class="form-label">ISBN Number</label>
<input type="text" class="form-control" id="book_isbn" name="book_isbn" value="<?= htmlspecialchars($isbn) ?>">
</div>

<!-- Description -->
<div class="mb-4">
<label for="book_description" class="form-label">Description</label>
<textarea class="form-control" id="book_description" name="book_description" rows="4"><?= htmlspecialchars($desc) ?></textarea>
</div>

<!-- Pages -->
<div class="mb-4">
<label for="book_pages" class="form-label">Number of Pages</label>
<input type="number" class="form-control" id="book_pages" name="book_pages" value="<?= htmlspecialchars($pages) ?>">
</div>

<!-- Published Year -->
<div class="mb-4">
<label for="published_year" class="form-label">Published Year</label>
<input type="number" class="form-control" id="published_year" name="published_year" min="1800" max="<?= date('Y') ?>" value="<?= htmlspecialchars($published_year) ?>">
</div>

<!-- Author -->
<div class="mb-4">
<label for="book_author" class="form-label">Author <span class="text-danger">*</span></label>
<select class="form-select" id="book_author" name="book_author" required>
<option value="">Select Author</option>
<?php if ($authors != 0): foreach ($authors as $author): ?>
<option value="<?= $author['id'] ?>" <?= $author_id == $author['id'] ? 'selected' : '' ?>><?= htmlspecialchars($author['name']) ?></option>
<?php endforeach; endif; ?>
</select>
</div>

<!-- Category -->
<div class="mb-4">
<label for="book_category" class="form-label">Category <span class="text-danger">*</span></label>
<select class="form-select" id="book_category" name="book_category" required>
<option value="">Select Category</option>
<?php if ($categories != 0): foreach ($categories as $category): ?>
<option value="<?= $category['id'] ?>" <?= $category_id == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
<?php endforeach; endif; ?>
</select>
</div>

<!-- Book Cover -->
<div class="mb-4">
<label class="form-label">Book Cover <span class="text-danger">*</span></label>
<input type="file" class="form-control" name="book_cover" accept="image/*" required>
</div>

<!-- Book File -->
<div class="mb-4">
<label class="form-label">Book File <span class="text-danger">*</span></label>
<input type="file" class="form-control" name="file" accept=".pdf" required>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
<button type="reset" class="btn btn-outline-secondary"><i class="fas fa-undo"></i> Reset</button>
<button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add Book</button>
</div>

</form>
</div>
</div>
</div>
</div>

<footer class="pt-4 mt-4 text-muted border-top">&copy; <?= date("Y"); ?> LiteraryHub Admin Dashboard</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } else {
header("Location: login.php");
exit;
} ?>
