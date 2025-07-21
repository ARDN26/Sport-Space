<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM sarpras WHERE ID_Sarpras = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: edit_sarana.php?status=sukses");
        exit;
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan di URL.";
}
?>
