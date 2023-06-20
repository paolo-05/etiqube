<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])
        && $_GET['action'] == 'logout') {
    // Clear the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}
?>