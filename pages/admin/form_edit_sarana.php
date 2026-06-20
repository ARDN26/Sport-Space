<?php
include '../../config/db.php';
if (isset($_GET['id'])) {
    $id_sarpras = $_GET['id'];
} else {
    echo "ID sarana tidak ditemukan.";
    exit;
}
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sarpras WHERE ID_Sarpras = '$id_sarpras'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sarana</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../asset/tanpa judul.jpg">
  <link rel="stylesheet" href="../../css/editsarana.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

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
        <h1>EDIT SARANA</h1>
    </div>
</header>
<main>
    <div class="nama-kembali">
    <a href="edit_sarana.php" class="btn-kembali">
        <img src="../../asset/tombol/previous.png" />
    </a>
    <h2>Form Edit Sarana</h2>
    </div>
    <hr class="garis">
<form id="myForm" method="POST" action="../../handlers/proses_edit_sarana.php" enctype="multipart/form-data">
    <input type="hidden" name="id_sarpras" value="<?= $id_sarpras ?>">

    <!-- FORM 1 -->
    <div class="tab" style="display:block; position: relative;">
        <h3>Form 1: Detail Sarana</h3>
        <hr class="garis-tipis">
     <div class="form1">

      <div class="form-child">
        <label>Nama Sarana:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['Nama_Sarana']) ?>"><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi"><?= htmlspecialchars($data['Deskripsi']) ?></textarea><br>

        <label>Alamat:</label><br>
        <input type="text" name="alamat" value="<?= htmlspecialchars($data['Alamat_Sarana']) ?>"><br>

        <label>Jam Operasional:</label><br>
        <input type="text" name="jam_operasional" value="<?= htmlspecialchars($data['Jam_Operasional']) ?>"><br>

        <label>Jangkauan Harga:</label><br>
        <input type="text" name="jangkauan_harga" value="<?= htmlspecialchars($data['Harga']) ?>"><br>

        <label>Fasilitas:</label><br>
        <textarea name="fasilitas"><?= htmlspecialchars($data['Fasilitas']) ?></textarea><br>
      </div>

      <div class="form-child">
        <label>Fungsi:</label><br>
        <textarea name="fungsi"><?= htmlspecialchars($data['fungsi']) ?></textarea><br>

        <label>Aturan dan Kebijakan:</label><br>
        <textarea name="aturan"><?= htmlspecialchars($data['Aturan_Dan_Kebijakan']) ?></textarea><br>

        <label>Syarat Penyewaan:</label><br>
        <textarea name="syarat"><?= htmlspecialchars($data['Syarat_Penyewaan']) ?></textarea><br>
      </div>

      <div class="form-child">
        <label>Foto Display:</label><br>
        <img src="../../asset/Foto Sarana/<?= htmlspecialchars($data['Foto_Sarpras']) ?>"><br>
        <input type="file" name="foto_sarpras"><br>
        <label>Galeri Sarana:</label><br>

        <?php
        $galeri = mysqli_query($conn, "SELECT * FROM galeri_sarana WHERE ID_Sarpras = '$id_sarpras'");
        while ($g = mysqli_fetch_assoc($galeri)) {
        ?>
            <div class="galeri-item">
                <img src="../../asset/Foto Sarana/<?= htmlspecialchars($g['foto2_sarana']) ?>"><br>
                <label><input type="checkbox" name="hapus_galeri[]" value="<?= $g['ID_Gsarana'] ?>"> Hapus</label>
            </div>
        <?php } ?>

        <br><label>Tambah Foto Galeri (bisa lebih dari 1):</label><br>
        <input type="file" name="tambah_galeri[]" multiple><br>
   
      </div>

     </div>
        

        <button class="button-tab" type="button" onclick="nextTab()">Selanjutnya</button>
    </div>

    <!-- FORM 2 -->
    <div class="tab" style="display:none; position: relative;">
        <h3>Form 2: Area, Waktu, dan Kegiatan</h3>
        <hr class="garis-tipis">

        <?php
        $area = mysqli_query($conn, "SELECT * FROM area WHERE ID_Sarpras = '$id_sarpras'");
        while ($a = mysqli_fetch_assoc($area)) {
            $id_area = $a['ID_Area'];
            $area_nama = htmlspecialchars($a['Area']);
            $foto_area = htmlspecialchars($a['Foto_Area']);

            echo "<div class='area-container'>";
            echo "<label>Area:</label>";
            echo "<div class='label-btn'>";
            
            echo "<input type='hidden' name='id_area[]' value='{$id_area}'>";
            echo "<input type='text'  name='area[]' value='{$area_nama}'>";

            echo "<button class='btn-danger btn-konfirmasi-hapus' type='button' data-id='{$id_area}' data-name='hapus_area'>";
            echo "<img src='../../asset/tombol/bin.png' alt='Hapus' class='icon'>";
            echo "</button>";
            echo "</div>";

            echo "<label>Foto Area:</label>";
            echo "<img src='../../asset/Foto Area/{$foto_area}' alt='Foto Area' class='preview-img'>";
            echo "<input type='file' name='foto_area[]' >";

            $kegiatan = mysqli_query($conn, "SELECT * FROM jenis_kegiatan WHERE ID_Area = '$id_area'");
            while ($j = mysqli_fetch_assoc($kegiatan)) {
                $id_kegiatan = $j['ID_Kegiatan'];
                $nama_kegiatan = htmlspecialchars($j['Nama_Kegiatan']);

                echo "<hr class='garis-batas'>";
                echo "<label>Jenis Kegiatan:</label>";
                echo "<div class='label-btn'>";
                echo "<input type='hidden' name='id_kegiatan[]' value='{$id_kegiatan}'>";
                echo "<input type='text' name='nama_kegiatan[]' value='{$nama_kegiatan}'>";
                echo "<button class='btn-danger btn-konfirmasi-hapus' type='button' data-id='{$id_kegiatan}' data-name='hapus_kegiatan'>";
                echo "<img src='../../asset/tombol/bin.png' alt='Hapus' class='icon'>";
                echo "</button>";
                echo "</div>";

                // Kategori
                $kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE ID_Kegiatan = '$id_kegiatan'");
                echo "<label>Kategori:</label>";
                while ($k = mysqli_fetch_assoc($kategori)) {
                    $id_kategori = $k['ID_Kategori'] ?? '' ;
                    $kategori_nama = htmlspecialchars($k['Kategori'] ?? '');
                    echo "<div class='label-btn'>";
                    echo "<input type='hidden' name='id_kategori[]' value='{$id_kategori}'>";
                    echo "<input type='text'  name='kategori[]' value='{$kategori_nama}'>";
                    echo "<button class='btn-danger btn-konfirmasi-hapus' type='button' data-id='{$id_kategori}'data-name='hapus_kategori'>";
                    echo "<img src='../../asset/tombol/bin.png' alt='Hapus' class='icon'>";
                    echo "</button>";
                    echo "</div>";
                }
                echo "<button type='button' class='btn-primary btn-konfirmasi-tambah' data-id='{$id_kegiatan}' data-name='tambah_kategori' data-title='Tambah kategori?'>+ Tambah Kategori</button><br>";


               
              $waktu = mysqli_query($conn, "SELECT * FROM waktu WHERE ID_Kegiatan = '$id_kegiatan'");
                while ($w = mysqli_fetch_assoc($waktu)) {

                    $id_waktu = $w['ID_Waktu'];
                    $waktu_nama = htmlspecialchars($w['Waktu']);

                    echo "<label>Waktu:</label>";
                    echo "<div class='label-btn'>";
                    echo "<input type='hidden' name='id_waktu[]' value='{$id_waktu}'>";
                    echo "<input type='text'  name='waktu[]' value='{$waktu_nama}'>";
                    echo "<button class='btn-danger btn-konfirmasi-hapus' type='button' data-id='{$id_waktu}' data-name='hapus_waktu'>";
                    echo "<img src='../../asset/tombol/bin.png' alt='Hapus' class='icon'>";
                    echo "</button>";
                    echo "</div>";
                    // Jam (dengan Jam Mulai dan Jam Akhir)
                    $jam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jam WHERE ID_Waktu = '$id_waktu' LIMIT 1"));
                    $id_jam = $jam['ID_Jam'] ?? '';
                    $jam_mulai = htmlspecialchars($jam['Jam_Mulai'] ?? '');
                    $jam_akhir = htmlspecialchars($jam['Jam_Selesai'] ?? '');

                    echo "<label>Jam Mulai:</label>";
                    echo "<input type='hidden' name='id_jam[]' value='{$id_jam}'>";
                    echo "<input type='time' name='jam_mulai[]' value='{$jam_mulai}'>";

                    echo "<label>Jam Selesai:</label>";
                    echo "<input type='time' name='jam_akhir[]' value='{$jam_akhir}'>";

                }
                echo "<label>Waktu:</label>";
                echo "<button type='button' class='btn-primary btn-konfirmasi-tambah' data-id='{$id_kegiatan}' data-name='tambah_waktu' data-title='Tambah Waktu?'>+ Tambah Waktu</button>";

            }
            echo "<label>Kegiatan:</label>";
            echo "<button type='button' class='btn-secondary btn-konfirmasi-tambah' data-id='{$id_area}' data-name='tambah_kegiatan' data-title='Tambah Kegiatan?'>+ Tambah Kegiatan</button>";
            echo "</div>";
        }
        ?>
        <label>Area:</label><br>
        <button type='button' class='btn-secondary btn-konfirmasi-tambah' data-name='tambah_area' data-title='Tambah Area?'>+ Tambah Area</button><br><br>
        <div class="tab-navigation">
        <button type="button" class="button-tab" onclick="prevTab()">Sebelumnya</button>
        <button type="button" class="button-tab" onclick="nextTab()">Selanjutnya</button>
        </div>
    </div>


    <!-- FORM 3 -->
   <div class="tab" style="display:none; position: relative;">
    <h3>Form 3: Edit Harga</h3>
    <hr class="garis-tipis">
        <input type="hidden" name="id_sarpras" value="<?= $id_sarpras ?>">
        <table border="1">
            <tr>
                <th>Sarana</th>
                <th>Area</th>
                <th>Jenis Kegiatan</th>
                <th>Kategori</th>
                <th>Waktu</th>
                <th>Harga</th>
            </tr>

            <?php
            $harga = mysqli_query($conn, "
                SELECT h.*, s.Nama_Sarana, a.Area, k.Kategori, j.Nama_Kegiatan, w.Waktu
                FROM harga h
                JOIN sarpras s ON h.ID_Sarpras = s.ID_Sarpras
                JOIN area a ON h.ID_Area = a.ID_Area
                LEFT JOIN kategori k ON h.ID_Kategori = k.ID_Kategori
                JOIN jenis_kegiatan j ON h.ID_Kegiatan = j.ID_Kegiatan
                JOIN waktu w ON h.ID_Waktu = w.ID_Waktu
                WHERE h.ID_Sarpras = '$id_sarpras'
            ");
            while ($h = mysqli_fetch_assoc($harga)) {
            ?>
            <tr>
                <td><?= htmlspecialchars($h['Nama_Sarana']) ?></td>
                <td><?= htmlspecialchars($h['Area']) ?></td>
                <td><?= htmlspecialchars($h['Nama_Kegiatan']) ?></td>
                <td><?= htmlspecialchars($h['Kategori'] ?? '-') ?></td>
                <td><?= htmlspecialchars($h['Waktu']) ?></td>
                <td>
                    <input type="hidden" name="id_harga[]" value="<?= $h['ID_Harga'] ?>">
                    <input type="number" name="harga[]"  value="<?= $h['Harga'] ?>" required>
                    <button class='btn-hapus btn-konfirmasi-hapus' type='button' data-id='{$id_harga}' data-name='hapus_harga'>Hapus</button>
                </td>
            </tr>
            <?php } ?>

            <!-- Tambah Harga Baru -->
            <tr>
              <td>  <?= htmlspecialchars($data['Nama_Sarana']) ?>
                    <input type="hidden" name="id_sarpras_baru" value="<?= $id_sarpras ?>">
            </td>
                <td>
                    <select id="area-select" name="id_areaa" >
                        <option value="">Pilih</option>
                        <?php
                        $area = mysqli_query($conn, "SELECT * FROM area WHERE ID_Sarpras = '$id_sarpras'");
                        while ($a = mysqli_fetch_assoc($area)) {
                            echo "<option value='{$a['ID_Area']}'>{$a['Area']}</option>";
                        }
                        ?>
                    </select>
                </td>

                <td>
                    <select id="kegiatan-select" name="id_kegiatann" >
                        <option value="">Pilih</option>
                    </select>
                </td>

                <td>
                    <select id="kategori-select" name="id_kategorii" >
                        <option value="">Pilih</option>
                    </select>
                </td>
                
                <td>
                    <select id="waktu-select" name="id_waktuu" >
                        <option value="">Pilih</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="harga_baru" placeholder="Harga" >
                    <button type='button' class='btn-secondary btn-konfirmasi-tambah' data-name='tambah_harga' data-title='Tambah Harga?'>Tambah</button>
                </td>
            </tr>
        </table>

        <div class="tab-navigation">
        <button class="button-tab" type="button" onclick="prevTab()">Sebelumnya</button>
        <button class="button-tab" type="submit" name="submit_all">Simpan Semua</button>
        </div>

</div>

</form>
</main>
<script>

document.getElementById('area-select').addEventListener('change', function () {
    const areaId = this.value;
    fetch('../../api/get_kegiatan_admin.php?id_area=' + areaId)
        .then(res => res.json())
        .then(data => {
            const kegiatan = document.getElementById('kegiatan-select');
            kegiatan.innerHTML = '<option value="">Pilih</option>';
            data.forEach(k => {
                kegiatan.innerHTML += `<option value="${k.ID_Kegiatan}">${k.Nama_Kegiatan}</option>`;
            });
        });
});

document.getElementById('kegiatan-select').addEventListener('change', function () {
    const kegiatanId = this.value;
    fetch('../../api/get_kategori_waktu_admin.php?id_kegiatan=' + kegiatanId)
        .then(res => res.json())
        .then(data => {
            const waktu = document.getElementById('waktu-select');
            const kategori = document.getElementById('kategori-select');

            waktu.innerHTML = '<option value="">Pilih</option>';
            kategori.innerHTML = '<option value="">Pilih</option>';

            data.waktu.forEach(w => {
                waktu.innerHTML += `<option value="${w.ID_Waktu}">${w.Waktu}</option>`;
            });
            data.kategori.forEach(k => {
                kategori.innerHTML += `<option value="${k.ID_Kategori}">${k.Kategori}</option>`;
            });
        });
});

let currentTab = 0;
function showTab(index) {
    const tabs = document.querySelectorAll('.tab');
    if (index >= 0 && index < tabs.length) {
        tabs.forEach((tab, i) => {
            tab.style.display = i === index ? "block" : "none";
        });
        currentTab = index;
    }
}
function nextTab() {
    showTab(currentTab + 1);
}
function prevTab() {
    showTab(currentTab - 1);
}
</script>



<script>
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get('status');
  const msg = urlParams.get('msg');

  if (status === 'success' && msg) {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: decodeURIComponent(msg),
      confirmButtonColor: '#3085d6'
    }).then(() => {
      // Hapus parameter dari URL setelah alert ditutup
      const url = new URL(window.location);
      url.searchParams.delete('status');
      url.searchParams.delete('msg');
      window.history.replaceState({}, document.title, url);
    });
  }

document.querySelectorAll('.btn-konfirmasi-hapus').forEach(function(button) {
    button.addEventListener('click', function () {
        const namaField = this.dataset.name; // contoh: 'hapus_area'
        const idValue = this.dataset.id;

        Swal.fire({
            title: 'Hapus data ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = namaField;
                input.value = idValue;

                const form = document.getElementById('myForm'); // pastikan ID form sesuai
                form.appendChild(input);
                form.submit();
            }
        });
    });
});

document.querySelectorAll('.btn-konfirmasi-tambah').forEach(function(button) {
    button.addEventListener('click', function () {
        const namaField = this.dataset.name; // misal: tambah_kegiatan
        const idValue = this.dataset.id;
        const title = this.dataset.title || 'Tambah data ini?';

        Swal.fire({
            title: title,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tambah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = namaField;
                input.value = idValue;

                const form = document.getElementById('myForm'); // pastikan form punya ID ini
                form.appendChild(input);
                form.submit();
            }
        });
    });
});

</script>

</body>
</html>