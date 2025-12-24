<?php
session_start();
if(!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h2>Admin Dashboard</h2>
<a href="products.php" class="btn btn-primary mb-2">Manage Products</a>
<a href="categories.php" class="btn btn-secondary mb-2">Manage Categories</a>
<a href="orders.php" class="btn btn-success mb-2">Manage Orders</a>
<a href="logout.php" class="btn btn-danger mb-2">Logout</a>
</div>
</body>
</html>
