<?php
require 'config.php';
requireAdmin();
echo getFlash();

if (isset($_GET['toggle'])) {
    $uid    = intval($_GET['toggle']);
    $user   = $conn->query("SELECT is_active FROM users WHERE id=$uid")->fetch_assoc();
    $newval = $user['is_active'] ? 0 : 1;
    $conn->query("UPDATE users SET is_active=$newval WHERE id=$uid");
    redirect('admin_users.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = clean($conn, $_POST['name']);
    $email    = clean($conn, $_POST['email']);
    $password = $_POST['password'];
    if (empty($name)||empty($email)||empty($password)) {
        $error = 'All fields required.';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt   = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $email, $hashed);
        $stmt->execute();
        setFlash('success', 'User added!');
        redirect('admin_users.php');
    }
}
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Manage Users</title><link rel="stylesheet" href="style.css"></head>
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
    <div class="page-title">Manage Users</div>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;padding:14px;background:#f7f9ff;border-radius:6px;">
      <input type="text"     name="name"     placeholder="Name *"     style="flex:1;min-width:120px;padding:8px;border:1px solid #ccd;border-radius:4px;" required>
      <input type="email"    name="email"    placeholder="Email *"    style="flex:1;min-width:160px;padding:8px;border:1px solid #ccd;border-radius:4px;" required>
      <input type="password" name="password" placeholder="Password *" style="flex:1;min-width:120px;padding:8px;border:1px solid #ccd;border-radius:4px;" required>
      <button type="submit" class="btn btn-primary">Add User</button>
    </form>
    <table class="data-table">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Status</th><th>Joined</th><th>Action</th></tr></thead>
      <tbody>
        <?php $i=1; while ($u = $users->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><span class="badge badge-<?= $u['is_active']?'active':'inactive' ?>"><?= $u['is_active']?'Active':'Inactive' ?></span></td>
          <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          <td>
            <a href="admin_users.php?toggle=<?= $u['id'] ?>"
               class="btn btn-<?= $u['is_active']?'danger':'success' ?> btn-sm">
              <?= $u['is_active']?'Deactivate':'Activate' ?>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
