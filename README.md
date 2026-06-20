# Sport Space - Kabupaten Gresik 🏟️

**Sport Space Kabupaten Gresik** adalah aplikasi website terpadu yang dirancang untuk mempermudah masyarakat dalam melakukan pemesanan dan penyewaan berbagai fasilitas/sarana olahraga yang dikelola oleh Pemerintah Kabupaten Gresik secara daring.

Aplikasi ini memiliki antarmuka yang ramah pengguna serta panel admin yang lengkap untuk memvalidasi dan memantau seluruh riwayat pesanan sarana.

---

## 🚀 Fitur Utama

### 👤 Fitur Pengguna (User)
1. **Autentikasi Akun**
   - Pendaftaran akun (Registrasi) dan proses Login yang aman untuk setiap warga.
   - Manajemen Profil (Ubah nama, email, nomor HP).
2. **Katalog & Detail Sarana Olahraga**
   - Menampilkan daftar fasilitas olahraga yang tersedia beserta detail informasi dan galeri fotonya.
3. **Pemesanan Interaktif (Form Sewa)**
   - Pilihan lokasi area (lapangan) secara spesifik.
   - Penentuan jenis kegiatan olahraga.
   - Pengecekan ketersediaan jam dan hari secara dinamis (via AJAX).
   - Kalkulasi tarif otomatis berdasarkan durasi dan jadwal yang dipilih.
4. **Pembayaran & Berkas Persyaratan**
   - Pembayaran terintegrasi via *QRIS*.
   - Fitur unggah "Surat Permohonan" dan "Bukti Pembayaran".
5. **Manajemen Pesanan Saya**
   - Memantau status setiap transaksi penyewaan (Dikonfirmasi, Ditolak, atau Proses).
   - Fitur Pembatalan Pesanan dengan penyertaan alasan batal.

### 🛡️ Fitur Administrator (Admin)
1. **Dashboard Khusus Admin**
   - Tampilan ringkas mengenai seluruh layanan dan profil admin.
2. **Manajemen Sarana (CRUD)**
   - Menambahkan (Add), mengedit (Edit), dan menghapus (Delete) daftar sarana, foto galeri, area lapangan, hingga harga per rentang waktu.
3. **Manajemen Validasi Pesanan**
   - Menerima pesanan masuk dari masyarakat.
   - Mengunduh (download) dokumen Surat Permohonan dan Bukti Pembayaran untuk validasi.
   - Melakukan konfirmasi (Approve) atau penolakan (Reject) dengan menyertakan alasan.

---

## 📸 Cuplikan Layar (Screenshots)

Berikut adalah beberapa tampilan antarmuka dari website Sport Space:

### 1. Halaman Registrasi & Login
![Halaman Registrasi](ss/Halaman%20Regis.png)

### 2. Beranda Utama (Katalog Sarana)
![Beranda User](ss/Beranda%20User.png)

### 3. Halaman Detail Sarana
![Info Sarana](ss/Info%20Sarana.png)

### 4. Formulir Pemesanan (Sewa) Terpadu
![Halaman Sewa](ss/Halaman%20Sewa.png)
![Halaman Sewa Lanjutan](ss/Halaman%20Sewa%202.png)

### 5. Beranda & Manajemen Admin
![Beranda Admin](ss/Beranda%20Admin.png)

### 6. Riwayat Pesanan & Validasi Admin
![Riwayat Pesanan Admin](ss/Riwayat%20Pesanan%20Admin.png)

---

## 🛠️ Teknologi yang Digunakan
- **Frontend**: HTML5, CSS3 murni (Vanilla), JavaScript, jQuery (untuk API/AJAX).
- **Backend**: PHP (Native).
- **Database**: MySQL.
- **Library Tambahan**: SweetAlert2 (untuk notifikasi pop-up dinamis).

## 📂 Struktur Direktori Proyek

Proyek ini telah direstrukturisasi agar rapi dengan pola pembagian folder sebagai berikut:
- `/config` — File koneksi database dan middleware autentikasi.
- `/pages` — Berisi seluruh antarmuka HTML (`/auth`, `/admin`, dan `/user`).
- `/handlers` — Seluruh logika pemrosesan form (*Backend action*).
- `/api` — Titik akhir (endpoint) berbasis JSON/AJAX.
- `/css` — Kumpulan file gaya (*stylesheets*).
- `/asset` & `/uploads` — Manajemen direktori foto dan arsip file berkas permohonan masyarakat.
