<?php
include 'includes/auth-check.php';
include 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT name,email FROM users WHERE id=$user_id")->fetch_assoc();

$msg = "";
if(isset($_POST['update'])){
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$user_id");
  $_SESSION['user_name'] = $name;
  $msg = "Profile updated successfully";
}
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">My Profile</h3>

  <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

  <div class="card p-4 profile-card">
    <form method="POST">
      <label class="fw-bold">Name</label>
      <input class="form-control mb-3" name="name" value="<?= $user['name'] ?>" required>

      <label class="fw-bold">Email</label>
      <input class="form-control mb-3" name="email" type="email" value="<?= $user['email'] ?>" required>

      <button class="btn btn-pink">Update Profile</button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
