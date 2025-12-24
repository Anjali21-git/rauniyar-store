<?php
include 'includes/db.php';
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

// Add to cart
if(isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    // Check if product already in cart
    $stmt = $conn->prepare("SELECT id,quantity FROM cart WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii",$user_id,$product_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cart_id,$cart_qty);
    if($stmt->num_rows > 0) {
        $stmt->fetch();
        $new_qty = $cart_qty + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
        $update->bind_param("ii",$new_qty,$cart_id);
        $update->execute();
        $update->close();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id,product_id,quantity) VALUES (?,?,?)");
        $insert->bind_param("iii",$user_id,$product_id,$quantity);
        $insert->execute();
        $insert->close();
    }
    $stmt->close();
    header("Location: cart.php");
    exit;
}

// Remove from cart
if(isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE id=$cart_id");
    header("Location: cart.php");
    exit;
}

// Fetch cart items
$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id as cart_id, p.name, p.price, p.image, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id=$user_id";
$result = $conn->query($sql);

include 'includes/header.php';
?>

<h2 class="mb-3">Your Cart</h2>
<?php if($result->num_rows > 0): ?>
<table class="table table-bordered text-center">
<thead>
<tr><th>Image</th><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>
</thead>
<tbody>
<?php 
$total_amount = 0;
while($row = $result->fetch_assoc()):
$total = $row['price'] * $row['quantity'];
$total_amount += $total;
?>
<tr>
<td><img src="assets/images/<?php echo $row['image']; ?>" width="60"></td>
<td><?php echo $row['name']; ?></td>
<td>Rs:<?php echo number_format($row['price'],2); ?></td>
<td><?php echo $row['quantity']; ?></td>
<td>Rs:<?php echo number_format($total,2); ?></td>
<td><a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="btn btn-sm btn-danger">Remove</a></td>
</tr>
<?php endwhile; ?>
<tr>
<td colspan="4" class="text-end fw-bold">Total</td>
<td colspan="2" class="fw-bold">Rs:<?php echo number_format($total_amount,2); ?></td>
</tr>
</tbody>
</table>
<a href="checkout.php" class="btn btn-dark">Proceed to Checkout</a>
<?php else: ?>
<p class="text-muted">Your cart is empty. <a href="products.php">Shop now</a></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
