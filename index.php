<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-wrapper">
  <
  <div class="login-box" style="width:480px;text-align:center;">
    <h2>Event Management System</h2>
    <p style="margin-bottom:28px;color:#555;">Welcome! Our platform simplifies event planning and management.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
      <a href="admin_login.php"  class="btn btn-primary">Admin Login</a>
      <a href="vendor_login.php" class="btn btn-primary">Vendor Login</a>
      <a href="user_login.php"   class="btn btn-primary">User Login</a>
    </div>
    <div style="margin-top:20px;">
      <a href="vendor_signup.php" class="btn btn-light btn-sm">Register as Vendor</a>
      <a href="user_signup.php"   class="btn btn-light btn-sm" style="margin-left:8px;">Register as User</a>
    </div>
  </div>
</div>
</body>
</html>
