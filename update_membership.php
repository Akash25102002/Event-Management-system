<?php
require 'config.php';
requireAdmin();
echo getFlash();

$vendors     = $conn->query("SELECT v.*, m.plan_name FROM vendors v LEFT JOIN memberships m ON m.id=v.membership_id ORDER BY v.name");
$memberships = $conn->query("SELECT * FROM memberships ORDER BY duration_months");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_id = intval($_POST['vendor_id']);
    $action    = $_POST['action'];
    if ($action === 'cancel') {
        $conn->query("UPDATE vendors SET membership_id=NULL,membership_start=NULL,membership_end=NULL WHERE id=$vendor_id");
        setFlash('success', 'Membership cancelled.');
    } else {
        $membership_id = intval($_POST['membership_id']);
        $mem   = $conn->query("SELECT * FROM memberships WHERE id=$membership_id")->fetch_assoc();
        $start = date('Y-m-d');
        $end   = date('Y-m-d', strtotime("+{$mem['duration_months']} months"));
        $stmt  = $conn->prepare("UPDATE vendors SET membership_id=?,membership_start=?,membership_end=? WHERE id=?");
        $stmt->bind_param("issi", $membership_id, $start, $end, $vendor_id);
        $stmt->execute();
        setFlash('success', 'Membership extended.');
    }
    redirect('update_membership.php');
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Update Membership</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="maintenance.php">← Back</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div class="page-title">Update Membership for Vendor</div>
    <p style="font-size:12px;color:#777;margin-bottom:16px;">* Default: 6-month extension selected.</p>
    <table class="data-table">
      <thead><tr><th>Vendor</th><th>Email</th><th>Current Plan</th><th>Expires</th><th>Action</th></tr></thead>
      <tbody>
        <?php while ($v = $vendors->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($v['name']) ?></td>
          <td><?= htmlspecialchars($v['email']) ?></td>
          <td><?= $v['plan_name'] ?: '<span style="color:#999">None</span>' ?></td>
          <td><?= $v['membership_end'] ?: '—' ?></td>
          <td>
            <form method="POST" style="display:inline-flex;gap:6px;align-items:center;">
              <input type="hidden" name="vendor_id" value="<?= $v['id'] ?>">
              <select name="membership_id" style="padding:4px;border:1px solid #ccd;border-radius:4px;font-size:12px;">
                <?php $memberships->data_seek(0); $first=true; while ($m=$memberships->fetch_assoc()): ?>
                  <option value="<?= $m['id'] ?>" <?= $first?'selected':'' ?>><?= $m['plan_name'] ?></option>
                <?php $first=false; endwhile; ?>
              </select>
              <button type="submit" name="action" value="extend" class="btn btn-primary btn-sm">Extend</button>
              <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm"
                      onclick="return confirm('Cancel this membership?')">Cancel</button>
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
