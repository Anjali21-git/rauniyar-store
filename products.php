<?php
include 'includes/db.php';
include 'includes/header.php';

// Fetch categories
$cat_result = $conn->query("SELECT * FROM categories");

$where = "";
if(isset($_GET['category']) && $_GET['category'] != "") {
    $cat_id = intval($_GET['category']);
    $where = "WHERE p.category_id=$cat_id";
}
if(isset($_GET['search']) && $_GET['search'] != "") {
    $search = $conn->real_escape_string($_GET['search']);
    $where = "WHERE p.name LIKE '%$search%'";
}

// Fetch products
$sql = "SELECT p.id, p.name, p.price, p.image, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id
        $where";
$result = $conn->query($sql);
?>

<h2 class="mb-4">Products</h2>

<form method="GET" class="mb-3 d-flex flex-wrap gap-2">
    <select name="category" class="form-select w-auto">
        <option value="">All Categories</option>
        <?php while($cat = $cat_result->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if(isset($_GET['category']) && $_GET['category']==$cat['id']) echo 'selected'; ?>>
                <?php echo $cat['name']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <input type="text" name="search" class="form-control w-auto" placeholder="Search products..." value="<?php echo isset($_GET['search'])?$_GET['search']:''; ?>">
    <button class="btn btn-dark">Filter</button>
</form>

<div class="row">
<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                    <p class="card-text text-muted"><?php echo $row['category_name']; ?></p>
                    <p class="card-text fw-bold">Rs:<?php echo number_format($row['price'],2); ?></p>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                        <button type="submit" name="add_to_cart" class="btn btn-dark w-100">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="text-muted">No products found.</p>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
