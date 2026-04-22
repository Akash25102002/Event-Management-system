<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = clean($conn, $_POST['name']);
    $email    = clean($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are mandatory.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = 'Email already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt   = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
            $stmt->bind_param("sss", $name, $email, $hashed);
            if ($stmt->execute()) {
                setFlash('success', 'Registration successful! Please login.');
                redirect('user_login.php');
            } else {
                $error = 'Signup failed. Try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>User Signup</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="login-wrapper">
  <div class="login-box">
    <h2>Event Management System</h2>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" placeholder="Your name" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="email@example.com" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min 6 characters" required>
      </div>
      <div style="text-align:center;margin-top:20px;">
        <button type="submit" class="btn btn-primary">Sign Up</button>
        <a href="user_login.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
