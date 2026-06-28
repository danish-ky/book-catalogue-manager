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
<title>BookVault Admin | Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
<link rel="icon" href="icon/Neymar.ico" type="image/x-icon">


</head>
<body class="dashboard-body">

<div class="d-flex w-100">
<?php include "includes/sidebar.php"; ?>

<div class="main-content w-100" id="mainContent">
  <nav class="topbar mb-4">
    <button class="btn d-lg-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="ms-auto d-flex align-items-center">
      <form action="search.php" method="GET" class="topbar-search position-relative me-3">
        <input type="text" name="key" class="form-control ps-3" placeholder="Search books..." required>
        <button type="submit" class="btn"><i class="fas fa-search"></i></button>
      </form>
      <?php if(isset($_SESSION['user_id'])): ?>
      <div class="user-dropdown dropdown">
        <span class="d-none d-lg-inline me-2"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
        <div class="user-avatar dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"><i class="fas fa-user-shield"></i></div>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </nav>

  <div class="container-fluid">

    <!-- Stats Section -->
    <div class="stats-container">
      <div class="stats-card">
        <div class="stat-title">TOTAL BOOKS</div>
        <div class="stat-value"><?= count($books) ?></div>
        <div class="stat-icon text-primary"><i class="fas fa-book"></i></div>
      </div>
      <div class="stats-card">
        <div class="stat-title">AUTHORS</div>
        <div class="stat-value"><?= count($authors) ?></div>
        <div class="stat-icon text-success"><i class="fas fa-user-tie"></i></div>
      </div>
      <div class="stats-card">
        <div class="stat-title">CATEGORIES</div>
        <div class="stat-value"><?= count($categories) ?></div>
        <div class="stat-icon text-warning"><i class="fas fa-tags"></i></div>
      </div>
    </div>

    <h3 class="mb-4">Book Collection</h3>
    <div class="row justify-content-start" id="bookResults">
      <?php foreach($books as $book):
        $authorName = '';
        foreach($authors as $author) { if($author['id']==$book['author_id']) $authorName=$author['name']; }
        $categoryName = $categories[array_search($book['category_id'], array_column($categories,'id'))]['name'] ?? 'N/A';
      ?>
      <div class="col-md-3 col-sm-6 text-center">
        <div class="flip-card" data-book='<?= json_encode([
          "id"=>$book['id'],
          "title"=>$book['title'],
          "cover"=>$book['cover'],
          "author"=>$authorName,
          "category"=>$categoryName,
          "publisher"=>$book['publisher'],
          "isbn"=>$book['isbn'],
          "pages"=>$book['pages'],
          "year"=>$book['published_year'],
          "description"=>$book['description']
        ]) ?>'>
          <div class="flip-card-inner">
            <div class="flip-card-front">
              <img src="uploads/cover/<?= $book['cover'] ?>" alt="<?= htmlspecialchars($book['title']) ?>">
            </div>
            <div class="flip-card-back">
              <h5><?= htmlspecialchars($book['title']) ?></h5>
              <div class="info-row"><i class="fas fa-user-tie"></i> <?= htmlspecialchars($authorName) ?></div>
              <div class="info-row"><i class="fas fa-tags"></i> <?= htmlspecialchars($categoryName) ?></div>
              <div class="info-row"><i class="fas fa-building"></i> <?= htmlspecialchars($book['publisher'] ?? 'N/A') ?></div>
              <div class="info-row"><i class="fas fa-barcode"></i> ISBN: <?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></div>
              <div class="info-row"><i class="fas fa-file-alt"></i> <?= htmlspecialchars($book['pages'] ?? 'N/A') ?> pages</div>
              <div class="info-row"><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($book['published_year'] ?? 'N/A') ?></div>
              <div class="book-actions">
                <a href="view-book.php?id=<?= $book['id'] ?>" class="btn-outline-primary">View</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                <a href="edit-book.php?id=<?= $book['id'] ?>" class="btn-primary">Edit</a>
                <a href="php/delete-book.php?id=<?= $book['id'] ?>" class="btn-outline-secondary" onclick="return confirm('Are you sure?')">Delete</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
