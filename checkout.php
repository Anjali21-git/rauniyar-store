<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$errors = [];

// Fetch cart items
$cart_result = $conn->query("SELECT c.id as cart_id, p.id as product_id, p.name, p.price, c.quantity 
                             FROM cart c 
                             JOIN products p ON c.product_id = p.id 
                             WHERE c.user_id=$user_id");

if($cart_result->num_rows == 0){
    header("Location: cart.php");
    exit;
}

// Calculate total
$total_amount = 0;
while($row = $cart_result->fetch_assoc()){
    $total_amount += $row['price'] * $row['quantity'];
}

// Place order
if(isset($_POST['place_order'])){
    $payment_method = $_POST['payment_method'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if(empty($payment_method) || empty($name) || empty($email) || empty($phone) || empty($address)){
        $errors[] = "All fields are required.";
    }

    if(empty($errors)){
        // Prepare statement
        $stmt = $conn->prepare("INSERT INTO orders (user_id,total,status,customer_name,customer_email,customer_phone,customer_address,payment_method) VALUES (?,?,?,?,?,?,?,?)");
        if(!$stmt){
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $status = ($payment_method == 'Cash') ? 'Pending' : 'Pending Payment';

        // Bind parameters
        $stmt->bind_param("idssssss", $user_id, $total_amount, $status, $name, $email, $phone, $address, $payment_method);

        if($stmt->execute()){
            $order_id = $stmt->insert_id;
            // Clear cart
            $conn->query("DELETE FROM cart WHERE user_id=$user_id");

            $_SESSION['order_success'] = "Order placed successfully!";
            header("Location: orders.php");
            exit;
        } else {
            $errors[] = "Error placing order: " . $stmt->error;
        }

        $stmt->close();
    }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Checkout</h2>

    <?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach($errors as $error) echo "<div>$error</div>"; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row g-4">
            <!-- Shipping Details -->
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-3">Shipping Details</h4>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">-- Select Payment Method --</option>
                            <option value="Cash">Cash on Delivery</option>
                            <option value="Online">Online Payment</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-3">Order Summary</h4>
                    <ul class="list-group mb-4">
                        <?php 
                        $cart_result->data_seek(0);
                        while($row = $cart_result->fetch_assoc()):
                            $total = $row['price'] * $row['quantity'];
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $row['name']; ?> (x<?php echo $row['quantity']; ?>)
                            <span>$<?php echo number_format($total,2); ?></span>
                        </li>
                        <?php endwhile; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            Total
                            <span>$<?php echo number_format($total_amount,2); ?></span>
                        </li>
                    </ul>
                    <button type="submit" name="place_order" class="btn btn-dark w-100">Place Order</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
