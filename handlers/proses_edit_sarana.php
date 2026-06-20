<?php
include '../config/db.php'; // koneksi $conn


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/admin/form_edit_sarana.php');
    exit;
}


$id_sarpras = intval($_POST['id_sarpras']);



// 1. UPDATE sarana utama
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$jam_operasional = mysqli_real_escape_string($conn, $_POST['jam_operasional']);
$jangkauan_harga = mysqli_real_escape_string($conn, $_POST['jangkauan_harga']);
$fasilitas = mysqli_real_escape_string($conn, $_POST['fasilitas']);
$fungsi = mysqli_real_escape_string($conn, $_POST['fungsi']);
$aturan = mysqli_real_escape_string($conn, $_POST['aturan']);
$syarat = mysqli_real_escape_string($conn, $_POST['syarat']);

mysqli_query($conn, "
    UPDATE sarpras SET
        Nama_Sarana='$nama',
        Deskripsi='$deskripsi',
        Alamat_Sarana='$alamat',
        Jam_Operasional='$jam_operasional',
        Harga='$jangkauan_harga',
        Fasilitas='$fasilitas',
        fungsi='$fungsi',
        Aturan_Dan_Kebijakan='$aturan',
        Syarat_Penyewaan='$syarat'
    WHERE ID_Sarpras=$id_sarpras
");

// Foto display
if (!empty($_FILES['foto_sarpras']['tmp_name'])) {
    // (upload to folder, validasi, dll)
    $file = $_FILES['foto_sarpras'];
    $newName = uniqid() . '_' . basename($file['name']);
    move_uploaded_file($file['tmp_name'], "../asset/Foto Sarana/$newName");
    mysqli_query($conn, "UPDATE sarpras SET Foto_Sarpras='$newName' WHERE ID_Sarpras=$id_sarpras");
}

// Galeri: hapus
if (!empty($_POST['hapus_galeri'])) {
    $toDelete = $_POST['hapus_galeri'];
    $ids = array_map('intval', $toDelete);
    $idList = implode(',', $ids);
    mysqli_query($conn, "DELETE FROM galeri_sarana WHERE ID_Gsarana IN($idList)");
}

// Galeri: tambah
if (!empty($_FILES['tambah_galeri']['tmp_name'])) {
    foreach ($_FILES['tambah_galeri']['tmp_name'] as $i => $tmp) {
        if (!$tmp) continue;
        $file = [
            'tmp_name'=>$tmp,
            'name'=>$_FILES['tambah_galeri']['name'][$i]
        ];
        $newName = uniqid() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], "../asset/Foto Sarana/$newName");
        mysqli_query($conn, "
            INSERT INTO galeri_sarana (ID_Sarpras, foto2_sarana)
            VALUES ($id_sarpras, '$newName')
        ");
    }
}

