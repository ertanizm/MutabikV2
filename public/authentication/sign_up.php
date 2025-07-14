<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firma_adi = $_POST['firma_adi'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Şifreyi hashle
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 2. 6 haneli kod üret
    $verification_code = rand(100000, 999999);

    // 3. Veritabanına kaydet
    // PDO kullanıyorsan örnek:
    /*
    $stmt = $pdo->prepare("INSERT INTO users (firma_adi, email, password, verification_code, is_verified) VALUES (?, ?, ?, ?, 0)");
    $stmt->execute([$firma_adi, $email, $hashedPassword, $verification_code]);
    */

    // 4. Mail gönder
    $subject = "MutabikV2 Hesap Doğrulama Kodu";
    $message = "Doğrulama kodunuz: " . $verification_code;
    $headers = "From: noreply@seninsite.com";

    mail($email, $subject, $message, $headers);

    // 5. Kullanıcıyı doğrulama formuna yönlendir
    header("Location: verify.php?email=" . urlencode($email));
    exit;
}
?>


<link rel="stylesheet" href="../assets/authentication.css">
<div class="signup-container">
  <h2>Hesap Oluştur</h2>
  <p>Hızlı ve kolay bir şekilde mutabık olmaya başla.</p>
  <form method="POST" action="" class="signup-form">
    <input type="text" name="firma_adi" placeholder="Firma Adı" required>
    <input type="email" name="email" placeholder="E-posta" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Kayıt Ol</button>
  </form>
  <p class="login-link">Zaten bir hesabın var mı? <a href="login.php">Giriş Yap</a></p>
</div>

