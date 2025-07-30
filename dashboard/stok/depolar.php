<?php
// 1. Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'abc_db'; // Veritabanı adını kendi projenle aynı yap
$user = 'root';
$pass = ''; // XAMPP kullanıyorsan genellikle boş olur

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Yeni Depo ekleme (NOT TOUCHED as per request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supplier'])) {
    $depo_adi = $_POST['depo_adi'] ?? '';
    $depo_adresi = $_POST['depo_adresi'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    $yetkili = $_POST['yetkili'] ?? '';
    // Durum alanı eklenmemiş, önceki kodda vardı, ancak kullanıcı "dokunma" dediği için burada eklemiyorum.
    // Eğer ekleme formunda durum alanı varsa ve kaydedilmesini istiyorsanız bu satırı eklemelisiniz:
    // $durum = $_POST['durum'] ?? 'aktif'; 

    $stmt = $pdo->prepare("INSERT INTO depolar (depo_adi, depo_adresi, telefon, yetkili) 
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([$depo_adi, $depo_adresi, $telefon, $yetkili]);

    echo "<script>alert('Yeni Depo başarıyla eklendi!'); window.location.href='depolar.php';</script>";
    exit;
}

// Depolar güncelleme (ONLY THIS SECTION HAS BEEN MODIFIED)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_supplier'])) {
    $id = $_POST['id'] ?? null; // Use null coalescing for safety
    $depo_adi = $_POST['depo_adi'] ?? '';
    $depo_adresi = $_POST['depo_adresi'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    $yetkili = $_POST['yetkili'] ?? '';
    $durum = $_POST['durum'] ?? 'aktif'; // IMPORTANT: Get the 'durum' value from the form

    // Basic validation for ID
    if (!$id || !is_numeric($id)) {
        echo "<script>alert('Geçersiz Depo ID\'si.'); window.location.href='depolar.php?_t=" . time() . "';</script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE depolar 
                                SET depo_adi = ?, depo_adresi = ?, telefon = ?, yetkili = ?, durum = ? 
                                WHERE id = ?"); // IMPORTANT: 'durum' added to the UPDATE query
        $stmt->execute([$depo_adi, $depo_adresi, $telefon, $yetkili, $durum, $id]);

        // Check if any rows were affected by the update
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Depo bilgileri başarıyla güncellendi!'); window.location.href='depolar.php?_t=" . time() . "';</script>";
        } else {
            // This means the query ran, but no rows were updated (e.g., ID not found, or no changes made)
            echo "<script>alert('Depo bilgileri güncellenemedi veya herhangi bir değişiklik yapılmadı.'); window.location.href='depolar.php?_t=" . time() . "';</script>";
        }
    } catch (PDOException $e) {
        // Log the actual database error for debugging (check your server's PHP error log)
        error_log("Depo güncelleme hatası: " . $e->getMessage() . " - ID: " . $id . " - Data: " . json_encode($_POST));
        echo "<script>alert('Depo güncelleme hatası: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='depolar.php?_t=" . time() . "';</script>";
    }
    exit;
}

// Depo Silme (NOT TOUCHED as per request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_supplier'])) {
    $id = $_POST['supplier_id'];
    $stmt = $pdo->prepare("DELETE FROM depolar WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Depo başarıyla silindi!'); window.location.href='depolar.php';</script>";
    exit;
}

// Verileri çekme
$stmt = $pdo->query("SELECT * FROM depolar ORDER BY id ASC");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Depolar - Mutabık</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <!-- Özel CSS -->
    <link rel="stylesheet" href="../dashboard.css" />
    <style>
        /* Top Header */
        .top-header {
            background-color: white;
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color, #ddd);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 24px;
            color: var(--text-primary, #333);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content p-4">
        <!-- Üst Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Depolar</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <!-- Depo Ekle Butonu -->
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#depoEkleModal">
                <i class="fas fa-plus"></i> Depo Ekle
            </button>
        </div>

        <!-- Depo Listesi Tablosu -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Depo Adı</th>
                        <th>Adres</th>
                        <th>Telefon</th>
                        <th>Yetkili</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody id="depoTableBody">
                    <?php foreach ($suppliers as $index => $supplier): ?>
                        
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($supplier['depo_adi']) ?></td>
                            <td><?= htmlspecialchars($supplier['depo_adresi']) ?></td>
                            <td><?= htmlspecialchars($supplier['telefon']) ?></td>
                            <td><?= htmlspecialchars($supplier['yetkili']) ?></td>

                            <td>
                                <?php if (isset($supplier['durum']) && $supplier['durum'] == 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pasif</span>
                                <?php endif; ?>
                            </td>

                            <td class="action-buttons">
                                <!-- Düzenle Butonu -->
                                <button class="btn btn-sm btn-warning me-1" title="Düzenle" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $supplier['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Silme Formu -->
                                <form method="POST" action="depolar.php"
                                    onsubmit="return confirm('Bu depoyu silmek istediğinize emin misiniz?');"
                                    style="display:inline;">
                                    <input type="hidden" name="delete_supplier" value="1">
                                    <input type="hidden" name="supplier_id" value="<?= $supplier['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

    <!-- Depo Ekle Modal -->
    <div class="modal fade" id="depoEkleModal" tabindex="-1" aria-labelledby="depoEkleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="depoEkleForm" class="modal-content" method="POST">
                <input type="hidden" name="add_supplier" value="1">
                <div class="modal-header">
                    <h5 class="modal-title" id="depoEkleModalLabel">Yeni Depo Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="depoAdi" class="form-label">Depo Adı</label>
                        <input type="text" class="form-control" id="depoAdi" name="depo_adi" required />
                    </div>
                    <div class="mb-3">
                        <label for="depoAdres" class="form-label">Adres</label>
                        <textarea class="form-control" id="depoAdres" name="depo_adresi" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="depoTelefon" class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="depoTelefon" name="telefon" />
                    </div>
                    <div class="mb-3">
                        <label for="depoYetkili" class="form-label">Yetkili</label>
                        <input type="text" class="form-control" id="depoYetkili" name="yetkili" />
                    </div>
                    <div class="mb-3">
                        <label for="depoDurum" class="form-label">Durum</label>
                        <select id="depoDurum" name="durum" class="form-select" required>
                            <option value="aktif" selected>Aktif</option>
                            <option value="pasif">Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Depo Modals (one for each supplier) -->
    <?php foreach ($suppliers as $supplier): ?>
    <div class="modal fade" id="editModal<?= $supplier['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $supplier['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST">
                <input type="hidden" name="edit_supplier" value="1">
                <input type="hidden" name="id" value="<?= $supplier['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?= $supplier['id'] ?>">Depo Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Depo Adı</label>
                        <input type="text" class="form-control" name="depo_adi" value="<?= htmlspecialchars($supplier['depo_adi']) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <textarea class="form-control" name="depo_adresi" rows="2" required><?= htmlspecialchars($supplier['depo_adresi']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="tel" class="form-control" name="telefon" value="<?= htmlspecialchars($supplier['telefon']) ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yetkili</label>
                        <input type="text" class="form-control" name="yetkili" value="<?= htmlspecialchars($supplier['yetkili']) ?>" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durum</label>
                        <select name="durum" class="form-select" required>
                            <option value="aktif" <?= (isset($supplier['durum']) && $supplier['durum'] == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="pasif" <?= (isset($supplier['durum']) && $supplier['durum'] == 'pasif') ? 'selected' : '' ?>>Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // CLIENT-SIDE JAVASCRIPT FOR ADDING (NOT TOUCHED as per request)
            
            const depoAdi = document.getElementById('depoAdi').value.trim();
            const depoAdres = document.getElementById('depoAdres').value.trim();
            const depoTelefon = document.getElementById('depoTelefon').value.trim();
            const depoYetkili = document.getElementById('depoYetkili').value.trim();
            const depoDurum = document.getElementById('depoDurum').value;

            if (!depoAdi || !depoAdres) {
                alert('Depo adı ve adresi zorunludur.');
                return;
            }

            // This part only manipulates the DOM, it does NOT send data to PHP backend for addition.
            // The PHP 'add_supplier' block handles actual database insertion.
            const tbody = document.getElementById('depoTableBody');
            const newRow = document.createElement('tr');
            const newId = tbody.children.length + 1; // This ID is client-side only

            newRow.innerHTML = `
                <td>${newId}</td>
                <td>${depoAdi}</td>
                <td>${depoAdres}</td>
                <td>${depoTelefon}</td>
                <td>${depoYetkili}</td>
                <td><span class="badge bg-${depoDurum === 'aktif' ? 'success' : 'secondary'}">${depoDurum.charAt(0).toUpperCase() + depoDurum.slice(1)}</span></td>
                <td>
                    <button class="btn btn-sm btn-warning" title="Düzenle"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
            

            const modal = bootstrap.Modal.getInstance(document.getElementById('depoEkleModal'));
            modal.hide();
            this.reset();
        

        // CLIENT-SIDE JAVASCRIPT FOR DELETION (NOT TOUCHED as per request)
        document.getElementById('depoTableBody').addEventListener('click', function (e) {
            const button = e.target.closest('button.btn-danger');
            if (button) {
                const confirmSil = confirm('Bu depoyu silmek istediğinizden emin misiniz?');
                if (confirmSil) {
                    // This part only removes the row from the DOM, it does NOT send data to PHP backend for deletion.
                    // The PHP 'delete_supplier' block handles actual database deletion.
                    const tr = button.closest('tr');
                    tr.remove();
                }
            }
        });

        // This console log helps confirm if the cache-busting parameter is working
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Page loaded with URL:", window.location.href);
        });
    </script>
     <script src="../script2.js"></script> 
</body>

</html>
