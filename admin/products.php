<?php
session_start();
if(!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';

// Add product
if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../assets/images/$image");

    $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, description, image) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sidss",$name,$category,$price,$desc,$image);
    $stmt->execute();
    $stmt->close();
    header("Location: products.php");
}

// Delete product
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php");
}

// Fetch products
$result = $conn->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id");
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Products</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Back Dashboard</a>

<!-- Add Product Form -->
<form method="POST" enctype="multipart/form-data" class="mb-4">
<div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Product Name" required></div>
<div class="mb-2">
<select name="category" class="form-select" required>
<option value="">Select Category</option>
<?php while($cat = $categories->fetch_assoc()) echo "<option value='{$cat['id']}'>{$cat['name']}</option>"; ?>
</select>
</div>
<div class="mb-2"><input type="number" name="price" class="form-control" placeholder="Price" required step="0.01"></div>
<div class="mb-2"><input type="file" name="image" class="form-control" required></div>
<div class="mb-2"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
<button type="submit" name="add" class="btn btn-primary">Add Product</button>
</form>

<!-- Product List -->
<table class="table table-bordered">
<thead>
<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Action</th></tr>
</thead>
<tbody>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['category_name']; ?></td>
<td>$<?php echo number_format($row['price'],2); ?></td>
<td><img src="../assets/images/<?php echo $row['image']; ?>" width="50"></td>
<td>
<a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
<a href="products.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
