<?php


// Config dosyasını dahil et
require '../config/config.php';  // Burada $masterPdo hazır

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $code = $_POST['code'] ?? '';

    if (!$email || !$code) {
        $error = "Lütfen tüm alanları doldurun.";
    } else {
        $stmt = $masterPdo->prepare("SELECT verification_code, is_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "Kullanıcı bulunamadı.";
        } elseif ($user['is_verified'] == 1) {
            $error = "Bu hesap zaten doğrulanmış.";
        } elseif ($user['verification_code'] == $code) {
            $update = $masterPdo->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
            $update->execute([$email]);
            $success = "Hesabınız doğrulandı! Giriş yapabilirsiniz.";
        } else {
            $error = "Doğrulama kodu yanlış.";
        }
    }
}
?>

<link rel="stylesheet" href="../assets/verify.css">
<div class="verify-container">
  <h2>Doğrulama Kodu Gir</h2>

  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php elseif ($success): ?>
    <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <p><a href="login.php" style="font-size:18px; text-decoration:none; color:blue;">Giriş Yap</a></p>
  <?php endif; ?>

  <?php if (!$success): ?>
  <form method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
    <input type="text" name="code" placeholder="Doğrulama Kodu" required>
    <button type="submit">Doğrula</button>
  </form>
  <p>Mail adresine gönderilen kodu giriniz.</p>
  <?php endif; ?>
</div>


