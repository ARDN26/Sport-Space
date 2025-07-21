<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['ID_User']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>
