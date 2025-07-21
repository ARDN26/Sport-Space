<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_sarana']);
    $foto = $_FILES['foto_sarana']['name'];
    $tmp = $_FILES['foto_sarana']['tmp_name'];

    $uploadDir = "asset/Foto Sarana/";
    move_uploaded_file($tmp, $uploadDir . $foto);

    $query = "INSERT INTO sarpras (Nama_Sarana, Foto_Sarpras) VALUES ('$nama', '$foto')";
    if (mysqli_query($conn, $query)) {
        echo "Sukses";
    } else {
        http_response_code(500);
        echo "Gagal menyimpan";
    }
}
?>
