<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$uid = $_SESSION['user_id'];
$errors = [];
$success = "";

if(isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if(empty($current) || empty($new) || empty($confirm)) $errors[] = "All fields are required.";
    if($new !== $confirm) $errors[] = "New password and confirm password do not match.";

    // Check current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $stmt->close();

    if(!password_verify($current, $hashed)) $errors[] = "Current password is incorrect.";

    if(empty($errors)) {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si",$new_hash,$uid);
        if($stmt->execute()) $success = "Password changed successfully.";
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<h2 class="mb-3">Change Password</h2>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST" class="w-50 mx-auto">
    <div class="mb-3">
        <label>Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" name="change_password" class="btn btn-dark w-100">Change Password</button>
</form>

<?php include 'includes/footer.php'; ?>
