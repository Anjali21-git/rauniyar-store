<?php
session_start();
if(!isset($_SESSION['admin_id'])) header("Location: login.php");
include '../includes/db.php';

// Add category
if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s",$name);
    $stmt->execute();
    $stmt->close();
    header("Location: categories.php");
}

// Delete category
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id=$id");
    header("Location: categories.php");
}

// Fetch categories
$result = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Categories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Categories</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Back Dashboard</a>

<!-- Add Category -->
<form method="POST" class="mb-3">
<div class="input-group">
<input type="text" name="name" class="form-control" placeholder="Category Name" required>
<button type="submit" name="add" class="btn btn-primary">Add Category</button>
</div>
</form>

<!-- Category List -->
<table class="table table-bordered">
<thead>
<tr><th>ID</th><th>Name</th><th>Action</th></tr>
</thead>
<tbody>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td>
<a href="categories.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
