<?php
session_start();
include 'db.php'; // Database connection
include 'navbar.php'; // Navigation bar
?>


<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $product_id => $qty){
        $user_id = $_SESSION['user_id'];
        $conn->query("INSERT INTO orders (user_id, product_id, quantity) VALUES('$user_id','$product_id','$qty')");
    }
    unset($_SESSION['cart']);
    $message = "Order confirmed! Thank you.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2><?php echo $message; ?></h2>
    <a href="index.php" class="btn btn-primary mt-3">Back to Shop</a>
</div>
</body>
</html>
