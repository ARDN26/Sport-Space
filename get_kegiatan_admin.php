<?php
include 'db.php';
header('Content-Type: application/json');

$id_area = $_GET['id_area'];

$result = mysqli_query($conn, "SELECT * FROM jenis_kegiatan WHERE ID_Area = '$id_area'");
$kegiatan = [];

while ($row = mysqli_fetch_assoc($result)) {
    $kegiatan[] = $row;
}

echo json_encode($kegiatan);
?>