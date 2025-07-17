<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// VERİTABANI BAĞLANTISI
$host = 'localhost';
$dbname = 'master_db';
$user = 'root';
$pass = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Veritabanı bağlantı hatasında mesajı session'a kaydet ve yönlendir
    session_start(); // Session'ı burada başlatmak önemli
    $_SESSION['message'] = ['type' => 'danger', 'text' => "Veritabanı bağlantı hatası: " . $e->getMessage()];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// CSRF TOKEN OLUŞTURMA VE KONTROL
session_start(); // Session'ı burada başlatmak önemli
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verifyCsrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => "CSRF token doğrulama hatası."];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// FORM İŞLEMLERİ (Ekleme, Düzenleme, Silme)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'delete':
                if (isset($_POST['id'])) {
                    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
                    $stmt->execute([':id' => (int)$_POST['id']]);
                    $_SESSION['message'] = ['type' => 'success', 'text' => "Kullanıcı başarıyla silindi."];
                } else {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => "Silinecek kullanıcı ID'si belirtilmedi."];
                }
                break;

            case 'create':
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $role = $_POST['role'];
                // company_id'yi tamsayı olarak doğrula ve filtrele
                $company_id = filter_var($_POST['company_id'], FILTER_VALIDATE_INT);
                $username = trim($_POST['username']); // Kullanıcı adı eklendi

                // Tüm gerekli alanların dolu olduğundan emin ol
                if (empty($email) || empty($password) || empty($role) || $company_id === false || empty($username)) {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => "Tüm gerekli alanları doldurunuz (E-posta, Şifre, Rol, Şirket ID, Kullanıcı Adı)."];
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (email, password_hash, role, company_id, username) VALUES (:email, :password_hash, :role, :company_id, :username)");
                    $stmt->execute([
                        ':email' => $email,
                        ':password_hash' => $hashedPassword,
                        ':role' => $role,
                        ':company_id' => $company_id,
                        ':username' => $username
                    ]);
                    $_SESSION['message'] = ['type' => 'success', 'text' => "Kullanıcı başarıyla eklendi."];
                }
                break;

            case 'update':
                $id = (int)$_POST['id'];
                $email = trim($_POST['email']);
                $password = $_POST['password'] ?? ''; // Şifre boş bırakılabilir
                $role = $_POST['role'];
                // company_id'yi tamsayı olarak doğrula ve filtrele
                $company_id = filter_var($_POST['company_id'], FILTER_VALIDATE_INT);
                $username = trim($_POST['username']); // Kullanıcı adı eklendi

                // E-posta, Rol, Şirket ID ve Kullanıcı Adı alanlarının dolu olduğundan emin ol
                if (empty($email) || empty($role) || $company_id === false || empty($username)) {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => "Tüm gerekli alanları doldurunuz (E-posta, Rol, Şirket ID, Kullanıcı Adı)."];
                } else {
                    $query = "UPDATE users SET email = :email, role = :role, company_id = :company_id, username = :username";
                    $params = [
                        ':email' => $email,
                        ':role' => $role,
                        ':company_id' => $company_id,
                        ':username' => $username,
                        ':id' => $id
                    ];

                    if (!empty($password)) { // Şifre doluysa güncelle
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $query .= ", password_hash = :password_hash";
                        $params[':password_hash'] = $hashedPassword;
                    }

                    $query .= " WHERE id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->execute($params);
                    $_SESSION['message'] = ['type' => 'success', 'text' => "Kullanıcı başarıyla güncellendi."];
                }
                break;
        }
    } catch (PDOException $e) {
        // Veritabanı işlem hatalarını yakala
        $_SESSION['message'] = ['type' => 'danger', 'text' => "Veritabanı işlemi hatası: " . $e->getMessage()];
    }

    // İşlem sonrası sayfayı yenile ve GET parametrelerini temizle
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// FİLTRELEME VE ARAMA İŞLEMLERİ İÇİN SORGULARI HAZIRLAMA
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

// username sütununu da seçtiğimizden emin olalım
$sql = "SELECT id, email, password_hash, company_id, role, username FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    // Yeni: username sütununu da arama kriterlerine eklendi
    $sql .= " AND (email LIKE :search OR company_id LIKE :search OR role LIKE :search OR username LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if ($filter === 'admin') {
    $sql .= " AND role = 'Admin'";
} elseif ($filter === 'kullanici') {
    $sql .= " AND role = 'Kullanici'";
}