// 2. Proses area/kegiatan/kategori/waktu/jam
// Hapus area
if (!empty($_POST['hapus_area'])) {
    $id = intval($_POST['hapus_area']);
    mysqli_query($conn,"DELETE a, j, k, w, jm
        FROM area a
        LEFT JOIN jenis_kegiatan j ON j.ID_Area=a.ID_Area
        LEFT JOIN kategori k ON k.ID_Kegiatan=j.ID_Kegiatan
        LEFT JOIN waktu w ON w.ID_Kegiatan=j.ID_Kegiatan
        LEFT JOIN jam jm ON jm.ID_Waktu=w.ID_Waktu
        WHERE a.ID_Area=$id
    ");
}
// Hapus kegiatan
if (!empty($_POST['hapus_kegiatan'])) {
    $id = intval($_POST['hapus_kegiatan']);
    mysqli_query($conn,"DELETE j, k, w, jm
        FROM jenis_kegiatan j
        LEFT JOIN kategori k ON k.ID_Kegiatan=j.ID_Kegiatan
        LEFT JOIN waktu w ON w.ID_Kegiatan=j.ID_Kegiatan
        LEFT JOIN jam jm ON jm.ID_Waktu=w.ID_Waktu
        WHERE j.ID_Kegiatan=$id
    ");
}
// Hapus kategori
if (!empty($_POST['hapus_kategori'])) {
    $id = intval($_POST['hapus_kategori']);
    mysqli_query($conn,"DELETE FROM kategori WHERE ID_Kategori=$id");
}
// Hapus waktu
if (!empty($_POST['hapus_waktu'])) {
    $id = intval($_POST['hapus_waktu']);
    mysqli_query($conn,"DELETE w, jm
        FROM waktu w
        LEFT JOIN jam jm ON jm.ID_Waktu=w.ID_Waktu
        WHERE w.ID_Waktu=$id
    ");
}

// Tambah area
if (isset($_POST['tambah_area'])) {
    mysqli_query($conn, "INSERT INTO area (ID_Sarpras, Area) VALUES ($id_sarpras, 'New Area')");
}

if (!empty($_POST['id_area']) && !empty($_POST['area'])) {
    foreach ($_POST['id_area'] as $index => $id_area) {
        $id_area = intval($id_area);
        $nama_area = mysqli_real_escape_string($conn, $_POST['area'][$index]);

        // Update nama area
        mysqli_query($conn, "UPDATE area SET Area='$nama_area' WHERE ID_Area=$id_area");

        // Proses upload foto jika ada file baru
        if (isset($_FILES['foto_area']['name'][$index]) && $_FILES['foto_area']['name'][$index] != '') {
            $foto_name = $_FILES['foto_area']['name'][$index];
            $foto_tmp = $_FILES['foto_area']['tmp_name'][$index];
            $foto_ext = pathinfo($foto_name, PATHINFO_EXTENSION);
            $foto_new_name = uniqid('area_') . '.' . $foto_ext;
            $target_path = '../asset/Foto Area/' . $foto_new_name;

            // Pindahkan file
            if (move_uploaded_file($foto_tmp, $target_path)) {
                // Simpan nama file baru ke DB
                mysqli_query($conn, "UPDATE area SET Foto_Area='$foto_new_name' WHERE ID_Area=$id_area");
            }
        }
    }
}


// Tambah kegiatan
if (isset($_POST['tambah_kegiatan'])) {
    $id_area = intval($_POST['tambah_kegiatan']);
    mysqli_query($conn, "INSERT INTO jenis_kegiatan (ID_Area, Nama_Kegiatan) VALUES ($id_area, 'New Kegiatan')");
}

if (isset($_POST['id_kegiatan'], $_POST['nama_kegiatan'])) {   
        foreach ($_POST['id_kegiatan'] as $i => $id_kegiatan) {
            $nama_kegiatan = mysqli_real_escape_string($conn, $_POST['nama_kegiatan'][$i]);
            mysqli_query($conn, "UPDATE jenis_kegiatan SET Nama_Kegiatan = '$nama_kegiatan' WHERE ID_Kegiatan = '$id_kegiatan'");
        }
}



// Tambah kategori
if (isset($_POST['tambah_kategori'])) {
    $id_kegiatan = intval($_POST['tambah_kategori']);
    mysqli_query($conn, "INSERT INTO kategori (ID_Kegiatan, Kategori) VALUES ($id_kegiatan, 'New Kategori')");
}

if (!empty($_POST['id_kategori']) && !empty($_POST['kategori'])) {
    foreach ($_POST['id_kategori'] as $index => $id_kategori) {
        $id_kategori = intval($id_kategori);
        $nama_kategori = mysqli_real_escape_string($conn, $_POST['kategori'][$index]);
        mysqli_query($conn, "UPDATE kategori SET Kategori='$nama_kategori' WHERE ID_Kategori=$id_kategori");
    }
}


if (isset($_POST['tambah_waktu'])) {
    $id_kegiatan = intval($_POST['tambah_waktu']);


    $queryWaktu = mysqli_query($conn, "INSERT INTO waktu (ID_Kegiatan, Waktu) VALUES ($id_kegiatan, 'New Waktu')");

    if ($queryWaktu) {
        $id_waktu_baru = mysqli_insert_id($conn);
       
        $queryJam = mysqli_query($conn, "
            INSERT INTO jam (ID_Waktu, Jam_Mulai, Jam_Selesai) 
            VALUES ($id_waktu_baru, '00:00:00', '00:00:00')
        ");
    } 
}



    if (!empty($_POST['id_waktu']) && !empty($_POST['waktu'])) {
        foreach ($_POST['id_waktu'] as $index => $id_waktu) {
            $id_waktu = intval($id_waktu);
            $nama_waktu = mysqli_real_escape_string($conn, $_POST['waktu'][$index]);
            $sql = "UPDATE waktu SET Waktu='$nama_waktu' WHERE ID_Waktu=$id_waktu";
            $update = mysqli_query($conn, $sql);
            if (!$update) {
                echo "Gagal update waktu ID $id_waktu: " . mysqli_error($conn) . "<br>";
            }
        }
    }


if (!empty($_POST['id_jam']) && !empty($_POST['jam_mulai']) && !empty($_POST['jam_akhir'])) {
    foreach ($_POST['id_jam'] as $index => $id_jam) {
        $id_jam = intval($id_jam);
        $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai'][$index]);
        $jam_akhir = mysqli_real_escape_string($conn, $_POST['jam_akhir'][$index]);

        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_mulai) && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jam_akhir)) {
            mysqli_query($conn, "
                UPDATE jam SET 
                    Jam_Mulai = '$jam_mulai', 
                    Jam_Selesai = '$jam_akhir' 
                WHERE ID_Jam = $id_jam
            ");
        }
    }
}




// 3. Proses harga
// Hapus harga
if (!empty($_POST['hapus_harga'])) {
    $id = intval($_POST['hapus_harga']);
    mysqli_query($conn, "DELETE FROM harga WHERE ID_Harga=$id");
}
// Update harga existing
if (!empty($_POST['id_harga'])) {
    foreach ($_POST['id_harga'] as $i => $hid) {
        $harga = floatval($_POST['harga'][$i]);
        $id = intval($hid);
        mysqli_query($conn,"UPDATE harga SET Harga=$harga WHERE ID_Harga=$id");
    }
}
// Tambah harga baru
if (isset($_POST['tambah_harga'])) {
    $area = intval($_POST['id_areaa']);
    $kegiatan = intval($_POST['id_kegiatann']);
    $kategori = intval($_POST['id_kategorii']) ?: 'NULL';
    $waktu = intval($_POST['id_waktuu']);
    $harga_baru = floatval($_POST['harga_baru']);
    mysqli_query($conn, "
        INSERT INTO harga
            (ID_Sarpras, ID_Area, ID_Kegiatan, ID_Kategori, ID_Waktu, Harga)
        VALUES
            ($id_sarpras, $area, $kegiatan, $kategori, $waktu, $harga_baru)
    ");
}

header("Location: ../pages/admin/form_edit_sarana.php?id=$id_sarpras&status=success&msg=Data sarana berhasil diperbarui");
exit;

?>
