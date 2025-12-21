<?php
session_start();
include 'db.php';

// Add to cart
if(isset($_GET['add'])){
    $id = $_GET['add'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit;
}

// Update quantity
if(isset($_POST['update'])){
    foreach($_POST['qty'] as $id => $qty){
        if($qty <= 0){
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
}

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updateSubtotal(id){
            var qty = document.getElementById('qty_'+id).value;
            var price = parseFloat(document.getElementById('price_'+id).innerText);
            document.getElementById('subtotal_'+id).innerText = (qty*price).toFixed(2);
            
            // Update total
            var total = 0;
            document.querySelectorAll('.subtotal').forEach(function(e){
                total += parseFloat(e.innerText);
            });
            document.getElementById('total').innerText = total.toFixed(2);
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Your Cart</h2>
    <form method="post">
    <table class="table">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        <?php
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $id => $qty){
                $result = $conn->query("SELECT * FROM products WHERE id='$id'");
                $product = $result->fetch_assoc();
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
        ?>
        <tr>
            <td><?php echo $product['name']; ?></td>
            <td>
                <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $qty; ?>" min="0" id="qty_<?php echo $id; ?>" class="form-control" onchange="updateSubtotal(<?php echo $id; ?>)">
            </td>
            <td>$<span id="price_<?php echo $id; ?>"><?php echo $product['price']; ?></span></td>
            <td>$<span class="subtotal" id="subtotal_<?php echo $id; ?>"><?php echo number_format($subtotal,2); ?></span></td>
        </tr>
        <?php } } ?>
        <tr>
            <td colspan="3" class="text-end fw-bold">Total:</td>
            <td>$<span id="total"><?php echo number_format($total,2); ?></span></td>
        </tr>
    </table>
    <button type="submit" name="update" class="btn btn-warning">Update Cart</button>
    <a href="order-confirm.php" class="btn btn-success">Confirm Order</a>
    </form>
</div>
</body>
</html>
