<?php
require 'config.php';
requireAdmin();

$orders = $conn->query("
    SELECT o.*, u.name AS user_name
    FROM orders o JOIN users u ON u.id=o.user_id
    ORDER BY o.created_at DESC
");
$total   = $conn->query("SELECT SUM(grand_total) AS t FROM orders")->fetch_assoc()['t'] ?? 0;
$pending = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE order_status='Pending'")->fetch_assoc()['c'];
$done    = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE order_status='Delivered'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Reports</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="admin_dashboard.php">Home</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">

  <!-- Summary row -->
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div style="background:#3b5ea6;color:#fff;padding:16px;border-radius:8px;text-align:center;">
      <div style="font-size:24px;font-weight:700;">Rs/- <?= number_format($total,2) ?></div>
      <div style="font-size:13px;margin-top:4px;">Total Revenue</div>
    </div>
    <div style="background:#e67e22;color:#fff;padding:16px;border-radius:8px;text-align:center;">
      <div style="font-size:24px;font-weight:700;"><?= $pending ?></div>
      <div style="font-size:13px;margin-top:4px;">Pending Orders</div>
    </div>
    <div style="background:#27ae60;color:#fff;padding:16px;border-radius:8px;text-align:center;">
      <div style="font-size:24px;font-weight:700;"><?= $done ?></div>
      <div style="font-size:13px;margin-top:4px;">Delivered Orders</div>
    </div>
  </div>

  <div class="card">
    <div class="page-title">Transaction Reports</div>
    <table class="data-table">
      <thead>
        <tr><th>Order #</th><th>Customer</th><th>Email</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr>
      </thead>
      <tbody>
        <?php if ($orders->num_rows === 0): ?>
          <tr><td colspan="7" style="text-align:center;padding:20px;">No transactions yet.</td></tr>
        <?php endif; ?>
        <?php while ($o = $orders->fetch_assoc()):
          $sc = [
            'Pending'          => 'pending',
            'Received'         => 'received',
            'Ready for Shipping'=> 'shipping',
            'Out For Delivery' => 'delivery',
            'Delivered'        => 'delivered'
          ][$o['order_status']] ?? 'pending';
        ?>
        <tr>
          <td>#<?= $o['id'] ?></td>
          <td><?= htmlspecialchars($o['user_name']) ?></td>
          <td><?= htmlspecialchars($o['email']) ?></td>
          <td>Rs/- <?= number_format($o['grand_total'],2) ?></td>
          <td><?= $o['payment_method'] ?></td>
          <td><span class="badge badge-<?= $sc ?>"><?= $o['order_status'] ?></span></td>
          <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
