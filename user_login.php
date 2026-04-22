<?php
require 'config.php';
if (isUser()) redirect('user_portal.php');
echo getFlash();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = clean($conn, $_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        redirect('user_portal.php');
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>User Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="login-wrapper">
  <div class="login-box">
    <h2>Event Management System</h2>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>User Id (Email)</label>
        <input type="email" name="email" placeholder="user@email.com" required>
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
    <p style="text-align:center;margin-top:14px;font-size:13px;">No account? <a href="user_signup.php">Sign Up</a></p>
  </div>
</div>
</body>
</html>
