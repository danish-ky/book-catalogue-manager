<?php
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include "db_conn.php";

// Initialize variables
$error = '';
$success = '';
$admin = [];

// Get current admin data
try {
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['admin_id'], PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if (!$admin) {
        throw new Exception("Admin account not found");
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $full_name = htmlspecialchars(trim($_POST['full_name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    
    if (empty($full_name)) {
        $error = "Full name cannot be empty";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE admin SET full_name = :name, email = :email WHERE id = :id");
            $stmt->bindParam(':name', $full_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $_SESSION['admin_id'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $_SESSION['admin_name'] = $full_name;
                $admin['full_name'] = $full_name;
                $admin['email'] = $email;
                $success = "Profile updated successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookVault | Admin Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body class="dashboard-body">
  <div class="d-flex">
    <?php include "includes/sidebar.php"; ?>
    
    <div class="main-content" id="mainContent">
      <nav class="topbar mb-4">
        <button class="btn d-lg-none" id="sidebarToggle">
          <i class="fas fa-bars"></i>
        </button>
        
        <div class="ms-auto d-flex align-items-center">
          <?php if(isset($_SESSION['admin_id'])): ?>
          <div class="user-dropdown dropdown">
            <span class="d-none d-lg-inline me-2"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
            <div class="user-avatar dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
              <i class="fas fa-user-shield"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item active" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
          </div>
          <?php endif; ?>
        </div>
      </nav>
      
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card profile-card">
              <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-user-cog me-2"></i>Admin Profile</h3>
              </div>
              
              <div class="card-body">
                <?php if($error): ?>
                  <div class="alert alert-danger"><?= $error ?></div>
                <?php elseif($success): ?>
                  <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <form method="POST">
                  <div class="row mb-4">
                    <div class="col-md-4 text-center">
                      <div class="profile-avatar">
                        <i class="fas fa-user-shield fa-5x text-secondary"></i>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2">
                          Change Avatar
                        </button>
                      </div>
                    </div>
                    
                    <div class="col-md-8">
                      <div class="mb-3">
                        <label class="form-label">Admin ID</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($admin['id'] ?? '') ?>" readonly>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($admin['username'] ?? '') ?>" readonly>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" 
                               value="<?= htmlspecialchars($admin['full_name'] ?? '') ?>" required>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary px-4">
                      <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="change-password.php" class="btn btn-outline-secondary px-4">
                      <i class="fas fa-lock me-2"></i>Change Password
                    </a>
                  </div>
                </form>
                
                <hr class="my-4">
                
                <div class="profile-meta">
                  <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($admin['created_at'] ?? 'now')) ?></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Last Login:</strong> <?= date('F j, Y, g:i a', strtotime($admin['last_login'] ?? 'now')) ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>