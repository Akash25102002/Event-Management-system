<?php
require 'config.php';
requireUser();
$oid = intval($_SESSION['last_order_id'] ?? 0);
if (!$oid) redirect('user_portal.php');
$order = $conn->query("SELECT * FROM orders WHERE id=$oid")->fetch_assoc();
unset($_SESSION['last_order_id']);
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Order Success</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="modal-overlay">
  <div class="modal-box">
    <h3>🎉 THANK YOU!</h3>
    <p style="margin-bottom:16px;color:#555;">Your order has been placed successfully.</p>
    <div style="background:#3b5ea6;color:#fff;padding:10px;border-radius:6px;margin-bottom:16px;font-weight:600;text-align:center;">
      Total Amount: Rs/- <?= number_format($order['grand_total'], 2) ?>
    </div>
    <table style="width:100%;font-size:13px;border-collapse:collapse;">
      <?php foreach ([
        'Name'=>$order['name'], 'Number'=>$order['phone'],
        'E-mail'=>$order['email'], 'Payment Method'=>$order['payment_method'],
        'Address'=>$order['address'].', '.$order['city'].', '.$order['state'].' - '.$order['pincode'],
        'OinCode'=>$order['pincode']
      ] as $label=>$val): ?>
      <tr>
        <td style="background:#3b5ea6;color:#fff;padding:6px 10px;border-radius:4px;margin-bottom:4px;display:inline-block;min-width:100px;"><?= $label ?></td>
        <td style="padding:6px 10px;"><?= htmlspecialchars($val) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <div style="margin-top:20px;text-align:center;">
      <a href="vendors.php" class="btn btn-primary">Continue Shopping</a>
    </div>
  </div>
</div>
</body>
</html>
