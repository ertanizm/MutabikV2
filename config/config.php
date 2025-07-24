<?php
// Proje kök yolu (tüm yönlendirme ve linklerde kullanmak için)
define('BASE_URL', '/MutabikV2/');
// Giriş bilgileri
$host = 'localhost';
$masterDbUser = 'root';
$masterDbPass = '';
$masterDbName = 'master_db';

// 0. master_db yoksa oluştur ve tabloları oluştur
try {
    $pdo = new PDO("mysql:host=$host", $masterDbUser,'');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Veritabanı oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$masterDbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Veritabanını seç
    $pdo->exec("USE `$masterDbName`");

    // companies tablosu
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS companies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            db_name VARCHAR(100) NOT NULL UNIQUE,
            start_date DATE,
            status ENUM('Aktif', 'Pasif') DEFAULT 'Aktif'
        )
    ");

    // users tablosu
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            company_id INT NOT NULL,
            role ENUM('Admin', 'Kullanici') DEFAULT 'Kullanici',
            FOREIGN KEY (company_id) REFERENCES companies(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        )
    ");

} catch (PDOException $e) {
    die("Ana veritabanı oluşturulamadı: " . $e->getMessage());
}

// 1. master_db bağlantısı
try {
    $masterPdo = new PDO("mysql:host=$host;dbname=$masterDbName", $masterDbUser, '');
    $masterPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

// 2. Formdan gelen veriler
$firmaAdi = $_POST['firma_adi'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($firmaAdi) || empty($email) || empty($password)) {
    die("❗ Lütfen tüm alanları doldurun.");
}

// Veritabanı ismini oluştur (örn: atia_yazilim_db)
$dbName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $firmaAdi)) . '_db';

try {
    // Aynı db_name var mı kontrol et
    $stmt = $masterPdo->prepare("SELECT COUNT(*) FROM companies WHERE db_name = :db_name");
    $stmt->execute(['db_name' => $dbName]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        die("❗ Bu firma adına ait veritabanı zaten mevcut. Lütfen farklı bir isim deneyin.");
    }

    // Önce veritabanını oluştur (transaction dışında)
    $masterPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Transaction başlat (şirket ve kullanıcı ekleme işlemleri için)
    $masterPdo->beginTransaction();

    // companies tablosuna kayıt ekle
    $stmt = $masterPdo->prepare("
        INSERT INTO companies (name, db_name, start_date, status)
        VALUES (:name, :db_name, CURDATE(), 'Aktif')
    ");
    $stmt->execute([
        'name' => $firmaAdi,
        'db_name' => $dbName
    ]);
    $companyId = $masterPdo->lastInsertId();

    // users tablosuna admin kullanıcı ekle
    $stmt = $masterPdo->prepare("
        INSERT INTO users (email, password_hash, company_id, role)
        VALUES (:email, :password, :company_id, 'Admin')
    ");
    $stmt->execute([
        'email' => $email,
        'password' => hash('sha256', $password),
        'company_id' => $companyId
    ]);

    // Transaction commit et
    $masterPdo->commit();

    // Yeni veritabanına bağlan
    $tenantPdo = new PDO("mysql:host=$host;dbname=$dbName", $masterDbUser, $masterDbPass);
    $tenantPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tenant tablolarını oluştur (her tablo ayrı exec ile)
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
            unvan VARCHAR(255),
            vergi_no VARCHAR(50),
            telefon VARCHAR(20),
            email VARCHAR(100)
        )
    ");

    echo "✅ Şirket ve veritabanı başarıyla oluşturuldu: $dbName";

} catch (PDOException $e) {
    if ($masterPdo->inTransaction()) {
        $masterPdo->rollBack();
    }
    echo "❌ Hata: " . $e->getMessage();
}
