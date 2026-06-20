<?php
include '../config/db.php';

$waktu_id = $_POST['waktu_id'];

$query = $conn->query("SELECT * FROM jam WHERE ID_Waktu = '$waktu_id'");

if ($query->num_rows > 0) {
    echo '<option value="">--Pilih Jam--</option>';
    while ($row = $query->fetch_assoc()) {
        echo '<option value="' . $row['ID_Jam'] . '">' . $row['Jam'] . '</option>';
    }
} else {
    echo '<option value="">-</option>';
}
?>
