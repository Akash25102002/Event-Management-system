<?php
require 'config.php';
requireUser();
$cat = isset($_GET['cat']) ? clean($conn, $_GET['cat']) : '';
$where = $cat ? "WHERE category='$cat' AND is_active=1" : "WHERE is_active=1";
$vendors = $conn->query("SELECT * FROM vendors $where ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Vendors</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="user_portal.php">Home</a>
    <a href="cart.php">Cart</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php foreach ([''=>'All','Catering'=>'Catering','Florist'=>'Florist','Decoration'=>'Decoration','Lighting'=>'Lighting'] as $val=>$label): ?>
      <a href="vendors.php<?= $val?'?cat='.$val:'' ?>"
         class="btn btn-sm <?= $cat===$val?'btn-primary':'btn-light' ?>"><?= $label ?></a>
    <?php endforeach; ?>
  </div>
  <div class="product-grid" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr));">
    <?php while ($v = $vendors->fetch_assoc()): ?>
    <div class="product-card">
      <h3><?= htmlspecialchars($v['name']) ?></h3>
      <p style="opacity:0.85;font-size:12px;margin-bottom:4px;"><?= $v['category'] ?></p>
      <p style="font-size:12px;margin-bottom:12px;"><?= htmlspecialchars($v['email']) ?></p>
      <a href="products.php?vid=<?= $v['id'] ?>" class="btn" style="width:100%;background:#fff;color:#3b5ea6;font-weight:600;">Shop Item</a>
    </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
