<?php
require 'config.php';
requireVendor();
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Vendor Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="portal-container" style="min-height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;">
  <div style="width:500px;">
    <h2>Welcome <?= htmlspecialchars($_SESSION['vendor_name']) ?></h2>
    <div class="portal-buttons">
      <a href="your_items.php"  class="portal-btn">Your Item</a>
      <a href="add_item.php"    class="portal-btn">Add New Item</a>
      <a href="transaction.php" class="portal-btn">Transaction</a>
      <a href="logout.php"      class="portal-btn">LogOut</a>
    </div>
  </div>
</div>
</body>
</html>
