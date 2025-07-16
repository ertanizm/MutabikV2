<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// VERİTABANI BAĞLANTISI
$host = 'localhost';
$dbname = 'master_db';
$user = 'root';
$pass = 'akdere';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// CSRF TOKEN OLUŞTURMA VE KONTROL
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verifyCsrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token doğrulama hatası.");
    }
}

// FORM İŞLEMLERİ (Ekleme, Düzenleme, Silme)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'delete':
            if (isset($_POST['id'])) {
                $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
                $stmt->execute([':id' => (int)$_POST['id']]);
            }
            break;

        case 'create':
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];
            $company_id = (int)$_POST['company_id'];

            if (!empty($email) && !empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (email, password_hash, role, company_id) VALUES (:email, :password_hash, :role, :company_id)");
                $stmt->execute([
                    ':email' => $email,
                    ':password_hash' => $hashedPassword,
                    ':role' => $role,
                    ':company_id' => $company_id
                ]);
            }
            break;

        case 'update':
            $id = (int)$_POST['id'];
            $email = trim($_POST['email']);
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'];
            $company_id = (int)$_POST['company_id'];

            if (!empty($email)) {
                $query = "UPDATE users SET email = :email, role = :role, company_id = :company_id";
                $params = [
                    ':email' => $email,
                    ':role' => $role,
                    ':company_id' => $company_id,
                    ':id' => $id
                ];

                if (!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $query .= ", password_hash = :password_hash";
                    $params[':password_hash'] = $hashedPassword;
                }

                $query .= " WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute($params);
            }
            break;
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// FİLTRELEME VE ARAMA İŞLEMLERİ İÇİN SORGULARI HAZIRLAMA
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (email LIKE :search OR company_id LIKE :search OR role LIKE :search)";
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

$admins = array_filter($users, fn($user) => $user['role'] === 'Admin');
$kullanicilar = array_filter($users, fn($user) => $user['role'] === 'Kullanici');

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
            background-color: white;
            border-radius: 15px;
            padding: 1.5rem 2rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .user-list-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .user-details {
            flex-grow: 1;
        }
        .user-details strong {
            display: block;
            font-size: 1.2rem;
            font-weight: 500;
            color: #212121;
        }
        .user-details span {
            color: #757575;
            font-size: 0.9rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.75rem;
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
    </style>
</head>
<body class="py-5">

<div class="container">
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
                <h4>Admin Kullanıcılar</h4>
                <?php foreach ($admins as $admin): ?>
                    <div class="user-list-item">
                        <div class="user-details">
                            <strong><?= htmlspecialchars($admin['email']) ?></strong>
                            <span>Rol: <?= htmlspecialchars($admin['role']) ?></span>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($admin)) ?>)">Detay</button>
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($admin)) ?>)">Düzenle</button>
                            <form method="post" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" class="d-inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id']) ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button class="btn btn-sm btn-danger text-white">Sil</button>
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
                            <strong><?= htmlspecialchars($user['email']) ?></strong>
                            <span>Rol: <?= htmlspecialchars($user['role']) ?></span>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($user)) ?>)">Detay</button>
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($user)) ?>)">Düzenle</button>
                            <form method="post" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" class="d-inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button class="btn btn-sm btn-danger text-white">Sil</button>
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
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
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

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Detayları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function prepareModal(action, user = null) {
        document.getElementById('formAction').value = action;
        document.getElementById('formId').value = '';
        document.getElementById('email').value = '';
        document.getElementById('company_id').value = '';
        document.getElementById('role').value = 'Kullanici';
        document.getElementById('password').value = '';

        const modalTitle = document.getElementById('modalTitle');
        const passwordHelp = document.getElementById('passwordHelp');
        const passwordInput = document.getElementById('password');

        if (action === 'create') {
            modalTitle.textContent = 'Yeni Kullanıcı';
            passwordHelp.style.display = 'none';
            passwordInput.required = true;
        } else { // 'update'
            modalTitle.textContent = 'Kullanıcıyı Düzenle';
            document.getElementById('formId').value = user.id;
            document.getElementById('email').value = user.email;
            document.getElementById('company_id').value = user.company_id;
            document.getElementById('role').value = user.role;
            passwordHelp.style.display = 'block';
            passwordInput.required = false;
        }
    }

    function showDetails(user) {
        document.getElementById('detail-email').textContent = user.email;
        document.getElementById('detail-role').textContent = user.role;
        document.getElementById('detail-company-id').textContent = user.company_id;
    }
</script>
</body>
</html>