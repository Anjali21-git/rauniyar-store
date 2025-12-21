<?php
include 'db.php';
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "images/$image");
    $conn->query("INSERT INTO products (name, description, price, image) VALUES('$name','$desc','$price','$image')");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Panel</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" class="form-control mb-2" required>
        <textarea name="description" placeholder="Description" class="form-control mb-2" required></textarea>
        <input type="number" name="price" placeholder="Price" class="form-control mb-2" required>
        <input type="file" name="image" class="form-control mb-2" required>
        <button type="submit" name="add" class="btn btn-success">Add Product</button>
    </form>
</div>
</body>
</html>
