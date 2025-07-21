<?php
include 'db.php';

$kegiatan_id = $_POST['kegiatan_id'];

// Ambil kategori berdasarkan ID_Kegiatan
$kategori_result = $conn->query("SELECT * FROM kategori WHERE ID_Kegiatan = '$kegiatan_id'");
$kategori_options = '';

if ($kategori_result->num_rows > 0) {
    $kategori_options .= '<option value="">--Pilih Kategori--</option>';
    while ($row = $kategori_result->fetch_assoc()) {
        $kategori_options .= '<option value="'.$row['ID_Kategori'].'">'.$row['Kategori'].'</option>';
    }
} else {
    $kategori_options .= '<option value="">Tidak ada kategori</option>';
}

// Ambil waktu berdasarkan ID_Kegiatan
$waktu_result = $conn->query("SELECT * FROM waktu WHERE ID_Kegiatan = '$kegiatan_id'");
$waktu_options = '<option value="">--Pilih Waktu--</option>';
while ($row = $waktu_result->fetch_assoc()) {
    $waktu_options .= '<option value="'.$row['ID_Waktu'].'">'.$row['Waktu'].'</option>';
}

// Return data sebagai JSON
echo json_encode([
    'kategori' => $kategori_options,
    'waktu' => $waktu_options
]);
