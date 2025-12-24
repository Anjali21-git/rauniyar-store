<?php
include '../includes/db.php';

$error = "";

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $db_password);

    if($stmt->num_rows == 1) {
        $stmt->fetch();

        // SIMPLE password check (NO HASH for now)
        if($password === $db_password) {
            session_start();
            $_SESSION['admin_id'] = $id;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-4">

<h3 class="text-center mb-3">Admin Login</h3>

<?php if($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
<button name="login" class="btn btn-dark w-100">Login</button>
</form>

</div>
</div>
</div>

</body>
</html>
