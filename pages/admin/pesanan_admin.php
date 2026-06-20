<?php
session_start();
include '../../config/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSql = $search !== '' ? "WHERE u.Nama_User LIKE '%" . $conn->real_escape_string($search) . "%'" : '';

$query = "SELECT s.*, u.Nama_User, sp.Nama_Sarana, a.Area, jk.Nama_Kegiatan, k.Kategori, w.Waktu,  s.Jam_Mulai, s.Jam_Selesai
          FROM sewa s
          JOIN users u ON s.ID_User = u.ID_User
          LEFT JOIN sarpras sp ON s.ID_Sarpras = sp.ID_Sarpras
          LEFT JOIN area a ON s.ID_Area = a.ID_Area
          LEFT JOIN jenis_kegiatan jk ON s.ID_Kegiatan = jk.ID_Kegiatan
          LEFT JOIN kategori k ON s.ID_Kategori = k.ID_Kategori
          LEFT JOIN waktu w ON s.ID_Waktu = w.ID_Waktu
          $searchSql
          ORDER BY s.Tanggal DESC";

$result = $conn->query($query);
if (!$result) {
    echo "Query error: " . $conn->error;
    exit;
}

$pesananDiproses = [];
$pesananSelesai = [];
while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'Menunggu') {
        $pesananDiproses[] = $row;
    } else {
        $pesananSelesai[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Sarana - Admin</title>
    <link rel="stylesheet" href="../../css/pesanan.css">
    <link rel="shortcut icon" href="../../asset/tanpa judul.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="head1">
        <img src="../../asset/tanpa judul.jpg" alt="logo_ss" class="logoss">
        <div class="tulisan">
            <h1>SPORT SPACE KABUPATEN GRESIK</h1>
            <img src="../../asset/hias/garis header.png">
            <h2>Website Penyewaan Sarana Olahraga Kabupaten Gresik</h2>
        </div>
        <img src="../../asset/logo_1.png" alt="logo_pemkab">
    </div>
    <div class="head2">
        <h1>DAFTAR PESANAN SARANA</h1>
    </div>
</header>

<main>
<div class="nama-kembali">
    <a href="beranda_admin.php" class="btn-kembali">
        <img src="../../asset/tombol/previous.png" />
    </a>
    <h2>Kembali</h2>
</div>

<div class="tab-buttons">
    <button class="tab-button active" onclick="showTab('proses')">Sedang Diproses</button>
    <button class="tab-button" onclick="showTab('selesai')">Selesai</button>
</div>

<form method="GET" class="search-form">
    <input type="text" name="search" placeholder="Cari berdasarkan nama user..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit">Cari</button>
</form>


<div id="proses" class="tab-content" style="display:block;">
    <?php if (count($pesananDiproses) > 0): ?>
    <table>
        <tr>
            <th>User</th><th>Tanggal</th><th>Sarana</th><th>Area</th><th>Kegiatan</th><th>Kategori</th><th>Waktu</th><th>Jam</th><th>Surat</th><th>Bukti</th><th>Aksi</th>
        </tr>
        <?php foreach ($pesananDiproses as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['Nama_User']) ?></td>
            <td><?= htmlspecialchars($row['Tanggal']) ?></td>
            <td><?= htmlspecialchars($row['Nama_Sarana']) ?></td>
            <td><?= htmlspecialchars($row['Area']) ?></td>
            <td><?= htmlspecialchars($row['Nama_Kegiatan']) ?></td>
            <td><?= htmlspecialchars($row['Kategori'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['Waktu']) ?></td>
            <td><?= $row['Jam_Mulai'] . ' - ' . $row['Jam_Selesai'] ?></td>
            <td>
                <?php if (!empty($row['Surat_Permohonan']) && file_exists('../../uploads/surat_permohonan/'.$row['Surat_Permohonan'])): ?>
                    <a href="../../uploads/surat_permohonan/<?= urlencode($row['Surat_Permohonan']) ?>" target="_blank" download>Download</a>
                <?php else: ?> - <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['Bukti_Pembayaran']) && file_exists('../../uploads/bukti_pembayaran/'.$row['Bukti_Pembayaran'])): ?>
                    <a href="../../uploads/bukti_pembayaran/<?= urlencode($row['Bukti_Pembayaran']) ?>" target="_blank" download>Download</a>
                <?php else: ?> - <?php endif; ?>
            </td>
            <td>
                <button class="btn-konfirmasi" onclick="openModal('konfirmasi', <?= $row['ID_Sewa'] ?>)">Konfirmasi</button>
                <button class="btn-tolak" onclick="openModal('tolak', <?= $row['ID_Sewa'] ?>)">Tolak</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Tidak ada pesanan yang sedang diproses.</p>
    <?php endif; ?>
</div>

<div id="selesai" class="tab-content" style="display:none;">
    <?php if (count($pesananSelesai) > 0): ?>
    <table>
        <tr>
            <th>User</th><th>Tanggal</th><th>Sarana</th><th>Area</th><th>Kegiatan</th><th>Kategori</th><th>Waktu</th><th>Jam</th><th>Status</th><th>Catatan</th>
        </tr>
        <?php foreach ($pesananSelesai as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['Nama_User']) ?></td>
            <td><?= htmlspecialchars($row['Tanggal']) ?></td>
            <td><?= htmlspecialchars($row['Nama_Sarana']) ?></td>
            <td><?= htmlspecialchars($row['Area']) ?></td>
            <td><?= htmlspecialchars($row['Nama_Kegiatan']) ?></td>
            <td><?= htmlspecialchars($row['Kategori'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['Waktu']) ?></td>
            <td><?= $row['Jam_Mulai'] . ' - ' . $row['Jam_Selesai'] ?></td>
            <td class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
            <td>
                <?php
                if ($row['status'] == 'Dikonfirmasi') {
                    echo nl2br(htmlspecialchars($row['catatan_admin']));
                } elseif ($row['status'] == 'Ditolak') {
                    echo nl2br(htmlspecialchars($row['alasan_penolakan']));
                }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Tidak ada pesanan yang selesai.</p>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form method="POST" action="../../handlers/proses_konfirmasi.php">
            <input type="hidden" name="id_sewa" id="modal-id-sewa">
            <div id="modal-konfirmasi" style="display: none;">
                <h3>Konfirmasi Pesanan</h3>
                <textarea name="catatan_admin" placeholder="Catatan admin (opsional)"></textarea>
                <button name="aksi" value="konfirmasi" class="submit-btn">Konfirmasi</button>
            </div>
            <div id="modal-tolak" style="display: none;">
                <h3>Tolak Pesanan</h3>
                <textarea name="alasan_penolakan" placeholder="Alasan penolakan (wajib)"></textarea>
                <button name="aksi" value="tolak" class="submit-btn reject">Tolak</button>
            </div>
        </form>
    </div>
</div>

</main>

<script>
function showTab(tabName) {
    const contents = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-button');

    contents.forEach(content => {
        content.style.display = content.id === tabName ? 'block' : 'none';
    });

    buttons.forEach(button => {
        button.classList.remove('active');
    });

    const clickedButton = Array.from(buttons).find(btn => btn.textContent.includes(tabName === 'proses' ? 'Sedang' : 'Selesai'));
    if (clickedButton) {
        clickedButton.classList.add('active');
    }
}

function openModal(type, idSewa) {
    document.getElementById('modal').style.display = 'block';
    document.getElementById('modal-id-sewa').value = idSewa;
    document.getElementById('modal-konfirmasi').style.display = (type === 'konfirmasi') ? 'block' : 'none';
    document.getElementById('modal-tolak').style.display = (type === 'tolak') ? 'block' : 'none';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}
</script>
<?php if (isset($_GET['status']) && $_GET['status'] === 'konfirmasi_sukses'): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Pesanan Dikonfirmasi!',
  text: 'Pesanan berhasil dikonfirmasi.',
  confirmButtonColor: '#3A4E92'
});
</script>
<?php elseif (isset($_GET['status']) && $_GET['status'] === 'penolakan_sukses'): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Pesanan Ditolak!',
  text: 'Pesanan berhasil ditolak.',
  confirmButtonColor: '#3A4E92'
});
</script>
<?php endif; ?>

</body>
</html>
