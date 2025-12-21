<?php
include 'db.php';
if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name,email,password) VALUES('$name','$email','$password')");
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Signup</h2>
    <form method="post" class="shadow p-4 rounded bg-white">
        <input type="text" name="name" placeholder="Name" class="form-control mb-3" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
        <button type="submit" name="signup" class="btn btn-success w-100">Signup</button>
        <p class="mt-2 text-center">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>
</body>
</html>
