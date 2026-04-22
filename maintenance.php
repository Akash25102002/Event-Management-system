<?php
require 'config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Maintenance Menu — EMS</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <a href="admin_dashboard.php" class="sidebar-item" title="Dashboard">
        <svg viewBox="0 0 24 24"><path d="M21 2H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h7l-2 3v1h8v-1l-2-3h7a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm0 14H3V4h18z"/></svg>
        <span class="tip">Dashboard</span>
    </a>
    <a href="admin_panel.php" class="sidebar-item" title="Reports">
        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        <span class="tip">Reports</span>
    </a>
    <a href="admin_orders.php" class="sidebar-item" title="Transactions">
        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
        <span class="tip">Transactions</span>
    </a>
    <a href="maintenance.php" class="sidebar-item active" title="Maintenance">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
        <span class="tip">Maintenance</span>
    </a>
    <a href="#" class="sidebar-item" title="Settings">
        <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
        <span class="tip">Settings</span>
    </a>
    <div class="sidebar-bottom">
        <a href="logout.php" class="sidebar-item" title="Logout">
            <svg viewBox="0 0 24 24"><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
            <span class="tip">Logout</span>
        </a>
    </div>
</aside>

<!-- TOPBAR -->
<header class="topbar">
    <div class="topbar-left">
        <span class="topbar-brand">SIDE <span>BAR</span></span>
        <button class="topbar-menu-btn" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
        </button>
    </div>
    <div class="topbar-right">
        <div class="topbar-avatar">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
        </div>
    </div>
</header>

<!-- MAIN -->
<main class="main-content" id="mainContent">
    <div class="page-wrapper">
        <div class="card">
            <div class="page-title">Maintenance Menu <small style="font-size:13px;color:#888;">(Admin access only)</small></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">
                <div class="card" style="border:1px solid #dde;">
                    <h3 style="margin-bottom:14px;font-size:15px;color:#1a1a2e;">&#128203; Membership</h3>
                    <div style="display:flex;gap:10px;">
                        <a href="add_membership.php"    class="btn btn-primary">Add</a>
                        <a href="update_membership.php" class="btn btn-light">Update</a>
                    </div>
                </div>
                <div class="card" style="border:1px solid #dde;">
                    <h3 style="margin-bottom:14px;font-size:15px;color:#1a1a2e;">&#128100; User Management</h3>
                    <div style="display:flex;gap:10px;">
                        <a href="admin_users.php" class="btn btn-primary">Add</a>
                        <a href="admin_users.php" class="btn btn-light">Update</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function toggleSidebar() {
    var s = document.getElementById('sidebar');
    var m = document.getElementById('mainContent');
    var t = document.querySelector('.topbar');
    var open = s.getAttribute('data-open') !== 'false';
    if (open) { s.style.width='0';s.style.overflow='hidden';m.style.marginLeft='0';t.style.left='0';s.setAttribute('data-open','false'); }
    else { s.style.width='60px';s.style.overflow='';m.style.marginLeft='60px';t.style.left='60px';s.setAttribute('data-open','true'); }
}
</script>
</body>
</html>
