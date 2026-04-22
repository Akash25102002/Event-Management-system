<?php
require 'config.php';
requireAdmin();
echo getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $oid    = intval($_POST['order_id']);
    $status = clean($conn, $_POST['order_status']);
    $stmt   = $conn->prepare("UPDATE orders SET order_status=? WHERE id=?");
    $stmt->bind_param("si", $status, $oid);
    $stmt->execute();
    setFlash('success', 'Order status updated!');
    redirect('admin_orders.php');
}

$orders = $conn->query("
    SELECT o.*, u.name AS user_name
    FROM orders o JOIN users u ON u.id=o.user_id
    ORDER BY o.created_at DESC
");
$status_options = ['Pending','Received','Ready for Shipping','Out For Delivery','Delivered'];
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Manage Orders</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="admin_dashboard.php">Home</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">Order Management — Update Status</div>
    <table class="data-table">
      <thead>
        <tr><th>#</th><th>Customer</th><th>Total</th><th>Payment</th><th>Current Status</th><th>Update Status</th></tr>
      </thead>
      <tbody>
        <?php if ($orders->num_rows === 0): ?>
          <tr><td colspan="6" style="text-align:center;padding:20px;">No orders yet.</td></tr>
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
          <td>
            <?= htmlspecialchars($o['user_name']) ?><br>
            <small style="color:#777;"><?= htmlspecialchars($o['email']) ?></small>
          </td>
          <td>Rs/- <?= number_format($o['grand_total'],2) ?></td>
          <td><?= $o['payment_method'] ?></td>
          <td><span class="badge badge-<?= $sc ?>"><?= $o['order_status'] ?></span></td>
          <td>
            <form method="POST">
              <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
              <div class="radio-group" style="padding:8px;margin-bottom:8px;">
                <?php foreach ($status_options as $so): ?>
                  <label>
                    <input type="radio" name="order_status" value="<?= $so ?>"
                           <?= $o['order_status']===$so ? 'checked' : '' ?>>
                    <?= $so ?>
                  </label>
                <?php endforeach; ?>
              </div>
              <button type="submit" class="btn btn-primary btn-sm">Update</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
