<?php
require 'config.php';
requireUser();
$uid = $_SESSION['user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Order Status</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="user_portal.php">Home</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">User Order Status</div>
    <table class="data-table">
      <thead><tr><th>Name</th><th>E-mail</th><th>Address</th><th>Status</th></tr></thead>
      <tbody>
        <?php if ($orders->num_rows === 0): ?>
          <tr><td colspan="4" style="text-align:center;padding:20px;">No orders yet.</td></tr>
        <?php endif; ?>
        <?php while ($o = $orders->fetch_assoc()):
          $sc = ['Pending'=>'pending','Received'=>'received','Ready for Shipping'=>'shipping','Out For Delivery'=>'delivery','Delivered'=>'delivered'][$o['order_status']] ?? 'pending';
        ?>
        <tr>
          <td><?= htmlspecialchars($o['name']) ?></td>
          <td><?= htmlspecialchars($o['email']) ?></td>
          <td><?= htmlspecialchars($o['address'].', '.$o['city']) ?></td>
          <td><span class="badge badge-<?= $sc ?>"><?= $o['order_status'] ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
