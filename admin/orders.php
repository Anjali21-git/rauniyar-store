<?php
session_start();
if(!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';

// Update order status
if(isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si",$status,$order_id);
    $stmt->execute();
    $stmt->close();
    header("Location: orders.php");
    exit;
}

// Fetch all orders
$result = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Manage Orders</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Back Dashboard</a>
<table class="table table-bordered">
<thead>
<tr><th>ID</th><th>User</th><th>Total</th><th>Status</th><th>Order Date</th><th>Action</th></tr>
</thead>
<tbody>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['user_name']; ?></td>
<td>$<?php echo number_format($row['total'],2); ?></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo $row['created_at']; ?></td>
<td>
<form method="POST">
<input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
<select name="status" class="form-select mb-1">
<option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
<option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
<option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
</select>
<button type="submit" name="update_status" class="btn btn-sm btn-primary w-100">Update</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
