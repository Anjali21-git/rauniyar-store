<?php
session_start();
include 'db.php';
include 'navbar.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Fetch products
$products = $conn->query("SELECT * FROM products");
if(!$products){
    die("Error fetching products: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rauniyar Shop Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar included from navbar.php -->

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard</h2>
        <div class="row">
            <?php while($row = $products->fetch_assoc()){ ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card shadow-sm h-100 p-2">
                    <img src="images/<?php echo $row['image']; ?>" class="card-img-top rounded" style="height:180px; object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $row['description']; ?></p>
                        <p class="card-text fw-bold">$<?php echo $row['price']; ?></p>
                        <a href="cart.php?add=<?php echo $row['id']; ?>" class="btn btn-primary mt-auto shadow-sm">Add to Cart</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Optional: Footer -->
    <footer class="mt-5">
        &copy; <?php echo date('Y'); ?> Rauniyar Shop. All Rights Reserved.
    </footer>

    <!-- Bootstrap JS (optional for navbar toggler) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
