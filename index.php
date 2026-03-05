<?php
// Admin Panel Entry Point
// Redirect to modern login page

session_start();

// If logged in, go to dashboard
if (isset($_SESSION['unohs'])) {
    header("Location: /app/pages/dashboard.php");
    exit;
}

// If not logged in, go to login
header("Location: /app/pages/login.php");
exit;
?>
