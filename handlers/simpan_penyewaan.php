<?php
session_start();
include '../config/db.php';

$id_user = $_POST['ID_User'];
$id_sarpras = $_POST['ID_Sarpras'];
$id_area = $_POST['ID_Area'];
$id_kegiatan = $_POST['ID_Kegiatan'];
$id_kategori = isset($_POST['ID_Kategori']) && $_POST['ID_Kategori'] !== '' ? intval($_POST['ID_Kategori']) : 0;
$id_waktu = $_POST['ID_Waktu'];
$tanggal = $_POST['tanggal'];
$jam_mulai = $_POST['Jam_Mulai'];
$jam_selesai = $_POST['Jam_Selesai'];


// Konversi harga (contoh: "Rp1.000,00" => 1000.00)
$harga = str_replace(['Rp', '.', ','], ['', '', '.'], $_POST['harga']);
$harga = floatval($harga);

// Upload surat_permohonan
$surat_name = time() . '_' . basename($_FILES['surat_permohonan']['name']);
$surat_tmp = $_FILES['surat_permohonan']['tmp_name'];
$surat_target = "../uploads/surat_permohonan/" . $surat_name;
move_uploaded_file($surat_tmp, $surat_target);

// Upload bukti_pembayaran
$bukti_name = time() . '_' . basename($_FILES['bukti_pembayaran']['name']);
$bukti_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
$bukti_target = "../uploads/bukti_pembayaran/" . $bukti_name;
move_uploaded_file($bukti_tmp, $bukti_target);

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO sewa 
    (ID_User, ID_Sarpras, ID_Area, ID_Kegiatan, ID_Kategori, ID_Waktu, Jam_Mulai, Jam_Selesai, Tanggal,  Surat_Permohonan, Bukti_Pembayaran, Harga) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("iiiiiisssssd",
    $id_user,
    $id_sarpras,
    $id_area,
    $id_kegiatan,
    $id_kategori,
    $id_waktu,
    $jam_mulai,
    $jam_selesai,
    $tanggal,
    $surat_name,
    $bukti_name,
    $harga
);


// Eksekusi dan redirect
if ($stmt->execute()) {
    header("Location: ../pages/user/sewa.php?sarpras=$id_sarpras&status=sukses");
} else {
   
    header("Location: ../pages/user/sewa.php?sarpras=$id_sarpras&status=gagal");
}
exit;
?>
