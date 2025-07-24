<?php
session_start();

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'deneme_db';
$user = 'root';
$pass = 'akdere';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    // Burada hata gösterimi yapabilirsin, şimdilik devam edelim
}

// Kullanıcı bilgileri ve form işlemi (senin kodun aynı kalabilir)

$userEmail = $_SESSION['email'] ?? 'default@email.com';
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';

if (isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT u.email, u.username, c.name as company_name 
                               FROM users u 
                               JOIN companies c ON u.company_id = c.id 
                               WHERE u.email = ?");
        $stmt->execute([$userEmail]);
        $userData = $stmt->fetch();

        if ($userData) {
            $userEmail = $userData['email'];
            $userName = $userData['username'] ?? 'Varsayılan Ad';
            $companyName = $userData['company_name'] ?? 'Varsayılan Şirket';
        }
    } catch (PDOException $e) {
        error_log("Kullanıcı bilgileri alınamadı: " . $e->getMessage());
    }
}
if (isset($_GET['export']) && $_GET['export'] == 1) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=musteriler.csv');
    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'İsim', 'Vergi No', 'Email', 'Telefon', 'Adres', 'İl', 'İlçe']);

    $stmt = $pdo->query("SELECT id, isim, vergi_no, email, telefon, adres, il, ilce FROM cariler WHERE tip = 'musteri' ORDER BY isim ASC");
    while ($row = $stmt->fetch()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit; // Export sonrası sayfanın geri kalan kısmını yazdırma
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Silme işlemi varsa öncelikli kontrol
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        header('Content-Type: application/json; charset=utf-8');
        $id = $_POST['id'] ?? null;
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM cariler WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true]);
                // Ajax veya form sonrası yönlendirme yapabilirsin
                // Örnek: echo json_encode(['success' => true]); exit;
                exit;
                } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Silme işlemi sırasında hata oluştu: ' . $e->getMessage()]);
                exit;
            }
        } else {
             echo json_encode(['success' => false, 'message' => 'Silinecek kayıt bulunamadı.']);
             exit;
        }
    } else {
        // Düzenleme veya ekleme işlemi
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['isim'] ?? '');
        $vergi_no = trim($_POST['vergi_no'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefon = trim($_POST['telefon'] ?? '');
        $adres = trim($_POST['adres'] ?? '');
        $il = trim($_POST['il'] ?? '');
        $ilce = trim($_POST['ilce'] ?? '');

        if ($name !== '') {
            try {
                if ($id) {
                    // Güncelleme
                    $stmt = $pdo->prepare("UPDATE cariler SET isim = ?, vergi_no = ?, email = ?, telefon = ?, adres = ?, il = ?, ilce = ? WHERE id = ?");
                    $stmt->execute([$name, $vergi_no, $email, $telefon, $adres, $il, $ilce, $id]);
                    header("Location: musteriler.php?updated=1");
                    exit;
                } else {
                    // Yeni ekleme
                    $stmt = $pdo->prepare("INSERT INTO cariler (isim, vergi_no, email, telefon, adres, il, ilce, tip) VALUES (?, ?, ?, ?, ?, ?, ?, 'musteri')");
                    $stmt->execute([$name, $vergi_no, $email, $telefon, $adres, $il, $ilce]);
                    header("Location: musteriler.php?success=1");
                    exit;
                }
            } catch (PDOException $e) {
                $error = "Müşteri işlemi sırasında hata oluştu: " . $e->getMessage();
            }
        } else {
            $error = "Müşteri adı zorunludur.";
        }
    }
}



