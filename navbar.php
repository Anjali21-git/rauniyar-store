<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
  <div class="container">
    <a class="navbar-brand" href="index.php">Rauniyar Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="order-history.php">Order History</a></li>
        <li class="nav-item"><a class="nav-link" href="order-confirm.php">Confirmation</a></li>

        <?php if(isset($_SESSION['user_name'])){ ?>
        <!-- Profile dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <img src="images/profile-icon.png" alt="Profile" width="30" height="30" class="rounded-circle me-2">
            <span><?php echo $_SESSION['user_name']; ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </li>
        <?php } else { ?>
        <li class="nav-item"><a class="nav-link btn btn-success text-white ms-2" href="login.php">Login</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
