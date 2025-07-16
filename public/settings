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
    <style>
        body { background-color: #f8f9fa; }
        .card-title { font-size: 1.1rem; font-weight: 600; }
        .btn-sm { font-size: 0.8rem; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kullanıcı Krtı</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('create')">Yeni Kullanıcı Ekle</button>
    </div>

    <div class="d-flex mb-4 gap-3">
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
            <h4 class="mb-3">Admin</h4>
            <div class="row mb-4">
                <?php foreach ($admins as $admin): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($admin['email']) ?></h5>
                                <p class="card-text"><strong>Rol:</strong> <?= htmlspecialchars($admin['role']) ?></p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($admin)) ?>)">Detay</button>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($admin)) ?>)">Düzenle</button>
                                        <form method="post" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" class="d-inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button class="btn btn-sm btn-danger">Sil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($filter === 'all' || $filter === 'kullanici'): ?>
        <?php if (!empty($kullanicilar)): ?>
            <h4 class="mb-3">Kullanıcılar</h4>
            <div class="row">
                <?php foreach ($kullanicilar as $user): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($user['email']) ?></h5>
                                <p class="card-text"><strong>Rol:</strong> <?= htmlspecialchars($user['role']) ?></p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($user)) ?>)">Detay</button>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($user)) ?>)">Düzenle</button>
                                        <form method="post" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" class="d-inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button class="btn btn-sm btn-danger">Sil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
