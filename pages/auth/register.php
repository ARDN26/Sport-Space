<?php
require '../../config/db.php';

$status = null;
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nama     = $_POST['Nama'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_telp  = $_POST["No_Telepon"];

    // Cek apakah email sudah terdaftar
    $check = $conn->prepare("SELECT ID_User FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $status = 'gagal';
        $message = 'Email sudah terdaftar!';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (Nama_User, email, password, No_Telepon) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $Nama, $email, $password, $no_telp);

        if ($stmt->execute()) {
            $status = 'sukses';
            $message = 'Registrasi berhasil! Silakan login.';
        } else {
            $status = 'gagal';
            $message = 'Terjadi kesalahan. Coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sport Space - Daftar</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../asset/tanpa judul.jpg">
  <link rel="stylesheet" href="../../css/Loginregis.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <img src="../../asset/dengan judul.jpg" alt="Logo SS">
  <div class="Daftar">
    <h2>DAFTAR</h2>
    <hr class="garis">
    <form action="" method="POST">
      <label>Nama Pengguna:</label>
      <input type="text" name="Nama" required><br>

      <label>Email:</label>
      <input type="email" name="email" required><br>

      <label for="password">Password:</label>
        <div class="password-wrapper" style="margin: 0 auto;">
        <input type="password" id="password" name="password" required>
        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
        </div><br>


      <label>No Telepon:</label>
      <input type="text" name="No_Telepon" required><br>

      <p>Sudah Punya Akun? <a href="login.php">Masuk!</a></p>
      <button type="submit">Daftar</button>
    </form>
  </div>


<?php if ($status !== null): ?>
<script>
Swal.fire({
    icon: <?= $status === 'sukses' ? "'success'" : "'error'" ?>,
    title: <?= $status === 'sukses' ? "'Pendaftaran Berhasil!'" : "'Pendaftaran Gagal!'" ?>,
    text: <?= json_encode($message) ?>,
    confirmButtonColor: <?= $status === 'sukses' ? "'#3085d6'" : "'#d33'" ?>,
}).then(() => {
    <?php if ($status === 'sukses'): ?>
        window.location.href = 'login.php';
    <?php endif; ?>
});
</script>
<?php endif; ?>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.querySelector(".toggle-password");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
    }
  }
</script>


</body>
</html>
