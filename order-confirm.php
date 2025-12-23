<?php
session_start();
include 'includes/db.php';
include 'includes/auth-check.php';

$user_id = $_SESSION['user_id'];
$address = $_POST['address'];
$payment = $_POST['payment'];

foreach($_SESSION['cart'] as $pid => $qty){
  $p = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
  $price = $p['price'];

  $conn->query("INSERT INTO orders 
    (user_id, product_id, quantity, price, payment_method, address)
    VALUES('$user_id','$pid','$qty','$price','$payment','$address')");
}

unset($_SESSION['cart']);
header("Location: order-history.php");
exit;
