<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Rauniyar Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Rauniyar Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">

        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>

        <!-- Cart Icon -->
        <li class="nav-item">
          <a class="nav-link position-relative" href="cart.php">
            <i class="bi bi-cart-fill"></i> Cart
            <?php
            if(isset($_SESSION['user_id'])) {
                $uid = $_SESSION['user_id'];
                $cart_count = $conn->query("SELECT SUM(quantity) as qty FROM cart WHERE user_id=$uid")->fetch_assoc()['qty'];
                if($cart_count > 0) echo "<span class='badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle'>$cart_count</span>";
            }
            ?>
          </a>
        </li>

        <?php if(isset($_SESSION['user_id'])): ?>
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_name']; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="change-password.php">Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div class="container">
