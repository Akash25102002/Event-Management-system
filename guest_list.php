<?php
require 'config.php';
requireUser();
$uid = $_SESSION['user_id'];
echo getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gname  = clean($conn, $_POST['guest_name']);
    $gemail = clean($conn, $_POST['guest_email']);
    $gphone = clean($conn, $_POST['guest_phone']);
    if ($gname) {
        $stmt = $conn->prepare("INSERT INTO guest_list (user_id,guest_name,guest_email,guest_phone) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $uid, $gname, $gemail, $gphone);
        $stmt->execute();
    }
    redirect('guest_list.php');
}
if (isset($_GET['delete'])) {
    $gid = intval($_GET['delete']);
    $conn->query("DELETE FROM guest_list WHERE id=$gid AND user_id=$uid");
    redirect('guest_list.php');
}
$guests = $conn->query("SELECT * FROM guest_list WHERE user_id=$uid ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Guest List</title><link rel="stylesheet" href="style.css"></head>
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
    <div class="page-title">My Guest List</div>
    <form method="POST" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
      <input type="text"  name="guest_name"  placeholder="Guest Name *" style="flex:1;padding:8px;border:1px solid #ccd;border-radius:4px;min-width:140px;" required>
      <input type="email" name="guest_email" placeholder="Email"         style="flex:1;padding:8px;border:1px solid #ccd;border-radius:4px;min-width:140px;">
      <input type="text"  name="guest_phone" placeholder="Phone"         style="flex:1;padding:8px;border:1px solid #ccd;border-radius:4px;min-width:120px;">
      <button type="submit" class="btn btn-primary">+ Add Guest</button>
    </form>
    <table class="data-table">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
        <?php $i=1; while ($g = $guests->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($g['guest_name']) ?></td>
          <td><?= htmlspecialchars($g['guest_email']) ?></td>
          <td><?= htmlspecialchars($g['guest_phone']) ?></td>
          <td><?= $g['status'] ?></td>
          <td><a href="guest_list.php?delete=<?= $g['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove guest?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
