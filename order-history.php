<?php
// session + login check
include 'includes/auth-check.php';

// database + navbar
include 'includes/db.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT 
        o.id,
        o.quantity,
        o.price,
        o.payment_method,
        o.status,
        o.address,
        o.created_at,
        p.name
    FROM orders o
    INNER JOIN products p ON o.product_id = p.id
    WHERE o.user_id = '$user_id'
    ORDER BY o.id DESC
");

?>

<div class="container mt-5">
    <h2>My Orders</h2>

    <?php if($orders && $orders->num_rows > 0): ?>
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['payment_method'] ?></td>
                <td>
                    <?php if($row['status'] === 'Pending'): ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php else: ?>
                        <span class="badge bg-success">Confirmed</span>
                    <?php endif; ?>
                </td>
                <td><?= $row['address'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>
