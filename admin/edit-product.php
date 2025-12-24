<?php
session_start();
if(!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

// Update product
if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    if(!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
        $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, description=?, image=? WHERE id=?");
        $stmt->bind_param("sidssi",$name,$category,$price,$desc,$image,$id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, description=? WHERE id=?");
        $stmt->bind_param("sidsi",$name,$category,$price,$desc,$id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: products.php");
    exit;
}

// Fetch categories
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Edit Product</h2>
<a href="products.php" class="btn btn-secondary mb-3">Back</a>

<form method="POST" enctype="multipart/form-data">
<div class="mb-2"><input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required></div>
<div class="mb-2">
<select name="category" class="form-select" required>
<?php while($cat=$categories->fetch_assoc()): ?>
<option value="<?php echo $cat['id']; ?>" <?php if($cat['id']==$product['category_id']) echo 'selected'; ?>><?php echo $cat['name']; ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="mb-2"><input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required step="0.01"></div>
<div class="mb-2"><textarea name="description" class="form-control"><?php echo $product['description']; ?></textarea></div>
<div class="mb-2"><input type="file" name="image" class="form-control"></div>
<button type="submit" name="update" class="btn btn-success">Update Product</button>
</form>
</body>
</html>
