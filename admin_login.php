<?php
require 'config.php';
if (isAdmin()) redirect('admin_dashboard.php');
echo getFlash();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($conn, $_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $username;
        redirect('admin_dashboard.php');
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Admin Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="login-wrapper">
  <div class="login-box">
    <h2>Event Management System</h2>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>User Id</label>
        <input type="text" name="username" placeholder="Admin" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••" required>
      </div>
      <div style="display:flex;gap:12px;justify-content:center;margin-top:20px;">
        <a href="index.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
