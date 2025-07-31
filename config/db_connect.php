<?php
session_start();

// .env dosyasından ayarları al
$config = require __DIR__ . '/.env.php';

$host = $config['DB_HOST'];
$dbname = $config['DB_NAME'];
$user = $config['DB_USER'];
$pass = $config['DB_PASS'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    die("Veritabanına bağlanılamadı.");
}

// Kullanıcı bilgilerini oturumdan çek
$userEmail = $_SESSION['email'] ?? '';
$userName = 'Kullanıcı';
$companyName = 'Şirket';

if ($userEmail && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT u.email, u.username, c.name as company_name 
                               FROM users u 
                               JOIN companies c ON u.company_id = c.id 
                               WHERE u.email = ?");
        $stmt->execute([$userEmail]);
        $userData = $stmt->fetch();

        if ($userData) {
            $userEmail = $userData['email'];
            $userName = $userData['username'] ?? 'Kullanıcı';
            $companyName = $userData['company_name'] ?? 'Şirket';
        }
    } catch (PDOException $e) {
        error_log("Kullanıcı bilgileri alınamadı: " . $e->getMessage());
    }
}