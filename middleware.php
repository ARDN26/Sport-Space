<?php
session_start();

// Cek apakah pengguna sudah login dan sebagai admin
if (!isset($_SESSION['ID_Admin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>



