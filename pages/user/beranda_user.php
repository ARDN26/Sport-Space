<?php
require '../../config/middleware_user.php';
require '../../config/db.php'; 
if ($_SESSION['role'] !== 'user') {
    die("Akses ditolak: Bukan user");
}

$id_user = $_SESSION['ID_User'];
$sql_user = "SELECT Nama_User, email, No_Telepon FROM users WHERE ID_User = ?";
$stmt = $conn->prepare($sql_user);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$stmt->bind_result($nama_user, $email_user, $no_hp_user);
$stmt->fetch();
$stmt->close();

$query = "SELECT * FROM sarpras";
$result = mysqli_query($conn, $query);

$faq_query = "SELECT * FROM faq_user";
$faq_result = mysqli_query($conn, $faq_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../asset/tanpa judul.jpg">
  <link rel="stylesheet" href="../../css/user.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <button class="circle-button"  onclick="openContactModal()">
        <img src="../../asset/tombol/telephone.png" alt="tombol-kontak" />
        </button>
        </div>
        <h1>SARANA OLAHRAGA KABUPATEN GRESIK</h1>
        <div class="right-button">
        <button class="circle-button" onclick="openProfileModal()">
        <img src="../../asset/tombol/user.png" alt="tombol-profile" />
        </button>
        <a href="pesanan_user.php" class="pesanan-button">
        <img src="../../asset/tombol/clipboard.png" alt="Icon Pesanan" />
        <h2>Pesananku</h2>
        </a>
        
        </div>
    </div>
</header>

<div id="contactModal" class="modal">
  <div class="modal-content-kontak">
    <span class="close" onclick="closeContactModal()">&times;</span>
    <h2>KONTAK ADMIN</h2>
    <p>Email Admin : admin@gmail.com</p>
    <p>Nomor Admin : +62 812-3456-7890</p>
    <p>Hubungi via WhatsApp:</p>
    <a href="https://wa.me/6281234567890" target="_blank" class="wa-button">Chat Admin di WhatsApp</a>
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


<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    <h2>PROFIL SAYA</h2>

    <div id="profileDisplay">
      <p>Nama Pengguna : <br> <span><?= htmlspecialchars($nama_user); ?></span></p>
      <p>Email: <br> <span><?= htmlspecialchars($email_user); ?></span></p>
      <p>No. HP:<br> <span><?= htmlspecialchars($no_hp_user); ?></span></p>
      <button onclick="toggleEditProfile()">Edit Profil</button>
      <a href="../../handlers/logout.php" class="logout-button">Keluar</a>
    </div>

    <form id="profileForm" action="../../handlers/update_profile.php" method="POST" style="display:none;">
      <input type="hidden" name="id_user" value="<?= $id_user; ?>">

      <label>Nama:</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($nama_user); ?>" required>

      <label>Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($email_user); ?>" required>

      <label>No. HP:</label>
      <input type="text" name="no_hp" value="<?= htmlspecialchars($no_hp_user); ?>" required>
      <div class="tmbl-edit">
      <button type="submit">Simpan </button>
      <button type="button" onclick="cancelEditProfile()">Batal</button>
      </div>
    </form>
  </div>
</div>

<main>
    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <img src="../../asset/Foto Sarana/<?= htmlspecialchars($row['Foto_Sarpras']) ?>" alt="<?= htmlspecialchars($row['Nama_Sarana']) ?>">
              
                    <h3><?= htmlspecialchars($row['Nama_Sarana']) ?></h3>
                    <hr class="Garis">
                    <h4>Harga Sewa : <?= htmlspecialchars($row['Harga']) ?></h4>
                    <p>Disewakan Untuk :</p>
                    <ul>
                        <?php
                        $fungsi = explode("\n", $row['fungsi']);
                        foreach ($fungsi as $f) {
                            echo '<li>' . htmlspecialchars(trim($f)) . '</li>';
                        }
                        ?>
                    </ul>
                    <a class="btn-selengkapnya" href="detail.php?id=<?= $row['ID_Sarpras']?>">Mulai Sewa</a>
                
            </div>
        <?php endwhile; ?>
    </div>
</main>
<script>
function openContactModal() {
  document.getElementById("contactModal").style.display = "block";
}
function closeContactModal() {
  document.getElementById("contactModal").style.display = "none";
}

function openProfileModal() {
  document.getElementById("profileModal").style.display = "block";
}
function closeProfileModal() {
  document.getElementById("profileModal").style.display = "none";
  cancelEditProfile();
}
function toggleEditProfile() {
  document.getElementById("profileDisplay").style.display = "none";
  document.getElementById("profileForm").style.display = "block";
}
function cancelEditProfile() {
  document.getElementById("profileDisplay").style.display = "block";
  document.getElementById("profileForm").style.display = "none";
}
function openFaqModal() {
  document.getElementById("faqModal").style.display = "block";
}
function closeFaqModal() {
  document.getElementById("faqModal").style.display = "none";
}

window.onclick = function(event) {
  const profileModal = document.getElementById("profileModal");
  const contactModal = document.getElementById("contactModal");
  const faqModal = document.getElementById("faqModal");
  if (event.target == profileModal) closeProfileModal();
  if (event.target == contactModal) closeContactModal();
  if (event.target == faqModal) closeFaqModal();
};

// Accordion behavior
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


</script>
<?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: 'Profil berhasil diperbarui.',
  confirmButtonColor: '#3A4E92'
});
</script>
<?php elseif (isset($_GET['update']) && $_GET['update'] === 'failed'): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: 'Terjadi kesalahan saat memperbarui profil.',
  confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>

</body>
</html>
