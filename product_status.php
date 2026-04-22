<?php
require 'config.php';
requireVendor();
$vid = $_SESSION['vendor_id'];

$orders = $conn->query("
    SELECT DISTINCT o.id, o.name, o.email, o.address, o.order_status, o.created_at
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    WHERE oi.vendor_id = $vid
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Product Status</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="vendor_dashboard.php">Home</a>
    <a href="logout.php">Log Out</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">Product Status</div>
    <table class="data-table">
      <thead>
        <tr><th>Order #</th><th>Customer</th><th>Email</th><th>Address</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if ($orders->num_rows === 0): ?>
          <tr><td colspan="5" style="text-align:center;padding:20px;">No orders yet.</td></tr>
        <?php endif; ?>
        <?php while ($o = $orders->fetch_assoc()):
          $sc = ['Pending'=>'pending','Received'=>'received','Ready for Shipping'=>'shipping','Out For Delivery'=>'delivery','Delivered'=>'delivered'][$o['order_status']] ?? 'pending';
        ?>
        <tr>
          <td>#<?= $o['id'] ?></td>
          <td><?= htmlspecialchars($o['name']) ?></td>
          <td><?= htmlspecialchars($o['email']) ?></td>
          <td><?= htmlspecialchars($o['address']) ?></td>
          <td><span class="badge badge-<?= $sc ?>"><?= $o['order_status'] ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
