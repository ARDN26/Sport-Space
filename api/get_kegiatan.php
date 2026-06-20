<?php
include '../config/db.php';
$area_id = $_POST['area_id'];

$result = $conn->query("SELECT * FROM jenis_kegiatan WHERE ID_Area = '$area_id'");
echo '<option value="">--Pilih Kegiatan--</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['ID_Kegiatan'] . '">' . $row['Nama_Kegiatan'] . '</option>';
}
?>
