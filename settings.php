<?php
include 'includes/auth-check.php';
include 'includes/db.php';
include 'includes/navbar.php';

$msg = $err = "";

if(isset($_POST['change'])){
  $cur = $_POST['current'];
  $new = $_POST['new'];
  $conf = $_POST['confirm'];

  $res = $conn->query("SELECT password FROM users WHERE id=".$_SESSION['user_id']);
  $u = $res->fetch_assoc();

  if(!password_verify($cur,$u['password'])){
    $err = "Current password incorrect";
  } elseif($new !== $conf){
    $err = "Passwords do not match";
  } else {
    $hash = password_hash($new,PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$hash' WHERE id=".$_SESSION['user_id']);
    $msg = "Password changed successfully";
  }
}
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">Settings</h3>

  <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
  <?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>

  <div class="card p-4 profile-card">
    <form method="POST">
      <input class="form-control mb-3" type="password" name="current" placeholder="Current password" required>
      <input class="form-control mb-3" type="password" name="new" placeholder="New password" required>
      <input class="form-control mb-3" type="password" name="confirm" placeholder="Confirm password" required>
      <button class="btn btn-pink">Change Password</button>
    </form>

    <hr>
    <a href="logout.php" class="btn btn-outline-danger w-100">Logout</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
