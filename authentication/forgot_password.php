<?php
date_default_timezone_set('Europe/Istanbul');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/phpmailer/Exception.php';
require '../lib/phpmailer/PHPMailer.php';
require '../lib/phpmailer/SMTP.php';

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "master_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $update = "UPDATE users SET reset_token = '$token', reset_token_expire = '$expires' WHERE email = '$email'";
        $conn->query($update);

        $reset_link = "http://localhost/MutabikV2/public/authentication/reset_password.php?token=$token";

        // PHPMailer ile mail gönderimi
        $mail = new PHPMailer(true);
        try {
            // SMTP ayarları
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // SMTP sunucunu yaz
            $mail->SMTPAuth = true;
            $mail->Username = 'phpsmtp2@gmail.com';  // SMTP kullanıcı adın
            $mail->Password = 'ditdmciosmoqolba';     // SMTP şifren
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('phpsmtp2@gmail.com', 'MutabikV2');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Şifre Sıfırlama Talebi';
            $mail->Body    = "Merhaba,<br><br>Şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın:<br><a href='$reset_link'>$reset_link</a><br><br>Bu bağlantı 1 saat geçerlidir.";

            $mail->send();
            $success = "E-posta gönderildi! Lütfen kontrol et.";
        } catch (Exception $e) {
            $error = "E-posta gönderilemedi. Hata: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Bu e-posta adresi sistemde kayıtlı değil.";
    }
}
?>

<link rel="stylesheet" href="../assets/authentication.css">

<div class="signup-container">
  <h2>Şifremi Unuttum</h2>
  <p>Kayıtlı e-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim.</p>

  <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
  <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>

  <form method="POST" action="" class="signup-form">
    <input type="email" name="email" placeholder="E-posta adresiniz" required>
    <button type="submit">Sıfırlama Linki Gönder</button>
  </form>

  <p class="login-link"><a href="login.php">Giriş ekranına dön</a></p>
</div>

