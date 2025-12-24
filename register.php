<?php
include 'includes/db.php';
session_start();
$errors = [];

if(isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if(empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    } elseif($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0) $errors[] = "Email already registered.";
    $stmt->close();

    if(empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss",$name,$email,$hash);
        $stmt->execute();
        $stmt->close();

        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $name;
        header("Location: index.php");
        exit;
    }
}

include 'includes/header.php';
?>
<h2 class="mb-3">Register</h2>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
    <?php foreach($errors as $error) echo "<div>$error</div>"; ?>
</div>
<?php endif; ?>

<form method="POST" class="w-50 mx-auto">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="confirm" class="form-control" required>
    </div>
    <button type="submit" name="register" class="btn btn-dark w-100">Register</button>
    <p class="mt-2">Already have an account? <a href="login.php">Login here</a></p>
</form>

<?php include 'includes/footer.php'; ?>
