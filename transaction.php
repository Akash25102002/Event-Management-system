<?php
require 'config.php';
requireVendor();
$vid = $_SESSION['vendor_id'];

if (isset($_GET['action']) && isset($_GET['rid'])) {
    $rid    = intval($_GET['rid']);
    $action = $_GET['action'] === 'accept' ? 'Accepted' : 'Rejected';
    $stmt   = $conn->prepare("UPDATE item_requests SET status=? WHERE id=? AND vendor_id=?");
    $stmt->bind_param("sii", $action, $rid, $vid);
    $stmt->execute();
    redirect('transaction.php');
}

$requests = $conn->query("
    SELECT ir.*, u.name AS user_name, u.email AS user_email, p.product_name
    FROM item_requests ir
    JOIN users u ON u.id = ir.user_id
    LEFT JOIN products p ON p.id = ir.product_id
    WHERE ir.vendor_id = $vid
    ORDER BY ir.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Transactions</title><link rel="stylesheet" href="style.css"></head>
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
    <div class="page-title">Item Requests / Transactions</div>
    <table class="data-table">
      <thead><tr><th>User</th><th>Email</th><th>Product</th><th>Note</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
        <?php if ($requests->num_rows === 0): ?>
          <tr><td colspan="6" style="text-align:center;padding:20px;">No requests yet.</td></tr>
        <?php endif; ?>
        <?php while ($r = $requests->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($r['user_name']) ?></td>
          <td><?= htmlspecialchars($r['user_email']) ?></td>
          <td><?= htmlspecialchars($r['product_name'] ?? 'General Request') ?></td>
          <td><?= htmlspecialchars($r['request_note'] ?? '') ?></td>
          <td><span class="badge badge-<?= $r['status']==='Accepted'?'delivered':($r['status']==='Rejected'?'inactive':'pending') ?>"><?= $r['status'] ?></span></td>
          <td>
            <?php if ($r['status'] === 'Pending'): ?>
              <a href="transaction.php?action=accept&rid=<?= $r['id'] ?>" class="btn btn-success btn-sm">Accept</a>
              <a href="transaction.php?action=reject&rid=<?= $r['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
            <?php else: ?>
              <span style="font-size:12px;color:#999;"><?= $r['status'] ?></span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
