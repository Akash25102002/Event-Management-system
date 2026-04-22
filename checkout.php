<?php
require 'config.php';
requireUser();
$uid = $_SESSION['user_id'];

$cart_count = $conn->query("SELECT COUNT(*) AS c FROM cart WHERE user_id=$uid")->fetch_assoc()['c'];
if ($cart_count == 0) redirect('cart.php');

$grand = $conn->query("
    SELECT SUM(p.product_price * c.quantity) AS total
    FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=$uid
")->fetch_assoc()['total'];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = clean($conn, $_POST['name']);
    $phone   = clean($conn, $_POST['phone']);
    $email   = clean($conn, $_POST['email']);
    $address = clean($conn, $_POST['address']);
    $city    = clean($conn, $_POST['city']);
    $state   = clean($conn, $_POST['state']);
    $pincode = clean($conn, $_POST['pincode']);
    $payment = clean($conn, $_POST['payment_method']);

    if (empty($name)||empty($phone)||empty($email)||empty($address)||empty($city)||empty($state)||empty($pincode)||empty($payment)) {
        $error = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO orders (user_id,name,email,phone,address,city,state,pincode,payment_method,grand_total) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("issssssssd", $uid,$name,$email,$phone,$address,$city,$state,$pincode,$payment,$grand);
        $stmt->execute();
        $order_id = $conn->insert_id;

        $cart_items = $conn->query("
            SELECT c.quantity, p.id AS pid, p.vendor_id, p.product_name, p.product_price
            FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=$uid
        ");
        while ($ci = $cart_items->fetch_assoc()) {
            $stmt2 = $conn->prepare("INSERT INTO order_items (order_id,product_id,vendor_id,product_name,product_price,quantity) VALUES (?,?,?,?,?,?)");
            $stmt2->bind_param("iiisdi", $order_id,$ci['pid'],$ci['vendor_id'],$ci['product_name'],$ci['product_price'],$ci['quantity']);
            $stmt2->execute();
        }
        $conn->query("DELETE FROM cart WHERE user_id=$uid");
        $_SESSION['last_order_id'] = $order_id;
        redirect('success.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Checkout</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="navbar">
  <h1>Event Management System</h1>
  <div class="nav-links">
    <a href="cart.php">← Back to Cart</a>
    <a href="logout.php">LogOut</a>
  </div>
</nav>
<div class="page-wrapper">
  <div class="card">
    <div style="background:#3b5ea6;color:#fff;text-align:center;padding:12px;border-radius:6px;margin-bottom:20px;font-weight:600;">
      Grand Total: Rs/- <?= number_format($grand, 2) ?>
    </div>
    <?php if ($error): ?><div style="color:#c0392b;margin-bottom:12px;"><?= $error ?></div><?php endif; ?>
    <div class="page-title">Your Details</div>
    <form method="POST">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" placeholder="Full name" required>
      </div>
      <div class="form-group">
        <label>Number</label>
        <input type="text" name="phone" placeholder="Phone number" required>
      </div>
      <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" placeholder="House no., Street" required>
      </div>
      <div class="form-group">
        <label>City</label>
        <input type="text" name="city" required>
      </div>
      <div class="form-group">
        <label>State</label>
        <input type="text" name="state" required>
      </div>
      <div class="form-group">
        <label>Pin Code</label>
        <input type="text" name="pincode" maxlength="6" required>
      </div>
      <div class="form-group">
        <label>Payment Method</label>
        <select name="payment_method" required>
          <option value="">-- Select --</option>
          <option value="Cash">Cash</option>
          <option value="UPI">UPI</option>
        </select>
      </div>
      <div style="text-align:center;margin-top:20px;">
        <button type="submit" class="btn btn-primary" style="padding:12px 40px;">Order Now</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
