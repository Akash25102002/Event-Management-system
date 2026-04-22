<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'event_management');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

define('UPLOAD_DIR', 'uploads/');

function redirect($url) { header("Location: $url"); exit(); }

function clean($conn, $data) {
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

function isAdmin()  { return isset($_SESSION['admin_id']); }
function isVendor() { return isset($_SESSION['vendor_id']); }
function isUser()   { return isset($_SESSION['user_id']); }

function requireAdmin()  { if (!isAdmin())  redirect('admin_login.php'); }
function requireVendor() { if (!isVendor()) redirect('vendor_login.php'); }
function requireUser()   { if (!isUser())   redirect('user_login.php'); }

function setFlash($type, $msg) { $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg]; }

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash']; unset($_SESSION['flash']);
        $color = $f['type'] === 'success' ? '#1a7a4a' : '#c0392b';
        return "<div style='background:{$color};color:#fff;padding:10px 16px;margin-bottom:12px;border-radius:6px;'>{$f['msg']}</div>";
    }
    return '';
}
?>