// Veritabanından müşteri verilerini çekiyoruz
$customers = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT id, isim, vergi_no, email, telefon, adres, il, ilce FROM cariler WHERE tip = 'musteri' ORDER BY id ASC");
        $customers = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Müşteri verileri alınamadı: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <!-- Başlık ve stil dosyaları aynı -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Müşteriler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        /* Müşteriler sayfası özel stil */
        .customer-actions-bar {
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .customer-actions-bar .search-box {
            flex-grow: 1;
            max-width: 300px;
        }
        .customer-list-section .table {
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden; /* Köşeleri yuvarlatmak için */
        }
        .customer-list-section .table thead {
            background-color: var(--sidebar-bg);
            color: white;
        }
        .customer-list-section .table th,
        .customer-list-section .table td {
            vertical-align: middle;
        }
        .customer-list-section .table tbody tr:nth-child(even) {
            background-color: #f0f2f5;
        }
        .customer-list-section .table-bordered {
            border: 1px solid var(--border-color);
        }
        .customer-list-section .action-buttons .btn {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        /* Modal Stilleri */
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }
        .modal-header .btn-close {
            filter: invert(1); /* Beyaz çarpı işareti */
        }
        .modal-footer {
            border-top: none;
        }
        /* PROFIL DROPDOWN & WIDGET BAŞLANGIÇ */
        .profile-dropdown {
            position: absolute;
            top: 60px;
            right: 25px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            min-width: 180px;
            z-index: 2000;
            display: none;
        }
        .profile-dropdown.active { display: block; }
        .profile-dropdown ul { list-style: none; margin: 0; padding: 0; }
        .profile-dropdown li { border-bottom: 1px solid #f0f0f0; }
        .profile-dropdown li:last-child { border-bottom: none; }
        .profile-dropdown a, .profile-dropdown button {
            display: block;
            width: 100%;
            padding: 12px 18px;
            background: none;
            border: none;
            text-align: left;
            color: #2c3e50;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .profile-dropdown a:hover, .profile-dropdown button:hover { background: #f5f5f5; }
        .profile-widget {
            position: fixed;
            top: 80px;
            right: 40px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            min-width: 320px;
            z-index: 3000;
            display: none;
            padding: 28px 32px 24px 32px;
        }
        .profile-widget.active { display: block; }
        .profile-widget .profile-title { font-size: 20px; font-weight: bold; margin-bottom: 18px; color: #2c3e50; }
        .profile-widget .profile-row { display: flex; align-items: center; margin-bottom: 12px; }
        .profile-widget .profile-row i { width: 22px; color: #888; margin-right: 10px; }
        .profile-widget .profile-label { font-weight: 500; color: #888; width: 120px; }
        .profile-widget .profile-value { color: #2c3e50; }
        .profile-widget .close-btn { position: absolute; top: 12px; right: 16px; background: none; border: none; font-size: 20px; color: #888; cursor: pointer; }
        /* PROFIL DROPDOWN & WIDGET BİTİŞ */
    </style>
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Müşteriler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
       
        <div class="customer-actions-bar">
            <input type="text" class="form-control search-box" placeholder="Müşteri Ara...">
            <div>
                <a href="musteriler.php?export=1" class="btn btn-info text-white me-2">
                    <i class="fas fa-file-export"></i> Dışa Aktar
                </a>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus"></i> Yeni Müşteri Ekle
                </button>
            </div>
        </div>

        <div class="financial-section customer-list-section">
            <h2 class="section-title">
                <i class="fas fa-users"></i>
                <span>Müşteri Listesi</span>
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Müşteri Adı</th>
                            <th>Vergi Numarası / TCKN</th>
                            <th>E-posta</th>
                            <th>Telefon</th>
                            <th>Adres</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($customers) === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center">Kayıtlı müşteri bulunamadı.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $index => $customer): ?>
                                <tr>
                                    <td><?= htmlspecialchars($index + 1) ?></td>
                                    <td><?= htmlspecialchars($customer['isim']) ?></td>
                                    <td><?= htmlspecialchars($customer['vergi_no']) ?></td>
                                    <td><?= htmlspecialchars($customer['email']) ?></td>
                                    <td><?= htmlspecialchars($customer['telefon']) ?></td>
                                    <td><?= htmlspecialchars($customer['adres']) ?></td>
                                    

                                    <td class="action-buttons">
                                        <button 
                                        class="btn btn-sm btn-warning me-1 edit-btn" 
                                        title="Düzenle"
                                        data-id="<?= $customer['id'] ?>"
                                        data-isim="<?= htmlspecialchars($customer['isim']) ?>"
                                        data-vergi_no="<?= $customer['vergi_no'] ?>"
                                        data-telefon="<?= $customer['telefon'] ?>"
                                        data-email="<?= $customer['email'] ?>"
                                        data-adres="<?= htmlspecialchars($customer['adres']) ?>"
                                        data-il="<?= htmlspecialchars($customer['il'] ?? '') ?>"
                                        data-ilce="<?= htmlspecialchars($customer['ilce'] ?? '') ?>"
                                        >
                                        <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger delete-btn" title="Sil" data-id="<?= $customer['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Müşteri Sayfalama" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Önceki</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sonraki</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal ve form aynı kalabilir -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Yeni Müşteri Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                   <form id="addCustomerForm" method="post" action="">
                    <input type="hidden" id="customerId" name="id">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Müşteri Adı / Unvanı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" name="isim" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerTaxId" class="form-label">Vergi Numarası / T.C. Kimlik Numarası</label>
                            <input type="text" class="form-control" id="customerTaxId" name="vergi_no">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerEmail" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="customerEmail" name="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customerPhone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="customerPhone" name="telefon">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Adres</label>
                            <textarea class="form-control" id="customerAddress" name="adres" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerCity" class="form-label">İl</label>
                                <input type="text" class="form-control" id="customerCity" name="il">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customerDistrict" class="form-label">İlçe</label>
                                <input type="text" class="form-control" id="customerDistrict" name="ilce">
                            </div>
                        </div>
                   </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" id="submitCustomerBtn" form="addCustomerForm" class="btn btn-primary">Müşteriyi Kaydet</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script2.js"></script>
<script>
  window.addEventListener("DOMContentLoaded", function () {
    const newBtn = document.querySelector('button[data-bs-target="#addCustomerModal"]:not(.edit-btn)');
    if (newBtn) {
      newBtn.addEventListener("click", () => {
        const form = document.getElementById("addCustomerForm");
        if (form) form.reset();

        const idInput = document.getElementById("customerId");
        if (idInput) idInput.value = "";

        const modalTitle = document.getElementById("addCustomerModalLabel");
        const submitBtn = document.getElementById("submitCustomerBtn");

        if (modalTitle) modalTitle.textContent = "Yeni Müşteri Ekle";
        if (submitBtn) submitBtn.textContent = "Müşteriyi Kaydet";
      });
    }

    document.querySelectorAll(".edit-btn").forEach((button) => {
      button.addEventListener("click", () => {
        const modal = new bootstrap.Modal(document.getElementById("addCustomerModal"));

        document.getElementById("customerId").value = button.dataset.id || "";
        document.getElementById("customerName").value = button.dataset.isim || "";
        document.getElementById("customerTaxId").value = button.dataset.vergi_no || "";
        document.getElementById("customerEmail").value = button.dataset.email || "";
        document.getElementById("customerPhone").value = button.dataset.telefon || "";
        document.getElementById("customerAddress").value = button.dataset.adres || "";
        document.getElementById("customerCity").value = button.dataset.il || "";
        document.getElementById("customerDistrict").value = button.dataset.ilce || "";

        const modalTitle = document.getElementById("addCustomerModalLabel");
        const submitBtn = document.getElementById("submitCustomerBtn");

        if (modalTitle) modalTitle.textContent = "Müşteri Düzenle";
        if (submitBtn) submitBtn.textContent = "Güncellemeyi Kaydet";

        modal.show();
      });
    });
    document.querySelectorAll('button.btn-danger').forEach(button => {
  button.addEventListener('click', function() {
    const id = this.dataset.id;
    if (!id) return;

    if (!confirm('Müşteriyi silmek istediğinize emin misiniz?')) return;

    fetch('musteriler.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=delete&id=${encodeURIComponent(id)}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Satırı DOM'dan kaldır
        this.closest('tr').remove();
        alert('Müşteri başarıyla silindi.');
      } else {
        alert('Silme sırasında hata: ' + (data.message || 'Bilinmeyen hata'));
      }
    })
    .catch(() => {
      alert('Silme isteği gönderilirken hata oluştu.');
    });
  });
});
  });
</script>

</body>
</html>




