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
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #fdf2e9 url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" opacity="0.1"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="%23ff9800" /><stop offset="100%" stop-color="%232196f3" /></linearGradient></defs><rect width="100%" height="100%" fill="url(%23g)" /></svg>');
            background-size: cover;
            font-family: 'Rubik', sans-serif;
            color: #444;
            min-height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 3rem;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 2rem;
            border-bottom: 2px solid #eee;
            margin-bottom: 2rem;
        }
        h2 {
            font-weight: 700;
            color: #ff5722;
        }
        .btn-primary {
            background-color: #ff9800;
            border-color: #ff9800;
            border-radius: 30px;
            font-weight: 500;
            padding: 10px 24px;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #f57c00;
            border-color: #f57c00;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 124, 0, 0.5);
        }
        .filter-search-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .btn-group .btn {
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-group .btn-outline-secondary {
            border: 2px solid #ccc;
            color: #666;
        }
        .btn-group .btn-outline-secondary.active {
            background-color: #2196f3;
            border-color: #2196f3;
            color: white;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.4);
        }
        .form-control, .form-select {
            border-radius: 30px;
            border: 2px solid #ccc;
            padding: 10px 20px;
            box-shadow: none;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 0.25rem rgba(33, 150, 243, 0.25);
        }
        .list-section h4 {
            font-weight: 700;
            color: #2196f3;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        .user-list-item {
            background-color: #fff;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            display: flex; /* Flexbox etkinleştirildi */
            flex-direction: row; /* İçerik yatayda sıralandı */
            align-items: center; /* Dikeyde ortalandı */
            justify-content: space-between; /* Kullanıcı bilgileri sola, butonlar sağa dağıtıldı */
            min-height: 100px; /* Kartların aynı boyutta olması için min-height */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .user-list-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .user-details {
            flex-grow: 1; /* Mevcut alanı kaplayarak butonları sağa iter */
            display: flex; /* Kendi içindeki elemanları da flex olarak ayarla */
            flex-direction: column; /* Kullanıcı adı, email, rol dikey sıralandı */
            /* gap: 0.25rem; */ /* İsteğe bağlı: detaylar arasında boşluk */
        }
        .user-details strong {
            font-size: 1.1rem;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0.25rem; /* İsim ile email arasında boşluk */
        }
        .user-details span {
            color: #64748b;
            font-size: 0.9rem;
            display: block; /* Email ve Rolü ayrı satırlarda göster */
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0; /* Artık üstte boşluğa gerek yok */
            flex-shrink: 0; /* Butonların sıkışmasını engelle */
        }
        .btn-sm {
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .btn-info {
            background-color: #4caf50;
            border-color: #4caf50;
        }
        .btn-info:hover {
            background-color: #388e3c;
            border-color: #388e3c;
        }
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-warning:hover {
            background-color: #f57c00;
            border-color: #f57c00;
        }
        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }
        .modal-content {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .modal-header h5 {
            font-weight: 700;
            color: #212121;
        }
        .alert-info {
            background-color: #e3f2fd;
            border-color: #bbdefb;
            color: #1976d2;
            border-radius: 10px;
        }
        /* Özel onay modalı için stil */
        .custom-confirm-modal {
            display: none; /* Varsayılan olarak gizli */
            position: fixed;
            z-index: 1060; /* Bootstrap modalından daha yüksek */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .custom-confirm-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
        }
        .custom-confirm-modal-content h5 {
            margin-bottom: 20px;
        }
        .custom-confirm-modal-content .btn {
            margin: 0 10px;
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
                <?php foreach ($admins as $admin): ?>
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
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($filter === 'all' || $filter === 'kullanici'): ?>
        <?php if (!empty($kullanicilar)): ?>
            <div class="list-section">
                <h4 class="mt-4">Kullanıcılar</h4>
                <?php foreach ($kullanicilar as $user): ?>
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
                <?php endforeach; ?>
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
                <p><strong>Kullanıcı Adı:</strong> <span id="detail-username"></span></p> <!-- Kullanıcı adı detayı eklendi -->
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