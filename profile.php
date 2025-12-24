<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$uid = $_SESSION['user_id'];
$errors = [];
$success = "";

// Fetch user data
$user = $conn->query("SELECT name,email FROM users WHERE id=$uid")->fetch_assoc();

// Update profile
if(isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if(empty($name) || empty($email)) $errors[] = "All fields are required.";

    // Check if email already exists for other users
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? AND id<>?");
    $stmt->bind_param("si",$email,$uid);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0) $errors[] = "Email already in use.";
    $stmt->close();

    if(empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi",$name,$email,$uid);
        if($stmt->execute()) $success = "Profile updated successfully.";
        $stmt->close();
        $_SESSION['user_name'] = $name; // Update session
    }
}

// Fetch user orders
$orders_result = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");

include 'includes/header.php';
?>

<h2 class="mb-4">Welcome, <span class="text-dark fw-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>!</h2>

<div class="row">
    <!-- Profile Update Form -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-4">
            <h4 class="mb-3">Update Profile</h4>
            <?php if(!empty($errors)): ?>
            <div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div>
            <?php endif; ?>
            <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn btn-dark w-100">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Order History Table -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-4">
            <h4 class="mb-3">Order History</h4>
            <?php if($orders_result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td>$<?php echo number_format($order['total'],2); ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0">You have no orders yet. <a href="products.php">Shop now</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
