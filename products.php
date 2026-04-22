<?php
require 'config.php';
requireUser();
$vid = intval($_GET['vid'] ?? 0);

$vendor = $conn->query("SELECT * FROM vendors WHERE id=$vid AND is_active=1")->fetch_assoc();
if (!$vendor) redirect('vendors.php');

if (isset($_POST['add_to_cart'])) {
    $pid = intval($_POST['product_id']);
    $uid = $_SESSION['user_id'];
    $exists = $conn->query("SELECT id FROM cart WHERE user_id=$uid AND product_id=$pid")->fetch_assoc();
    if ($exists) {
        $conn->query("UPDATE cart SET quantity=quantity+1 WHERE user_id=$uid AND product_id=$pid");
    } else {
        $conn->query("INSERT INTO cart (user_id,product_id,quantity) VALUES ($uid,$pid,1)");
    }
    setFlash('success', 'Added to cart!');
    redirect("products.php?vid=$vid");
}
echo getFlash();
$products = $conn->query("SELECT * FROM products WHERE vendor_id=$vid AND status='Active' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Products</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="user_portal.php">Home</a>
    <a href="vendors.php">← Vendors</a>
    <a href="cart.php">Cart</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="page-title">
    <?= htmlspecialchars($vendor['name']) ?> — Products
    <a href="request_item.php?vid=<?= $vid ?>" class="btn btn-sm btn-light" style="margin-left:16px;">Request Item</a>
  </div>
  <div class="product-grid">
    <?php if ($products->num_rows === 0): ?>
      <p>No products available from this vendor.</p>
    <?php endif; ?>
    <?php while ($p = $products->fetch_assoc()): ?>
    <div class="product-card">
      <?php if ($p['product_image']): ?>
        <img src="<?= UPLOAD_DIR . htmlspecialchars($p['product_image']) ?>" alt="<?= htmlspecialchars($p['product_name']) ?>">
      <?php endif; ?>
      <h3><?= htmlspecialchars($p['product_name']) ?></h3>
      <p>Rs/- <?= number_format($p['product_price'], 2) ?></p>
      <form method="POST">
        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
        <button type="submit" name="add_to_cart" class="btn" style="width:100%;background:#fff;color:#3b5ea6;font-weight:600;">Add to Cart</button>
      </form>
    </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
