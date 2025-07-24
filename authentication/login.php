<?php
session_start();

// Veritabanı bilgilerin
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "master_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
  die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// Form post edildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Email var mı?
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Kullanıcı bulundu → şifre kontrol et
    $row = $result->fetch_assoc();
    $hashed_password = $row['password_hash'];

    if (password_verify($password, $hashed_password)) {
      // Doğru → session aç, YÖNLENDİR
      $_SESSION['email'] = $email;
      header("Location: ../dashboard/dashboard2.php");
      exit();
    } else {
      $error = "Hatalı şifre!";
    }
  } else {
    $error = "E-posta bulunamadı!";
  }
}
?>

<link rel="stylesheet" href="../assets/authentication.css">

<div class="signup-container">
  <h2>Giriş Yap</h2>
  <p>Hesabınıza erişmek için bilgilerinizi girin.</p>

  <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

  <form method="POST" action="" class="signup-form">
    <input type="email" name="email" placeholder="E-posta" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Giriş Yap</button>
  </form>

  
    <p class="login-link">Hesabın yok mu? <a href="sign_up.php">Kayıt Ol</a></p>
    <p class="login-link"><a href="forgot_password.php">Şifreni mi unuttun?</a></p>


  
</div>




