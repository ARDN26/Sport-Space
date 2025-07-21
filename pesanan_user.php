<?php
session_start();
if (!isset($_SESSION['ID_User'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
$id_user = $_SESSION['ID_User'];

$query = "SELECT s.*, sp.Nama_Sarana, a.Area, jk.Nama_Kegiatan, k.Kategori, w.Waktu,
                 s.Jam_Mulai, s.Jam_Selesai
          FROM sewa s
          LEFT JOIN sarpras sp ON s.ID_Sarpras = sp.ID_Sarpras
          LEFT JOIN area a ON s.ID_Area = a.ID_Area
          LEFT JOIN jenis_kegiatan jk ON s.ID_Kegiatan = jk.ID_Kegiatan
          LEFT JOIN kategori k ON s.ID_Kategori = k.ID_Kategori
          LEFT JOIN waktu w ON s.ID_Waktu = w.ID_Waktu
          WHERE s.ID_User = '$id_user'
          ORDER BY s.Tanggal DESC";

$result = $conn->query($query);
if (!$result) {
    echo "Query error: " . $conn->error;
    exit;
}
$sedangDiproses = [];
$selesai = [];

while ($row = $result->fetch_assoc()) {
    if ($row['status'] === 'Menunggu') {
        $sedangDiproses[] = $row;
    } else {
        $selesai[] = $row;
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesananku</title>
    <link rel="shortcut icon" type="image/x-icon" href="asset/tanpa judul.jpg">
  <link rel="stylesheet" href="pesanan.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="head1">
    <img src="asset/tanpa judul.jpg" alt="logo_ss" class="logoss">
    <div class="tulisan">
    <h1>SPORT SPACE KABUPATEN GRESIK</h1>
    <img src="asset/hias/garis header.png" >
    <h2>Website Penyewaan Sarana Olahraga Kabupaten Gresik</h2>
    </div>
    <img src="asset/logo_1.png" alt="logo_pemkab">
    </div>
    <div class="head2">
    <h1>PESANAN SAYA</h1>
    </div>
</header>
<main>
    <div class="nama-kembali">
<a href="beranda_user.php" class="btn-kembali">
<img src="asset/tombol/previous.png" />
</a>
    <h2>Kembali</h2>
</div>
    <div class="tab-buttons">
        <button class="tab-button active" onclick="showTab('proses')">Sedang Diproses</button>
        <button class="tab-button" onclick="showTab('selesai')">Selesai</button>
    </div>

    <div id="tab-proses">
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Sarana</th>
                <th>Area</th>
                <th>Kegiatan</th>
                <th>Kategori</th>
                <th>Waktu</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($sedangDiproses as $row): ?>
            <tr>
                <td><?= $row['Tanggal'] ?></td>
                <td><?= $row['Nama_Sarana'] ?></td>
                <td><?= $row['Area'] ?></td>
                <td><?= $row['Nama_Kegiatan'] ?></td>
                <td><?= $row['Kategori'] ?? '-' ?></td>
                <td><?= $row['Waktu'] ?></td>
                <td><?= $row['Jam_Mulai'] . ' - ' . $row['Jam_Selesai'] ?></td>
                <td class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
                <td>
                    <button class="btn-batal" data-id="<?= $row['ID_Sewa'] ?>">Batalkan</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="tab-selesai" style="display: none;">
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Sarana</th>
                <th>Area</th>
                <th>Kegiatan</th>
                <th>Kategori</th>
                <th>Waktu</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Catatan / Alasan</th>
                <th>Tanggal Konfirmasi</th>
            </tr>
            <?php foreach ($selesai as $row): ?>
            <tr>
                <td><?= $row['Tanggal'] ?></td>
                <td><?= $row['Nama_Sarana'] ?></td>
                <td><?= $row['Area'] ?></td>
                <td><?= $row['Nama_Kegiatan'] ?></td>
                <td><?= $row['Kategori'] ?? '-' ?></td>
                <td><?= $row['Waktu'] ?></td>
                <td><?= $row['Jam_Mulai'] . ' - ' . $row['Jam_Selesai'] ?></td>
                <td class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
                <td>
                    <?php
                    if ($row['status'] == 'Dikonfirmasi') {
                        echo $row['catatan_admin'];
                    } elseif ($row['status'] == 'Ditolak') {
                        echo $row['alasan_penolakan'];
                    } elseif ($row['status'] == 'Dibatalkan') {
                        echo $row['alasan_pembatalan'];
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td><?= $row['tanggal_konfirmasi'] ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Modal Pembatalan -->
    <div id="modal-overlay"></div>
<div id="modal-batal">
    <h3>Alasan Pembatalan</h3>
    <form id="form-batal" method="POST" action="batal_pesanan.php">
        <input type="hidden" name="id_sewa" id="id_sewa_batal">
        <textarea name="alasan" rows="4" required></textarea><br>
        <div style="text-align: right;">
            <button type="submit">Kirim</button>
            <button type="button" onclick="tutupModal()">Batal</button>
        </div>
    </form>
</div>



</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.btn-batal').click(function () {
    const id = $(this).data('id');
    $('#id_sewa_batal').val(id);
    $('#modal-overlay').fadeIn(150);
    $('#modal-batal').fadeIn(200);
});

function tutupModal() {
    $('#modal-batal').fadeOut(200);
    $('#modal-overlay').fadeOut(150);
}

function showTab(tab) {
    if (tab === 'proses') {
        $('#tab-proses').show();
        $('#tab-selesai').hide();
    } else {
        $('#tab-proses').hide();
        $('#tab-selesai').show();
    }

    $('.tab-button').removeClass('active');
    $(`.tab-button:contains("${tab === 'proses' ? 'Sedang Diproses' : 'Selesai'}")`).addClass('active');
}

<?php if (isset($_GET['batal']) && $_GET['batal'] === 'berhasil'): ?>

Swal.fire({
  icon: 'success',
  title: 'Pembatalan Berhasil',
  text: 'Pesanan kamu berhasil dibatalkan.',
  confirmButtonColor: '#3085d6',
  confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

</script>

</body>
</html>
