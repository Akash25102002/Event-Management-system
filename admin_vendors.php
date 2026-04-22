<?php
require 'config.php';
requireAdmin();
echo getFlash();

if (isset($_GET['toggle'])) {
    $vid    = intval($_GET['toggle']);
    $vendor = $conn->query("SELECT is_active FROM vendors WHERE id=$vid")->fetch_assoc();
    $newval = $vendor['is_active'] ? 0 : 1;
    $conn->query("UPDATE vendors SET is_active=$newval WHERE id=$vid");
    redirect('admin_vendors.php');
}
$vendors = $conn->query("SELECT v.*, m.plan_name FROM vendors v LEFT JOIN memberships m ON m.id=v.membership_id ORDER BY v.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Manage Vendors</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="admin_dashboard.php">Home</a>
    <a href="add_membership.php">Add Membership</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">Manage Vendors</div>
    <table class="data-table">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Category</th><th>Plan</th><th>Expires</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php $i=1; while ($v = $vendors->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($v['name']) ?></td>
          <td><?= htmlspecialchars($v['email']) ?></td>
          <td><?= $v['category'] ?></td>
          <td><?= $v['plan_name'] ?: '—' ?></td>
          <td><?= $v['membership_end'] ?: '—' ?></td>
          <td><span class="badge badge-<?= $v['is_active']?'active':'inactive' ?>"><?= $v['is_active']?'Active':'Inactive' ?></span></td>
          <td>
            <a href="admin_vendors.php?toggle=<?= $v['id'] ?>"
               class="btn btn-<?= $v['is_active']?'danger':'success' ?> btn-sm">
              <?= $v['is_active']?'Deactivate':'Activate' ?>
            </a>
            <a href="add_membership.php" class="btn btn-primary btn-sm">Membership</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
