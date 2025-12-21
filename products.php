
<?php
session_start();
include 'db.php'; // Database connection
include 'navbar.php'; // Navigation bar
?>

<?php $email = $_GET['email']; ?>

<h2>Products</h2>

<form action="order.php" method="POST">
  <input type="hidden" name="email" value="<?php echo $email; ?>">
  <input type="hidden" name="product" value="Product One">
  <input type="hidden" name="price" value="499">
  <button type="submit">Buy Product One (Rs.499)</button>
</form>
