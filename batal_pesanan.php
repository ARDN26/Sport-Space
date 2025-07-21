<?php
session_start();
include 'db.php';

if (!isset($_POST['id_sewa']) || !isset($_POST['alasan'])) {
    echo "Data tidak lengkap.";
    exit;
}

$id_sewa = $_POST['id_sewa'];
$alasan = $conn->real_escape_string($_POST['alasan']);

// Update status dan alasan
$update = $conn->query("UPDATE sewa SET status='Dibatalkan', alasan_pembatalan='$alasan' WHERE ID_Sewa='$id_sewa'");

if ($update) {
    header("Location: pesanan_user.php?batal=berhasil");
} else {
    echo "Gagal membatalkan: " . $conn->error;
}
?>
