<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];

// Fetch orders
$sql = "SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC";
$result = $conn->query($sql);

include 'includes/header.php';
?>

<h2 class="mb-3">My Orders</h2>

<?php if(isset($_SESSION['order_success'])): ?>
<div class="alert alert-success"><?php echo $_SESSION['order_success']; unset($_SESSION['order_success']); ?></div>
<?php endif; ?>

<?php if($result->num_rows > 0): ?>
<table class="table table-bordered text-center">
<thead>
<tr><th>Order ID</th><th>Total</th><th>Status</th><th>Order Date</th></tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td>Rs:<?php echo number_format($row['total'],2); ?></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo $row['created_at']; ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-muted">You have no orders yet. <a href="products.php">Shop now</a></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
