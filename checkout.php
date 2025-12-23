<?php

include 'includes/db.php';
include 'includes/auth-check.php';
include 'includes/navbar.php';

if(empty($_SESSION['cart'])){
  header("Location: cart.php");
  exit;
}
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">Checkout</h3>

  <form method="POST" action="order-confirm.php">
    <div class="row">
      <div class="col-md-6">
        <label class="fw-bold mb-2">Delivery Address</label>
        <textarea name="address" class="form-control mb-4" rows="4" required></textarea>

        <label class="fw-bold mb-2">Payment Method</label>
        <select name="payment" class="form-control" required>
          <option value="Cash">Cash on Delivery</option>
          <option value="Online">Online Payment</option>
        </select>
      </div>

      <div class="col-md-6">
        <h5 class="fw-bold mb-3">Order Summary</h5>

<?php
$grand = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
while($p = $res->fetch_assoc()):
  $qty = $_SESSION['cart'][$p['id']];
  $total = $qty * $p['price'];
  $grand += $total;
?>
  <div class="d-flex justify-content-between mb-2">
    <span><?= $p['name'] ?> × <?= $qty ?></span>
    <span>Rs <?= $total ?></span>
  </div>
<?php endwhile; ?>

        <hr>
        <h5>Total: <span class="text-pink">Rs <?= $grand ?></span></h5>
        <button class="btn btn-pink w-100 mt-3">Confirm Order</button>
      </div>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
