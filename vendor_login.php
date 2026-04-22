<?php
require 'config.php';
if (isVendor()) redirect('vendor_dashboard.php');
echo getFlash();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = clean($conn, $_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM vendors WHERE email = ? AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $vendor = $stmt->get_result()->fetch_assoc();

    if ($vendor && password_verify($password, $vendor['password'])) {
        $_SESSION['vendor_id']   = $vendor['id'];
        $_SESSION['vendor_name'] = $vendor['name'];
        redirect('vendor_dashboard.php');
    } else {
        $error = 'Invalid credentials or account inactive.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Vendor Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="login-wrapper">
  <div class="login-box">
    <h2>Event Management System</h2>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>User Id (Email)</label>
        <input type="email" name="email" placeholder="vendor@email.com" required>
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
    <p style="text-align:center;margin-top:14px;font-size:13px;">No account? <a href="vendor_signup.php">Sign Up</a></p>
  </div>
</div>
</body>
</html>
