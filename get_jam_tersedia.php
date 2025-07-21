<?php
include 'db.php';

$waktu_id = $_POST['waktu_id'];
$tanggal = $_POST['tanggal'];
$id_area = $_POST['id_area'];
$id_sarpras = $_POST['id_sarpras'];

$query = "SELECT jam_mulai, jam_selesai FROM sewa 
    WHERE ID_Sarpras = '$id_sarpras' 
    AND ID_Area = '$id_area'
    AND ID_Waktu = '$waktu_id'
    AND tanggal = '$tanggal'
    AND status != 'Dibatalkan'"; 

$result = mysqli_query($conn, $query);
$booked = [];

while ($row = mysqli_fetch_assoc($result)) {
    $mulai = strtotime($row['jam_mulai']);
    $selesai = strtotime($row['jam_selesai']);

   while ($mulai + 30 * 60 <= $selesai) {
    $booked[] = date('H:i', $mulai);
    $mulai += 30 * 60;
}

}

echo json_encode(array_unique($booked));
?>
