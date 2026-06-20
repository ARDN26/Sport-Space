<?php
include '../../config/db.php';

$id = $_GET['id'];
$sarana = mysqli_query($conn, "SELECT * FROM sarpras WHERE ID_Sarpras = $id");
$detail = mysqli_fetch_assoc($sarana);

$fotos = mysqli_query($conn, "SELECT * FROM galeri_sarana WHERE ID_sarpras = $id");

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
  <link rel="stylesheet" href="../../css/detail.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
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

<div class="konten">
<main>
<div class="nama-kembali">
<a href="beranda_user.php" class="btn-kembali">
<img src="../../asset/tombol/previous.png" />
</a>
<h1><?= $detail['Nama_Sarana'] ?></h1>
</div>
<div class="detail-container">
   <div class="foto-gallery-wrapper">
  <div class="foto-gallery">
    <?php while($foto = mysqli_fetch_assoc($fotos)): ?>
      <img src="../../asset/Foto Sarana/<?= $foto['foto2_sarana'] ?>" alt="Foto Sarana">
    <?php endwhile; ?>
  </div>
</div>
  <h2>Deskripsi</h2>
  <p><?= $detail['Deskripsi'] ?></p>
  <hr class="garis-putus-putus">

  <h2>Jam Operasional</h2>
  <p><?= $detail['Jam_Operasional'] ?></p>
  <hr class="garis-putus-putus">

  <h2>Fasilitas</h2>
  <ul>
        <?php
        $fasilitas = explode("\n", $detail['Fasilitas']);
        foreach ($fasilitas as $f) {
                echo '<li>' . htmlspecialchars(trim($f)) . '</li>';
                }
        ?>
  </ul>
  <hr class="garis-putus-putus">

  <h2>Aturan dan Kebijakan</h2>
  <p><?= $detail['Aturan_Dan_Kebijakan'] ?></p>
  <hr class="garis-putus-putus">

  <h2>Syarat Penyewaan</h2>
  <p><?= $detail['Syarat_Penyewaan'] ?></p>

</div>
</main>
<aside>
    <div class="lokasi">
        <h1>Lokasi</h1>
        <div class="garis-putus"></div>
        <h2><?= $detail['Alamat_Sarana'] ?></h2>
        <div class="map-wrapper">
            <iframe
                src="https://www.google.com/maps?q=<?= urlencode($detail['Nama_Sarana']) ?>&output=embed"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
    <div class="Harga">
        <h1>Harga Sewa</h1>
        <div class="garis-putus"></div>
        <h2><?= $detail['Harga'] ?></h2>
    </div>
    <a class="btn-sewa" href="sewa.php?sarpras=<?= $detail['ID_Sarpras'] ?>">Mulai Penyewaan</a>

</aside>
</div>
<script>
  function openContactModal() {
  document.getElementById("contactModal").style.display = "block";
}

function closeContactModal() {
  document.getElementById("contactModal").style.display = "none";
}
function openFaqModal() {
  document.getElementById("faqModal").style.display = "block";
}
function closeFaqModal() {
  document.getElementById("faqModal").style.display = "none";
}


window.onclick = function(event) {
  const contactModal = document.getElementById("contactModal");
  const faqModal = document.getElementById("faqModal");
  if (event.target == contactModal) closeContactModal();
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


</script>
</body>
</html>