<?php
require 'config.php';
requireUser();
echo getFlash();
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>User Portal</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="portal-container" style="min-height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;">
  <div style="width:620px;">
    <h2>WELCOME USER</h2>
    <div class="portal-buttons">
      <a href="vendors.php"      class="portal-btn">Vendor</a>
      <a href="cart.php"         class="portal-btn">Cart</a>
      <a href="guest_list.php"   class="portal-btn">Guest List</a>
      <a href="order_status.php" class="portal-btn">Order Status</a>
      <a href="logout.php"       class="portal-btn">LogOut</a>
    </div>
  </div>
</div>
</body>
</html>
