<?php
require 'db.php'; 

$query = "SELECT * FROM sarpras";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sarana</title>
    <link rel="shortcut icon" type="image/x-icon" href="asset/tanpa judul.jpg">
  <link rel="stylesheet" href="editsarana.css">
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
        <h1>EDIT SARANA</h1>
    </div>
</header>
<main>
    <div class="nama-kembali">
    <a href="beranda_admin.php" class="btn-kembali">
        <img src="asset/tombol/previous.png" />
    </a>
    <h2>Edit Sarana</h2>
    </div>
    <hr class="garis">
    <div id="openModal" class="btn-tambah">
        <img src="asset/tombol/add.png"> 
        <a >Tambah Sarana</a>
    </div>

    <!-- MODAL TAMBAH SARANA -->
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Tambah Sarana</h3>
            <form id="formTambahSarana" action="tambah_sarana.php" method="POST" enctype="multipart/form-data">
                <label>Nama Sarana:</label><br>
                <input type="text" name="nama_sarana" required><br>

                <label>Foto Sarana:</label><br>
                <input type="file" name="foto_sarana" accept="image/*" required><br>

                <button class=btn-secondary type="submit" name="submit">Simpan</button>
            </form>
        </div>
    </div>


<div class="container-sarana">
<?php while($row = mysqli_fetch_assoc($result)) : ?>
    <div class="card-sarana">
        <img src="asset/Foto Sarana/<?= $row['Foto_Sarpras'] ?>" alt="<?= $row['Nama_Sarana'] ?>" class="foto-sarana">
        <div class = "nama-edit">
        <h3><?= $row['Nama_Sarana'] ?></h3>
        <div style="display: flex; gap: 5px;">
        <a href="form_edit_sarana.php?id=<?= $row['ID_Sarpras'] ?>" class="btn-edit">Edit</a>
        <button class="btn-hapus" data-id="<?= $row['ID_Sarpras'] ?>">Hapus</button>
        </div>
        </div>
    </div>
<?php endwhile; ?>
</div>
</main>

<script>
const modal = document.getElementById("modalTambah");
const btn = document.getElementById("openModal");
const span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-hapus').forEach(function (button) {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      console.log("Tombol diklik, ID:", id); // Tambahkan debug ini

      Swal.fire({
        title: 'Yakin ingin menghapus sarana ini?',
        text: "Tindakan ini tidak bisa dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed && id) {
          window.location.href = 'hapus_sarana.php?id=' + encodeURIComponent(id);
        }
      });
    });
  });
});



document.getElementById('formTambahSarana').addEventListener('submit', function(e) {
    e.preventDefault(); // Cegah reload form

    const form = e.target;
    const formData = new FormData(form);

    fetch('tambah_sarana.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text()) // bisa ubah ke res.json jika server kirim JSON
    .then(response => {
        Swal.fire({
            icon: 'success',
            title: 'Sarana berhasil disimpan.',
            text: 'Lanjutkan edit sarana untuk memperbarui informasinya!',
            timer: 3000,
            showConfirmButton: false
        });

        form.reset(); // reset form
        // Tutup modal jika perlu:
        document.getElementById('modalTambah').style.display = 'none';

        // Opsional: reload sarana
        setTimeout(() => location.reload(), 3000);
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data.'
        });
    });
});
</script>
</body>
</html>