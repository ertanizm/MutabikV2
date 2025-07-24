<?php
date_default_timezone_set('Europe/Istanbul');
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "master_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = trim($_POST['token'] ?? ($_GET['token'] ?? ''));
    
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($new_password !== $confirm_password) {
        $error = "Şifreler uyuşmuyor!";
    } elseif (strlen($new_password) < 6) {
        $error = "Şifre en az 6 karakter olmalı.";
    } else {
        // Hazırlanmış ifade ile token ve expire kontrolü
        $stmt = $conn->prepare("SELECT id, reset_token, reset_token_expire, NOW() as now_time FROM users WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<pre>";
        // echo "POST gelen token: [" . $token . "]<br>";

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // echo "DB'deki token: [" . $row['reset_token'] . "]<br>";
            // echo "Expire: " . $row['reset_token_expire'] . "<br>";
            // echo "MySQL NOW(): " . $row['now_time'] . "<br>";

            // Zaman karşılaştırması
            if ($row['reset_token_expire'] > $row['now_time']) {
                // echo "<span style='color:green;'>Token süresi geçerli.</span><br>";

                // Şifreyi güncelle
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expire = NULL WHERE reset_token = ?");
                $update_stmt->bind_param("ss", $hashed, $token);
                $update_stmt->execute();
                $success = "Şifren başarıyla güncellendi. Giriş yapabilirsin.";
            } else {
                echo "<span style='color:red;'>Token süresi dolmuş.</span><br>";
                $error = "Token süresi dolmuş.";
            }
        } else {
            echo "<span style='color:red;'>Token veritabanında bulunamadı.</span><br>";
            $error = "Token veritabanında bulunamadı.";
        }
        echo "</pre>";
    }
}
?>

<link rel="stylesheet" href="../assets/authentication.css">

<div class="signup-container">
  <h2>Yeni Şifre Belirle</h2>

  <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
  <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>

  <?php if (!isset($success)): ?>
  <form method="POST" action="" class="signup-form">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="new_password" placeholder="Yeni Şifre" required>
    <input type="password" name="confirm_password" placeholder="Şifreyi Onayla" required>
    <button type="submit">Şifreyi Güncelle</button>
  </form>
  <?php endif; ?>

  <p class="login-link"><a href="login.php">Giriş ekranına dön</a></p>
</div>


