<?php
session_start();
include 'db.php';
include 'navbar.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Fetch current user
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

// Handle form submission
$message = "";
if(isset($_POST['update_profile'])){
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update name & email
    $conn->query("UPDATE users SET name='$name', email='$email' WHERE id='$user_id'");
    $_SESSION['user_name'] = $name; // update session name
    $message = "Profile updated successfully!";
}

// Handle password change
if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if(password_verify($current, $user['password'])){
        if($new === $confirm){
            $new_hash = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$new_hash' WHERE id='$user_id'");
            $message = "Password changed successfully!";
        } else {
            $message = "New passwords do not match!";
        }
    } else {
        $message = "Current password is incorrect!";
    }
}
?>

<div class="container mt-5">
    <h2>My Profile</h2>
    <?php if($message){ echo "<div class='alert alert-success'>$message</div>"; } ?>
    <div class="row">
        <!-- Profile Info -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h5>Edit Profile</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h5>Change Password</h5>
                <form method="POST">
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
                    <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
