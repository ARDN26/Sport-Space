<?php
session_start();
require 'db.php';

$status = null;
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['Nama'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nama) || empty($password)) {
        $status = 'error';
        $message = 'Nama dan password harus diisi.';
    } else {
        // Cek di tabel admin
        $stmt = $conn->prepare("SELECT ID_Admin, Nama_Admin, password FROM admin WHERE Nama_Admin = ?");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($admin = $result->fetch_assoc()) {
            if (password_verify($password, $admin['password'])) {
                $_SESSION['ID_Admin'] = $admin['ID_Admin'];
                $_SESSION['Nama']     = $admin['Nama_Admin'];
                $_SESSION['role']     = 'admin';
                header("Location: beranda_admin.php");
                exit;
            } else {
                $status = 'error';
                $message = 'Password salah untuk admin.';
            }
        } else {
            // Cek di tabel user
            $stmt = $conn->prepare("SELECT ID_User, Nama_User, password FROM users WHERE Nama_User = ?");
            $stmt->bind_param("s", $nama);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['ID_User'] = $user['ID_User'];
                    $_SESSION['Nama']    = $user['Nama_User'];
                    $_SESSION['role']    = 'user';
                    header("Location: beranda_user.php");
                    exit;
                } else {
                    $status = 'error';
                    $message = 'Password salah untuk user.';
                }
            } else {
                $status = 'error';
                $message = 'Akun tidak ditemukan.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sport Space - Login</title>
  <link rel="shortcut icon" type="image/x-icon" href="asset/tanpa judul.jpg">
  <link rel="stylesheet" href="Loginregis.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
  <img src="asset/dengan judul.jpg" alt="Gambar depan">
  <div class="login">
    <h2>MASUK</h2>
    <hr class="garis">
    <form action="" method="POST">
      <label for="Nama">Nama:</label>
      <input type="text" id="Nama" name="Nama" required><br>
    
      <label for="password">Password:</label>
        <div class="password-wrapper" style="margin: 0 auto;">
        <input type="password" id="password" name="password" required>
        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
        </div><br>

      <p>Belum Punya Akun? <a href="register.php">Daftar!</a></p>
      <button type="submit">Masuk</button>
    </form>
  </div>

<?php if ($status === 'error' && $message): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Gagal',
    text: <?= json_encode($message) ?>,
    confirmButtonText: 'OK'
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
