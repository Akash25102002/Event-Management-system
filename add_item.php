<?php
require 'config.php';
requireVendor();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = clean($conn, $_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $vid   = $_SESSION['vendor_id'];
    $img   = '';

    if (empty($name) || $price <= 0) {
        $error = 'Product name and a valid price are required.';
    } else {
        if (!empty($_FILES['product_image']['name'])) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext     = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Only jpg/png/gif/webp images allowed.';
            } else {
                $filename = uniqid('prod_') . '.' . $ext;
                $dest     = UPLOAD_DIR . $filename;
                if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $dest)) {
                    $img = $filename;
                }
            }
        }
        if (!$error) {
            $stmt = $conn->prepare("INSERT INTO products (vendor_id, product_name, product_price, product_image) VALUES (?,?,?,?)");
            $stmt->bind_param("isds", $vid, $name, $price, $img);
            if ($stmt->execute()) {
                setFlash('success', 'Product added!');
                redirect('your_items.php');
            } else {
                $error = 'Failed to add product.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Add Item</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <span style="color:#fff;margin-right:16px;">Welcome <?= htmlspecialchars($_SESSION['vendor_name']) ?></span>
    <a href="product_status.php">Product Status</a>
    <a href="transaction.php">Request Item</a>
    <a href="your_items.php">View Product</a>
    <a href="logout.php">Log Out</a>
  </div>
</nav>
<div class="page-wrapper">
  <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
  <div class="card">
    <div class="page-title">Add New Item</div>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="product_name" placeholder="Enter product name" required>
      </div>
      <div class="form-group">
        <label>Product Price</label>
        <input type="number" name="product_price" placeholder="Enter price (Rs)" step="0.01" min="0" required>
      </div>
      <div class="form-group">
        <label>Product Image</label>
        <input type="file" name="product_image" accept="image/*">
      </div>
      <div style="text-align:center;margin-top:20px;">
        <button type="submit" class="btn btn-primary">Add The Product</button>
        <a href="vendor_dashboard.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
