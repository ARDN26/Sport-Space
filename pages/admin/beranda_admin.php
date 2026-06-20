<?php
require '../../config/middleware.php';
require '../../config/db.php'; 

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak: Bukan admin");
}

$id_admin = $_SESSION['ID_Admin']; 
$query = $conn->prepare("SELECT Nama_Admin, email, No_Telepon, No_Rekening FROM admin WHERE ID_Admin = ?");
$query->bind_param("i", $id_admin);
$query->execute();
$query->bind_result($nama_admin, $email_admin, $no_hp_admin, $no_rekening);
$query->fetch();
$query->close();

$faq_query = "SELECT * FROM faq_admin";
$faq_result = mysqli_query($conn, $faq_query);

function getPesananDikonfirmasi() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM sewa WHERE status = 'Dikonfirmasi'");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function getPesananTolak() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM sewa WHERE status = 'Ditolak'");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function getPesananBatal() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM sewa WHERE status = 'Dibatalkan'");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}


function getPesananMenunggu() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM sewa WHERE status = 'Menunggu'");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function getTotalPesanan() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM sewa");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

$filterTahun = $_GET['tahun'] ?? date('Y');
$filterBulan = $_GET['bulan'] ?? date('m');

// Ambil semua sarana terlebih dahulu
$querySarpras = mysqli_query($conn, "SELECT id_sarpras, Nama_Sarana FROM sarpras");
$sarprasList = [];
$jumlahPesananPerSarana = [];

while ($row = mysqli_fetch_assoc($querySarpras)) {
    $id_sarpras = $row['id_sarpras'];
    $nama_sarana = $row['Nama_Sarana'];
    $sarprasList[$id_sarpras] = $nama_sarana;
    $jumlahPesananPerSarana[$nama_sarana] = 0; 
}


