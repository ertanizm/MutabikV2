<?php


// Config dosyasını dahil et
require '../config/config.php';  // burada $masterPdo zaten tanımlı ve hazır

require '../lib/phpmailer/Exception.php';
require '../lib/phpmailer/PHPMailer.php';
require '../lib/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firma_adi = $_POST['firma_adi'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (!$firma_adi || !$email || !$pass) {
        die('Lütfen tüm alanları doldurun.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Geçerli bir email girin.');
    }

    // Şifreyi hashle
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // 6 haneli doğrulama kodu oluştur
    $verification_code = rand(100000, 999999);

    try {
        // Veritabanı ismini oluştur
        $db_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $firma_adi)) . '_db';

        // Aynı db_name var mı kontrol et
        $stmt = $masterPdo->prepare("SELECT id, db_name FROM companies WHERE name = ?");
        $stmt->execute([$firma_adi]);
        $company = $stmt->fetch();

        if ($company) {
            $company_id = $company['id'];
            $db_name = $company['db_name']; // varsa varolan db_name'i al
        } else {
            // Veritabanını oluştur (config.php içinde yoksa eklemelisin)
            $masterPdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

            // companies tablosuna kayıt ekle
            $stmt = $masterPdo->prepare("INSERT INTO companies (name, db_name, start_date, status) VALUES (?, ?, CURDATE(), 'Aktif')");
            $stmt->execute([$firma_adi, $db_name]);
            $company_id = $masterPdo->lastInsertId();

            // Yeni veritabanına bağlan ve tenant tablolarını oluştur
            $tenantPdo = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8mb4", $masterDbUser, $masterDbPass);
            $tenantPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Örnek tablolar (stoklar, faturalar, cariler)
            $tenantPdo->exec("
                CREATE TABLE IF NOT EXISTS stoklar (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    stok_kodu VARCHAR(50),
                    stok_adi VARCHAR(255),
                    birim VARCHAR(10),
                    miktar DECIMAL(10,2)
                )
            ");
            $tenantPdo->exec("
                CREATE TABLE IF NOT EXISTS faturalar (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    fatura_no VARCHAR(50),
                    tarih DATE,
                    cari_unvan VARCHAR(255),
                    tutar DECIMAL(12,2),
                    kdv_orani DECIMAL(5,2)
                )
            ");
            $tenantPdo->exec("
                CREATE TABLE IF NOT EXISTS cariler (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     isim VARCHAR(255),
                     vergi_no VARCHAR(50),
                     telefon VARCHAR(20),
                     email VARCHAR(100),
                     adres TEXT,
                     il VARCHAR(100),
                     ilce VARCHAR(100),
                     aciklama TEXT,
                     tip ENUM('Musteri', 'Calisan', 'Tedarikci')
                )
            ");
            $tenantPdo->exec("
                CREATE TABLE IF NOT EXISTS depolar (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     ad VARCHAR(255) NOT NULL,
                     adres TEXT,
                     telefon VARCHAR(20),
                     yetkili VARCHAR(255),
                     durum ENUM('aktif', 'pasif') DEFAULT 'aktif'
                )
            ");
            $tenantPdo->exec("
                CREATE TABLE IF NOT EXISTS depo_transferleri (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     transfer_no VARCHAR(50) NOT NULL,
                     tarih DATE NOT NULL,
                     kaynak_depo_id INT NOT NULL,
                     hedef_depo_id INT NOT NULL,
                     aciklama TEXT,
                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     FOREIGN KEY (kaynak_depo_id) REFERENCES depolar(id),
                     FOREIGN KEY (hedef_depo_id) REFERENCES depolar(id)
                )
            ");
        }

        // Kullanıcıyı ekle
        $stmt = $masterPdo->prepare("INSERT INTO users (email, password_hash, company_id, verification_code, is_verified) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$email, $hashedPassword, $company_id, $verification_code]);

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
        $mail->Password   = 'ditdmciosmoqolba';  // Gmail uygulama şifren
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('phpsmtp2@gmail.com', 'MutabikV2');
        $mail->addAddress($email, $firma_adi);

        $mail->isHTML(true);
        $mail->Subject = 'Hesap Doğrulama Kodu';
        $mail->Body    = "<p>Merhaba <b>$firma_adi</b>,</p><p>Doğrulama kodunuz: <b>$verification_code</b></p>";

        $mail->send();

        // Başarılıysa verify.php sayfasına yönlendir
        header("Location: verify.php?email=" . urlencode($email));
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
