<?php
include '../config/db.php';

$id_sewa = $_POST['id_sewa'];
$aksi = $_POST['aksi'];
$tanggal_konfirmasi = date('Y-m-d');

if ($aksi == 'konfirmasi') {
    $catatan = $_POST['catatan_admin'];
    $stmt = $conn->prepare("UPDATE sewa SET status = 'Dikonfirmasi', catatan_admin = ?, tanggal_konfirmasi = ? WHERE ID_Sewa = ?");
    $stmt->bind_param("ssi", $catatan, $tanggal_konfirmasi, $id_sewa);
    $stmt->execute(); 
    header("Location: ../pages/admin/pesanan_admin.php?status=konfirmasi_sukses");
    exit;
} elseif ($aksi == 'tolak') {
    $alasan = $_POST['alasan_penolakan'];
    $stmt = $conn->prepare("UPDATE sewa SET status = 'Ditolak', alasan_penolakan = ?, tanggal_konfirmasi = ? WHERE ID_Sewa = ?");
    $stmt->bind_param("ssi", $alasan, $tanggal_konfirmasi, $id_sewa);
    $stmt->execute(); 
    header("Location: ../pages/admin/pesanan_admin.php?status=penolakan_sukses");
    exit;
}
?>
