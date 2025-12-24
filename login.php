<?php
include 'includes/db.php';
session_start();
$errors = [];

if(isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)) $errors[] = "Both fields are required.";

    if(empty($errors)) {
        $stmt = $conn->prepare("SELECT id,name,password FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id,$name,$hashed_password);
        if($stmt->num_rows > 0) {
            $stmt->fetch();
            if(password_verify($password,$hashed_password)) {
                $_SESSION['user_id']=$id;
                $_SESSION['user_name']=$name;
                header("Location: index.php");
                exit;
            } else $errors[]="Invalid email or password.";
        } else $errors[]="Invalid email or password.";
        $stmt->close();
    }
}

include 'includes/header.php';
?>
<h2 class="mb-3">Login</h2>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
    <?php foreach($errors as $error) echo "<div>$error</div>"; ?>
</div>
<?php endif; ?>

<form method="POST" class="w-50 mx-auto">
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
    <p class="mt-2">Don't have an account? <a href="register.php">Register here</a></p>
</form>

<?php include 'includes/footer.php'; ?>
