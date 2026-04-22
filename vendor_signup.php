<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = clean($conn, $_POST['name']);
    $email    = clean($conn, $_POST['email']);
    $password = $_POST['password'];
    $category = clean($conn, $_POST['category']);

    if (empty($name) || empty($email) || empty($password) || empty($category)) {
        $error = 'All fields are mandatory.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = $conn->prepare("SELECT id FROM vendors WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = 'Email already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt   = $conn->prepare("INSERT INTO vendors (name, email, password, category) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $name, $email, $hashed, $category);
            if ($stmt->execute()) {
                setFlash('success', 'Signup successful! Please login.');
                redirect('vendor_login.php');
            } else {
                $error = 'Signup failed. Try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Vendor Signup</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="login-wrapper">
  <div class="login-box" style="width:500px;">
    <h2>Event Management System</h2>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" placeholder="Vendor name" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="email@example.com" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min 6 characters" required>
      </div>
      <div class="form-group">
        <label>Category</label>
        <select name="category" required>
          <option value="">-- Select --</option>
          <option value="Catering">Catering</option>
          <option value="Florist">Florist</option>
          <option value="Decoration">Decoration</option>
          <option value="Lighting">Lighting</option>
        </select>
      </div>
      <div style="text-align:center;margin-top:20px;">
        <button type="submit" class="btn btn-primary">Sign Up</button>
        <a href="index.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
