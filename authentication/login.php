<?php


// Config dosyasını dahil et
require '../config/config.php'; // burada $masterPdo zaten tanımlı ve hazır

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = "Lütfen e-posta ve şifre girin.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta girin.";
    } else {
        try {
            // Kullanıcıyı sorgula (prepared statement ile)
            $stmt = $masterPdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                if (password_verify($password, $user['password_hash'])) {
                    // Doğruysa session aç
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['company_id'] = $user['company_id'];
                    $_SESSION['role'] = $user['role'];
                    // İstersen doğrulanmış mı kontrol edebilirsin
                    if ($user['is_verified'] == 0) {
                        $error = "Hesabınız doğrulanmamış. Lütfen e-postanıza gelen kodla doğrulayın.";
                    } else {
                        // Başarılı giriş, dashboard sayfasına yönlendir
                        header("Location: ../dashboard/dashboard2.php");
                        exit();
                    }
                } else {
                    $error = "Hatalı şifre!";
                }
            } else {
                $error = "E-posta bulunamadı!";
            }
        } catch (PDOException $e) {
            $error = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>

<link rel="stylesheet" href="../assets/authentication.css">

<div class="signup-container">
  <h2>Giriş Yap</h2>
  <p>Hesabınıza erişmek için bilgilerinizi girin.</p>

  <?php if (isset($error)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form method="POST" action="" class="signup-form">
    <input type="email" name="email" placeholder="E-posta" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Giriş Yap</button>
  </form>

  <p class="login-link">Hesabın yok mu? <a href="sign_up.php">Kayıt Ol</a></p>
  <p class="login-link"><a href="forgot_password.php">Şifreni mi unuttun?</a></p>
</div>
