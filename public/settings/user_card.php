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
                $username = trim($_POST['username']);

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
                $username = trim($_POST['username']);

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1100px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* Yeni: Tüm kartların aynı boyutta olmasını sağlamak için minimum yükseklik */
            min-height: 200px; /* Bu değeri ihtiyacınıza göre ayarlayabilirsiniz */
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 1.5rem;
            display: flex; /* İçeriği dikeyde hizalamak için flexbox kullan */
            flex-direction: column; /* Öğeleri sütun şeklinde sırala */
            justify-content: space-between; /* İçeriği dikeyde eşit aralıklarla dağıt */
            height: 100%; /* Card'ın tüm yüksekliğini kapla */
        }
        .card-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.1rem; /* Kullanıcı adı için biraz daha büyük font */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-text {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 0.3rem; /* Satırlar arası boşluk azaltıldı */
        }
        h2, h4 {
            color: #212529;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-group .btn {
            border-radius: 8px;
        }
        .form-control {
            border-radius: 8px;
        }
        .modal-content {
            border-radius: 12px;
        }
        /* confirm yerine kullanılacak özel modal için stil */
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kullanıcı Yönetimi</h2>
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
            <h4 class="mb-3">Adminler</h4>
            <div class="row mb-4">
                <?php foreach ($admins as $admin): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($admin['username']) ?></h5>
                                <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
                                <p class="card-text"><strong>Rol:</strong> <?= htmlspecialchars($admin['role']) ?></p>
                                <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($admin)) ?>)">Detay</button>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($admin)) ?>)">Düzenle</button>
                                        <!-- Silme butonu ve formu birleştirildi -->
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button class="btn btn-sm btn-danger" type="button" onclick="customConfirm(event, this.form, 'Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</button>
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
                                <h5 class="card-title"><?= htmlspecialchars($user['username']) ?></h5>
                                <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                                <p class="card-text"><strong>Rol:</strong> <?= htmlspecialchars($user['role']) ?></p>
                                <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetails(<?= htmlspecialchars(json_encode($user)) ?>)">Detay</button>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareModal('update', <?= htmlspecialchars(json_encode($user)) ?>)">Düzenle</button>
                                        <!-- Silme butonu ve formu birleştirildi -->
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <button class="btn btn-sm btn-danger" type="button" onclick="customConfirm(event, this.form, 'Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</button>
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
                    <!-- Yeni: Kullanıcı Adı alanı eklendi -->
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı Adı</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <!-- type="email" yerine type="text" kullanıldı. Bu, tarayıcının katı e-posta formatı doğrulamasını atlayarak,
                             özel karakterler içeren e-posta adreslerinin girilmesine olanak tanır.
                             Sunucu tarafında hala doğrulama yapılmalıdır. -->
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
                <!-- Yeni: Kullanıcı Adı detayı eklendi -->
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
        document.getElementById('username').value = ''; // Yeni: username temizlendi

        const modalTitle = document.getElementById('modalTitle');
        const passwordHelp = document.getElementById('passwordHelp');
        const passwordInput = document.getElementById('password');
        const usernameInput = document.getElementById('username'); // Yeni: username inputu
        const emailInput = document.getElementById('email'); // Email inputunu al

        if (action === 'create') {
            modalTitle.textContent = 'Yeni Kullanıcı';
            passwordHelp.style.display = 'none';
            passwordInput.required = true;
            usernameInput.required = true; // Yeni kullanıcı için username gerekli
            emailInput.required = true; // Email alanı da gerekli
        } else { // 'update'
            modalTitle.textContent = 'Kullanıcıyı Düzenle';
            document.getElementById('formId').value = user.id;
            document.getElementById('email').value = user.email;
            document.getElementById('company_id').value = user.company_id;
            document.getElementById('role').value = user.role;
            document.getElementById('username').value = user.username; // Yeni: username dolduruldu
            passwordHelp.style.display = 'block';
            passwordInput.required = false;
            usernameInput.required = true; // Düzenlemede de username gerekli
            emailInput.required = true; // Email alanı da gerekli
        }
    }

    // Kullanıcı detay modalını dolduran fonksiyon
    function showDetails(user) {
        document.getElementById('detail-username').textContent = user.username; // Yeni: username detayı
        document.getElementById('detail-email').textContent = user.email;
        document.getElementById('detail-role').textContent = user.role;
        document.getElementById('detail-company-id').textContent = user.company_id;
    }

    // Özel onay modalını gösteren fonksiyon (confirm() yerine)
    function customConfirm(event, form, message) {
        event.preventDefault(); // Varsayılan form göndermeyi engelle
        const confirmModal = document.getElementById('customConfirmModal');
        const confirmMessage = document.getElementById('customConfirmMessage');
        const confirmYes = document.getElementById('customConfirmYes');
        const confirmNo = document.getElementById('customConfirmNo');

        confirmMessage.textContent = message;
        confirmModal.style.display = 'flex'; // Modalı göster

        // Evet butonuna basıldığında formu gönder
        confirmYes.onclick = function() {
            confirmModal.style.display = 'none'; // Modalı gizle
            form.submit(); // Formu gönder
        };

        // Hayır butonuna basıldığında modali gizle
        confirmNo.onclick = function() {
            confirmModal.style.display = 'none'; // Modalı gizle
        };
    }
</script>
</body>
</html>
