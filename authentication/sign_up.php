<?php
session_start();

require '../lib/phpmailer/Exception.php';
require '../lib/phpmailer/PHPMailer.php';
require '../lib/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Veritabanı bağlantısı
$host = 'localhost';
$db   = 'master_db';
$user = 'root';
<<<<<<< HEAD:authentication/sign_up.php
$pass = '';
=======
$pass = 'akdere';
>>>>>>> 6945677bd6f6d77483c463d515101e84c0109464:public/authentication/sign_up.php
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firma_adi = $_POST['firma_adi'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$firma_adi || !$email || !$password) {
        die('Lütfen tüm alanları doldurun.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Geçerli bir email girin.');
    }

    // Şifreyi hashle
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 6 haneli doğrulama kodu oluştur
    $verification_code = rand(100000, 999999);

    try {
        // 1. Şirket var mı kontrol et, yoksa ekle
        $stmt = $pdo->prepare("SELECT id FROM companies WHERE name = ?");
        $stmt->execute([$firma_adi]);
        $company = $stmt->fetch();

        if ($company) {
            $company_id = $company['id'];
        } else {
            $db_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $firma_adi)) . '_db';
            $stmt = $pdo->prepare("INSERT INTO companies (name, db_name, start_date, status) VALUES (?, ?, CURDATE(), 'Aktif')");
            $stmt->execute([$firma_adi, $db_name]);
            $company_id = $pdo->lastInsertId();
        }

        // 2. Kullanıcıyı ekle
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, company_id) VALUES (?, ?, ?)");
        $stmt->execute([$email, $hashedPassword, $company_id]);
    } catch (PDOException $e) {
        die("Kayıt sırasında hata: " . $e->getMessage());
    }

    // PHPMailer ile doğrulama maili gönder
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'phpsmtp2@gmail.com'; // Gmail adresin
        $mail->Password   = 'ditdmciosmoqolba';     // Gmail uygulama şifren
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('phpsmtp2@gmail.com', 'MutabikV2');
        $mail->addAddress($email, $firma_adi);

        $mail->isHTML(true);
        $mail->Subject = 'Hesap Doğrulama Kodu';
        $mail->Body    = "<p>Merhaba <b>$firma_adi</b>,</p><p>Doğrulama kodunuz: <b>$verification_code</b></p>";

        $mail->send();

        // Başarılıysa verify.php sayfasına yönlendir
        //header("Location: verify.php?email=" . urlencode($email));
        exit;
    } catch (Exception $e) {
        die("Mail gönderilemedi: {$mail->ErrorInfo}");
    }
}
?>

<!-- HTML form -->
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


