<?php

date_default_timezone_set('Europe/Istanbul');

// Config dosyasını dahil et
require '../config/config.php';  // burada $masterPdo hazır

$error = '';
$success = '';

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = trim($_POST['token'] ?? ($token));
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($new_password !== $confirm_password) {
        $error = "Şifreler uyuşmuyor!";
    } elseif (strlen($new_password) < 6) {
        $error = "Şifre en az 6 karakter olmalı.";
    } else {
        try {
            // Token ve expire kontrolü
            $stmt = $masterPdo->prepare("SELECT id, reset_token_expire, NOW() as now_time FROM users WHERE reset_token = :token");
            $stmt->execute(['token' => $token]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['reset_token_expire'] > $user['now_time']) {
                    // Token geçerli, şifreyi güncelle
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt = $masterPdo->prepare("
                        UPDATE users 
                        SET password_hash = :password_hash, reset_token = NULL, reset_token_expire = NULL 
                        WHERE id = :id
                    ");
                    $update_stmt->execute([
                        'password_hash' => $hashed,
                        'id' => $user['id']
                    ]);
                    $success = "Şifren başarıyla güncellendi. Giriş yapabilirsin.";
                } else {
                    $error = "Token süresi dolmuş.";
                }
            } else {
                $error = "Token veritabanında bulunamadı.";
            }
        } catch (PDOException $e) {
            $error = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>

<link rel="stylesheet" href="../assets/authentication.css">

<div class="signup-container">
  <h2>Yeni Şifre Belirle</h2>

  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <?php if ($success): ?>
    <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <p><a href="login.php" style="font-size:18px; text-decoration:none; color:blue;">Giriş Yap</a></p>
  <?php else: ?>
    <form method="POST" action="" class="signup-form">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <input type="password" name="new_password" placeholder="Yeni Şifre" required>
      <input type="password" name="confirm_password" placeholder="Şifreyi Onayla" required>
      <button type="submit">Şifreyi Güncelle</button>
    </form>
  <?php endif; ?>

  <p class="login-link"><a href="login.php">Giriş ekranına dön</a></p>
</div>



