<?php
require 'config.php';
requireVendor();
echo getFlash();
$vid = $_SESSION['vendor_id'];

if (isset($_GET['delete'])) {
    $pid  = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id=? AND vendor_id=?");
    $stmt->bind_param("ii", $pid, $vid);
    $stmt->execute();
    redirect('your_items.php');
}

$products = $conn->query("SELECT * FROM products WHERE vendor_id=$vid ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Your Items</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <span style="color:#fff;margin-right:16px;">Welcome <?= htmlspecialchars($_SESSION['vendor_name']) ?></span>
    <a href="product_status.php">Product Status</a>
    <a href="transaction.php">Request Item</a>
    <a href="add_item.php">Add Item</a>
    <a href="logout.php">Log Out</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">Your Products</div>
    <a href="add_item.php" class="btn btn-primary btn-sm" style="margin-bottom:14px;display:inline-block;">+ Add New Item</a>
    <table class="data-table">
      <thead>
        <tr><th>Image</th><th>Product Name</th><th>Product Price</th><th>Status</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php if ($products->num_rows === 0): ?>
          <tr><td colspan="5" style="text-align:center;padding:20px;">No products yet. <a href="add_item.php">Add one!</a></td></tr>
        <?php endif; ?>
        <?php while ($p = $products->fetch_assoc()): ?>
        <tr>
          <td>
            <?php if ($p['product_image']): ?>
              <img src="<?= UPLOAD_DIR . htmlspecialchars($p['product_image']) ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
            <?php else: ?>
              <div style="width:50px;height:50px;background:#e0e0e0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No img</div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['product_name']) ?></td>
          <td>Rs/- <?= number_format($p['product_price'], 2) ?></td>
          <td><span class="badge badge-<?= strtolower($p['status']) ?>"><?= $p['status'] ?></span></td>
          <td>
            <a href="edit_item.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Update</a>
            <a href="your_items.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Delete this product?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
