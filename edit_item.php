<?php
require 'config.php';
requireVendor();
$vid = $_SESSION['vendor_id'];
$pid = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND vendor_id=?");
$stmt->bind_param("ii", $pid, $vid);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) redirect('your_items.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = clean($conn, $_POST['product_name']);
    $price  = floatval($_POST['product_price']);
    $status = clean($conn, $_POST['status']);
    $img    = $product['product_image'];

    if (!empty($_FILES['product_image']['name'])) {
        $ext      = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
        $filename = uniqid('prod_') . '.' . $ext;
        $dest     = UPLOAD_DIR . $filename;
        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $dest)) {
            $img = $filename;
        }
    }
    $stmt = $conn->prepare("UPDATE products SET product_name=?,product_price=?,product_image=?,status=? WHERE id=? AND vendor_id=?");
    $stmt->bind_param("sdssii", $name, $price, $img, $status, $pid, $vid);
    $stmt->execute();
    setFlash('success', 'Product updated!');
    redirect('your_items.php');
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Edit Item</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="your_items.php">← Back</a>
    <a href="logout.php">Log Out</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card" style="max-width:520px;margin:0 auto;">
    <div class="page-title">Update Product</div>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Product Price</label>
        <input type="number" name="product_price" value="<?= $product['product_price'] ?>" step="0.01" required>
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="status">
          <option value="Active"   <?= $product['status']==='Active'  ?'selected':'' ?>>Active</option>
          <option value="Inactive" <?= $product['status']==='Inactive'?'selected':'' ?>>Inactive</option>
        </select>
      </div>
      <div class="form-group">
        <label>New Image</label>
        <input type="file" name="product_image" accept="image/*">
      </div>
      <?php if ($product['product_image']): ?>
      <div style="margin-bottom:12px;">Current image: <img src="<?= UPLOAD_DIR.$product['product_image'] ?>" style="height:60px;vertical-align:middle;border-radius:4px;margin-left:8px;"></div>
      <?php endif; ?>
      <div style="text-align:center;margin-top:16px;">
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="your_items.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
