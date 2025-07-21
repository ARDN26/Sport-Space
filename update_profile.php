<?php
session_start();
require 'db.php';


if (!isset($_SESSION['ID_User'])) {
    header("Location: login.php?message=Silahkan+Login+Terlebih+Dahulu");
    exit;
}


$id_user = $_SESSION['ID_User'];


$nama = $_POST['nama'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];

$sql = "UPDATE users SET Nama_User = ?, email = ?, No_Telepon = ? WHERE ID_User = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nama, $email, $no_hp, $id_user);

if ($stmt->execute()) {
    header("Location: beranda_user.php?update=success");
    exit;
} else {
    header("Location: beranda_user.php?update=failed");
    exit;
}
?>
