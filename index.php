<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/navbar.php';

$products = $conn->query("SELECT * FROM products WHERE status='active'");
?>

<div class="container mt-4">
<div class="row">

<?php while($p = $products->fetch_assoc()): ?>
<div class="col-md-3 mb-4">
  <div class="card shadow-sm h-100">
    <img src="assets/images/<?= $p['image'] ?>" class="card-img-top">
    <div class="card-body">
      <h6><?= $p['name'] ?></h6>
      <p class="fw-bold">Rs <?= $p['price'] ?></p>
      <a href="cart.php?add=<?= $p['id'] ?>" class="btn btn-dark w-100">Add to Cart</a>
    </div>
  </div>
</div>
<?php endwhile; ?>

</div>
</div>
