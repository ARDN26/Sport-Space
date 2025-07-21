<?php
include 'db.php';
$sarpras_id = $_POST['sarpras_id'];

$query = "SELECT ID_Area as id, Area as nama, Foto_Area as foto FROM area WHERE ID_Sarpras = $sarpras_id";
$result = mysqli_query($conn, $query);

$areas = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Gabungkan path foto
    $row['foto'] = 'asset/Foto Area/' . $row['foto'];
    $areas[] = $row;
}
echo json_encode($areas);
?>
