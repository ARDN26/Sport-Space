<?php
include '../config/db.php';
header('Content-Type: application/json');

$id_kegiatan = $_GET['id_kegiatan'];

$kategori = [];
$waktu = [];

$q_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE ID_Kegiatan = '$id_kegiatan'");
while ($k = mysqli_fetch_assoc($q_kategori)) {
    $kategori[] = $k;
}

$q_waktu = mysqli_query($conn, "SELECT * FROM waktu WHERE ID_Kegiatan = '$id_kegiatan'");
while ($w = mysqli_fetch_assoc($q_waktu)) {
    $waktu[] = $w;
}

echo json_encode([
    'kategori' => $kategori,
    'waktu' => $waktu
]);
?>
