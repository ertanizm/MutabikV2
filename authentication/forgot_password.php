<?php
date_default_timezone_set('Europe/Istanbul');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../config/config.php';  // $masterPdo hazır
require '../lib/phpmailer/Exception.php';
require '../lib/phpmailer/PHPMailer.php';
require '../lib/phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $error = "Lütfen e-posta adresinizi girin.";
    } else {
        try {
            // Kullanıcı var mı kontrol et
            $stmt = $masterPdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

                // Token ve expire güncelle
                $update = $masterPdo->prepare("UPDATE users SET reset_token = ?, reset_token_expire = ? WHERE email = ?");
                $update->execute([$token, $expires, $email]);

                // Sıfırlama linki (uygun base url ile değiştir)
               $reset_link = "http://localhost" . BASE_URL . "authentication/reset_password.php?token=$token";


                // PHPMailer ile mail gönderimi
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'phpsmtp2@gmail.com';
                    $mail->Password = 'ditdmciosmoqolba';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->setFrom('phpsmtp2@gmail.com', 'MutabikV2');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Şifre Sıfırlama Talebi';
                    $mail->Body = "Merhaba,<br><br>Şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın:<br><a href='$reset_link'>$reset_link</a><br><br>Bu bağlantı 1 saat geçerlidir.";

                    $mail->send();
                    $success = "E-posta gönderildi! Lütfen gelen kutunuzu kontrol edin.";
                } catch (Exception $e) {
                    $error = "E-posta gönderilemedi. Hata: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Bu e-posta adresi sistemde kayıtlı değil.";
            }
        } catch (PDOException $e) {
            $error = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>

<link rel="stylesheet" href="../assets/authentication.css">

<div class="signup-container">
  <h2>Şifremi Unuttum</h2>
  <p>Kayıtlı e-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim.</p>

  <?php if (isset($error)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <?php if (isset($success)): ?>
    <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
  <?php endif; ?>

  <form method="POST" action="" class="signup-form">
    <input type="email" name="email" placeholder="E-posta adresiniz" required>
    <button type="submit">Sıfırlama Linki Gönder</button>
  </form>

  <p class="login-link"><a href="login.php">Giriş ekranına dön</a></p>
</div>
