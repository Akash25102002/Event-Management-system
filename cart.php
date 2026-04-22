<?php
require 'config.php';
requireUser();
$uid = $_SESSION['user_id'];

if (isset($_GET['remove'])) {
    $cid = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE id=$cid AND user_id=$uid");
    redirect('cart.php');
}
if (isset($_GET['delete_all'])) {
    $conn->query("DELETE FROM cart WHERE user_id=$uid");
    redirect('cart.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $cid => $qty) {
        $cid = intval($cid); $qty = max(1, intval($qty));
        $conn->query("UPDATE cart SET quantity=$qty WHERE id=$cid AND user_id=$uid");
    }
    redirect('cart.php');
}

$items = $conn->query("
    SELECT c.id, c.quantity, p.product_name, p.product_price, p.product_image
    FROM cart c JOIN products p ON p.id = c.product_id
    WHERE c.user_id = $uid
");
$grand_total = 0; $cart_rows = [];
while ($row = $items->fetch_assoc()) {
    $row['total_price'] = $row['product_price'] * $row['quantity'];
    $grand_total += $row['total_price'];
    $cart_rows[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Cart</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="user_portal.php">Home</a>
    <a href="vendors.php">View Products</a>
    <a href="request_item.php">Request Item</a>
    <a href="product_status.php">Product Status</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">Shopping Cart</div>
    <?php if (empty($cart_rows)): ?>
      <p style="text-align:center;padding:24px;">Your cart is empty. <a href="vendors.php">Browse vendors</a></p>
    <?php else: ?>
    <form method="POST">
    <table class="data-table">
      <thead>
        <tr><th>Image</th><th>Name</th><th>Price</th><th>Quantity</th><th>Total Price</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cart_rows as $row): ?>
        <tr>
          <td><?php if ($row['product_image']): ?><img src="<?= UPLOAD_DIR.$row['product_image'] ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px;"><?php endif; ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td>Rs/- <?= number_format($row['product_price'], 2) ?></td>
          <td>
            <input type="number" name="qty[<?= $row['id'] ?>]" value="<?= $row['quantity'] ?>"
                   min="1" style="width:60px;padding:4px;border:1px solid #ccc;border-radius:4px;">
          </td>
          <td>Rs/- <?= number_format($row['total_price'], 2) ?></td>
          <td><a href="cart.php?remove=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Remove</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div style="text-align:right;margin-top:8px;">
      <button type="submit" class="btn btn-light btn-sm">Update Quantities</button>
    </div>
    </form>
    <div class="cart-total-row">
      <span>Grand Total: Rs/- <?= number_format($grand_total, 2) ?></span>
      <a href="cart.php?delete_all=1" class="btn btn-danger btn-sm"
         onclick="return confirm('Clear entire cart?')">Delete All</a>
    </div>
    <div style="text-align:center;margin-top:20px;">
      <a href="checkout.php" class="btn btn-success" style="padding:12px 36px;">Proceed to CheckOut</a>
    </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
