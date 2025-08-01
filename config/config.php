<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$config = require __DIR__ . '/.env.php';
// Proje kök yolu (tüm yönlendirme ve linklerde kullanmak için)
define('BASE_URL', '/MutabikV2/');

// Veritabanı erişim bilgileri
$host = $config['DB_HOST'];
$masterDbUser = $config['DB_USER'];
$masterDbPass = $config['DB_PASS'];
$masterDbName = $config['DB_NAME'];

try {
    // MySQL sunucusuna bağlantı (db seçmeden)
    $pdo = new PDO("mysql:host=$host", $masterDbUser, $masterDbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // master_db yoksa oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$masterDbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
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
            verification_code VARCHAR(10),
            is_verified TINYINT(1) DEFAULT 0,
            FOREIGN KEY (company_id) REFERENCES companies(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        )
    ");
} catch (PDOException $e) {
    die("Ana veritabanı oluşturulamadı: " . $e->getMessage());
}

try {
    // master_db bağlantısını PDO ile hazırla (global kullanıma)
    $masterPdo = new PDO("mysql:host=$host;dbname=$masterDbName;charset=utf8mb4", $masterDbUser, $masterDbPass);
    $masterPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ana veritabanına bağlanılamadı: " . $e->getMessage());
}

/**
 * Tenant veritabanı bağlantısı döner.
 * @param int $company_id
 * @return PDO
 * @throws Exception
 */
function getTenantPDO(int $company_id = null): PDO {
    global $host, $masterDbUser, $masterDbPass, $masterPdo;
   // Eğer company_id parametre olarak gelmezse, session'dan al
    if (!$company_id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['company_id'])) {
            $company_id = $_SESSION['company_id'];
        } else {
            throw new Exception("Company ID belirlenemedi. Tenant veritabanına bağlanılamıyor.");
        }
    }

    // Şirketin db_name bilgisini master_db'den al
    $stmt = $masterPdo->prepare("SELECT db_name FROM companies WHERE id = ?");
    $stmt->execute([$company_id]);
    $db = $stmt->fetch(PDO::FETCH_ASSOC);

     if (!$db) {
        throw new Exception("Geçersiz company ID: {$company_id}");
    }

    $tenantDbName = $db['db_name'];


    // Tenant veritabanına bağlan
    $tenantPdo = new PDO("mysql:host=$host;dbname=$tenantDbName;charset=utf8mb4", $masterDbUser, $masterDbPass);
    $tenantPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $tenantPdo;
}

