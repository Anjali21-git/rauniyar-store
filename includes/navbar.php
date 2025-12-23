<?php
// Include session
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!-- Bootstrap 5 Navbar -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">RauniyarStore</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Search Bar -->
      <form class="d-flex ms-auto me-3" method="GET" action="index.php">
        <input class="form-control me-2" type="search" placeholder="Search products" name="search" required>
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>

      <!-- Profile & Cart -->
      <ul class="navbar-nav">
        <!-- Cart Icon -->
        <li class="nav-item me-3">
          <a class="nav-link position-relative" href="cart.php">
            🛒 Cart
            <?php
            $cart_count = 0;
            if(isset($_SESSION['cart'])){
                $cart_count = count($_SESSION['cart']);
            }
            if($cart_count > 0){
                echo "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger'>$cart_count</span>";
            }
            ?>
          </a>
        </li>

        <!-- Profile Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            👤 <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Profile"; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Settings</a></li>
            <li><a class="dropdown-item" href="order-history.php">Order History</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
