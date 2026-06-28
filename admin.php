<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    # Database Connection File
    include "db_conn.php";

    # Book helper function
    include "php/func-book.php";
    $books = get_all_books($conn);

    # Author helper function
    include "php/func-author.php";
    $authors = get_all_author($conn);

    # Category helper function
    include "php/func-category.php";
    $categories = get_all_categories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Literary</title>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
.sidebar { width: 280px; height: 100vh; position: fixed; left: 0; top: 0; background: var(--light); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transition: all 0.3s ease; z-index: 1000; }
.sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--border); }
.sidebar-title { font-weight: 700; font-size: 1.25rem; color: var(--primary); display: flex; align-items: center; gap: 0.75rem; }
.sidebar-menu { padding: 1rem 0; }
.nav-link { display: flex; align-items: center; padding: 0.75rem 1.5rem; color: var(--text); font-weight: 500; transition: all 0.2s ease; margin: 0.25rem 0; }
.nav-link:hover, .nav-link.active { color: var(--primary); background-color: rgba(79, 70, 229, 0.05); border-left: 4px solid var(--primary); }
.nav-link i { width: 24px; margin-right: 12px; font-size: 1.1rem; }
.nav-link.logout { color: var(--danger); }
.nav-link.logout:hover { color: #fff; background-color: var(--danger); border-left: 4px solid var(--danger); }

/* Main Content */
.main-content { margin-left: 280px; min-height: 100vh; transition: all 0.3s ease; }

/* Top Header */
.top-header { background: var(--light); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
.user-menu { display: flex; align-items: center; gap: 1rem; }
.user-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; text-transform: uppercase; }

/* Dashboard Cards */
.dashboard-container { padding: 2rem; }
.dashboard-card { background: var(--light); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem; }
.card-title { font-weight: 600; font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--dark); display: flex; align-items: center; gap: 0.75rem; }

