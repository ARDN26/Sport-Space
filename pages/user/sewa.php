<?php
session_start();
if (!isset($_SESSION['ID_User'])) {
    header("Location: ../auth/login.php");
    exit;
}
$id_user = $_SESSION['ID_User'];
include '../../config/db.php';

if (!isset($_GET['sarpras'])) {
    echo "Sarana tidak ditemukan.";
    exit;
}
$id_sarpras = $_GET['sarpras'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sewa</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../asset/tanpa judul.jpg">
  <link rel="stylesheet" href="../../css/sewa.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <h1>FORMULIR PENYEWAAN SARANA</h1>
    </div>
</header>
<main>
    <form method="POST" action="../../handlers/simpan_penyewaan.php" enctype="multipart/form-data">
        <input type="hidden" name="ID_User" value="<?= $id_user ?>">
        <input type="hidden" name="ID_Sarpras" value="<?= $id_sarpras ?>">

        
        <div class="step active" id="step-1">

            <div class="area">
            <label>Area:</label>
            <div id="area-container" class="area-grid"></div>
            <input type="hidden" name="ID_Area" id="area-terpilih" required>
            </div>

        <div class="mid" >
            <label>Jenis Kegiatan:</label>
            <select id="jenis_kegiatan" name="ID_Kegiatan" required></select>

            <label>Kategori:</label>
            <select id="kategori" name="ID_Kategori"></select>

            <label>Waktu:</label>
            <select id="waktu" name="ID_Waktu" required></select>

            <label>Tanggal Sewa:</label>
            <input type="date" name="tanggal" required>
        </div>
        <div class="jam-flex">
            <input type="hidden" id="batas_mulai">
            <input type="hidden" id="batas_selesai">
            <label>Jam Mulai:</label>
            <input type="time" name="Jam_Mulai" id="Jam_Mulai" required>

            <label>Jam Selesai:</label>
            <input type="time" name="Jam_Selesai" id="Jam_Selesai" required>
        </div>

         <div class="jam-terbooking">
        <label>Jam Sudah/Sedang dibooking:</label>
        <div id="jam-terbooking-container" class="jam-terbooking-container">
           
        </div>
        </div>

        </div>
        
        <div class="step" id="step-2">
            <div class="sesi2">
            <label>Surat Permohonan (PDF):</label>
            <input type="file" name="surat_permohonan" accept="application/pdf" required>

            <label>Bukti Pembayaran (JPG/PNG/PDF):</label>
            <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required>

            <input type="text" name="harga_tampil" id="harga" readonly> 
            <input type="hidden" name="harga" id="harga_asli"> 
            </div>
            <div class="qris-wrapper">
                <label>Pembayaran melalui QRIS</label>
                <p>Silakan scan QR Code di bawah ini menggunakan aplikasi pembayaran digital Anda:</p>
                <img src="../../asset/hias/qris.png" alt="QRIS Pembayaran">
                <p style="font-weight : 500;">Nominal: <span id="harga-qris-text">Rp 0</span></p>
                <p>Setelah pembayaran, unggah bukti pembayaran di atas.</p>
            </div>

        </div>

       
        <div class="navigation">
        <button type="button" id="btn-kembali-detail">Kembali</button>
        <button type="button" id="prev-step">Sebelumnya</button>
        <button type="button" id="next-step">Selanjutnya</button>
        <button type="submit" id="submit-btn">Ajukan Sewa</button>
        </div>
    </form>
</main>

  <script>
$(document).ready(function () {
    let currentStep = 1;
    const idSarpras = <?= json_encode($id_sarpras) ?>;

    function showStep(step) {
        $('.step').removeClass('active');
        $('#step-' + step).addClass('active');
        $('#prev-step').toggle(step > 1);
        $('#next-step').toggle(step < 2);
        $('#submit-btn').toggle(step === 2);
        $('#btn-kembali-detail').toggle(step === 1);
    }


   $('#next-step').click(function () {
    const area = $('#area-terpilih').val();
    const kegiatan = $('#jenis_kegiatan').val();
    const waktu = $('#waktu').val();
    const tanggal = $('input[name="tanggal"]').val();
    const jamMulai = $('#Jam_Mulai').val();
    const jamSelesai = $('#Jam_Selesai').val();
    const batasMulai = $('#batas_mulai').val();  // akan kosong jika waktu tidak pakai jam
    const batasSelesai = $('#batas_selesai').val();

    const pakaiJam = batasMulai && batasSelesai;

    // Cek kelengkapan data dasar
    if (!area || !kegiatan || !waktu || !tanggal) {
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Lengkapi data Area, Kegiatan, Waktu, dan Tanggal terlebih dahulu.',
        });
        return;
    }

    // Jika waktu menggunakan sistem jam, validasi jam diperlukan
    if (pakaiJam) {
        if (!jamMulai || !jamSelesai) {
            Swal.fire({
                icon: 'warning',
                title: 'Jam Belum Dipilih',
                text: 'Lengkapi jam mulai dan jam selesai terlebih dahulu.',
            });
            return;
        }

        if (jamMulai < batasMulai || jamSelesai > batasSelesai) {
            Swal.fire({
                icon: 'error',
                title: 'Jam Di Luar Rentang',
                text: `Pilih jam antara ${batasMulai} - ${batasSelesai}`,
            });
            return;
        }

      
        let jamTerbooking = [];
        $('.jam-terbooking-item').each(function () {
            jamTerbooking.push($(this).text().trim());
        });

        const toMinutes = time => {
            const [h, m] = time.split(':').map(Number);
            return h * 60 + m;
        };

        const mulaiMinutes = toMinutes(jamMulai);
        const selesaiMinutes = toMinutes(jamSelesai);

        const isConflict = jamTerbooking.some(jam => {
            const jamBookMinutes = toMinutes(jam);
            return jamBookMinutes >= mulaiMinutes && jamBookMinutes < selesaiMinutes;
        });

        if (isConflict) {
            Swal.fire({
                icon: 'error',
                title: 'Jam Sudah/Sedang Dibooking',
                text: 'Jam yang kamu pilih tumpang tindih dengan jadwal yang sudah dibooking. Silakan pilih jam lain.',
            });
            return;
        }
    }

    // Lanjutkan step dan ambil harga
    currentStep++;
    showStep(currentStep);
    getHarga();
});



    $('#prev-step').click(function () {
        currentStep--;
        showStep(currentStep);
    });

    $('#btn-kembali-detail').click(function () {
        window.location.href = 'detail.php?id=' + idSarpras;
    });

    showStep(currentStep);


    $('#waktu').change(function () {
    const waktu_id = $(this).val();
    if (!waktu_id) return;

    // Ambil rentang waktu dari server
    $.post('../../api/get_rentang_waktu.php', { id_waktu: waktu_id }, function (data) {
        try {
            const result = JSON.parse(data);
            $('#batas_mulai').val(result.jam_mulai);
            $('#batas_selesai').val(result.jam_selesai);
            console.log('Batas waktu:', result.jam_mulai, '-', result.jam_selesai);
        } catch (e) {
                console.error('Gagal parsing rentang waktu');
            }
        });
    });

    // Ambil area berdasarkan sarpras_id
    $.post('../../api/get_area.php', { sarpras_id: idSarpras }, function (response) {
        const areas = JSON.parse(response);
        let html = '';
        areas.forEach(area => {
            html += `
                <div class="area-item" data-id="${area.id}">
                    <img src="${area.foto}" alt="${area.nama}">
                    <div>${area.nama}</div>
                </div>
            `;
        });
        $('#area-container').html(html);
    });

    // Pilih area
    $('#area-container').on('click', '.area-item', function () {
        $('.area-item').removeClass('selected');
        $(this).addClass('selected');
        const id = $(this).data('id');
        $('#area-terpilih').val(id).trigger('change');
    });

    // Area -> Kegiatan
    $('#area-terpilih').change(function () {
        const area_id = $(this).val();
        $.post('../../api/get_kegiatan.php', { area_id }, function (response) {
            $('#jenis_kegiatan').html(response);
            $('#kategori').html('<option value="">--Pilih Kategori--</option>');
            $('#waktu').html('<option value="">--Pilih Waktu--</option>');
            $('#jam-container').html('');
        });
    });

    // Kegiatan -> Kategori & Waktu
    $('#jenis_kegiatan').change(function () {
        const kegiatan_id = $(this).val();
        $.post('../../api/get_kategori_waktu.php', { kegiatan_id }, function (response) {
            const data = JSON.parse(response);
            $('#kategori').html(data.kategori || '<option value="">Tidak ada kategori</option>');
            $('#waktu').html(data.waktu);
            $('#jam-container').html('');
        });
    });

    // Ambil jam tersedia
    $('#waktu, input[name="tanggal"]').change(function () {
        const waktu_id = $('#waktu').val();
        const tanggal = $('input[name="tanggal"]').val();
        const id_area = $('#area-terpilih').val();

        if (waktu_id && tanggal && id_area) {
            $.post('../../api/get_jam_tersedia.php', {
                waktu_id,
                tanggal,
                id_area,
                id_sarpras: idSarpras
            }, function (response) {
                try {
                    const data = JSON.parse(response);
                    let html = '';
                    if (data.length === 0) {
                        html = '<span style="color: green;">Semua jam tersedia.</span>';
                    } else {
                        data.forEach(jam => {
                            html += `<div class="jam-terbooking-item">${jam}</div>`;
                        });
                    }
                    $('#jam-terbooking-container').html(html);
                } catch (e) {
                    $('#jam-terbooking-container').html('<span style="color:red;">Gagal memuat data jam.</span>');
                }
            });
        }
    });

    // Submit log
    $('form').submit(function (e) {
        console.log('ID_Jam yang akan dikirim:', $('#jam-terpilih').val());
    });

    // Ambil harga
   function getHarga() {
    const kategoriVal = $('#kategori').val();
    const kategori = kategoriVal !== "" ? parseInt(kategoriVal) : 0;

    const data = {
        sarpras: idSarpras,
        area: $('#area-terpilih').val(),
        kategori: kategori,
        kegiatan: $('#jenis_kegiatan').val(),
        waktu: $('#waktu').val(),
        jam_mulai: $('#Jam_Mulai').val(),
        jam_selesai: $('#Jam_Selesai').val()
    };

    console.log('Mengambil harga dengan data:', data);

    $.post('../../api/get_harga.php', data, function (response) {
        console.log('Respon harga:', response);
        const harga = parseFloat(response);
        if (!isNaN(harga)) {
            const hargaFormatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(harga);
            $('#harga').val(hargaFormatted);
            $('#harga_asli').val(harga);
            $('#harga-qris-text').text(hargaFormatted);
        } else {
            $('#harga').val('Tidak ditemukan');
            $('#harga_asli').val('');
        }

        currentStep = 2;
        showStep(currentStep);
    });
}



    // Reset harga jika input berubah
    $('#area-terpilih, #kategori, #jenis_kegiatan, #waktu').change(function () {
        $('#harga').val('');
        $('#harga_asli').val('');
    });

    // Alert sukses
    <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Penyewaan berhasil disimpan.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3A4E92'
    });
    <?php endif; ?>
});


</script>



</body>
</html>