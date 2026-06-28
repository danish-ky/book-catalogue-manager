<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <i class="fas fa-book-open"></i>
    <span>BookVault</span>
  </div>
  
  <div class="sidebar-nav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="index.php">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="books.php">
          <i class="fas fa-book"></i>
          <span>Books</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="authors.php">
          <i class="fas fa-user-tie"></i>
          <span>Authors</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="categories.php">
          <i class="fas fa-tags"></i>
          <span>Categories</span>
        </a>
      </li>
      
      <!-- New Browse Button -->
      <li class="nav-item">
        <a class="nav-link" href="browse.php">
          <i class="fas fa-search"></i>
          <span>Browse</span>
        </a>
      </li>
      
      <!-- Divider line -->
      <div class="sidebar-divider"></div>
      
      <?php if(isset($_SESSION['user_id'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </li>
      <?php else: ?>
      <li class="nav-item">
        <a class="nav-link" href="login.php">
          <i class="fas fa-sign-in-alt"></i>
          <span>Login</span>
        </a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</div>