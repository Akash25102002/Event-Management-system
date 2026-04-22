<?php
require 'config.php';
requireUser();
$uid = $_SESSION['user_id'];
$vid = intval($_GET['vid'] ?? 0);
$vendors = $conn->query("SELECT id, name FROM vendors WHERE is_active=1 ORDER BY name");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_id = intval($_POST['vendor_id']);
    $note      = clean($conn, $_POST['request_note']);
    if (!$vendor_id) {
        $error = 'Please select a vendor.';
    } else {
        $stmt = $conn->prepare("INSERT INTO item_requests (vendor_id,user_id,request_note) VALUES (?,?,?)");
        $stmt->bind_param("iis", $vendor_id, $uid, $note);
        $stmt->execute();
        setFlash('success', 'Request sent to vendor!');
        redirect('user_portal.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Request Item</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="user_portal.php">Home</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card" style="max-width:500px;margin:0 auto;">
    <div class="page-title">Request an Item</div>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Vendor</label>
        <select name="vendor_id" required>
          <option value="">-- Select Vendor --</option>
          <?php while ($v = $vendors->fetch_assoc()): ?>
            <option value="<?= $v['id'] ?>" <?= $v['id']==$vid?'selected':'' ?>><?= htmlspecialchars($v['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group" style="align-items:flex-start;">
        <label style="padding-top:8px;">Note</label>
        <textarea name="request_note" rows="3" placeholder="Describe what you need..." style="flex:1;padding:8px;border:1px solid #ccd;border-radius:4px;font-size:14px;"></textarea>
      </div>
      <div style="text-align:center;margin-top:16px;">
        <button type="submit" class="btn btn-primary">Send Request</button>
        <a href="user_portal.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
