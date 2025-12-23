<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/navbar.php';

if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];

if(isset($_GET['add'])){
  $id=$_GET['add'];
  $_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+1;
  header("Location: cart.php");
}
?>

<div class="container mt-4">
<h4>My Cart</h4>

<?php if(empty($_SESSION['cart'])): ?>
<p>Cart empty</p>
<?php endif; ?>

<?php foreach($_SESSION['cart'] as $id=>$q):
$p=$conn->query("SELECT name FROM products WHERE id=$id")->fetch_assoc();
?>
<p><?= $p['name'] ?> × <?= $q ?></p>
<?php endforeach; ?>
</div>