$queryFiltered = mysqli_query($conn, "
    SELECT sewa.id_sarpras, COUNT(sewa.id_sarpras) AS jumlah
    FROM sewa
    WHERE YEAR(sewa.tanggal) = '$filterTahun' AND MONTH(sewa.tanggal) = '$filterBulan'
    GROUP BY sewa.id_sarpras
");

while ($row = mysqli_fetch_assoc($queryFiltered)) {
    $id_sarpras = $row['id_sarpras'];
    $jumlah = $row['jumlah'];
    if (isset($sarprasList[$id_sarpras])) {
        $nama = $sarprasList[$id_sarpras];
        $jumlahPesananPerSarana[$nama] = $jumlah;
    }
}


$filteredLabels = array_keys($jumlahPesananPerSarana);
$filteredData = array_values($jumlahPesananPerSarana);

$querySarpras = mysqli_query($conn, "SELECT id_sarpras, Nama_Sarana FROM sarpras");
while ($row = mysqli_fetch_assoc($querySarpras)) {
    $sarpras[$row['id_sarpras']] = [
        'nama' => $row['Nama_Sarana'],
        'Menunggu' => 0,
        'Dikonfirmasi' => 0,
        'Ditolak' => 0,
        'Dibatalkan' => 0
    ];
}


$queryStatus = mysqli_query($conn, "
    SELECT id_sarpras, status, COUNT(*) as jumlah 
    FROM sewa 
    GROUP BY id_sarpras, status
");

while ($row = mysqli_fetch_assoc($queryStatus)) {
    $id = $row['id_sarpras'];
    $status = $row['status'];
    $sarpras[$id][$status] = $row['jumlah'];
}


$labels = [];
$dataMenunggu = [];
$dataDikonfirmasi = [];
$dataDitolak = [];
$dataDibatalkan = [];

foreach ($sarpras as $data) {
    $labels[] = $data['nama'];
    $dataMenunggu[] = $data['Menunggu'];
    $dataDikonfirmasi[] = $data['Dikonfirmasi'];
    $dataDitolak[] = $data['Ditolak'];
    $dataDibatalkan[] = $data['Dibatalkan'];
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../asset/tanpa judul.jpg">
  <link rel="stylesheet" href="../../css/admin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
 
<body>
<header>
    <div class="head1">
    <img src="../../asset/tanpa judul.jpg" alt="logo_ss" class="logoss">
    <div class="tulisan">
    <h1>SPORT SPACE KABUPATEN GRESIK</h1>
    <img src="../../asset/hias/garis header.png" >
    <h2>Website Penyewaan Sarana Olahraga Kabupaten Gresik</h2>
    </div>
    <img src="../../asset/logo_1.png" alt="logo_pemkab">
    </div>
    <div class="head2">
        <div class="left-button">
        <button class="circle-button" onclick="openFaqModal()">
        <img src="../../asset/tombol/customer-service.png" alt="tombol-faq" />
        </button>
        </div>
        <h1>BERANDA ADMIN</h1>
        <div class="right-button">
        <button class="circle-button" onclick="openProfileModal()">
        <img src="../../asset/tombol/user.png" alt="tombol-profile" />
        </button>
        
        </div>
    </div>
</header>

<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    <h2>PROFIL SAYA</h2>
  <div id="profileDisplay">
    <p>Nama Admin: <br> <span><?= htmlspecialchars($nama_admin); ?></span></p>
    <p>Email: <br> <span><?= htmlspecialchars($email_admin); ?></span></p>
    <p>No. HP:<br> <span><?= htmlspecialchars($no_hp_admin); ?></span></p>
    <p>No. Rekening:<br> <span><?= htmlspecialchars($no_rekening); ?></span></p>
    <a href="../../handlers/logout.php" class="logout-button">Keluar</a>
  </div>
 </div>
</div>

<div id="faqModal" class="modal">
  <div class="modal-content-faq">
    <span class="close" onclick="closeFaqModal()">&times;</span>
    <h2>PERTANYAAN UMUM (FAQ)</h2>
    <div class="faq-list">
      <?php while($faq = mysqli_fetch_assoc($faq_result)): ?>
        <div class="faq-item">
          <button class="accordion"><?= htmlspecialchars($faq['pertanyaan']) ?></button>
          <div class="panel">
            <p><?= nl2br(htmlspecialchars($faq['jawaban'])) ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

    <main>
  <section class="dashboard-admin">
    <h2>Selamat datang kembali Admin</h2>
    <hr class="garis">
    <div class="menu-grid">
      <a href="edit_sarana.php" class="menu-item">Edit Sarana
      </a>
      <a href="pesanan_admin.php" class="menu-item">Pesanan
      </a>
    </div>
  </section>

  <section class="dashboard-admin">
  <h2>Statistik Penyewaan</h2>
  <div class="stat-grid">
    <div class="stat-box">
      <h3>Total Pesanan</h3>
      <p><?php echo getTotalPesanan(); ?></p>
    </div>
    <div class="stat-box">
      <h3>Pesanan Dikonfirmasi</h3>
      <p><?php echo getPesananDikonfirmasi(); ?></p>
    </div>
    <div class="stat-box">
      <h3>Pesanan Menunggu</h3>
      <p><?php echo getPesananMenunggu(); ?></p>
    </div>
    <div class="stat-box">
      <h3>Pesanan Ditolak</h3>
      <p><?php echo getPesananTolak(); ?></p>
    </div>
    <div class="stat-box">
      <h3>Pesanan Dibatalkan</h3>
      <p><?php echo getPesananBatal(); ?></p>
    </div>
  </div>
   
  <h2>Grafik Penyewaan</h2>
  <section class="dashboard-grafik">
  <div class="chart-container">
  <h3>Grafik Pesanan Sarana berdasarakan Status</h3>
  <canvas id="pesananChart"></canvas>
 </div>
 <div class="chart-container">
    <h3>Grafik Pesanan Sarana per bulan ke-<?php echo $filterBulan; ?> Tahun <?php echo $filterTahun; ?></h3>
  
  <div class="filter-wrapper">
  <form method="GET" class="filter-form">
  <label for="tahun">Tahun:</label>
  <select name="tahun" id="tahun">
    <?php
      $currentYear = date('Y');
      for ($i = $currentYear; $i >= 2020; $i--) {
          $selected = isset($_GET['tahun']) && $_GET['tahun'] == $i ? "selected" : "";
          echo "<option value='$i' $selected>$i</option>";
      }
    ?>
  </select>

  <label for="bulan">Bulan:</label>
  <select name="bulan" id="bulan">
    <?php
      for ($i = 1; $i <= 12; $i++) {
          $bulanNama = date('F', mktime(0, 0, 0, $i, 10));
          $selected = isset($_GET['bulan']) && $_GET['bulan'] == $i ? "selected" : "";
          echo "<option value='$i' $selected>$bulanNama</option>";
      }
    ?>
  </select>

  <button type="submit">Tampilkan</button>
  </form>
    </div>

  <canvas id="filteredChart"></canvas>
</div>
</section>

</section>
</main>
<script>
function openProfileModal() {
  document.getElementById("profileModal").style.display = "block";
}
function closeProfileModal() {
  document.getElementById("profileModal").style.display = "none";
  cancelEditProfile();
}
function openFaqModal() {
  document.getElementById("faqModal").style.display = "block";
}
function closeFaqModal() {
  document.getElementById("faqModal").style.display = "none";
}
window.onclick = function(event) {
  const profileModal = document.getElementById("profileModal");
  const faqModal = document.getElementById("faqModal");
  if (event.target == profileModal) closeProfileModal();
  if (event.target == faqModal) closeFaqModal();
};

document.addEventListener("DOMContentLoaded", function() {
  const accordions = document.querySelectorAll(".accordion");
  accordions.forEach((acc) => {
    acc.addEventListener("click", function() {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      panel.style.display = panel.style.display === "block" ? "none" : "block";
    });
  });
});

const ctx = document.getElementById('pesananChart').getContext('2d');
const chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($labels); ?>,
    datasets: [
      {
        label: 'Menunggu',
        data: <?php echo json_encode($dataMenunggu); ?>,
        backgroundColor: 'rgba(255, 206, 86, 0.6)',
        borderColor: 'rgba(255, 206, 86, 1)',
        borderWidth: 1
      },
      {
        label: 'Dikonfirmasi',
        data: <?php echo json_encode($dataDikonfirmasi); ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      },
      {
        label: 'Ditolak',
        data: <?php echo json_encode($dataDitolak); ?>,
        backgroundColor: 'rgba(255, 99, 132, 0.6)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      },
      {
        label: 'Dibatalkan',
        data: <?php echo json_encode($dataDibatalkan); ?>,
        backgroundColor: 'rgba(148, 147, 147, 0.25)',
        borderColor: 'rgba(148, 147, 147, 0.6)',
        borderWidth: 1
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
    title: {
    display: false
    }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1
        }
      }
    }
  }
});
</script>
<script>
const ctxFiltered = document.getElementById('filteredChart').getContext('2d');
new Chart(ctxFiltered, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($filteredLabels); ?>,
    datasets: [{
      label: 'Jumlah Pesanan',
      data: <?php echo json_encode($filteredData); ?>,
      backgroundColor: 'rgba(54, 162, 235, 0.6)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
    title: {
    display: false
    }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1
        }
      }
    }
  }
});
</script>

</body>
</html>