$sql .= " ORDER BY role DESC, id DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

// Kullanıcıları role göre ayırma (gerekirse)
// Role değerlerinin veritabanında 'Admin' ve 'Kullanici' olarak tam eşleştiğinden emin olun.
$admins = array_filter($users, fn($user) => $user['role'] === 'Admin');
$kullanicilar = array_filter($users, fn($user) => $user['role'] === 'Kullanici');

// Mesajı göster ve temizle
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Yönetim Paneli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1A237E; /* Deep Indigo */
            --primary-light: #3F51B5; /* Indigo */
            --accent-orange: #FF6F00; /* Amber */
            --accent-green: #4CAF50; /* Green */
            --background-gradient-start: #E0F2F7; /* Light Blue */
            --background-gradient-end: #E8EAF6; /* Lighter Indigo */
            --card-bg: #FFFFFF;
            --text-dark: #212121;
            --text-medium: #616161;
            --text-light: #F5F5F5;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            --shadow-strong: rgba(0, 0, 0, 0.25);
        }

        body {
            background: linear-gradient(135deg, var(--background-gradient-start) 0%, var(--background-gradient-end) 100%);
            font-family: 'Roboto', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
            overflow-x: hidden;
        }

        .container {
            max-width: 1100px;
            background-color: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 50px var(--shadow-strong);
            padding: 3.5rem;
            animation: fadeInScaleUp 0.8s ease-out forwards;
        }

        @keyframes fadeInScaleUp {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(30px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid var(--primary-light);
            position: relative;
        }
        .header-section::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 25%;
            height: 6px;
            background-color: var(--accent-orange);
            border-radius: 3px;
            animation: underlineGrow 1s ease-out forwards;
        }

        @keyframes underlineGrow {
            from { width: 0; }
            to { width: 25%; }
        }

        h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 2.8rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: var(--accent-orange);
            border-color: var(--accent-orange);
            border-radius: 12px;
            font-weight: 600;
            padding: 0.9rem 2rem;
            box-shadow: 0 6px 20px rgba(255, 111, 0, 0.4);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-primary:hover {
            background-color: #E65100;
            border-color: #E65100;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 25px rgba(255, 111, 0, 0.6);
        }
        .btn-primary:active {
            transform: translateY(-1px) scale(0.98);
            box-shadow: 0 3px 10px rgba(255, 111, 0, 0.4);
        }

        .filter-search-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 3rem;
            padding: 2rem;
            background-color: #F5F5F5; /* Light Grey */
            border-radius: 15px;
            box-shadow: 0 8px 20px var(--shadow-light);
            animation: fadeIn 0.6s ease-out forwards;
            animation-delay: 0.2s;
            opacity: 0;
        }
        .filter-search-section .btn-group {
            flex-shrink: 0;
        }
        .filter-search-section form {
            flex-grow: 1;
        }

        .btn-group .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            padding: 0.7rem 1.4rem;
            text-transform: capitalize;
        }
        .btn-group .btn-outline-secondary {
            border: 2px solid var(--primary-light);
            color: var(--primary-light);
            background-color: transparent;
        }
        .btn-group .btn-outline-secondary.active {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
            color: white;
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.4);
        }
        .btn-group .btn-outline-secondary:hover:not(.active) {
            background-color: var(--primary-light);
            color: white;
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.3);
            transform: translateY(-2px);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #CFD8DC; /* Light Blue Grey */
            padding: 0.8rem 1.2rem;
            box-shadow: inset 0 1px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            background-color: #ECEFF1; /* Even Lighter Blue Grey */
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 0.25rem rgba(255, 111, 0, 0.25);
            background-color: var(--card-bg);
        }

        .list-section h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px dashed #E0E0E0;
            position: relative;
        }
        .list-section h4::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 10%;
            height: 4px;
            background-color: var(--accent-green);
            border-radius: 2px;
        }

        .user-list-item {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 1.8rem 2.2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px var(--shadow-light);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 180px; /* Kartların aynı boyutta olması için min-height */
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUpStagger 0.6s ease-out forwards;
        }
        /* Her kart için gecikmeli animasyon */
        .user-list-item:nth-child(1) { animation-delay: 0.3s; }
        .user-list-item:nth-child(2) { animation-delay: 0.4s; }
        .user-list-item:nth-child(3) { animation-delay: 0.5s; }
        .user-list-item:nth-child(4) { animation-delay: 0.6s; }
        .user-list-item:nth-child(5) { animation-delay: 0.7s; }
        .user-list-item:nth-child(6) { animation-delay: 0.8s; }

        @keyframes fadeInUpStagger {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-list-item:hover {
            transform: translateY(-10px) scale(1.01);
            box-shadow: 0 15px 40px var(--shadow-medium);
        }

        .user-details {
            flex-grow: 1;
            margin-bottom: 1.2rem;
        }
        .user-details strong {
            font-family: 'Poppins', sans-serif;
            display: block;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }
        .user-details span {
            color: var(--text-medium);
            font-size: 1rem;
            display: block;
            margin-bottom: 0.3rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.8rem;
            justify-content: flex-end;
        }
        .btn-sm {
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.7rem 1.3rem;
            transition: all 0.2s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .btn-info {
            background-color: #64B5F6; /* Light Blue */
            border-color: #64B5F6;
            color: white;
        }
        .btn-info:hover {
            background-color: #42A5F5;
            border-color: #42A5F5;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(100, 181, 246, 0.4);
        }
        .btn-warning {
            background-color: #FFB74D; /* Light Orange */
            border-color: #FFB74D;
            color: var(--text-dark);
        }
        .btn-warning:hover {
            background-color: #FFA726;
            border-color: #FFA726;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 183, 77, 0.4);
        }
        .btn-danger {
            background-color: #E57373; /* Light Red */
            border-color: #E57373;
            color: white;
        }
        .btn-danger:hover {
            background-color: #EF5350;
            border-color: #EF5350;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(229, 115, 115, 0.4);
        }

        .modal-content {
            border-radius: 20px;
            box-shadow: 0 20px 50px var(--shadow-strong);
            animation: modalPopIn 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }
        @keyframes modalPopIn {
            from { opacity: 0; transform: scale(0.8) translateY(-50px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            background: linear-gradient(90deg, var(--primary-dark), var(--primary-light));
            color: white;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 1.8rem 2rem;
            border-bottom: none;
        }
        .modal-header h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: white;
            font-size: 2rem;
        }
        .modal-header .btn-close {
            filter: invert(1);
            opacity: 0.9;
            transition: opacity 0.2s ease;
        }
        .modal-header .btn-close:hover {
            opacity: 1;
        }
        .modal-body {
            padding: 2.5rem 2rem;
        }
        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: none;
        }
        .modal-footer .btn-secondary {
            background-color: #B0BEC5; /* Blue Grey */
            border-color: #B0BEC5;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .modal-footer .btn-secondary:hover {
            background-color: #90A4AE;
            border-color: #90A4AE;
            transform: translateY(-1px);
        }
        .modal-footer .btn-success {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .modal-footer .btn-success:hover {
            background-color: #388E3C;
            border-color: #388E3C;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 15px;
            margin-bottom: 3rem;
            font-weight: 500;
            padding: 1.2rem 1.8rem;
            animation: fadeInDownMessage 0.6s ease-out forwards;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        @keyframes fadeInDownMessage {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background-color: #E8F5E9;
            border-color: #A5D6A7;
            color: #2E7D32;
        }
        .alert-danger {
            background-color: #FFEBEE;
            border-color: #EF9A9A;
            color: #C62828;
        }
        .alert-info {
            background-color: #E3F2FD;
            border-color: #90CAF9;
            color: #1976D2;
        }

        /* Özel onay modalı için stil */
        .custom-confirm-modal {
            display: none;
            position: fixed;
            z-index: 1060;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .custom-confirm-modal-content {
            background-color: var(--card-bg);
            margin: auto;
            padding: 35px;
            border: none;
            width: 90%;
            max-width: 550px;
            border-radius: 25px;
            box-shadow: 0 15px 40px var(--shadow-strong);
            text-align: center;
            animation: zoomInPop 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }
        @keyframes zoomInPop {
            from { opacity: 0; transform: scale(0.7); }
            to { opacity: 1; transform: scale(1); }
        }

        .custom-confirm-modal-content h5 {
            margin-bottom: 35px;
            font-size: 1.8rem;
            color: var(--primary-dark);
            font-weight: 600;
        }
        .custom-confirm-modal-content .btn {
            margin: 0 15px;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .custom-confirm-modal-content .btn-danger {
            background-color: #F44336;
            border-color: #F44336;
            color: white;
        }
        .custom-confirm-modal-content .btn-danger:hover {
            background-color: #D32F2F;
            border-color: #D32F2F;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }
        .custom-confirm-modal-content .btn-secondary {
            background-color: #9E9E9E;
            border-color: #9E9E9E;
            color: white;
        }
        .custom-confirm-modal-content .btn-secondary:hover {
            background-color: #757575;
            border-color: #757575;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(158, 158, 158, 0.4);
        }

        /* Responsive Ayarlamalar */
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
                margin-top: 1.5rem;
                margin-bottom: 1.5rem;
            }
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                margin-bottom: 2rem;
            }
            h2 {
                font-size: 2.2rem;
            }
            .btn-primary {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }
            .filter-search-section {
                flex-direction: column;
                align-items: stretch;
                padding: 1.5rem;
                gap: 1rem;
                margin-bottom: 2rem;
            }
            .filter-search-section .btn-group {
                width: 100%;
                justify-content: center;
            }
            .filter-search-section .btn-group .btn {
                flex-grow: 1;
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
            .user-list-item {
                padding: 1.2rem 1.5rem;
                min-height: 160px;
            }
            .user-details strong {
                font-size: 1.3rem;
            }
            .user-details span {
                font-size: 0.9rem;
            }
            .action-buttons {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 0.8rem;
            }
            .btn-sm {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
            .modal-header {
                padding: 1.2rem 1.5rem;
            }
            .modal-header h5 {
                font-size: 1.5rem;
            }
            .modal-body {
                padding: 1.5rem;
            }
            .custom-confirm-modal-content {
                padding: 25px;
            }
            .custom-confirm-modal-content h5 {
                font-size: 1.3rem;
                margin-bottom: 25px;
            }
            .custom-confirm-modal-content .btn {
                padding: 10px 20px;
                margin: 0 8px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message['text']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="header-section">
        <h2>Kullanıcı Yönetim Paneli</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('create')">Yeni Kullanıcı Ekle</button>
    </div>

    <div class="filter-search-section">
        <div class="btn-group" role="group">
            <a href="?search=<?= urlencode($search) ?>&filter=all" class="btn btn-outline-secondary <?= $filter === 'all' && empty($search) ? 'active' : '' ?>">Tümü</a>
            <a href="?search=<?= urlencode($search) ?>&filter=admin" class="btn btn-outline-secondary <?= $filter === 'admin' ? 'active' : '' ?>">Adminler</a>
            <a href="?search=<?= urlencode($search) ?>&filter=kullanici" class="btn btn-outline-secondary <?= $filter === 'kullanici' ? 'active' : '' ?>">Kullanıcılar</a>
        </div>
        <form method="get" class="d-flex flex-grow-1">
            <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
            <input type="text" name="search" class="form-control me-2" placeholder="Ara..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-secondary" type="submit">Ara</button>
        </form>
    </div>

    <?php if ($filter === 'all' || $filter === 'admin'): ?>
        <?php if (!empty($admins)): ?>
            <div class="list-section">
                <h4>Adminler</h4>
                <div class="row">
                    <?php foreach ($admins as $admin): ?>
                        <div class="col-md-6 mb-4">
                            <div class="user-list-item">
                                <div class="user-details">
                                    <strong><?= htmlspecialchars($admin['username']) ?></strong>
                                    <span>Email: <?= htmlspecialchars($admin['email']) ?></span>
                                    <span>Rol: <?= htmlspecialchars($admin['role']) ?></span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($admin)) ?>)">Detay</button>
                                    <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($admin)) ?>)">Düzenle</button>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id']) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button class="btn btn-sm btn-danger text-white" type="button" onclick="customConfirm(event, this.form, 'Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($filter === 'all' || $filter === 'kullanici'): ?>
        <?php if (!empty($kullanicilar)): ?>
            <div class="list-section">
                <h4 class="mt-4">Kullanıcılar</h4>
                <div class="row">
                    <?php foreach ($kullanicilar as $user): ?>
                        <div class="col-md-6 mb-4">
                            <div class="user-list-item">
                                <div class="user-details">
                                    <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    <span>Email: <?= htmlspecialchars($user['email']) ?></span>
                                    <span>Rol: <?= htmlspecialchars($user['role']) ?></span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($user)) ?>)">Detay</button>
                                    <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($user)) ?>)">Düzenle</button>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button class="btn btn-sm btn-danger text-white" type="button" onclick="customConfirm(event, this.form, 'Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">Kayıtlı kullanıcı bulunamadı.</div>
    <?php endif; ?>

</div>

<!-- Kullanıcı Ekle/Düzenle Modalı -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction">
                    <input type="hidden" name="id" id="formId">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <!-- Kullanıcı Adı alanı eklendi -->
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı Adı</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <!-- type="email" yerine type="text" kullanıldı -->
                        <input type="text" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small class="form-text text-muted" id="passwordHelp">Düzenleme yaparken boş bırakırsanız şifre değişmez.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şirket ID</label>
                        <input type="number" name="company_id" id="company_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="Admin">Admin</option>
                            <option value="Kullanici">Kullanıcı</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-success">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Kullanıcı Detay Modalı -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Detayları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Kullanıcı Adı:</strong> <span id="detail-username"></span></p>
                <p><strong>Email:</strong> <span id="detail-email"></span></p>
                <p><strong>Rol:</strong> <span id="detail-role"></span></p>
                <p><strong>Şirket ID:</strong> <span id="detail-company-id"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- Özel Onay Modalı (alert/confirm yerine) -->
<div id="customConfirmModal" class="custom-confirm-modal">
    <div class="custom-confirm-modal-content">
        <h5 id="customConfirmMessage"></h5>
        <button id="customConfirmYes" class="btn btn-danger">Evet</button>
        <button id="customConfirmNo" class="btn btn-secondary">Hayır</button>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Kullanıcı Ekle/Düzenle modalını hazırlayan fonksiyon
    function prepareModal(action, user = null) {
        document.getElementById('formAction').value = action;
        document.getElementById('formId').value = '';
        document.getElementById('email').value = '';
        document.getElementById('company_id').value = '';
        document.getElementById('role').value = 'Kullanici';
        document.getElementById('password').value = '';
        document.getElementById('username').value = ''; // username temizlendi

        const modalTitle = document.getElementById('modalTitle');
        const passwordHelp = document.getElementById('passwordHelp');
        const passwordInput = document.getElementById('password');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');

        if (action === 'create') {
            modalTitle.textContent = 'Yeni Kullanıcı';
            passwordHelp.style.display = 'none';
            passwordInput.required = true;
            usernameInput.required = true;
            emailInput.required = true;
        } else { // 'update'
            modalTitle.textContent = 'Kullanıcıyı Düzenle';
            document.getElementById('formId').value = user.id;
            document.getElementById('email').value = user.email;
            document.getElementById('company_id').value = user.company_id;
            document.getElementById('role').value = user.role;
            document.getElementById('username').value = user.username;
            passwordHelp.style.display = 'block';
            passwordInput.required = false;
            usernameInput.required = true;
            emailInput.required = true;
        }
    }

    // Kullanıcı detay modalını dolduran fonksiyon
    function showDetails(user) {
        document.getElementById('detail-username').textContent = user.username;
        document.getElementById('detail-email').textContent = user.email;
        document.getElementById('detail-role').textContent = user.role;
        document.getElementById('detail-company-id').textContent = user.company_id;
    }

    // Özel onay modalını gösteren fonksiyon (confirm() yerine)
    function customConfirm(event, form, message) {
        event.preventDefault();
        const confirmModal = document.getElementById('customConfirmModal');
        const confirmMessage = document.getElementById('customConfirmMessage');
        const confirmYes = document.getElementById('customConfirmYes');
        const confirmNo = document.getElementById('customConfirmNo');

        confirmMessage.textContent = message;
        confirmModal.style.display = 'flex';

        confirmYes.onclick = function() {
            confirmModal.style.display = 'none';
            form.submit();
        };

        confirmNo.onclick = function() {
            confirmModal.style.display = 'none';
        };
    }
</script>
</body>
</html>