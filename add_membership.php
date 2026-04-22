<?php
require 'config.php';
requireAdmin();
echo getFlash();

$vendors     = $conn->query("SELECT id, name, email FROM vendors ORDER BY name");
$memberships = $conn->query("SELECT * FROM memberships ORDER BY duration_months");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_id     = intval($_POST['vendor_id']);
    $membership_id = intval($_POST['membership_id']);
    if (!$vendor_id || !$membership_id) {
        $error = 'All fields are mandatory.';
    } else {
        $mem   = $conn->query("SELECT * FROM memberships WHERE id=$membership_id")->fetch_assoc();
        $start = date('Y-m-d');
        $end   = date('Y-m-d', strtotime("+{$mem['duration_months']} months"));
        $stmt  = $conn->prepare("UPDATE vendors SET membership_id=?,membership_start=?,membership_end=? WHERE id=?");
        $stmt->bind_param("issi", $membership_id, $start, $end, $vendor_id);
        $stmt->execute();
        setFlash('success', 'Membership assigned!');
        redirect('maintenance.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Add Membership</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="maintenance.php">← Back</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card" style="max-width:520px;margin:0 auto;">
    <div class="page-title">Add Membership for Vendor</div>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Vendor</label>
        <select name="vendor_id" required>
          <option value="">-- Select Vendor --</option>
          <?php $vendors->data_seek(0); while ($v = $vendors->fetch_assoc()): ?>
            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?> (<?= $v['email'] ?>)</option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group" style="align-items:flex-start;">
        <label style="padding-top:10px;">Plan</label>
        <div class="radio-group" style="flex:1;">
          <?php $memberships->data_seek(0); $first=true; while ($m = $memberships->fetch_assoc()): ?>
            <label>
              <input type="radio" name="membership_id" value="<?= $m['id'] ?>" <?= $first?'checked':'' ?> required>
              <?= htmlspecialchars($m['plan_name']) ?> — Rs/- <?= number_format($m['price'],2) ?>
            </label>
          <?php $first=false; endwhile; ?>
        </div>
      </div>
      <p style="font-size:12px;color:#777;margin-bottom:14px;">* Default: 6 months selected. Membership starts today.</p>
      <div style="text-align:center;">
        <button type="submit" class="btn btn-primary">Assign Membership</button>
        <a href="maintenance.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