/* Tables */
.data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.data-table thead th { background-color: var(--primary); color: white; padding: 1rem; text-align: left; font-weight: 500; position: sticky; top: 0; }
.data-table tbody tr { background-color: var(--light); transition: all 0.2s ease; }
.data-table tbody tr:nth-child(even) { background-color: var(--secondary); }
.data-table tbody tr:hover { background-color: rgba(79, 70, 229, 0.05); }
.data-table td { padding: 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
.book-cover { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

/* Buttons */
.btn { padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-sm { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
.btn-primary { background-color: var(--primary); border-color: var(--primary); }
.btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); }
.btn-edit { background-color: var(--accent); color: white; border: none; }
.btn-edit:hover { background-color: #0d9e6e; color: white; }
.btn-delete { background-color: var(--danger); color: white; border: none; }
.btn-delete:hover { background-color: #dc2626; color: white; }

/* Search */
.search-container { margin-bottom: 1.5rem; }
.search-input { padding: 0.75rem 1.25rem; border-radius: 6px; border: 1px solid var(--border); width: 100%; max-width: 400px; transition: all 0.2s ease; }
.search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

/* Empty State */
.empty-state { text-align: center; padding: 3rem; background-color: var(--light); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.empty-icon { font-size: 3rem; color: var(--text-light); margin-bottom: 1.5rem; opacity: 0.5; }

/* Alerts */
.alert { border-radius: 8px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; }

/* Badges */
.badge { padding: 0.35em 0.65em; font-weight: 500; border-radius: 6px; }

/* Responsive */
@media (max-width: 992px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; } .menu-toggle { display: block !important; } }
@media (max-width: 768px) { .data-table { display: block; overflow-x: auto; } }
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
            <li class="nav-item"><a class="nav-link active" href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="add-book.php"><i class="fas fa-book-medical"></i> Add Book</a></li>
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
        <button class="btn btn-outline-primary d-lg-none menu-toggle"><i class="fas fa-bars"></i></button>
        <div class="user-menu">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['user_email'], 0, 1)) ?></div>
            <span><?= $_SESSION['user_email'] ?></span>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Search Form -->
        <div class="search-container">
            <form action="search.php" method="get" class="d-flex gap-2">
                <input type="text" class="form-control search-input" name="key" placeholder="Search books..." aria-label="Search books">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <!-- Alerts -->
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_GET['error']) ?></div>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success" role="alert"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_GET['success']) ?></div>
        <?php } ?>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="dashboard-card"><h5 class="card-title"><i class="fas fa-book text-primary"></i> Total Books</h5><h3><?= is_array($books) ? count($books) : 0 ?></h3></div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card"><h5 class="card-title"><i class="fas fa-user-edit text-primary"></i> Total Authors</h5><h3><?= is_array($authors) ? count($authors) : 0 ?></h3></div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card"><h5 class="card-title"><i class="fas fa-tags text-primary"></i> Total Categories</h5><h3><?= is_array($categories) ? count($categories) : 0 ?></h3></div>
            </div>
        </div>

        <!-- Books Section (Extended Columns) -->
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title"><i class="fas fa-book-open"></i> All Books</h3>
                <a href="add-book.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Book</a>
            </div>
            
            <?php if (!is_array($books) || count($books) == 0) { ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-book"></i></div>
                    <h4>No Books Found</h4>
                    <p class="text-muted mb-4">There are currently no books in the database.</p>
                    <a href="add-book.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Book</a>
                </div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book ID</th> 
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Publisher</th>
                                <th>ISBN</th>
                                <th>Pages</th>
                                <th>Published Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($books as $i => $book) { ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><span class="badge bg-primary"><?= htmlspecialchars($book['book_id']) ?></span></td> <!-- UPDATED -->
        <td>
            <?php 
            $coverPath = "uploads/cover/" . $book['cover'];
            if (!empty($book['cover']) && file_exists($coverPath)) { ?>
                <img src="<?= $coverPath ?>" class="book-cover" alt="<?= htmlspecialchars($book['title']) ?>">
            <?php } else { ?>
                <div class="book-cover" style="background-color: #eee; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-book text-muted"></i>
                </div>
            <?php } ?>
        </td>
        <td>
            <div class="fw-bold"><?= htmlspecialchars($book['title']) ?></div>
            <div class="text-muted small mt-1"><?= substr(htmlspecialchars($book['description']), 0, 50) ?>...</div>
        </td>
        <td>
            <?php 
            $authorName = "Undefined";
            foreach ($authors as $author) {
                if ($author['id'] == $book['author_id']) { $authorName = $author['name']; break; }
            }
            echo htmlspecialchars($authorName);
            ?>
        </td>
        <td>
            <?php 
            $categoryName = "Undefined";
            foreach ($categories as $category) {
                if ($category['id'] == $book['category_id']) { $categoryName = $category['name']; break; }
            }
            echo htmlspecialchars($categoryName);
            ?>
        </td>
        <td><?= htmlspecialchars($book['publisher'] ?? '-') ?></td>
        <td><?= htmlspecialchars($book['isbn'] ?? '-') ?></td>
        <td><?= htmlspecialchars($book['pages'] ?? '-') ?></td>
        <td><?= htmlspecialchars($book['published_year'] ?? '-') ?></td>
        <td>
            <div class="d-flex gap-2">
                <a href="edit-book.php?id=<?= $book['id'] ?>" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
                <a href="php/delete-book.php?id=<?= $book['id'] ?>" class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></a>
            </div>
        </td>
    </tr>
<?php } ?>
</tbody>

                    </table>
                </div>
            <?php } ?>
        </div>

        <!-- Categories Section -->
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title"><i class="fas fa-tags"></i> All Categories</h3>
                <a href="add-category.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Category</a>
            </div>
            
            <?php if (!is_array($categories) || count($categories) == 0) { ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-tag"></i></div>
                    <h4>No Categories Found</h4>
                    <p class="text-muted mb-4">There are currently no categories in the database.</p>
                    <a href="add-category.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Category</a>
                </div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr><th>#</th><th>Category Name</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categories as $i => $category) { ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><span class="badge bg-primary-light text-primary"><?= htmlspecialchars($category['name']) ?></span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="edit-category.php?id=<?= $category['id'] ?>" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="php/delete-category.php?id=<?= $category['id'] ?>" class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>

        <!-- Authors Section -->
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title"><i class="fas fa-user-edit"></i> All Authors</h3>
                <a href="add-author.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Author</a>
            </div>
            
            <?php if (!is_array($authors) || count($authors) == 0) { ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-user-edit"></i></div>
                    <h4>No Authors Found</h4>
                    <p class="text-muted mb-4">There are currently no authors in the database.</p>
                    <a href="add-author.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Author</a>
                </div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr><th>#</th><th>Author Name</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($authors as $i => $author) { ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($author['name']) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="edit-author.php?id=<?= $author['id'] ?>" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="php/delete-author.php?id=<?= $author['id'] ?>" class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelector('.menu-toggle').addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('active');
});
</script>

</body>
</html>

<?php  
} else {
    header("Location: login.php");
    exit;
}
?>
