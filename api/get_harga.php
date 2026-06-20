<?php
include '../config/db.php';

$id_sarpras = $_POST['sarpras'] ?? null;
$id_area = $_POST['area'] ?? null;
$id_kategori = $_POST['kategori'] ?? null;
$id_kegiatan = $_POST['kegiatan'] ?? null;
$id_waktu = $_POST['waktu'] ?? null;
$jam_mulai = $_POST['jam_mulai'] ?? null;
$jam_selesai = $_POST['jam_selesai'] ?? null;

if (!$id_sarpras || !$id_area || $id_kategori === null || !$id_kegiatan || !$id_waktu) {
    echo 0;
    exit;
}

// Cek apakah waktu ini memiliki jam terkait
$cekJam = mysqli_query($conn, "SELECT COUNT(*) as total FROM jam WHERE ID_Waktu = '$id_waktu'");
$cekJamData = mysqli_fetch_assoc($cekJam);
$punya_jam = $cekJamData['total'] > 0;

// Query harga tetap menyertakan kategori (meskipun 0)
$query = "SELECT Harga FROM harga 
          WHERE ID_Sarpras = ? 
          AND ID_Area = ? 
          AND ID_Kategori = ? 
          AND ID_Kegiatan = ? 
          AND ID_Waktu = ?
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiiii", $id_sarpras, $id_area, $id_kategori, $id_kegiatan, $id_waktu);
$stmt->execute();
$stmt->bind_result($harga_per_unit);

if ($stmt->fetch()) {
    if ($punya_jam) {
        // Jika waktu punya jam, lakukan perhitungan durasi
        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);

        if (!$start || !$end || $start >= $end) {
            echo 0;
            exit;
        }

        $durasi = ($end - $start) / 3600;
        $total = $harga_per_unit * $durasi;
    } else {
        // Jika tidak punya jam, langsung pakai harga satuan
        $total = $harga_per_unit;
    }

    echo $total;
} else {
    echo 0;
}

$stmt->close();
