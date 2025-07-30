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
    fputcsv($output, ['ID', 'İsim', 'Vergi No', 'Email', 'Telefon', 'Adres', 'İl', 'İlçe', 'Açıklama']);

    $stmt = $pdo->query("SELECT id, isim, vergi_no, email, telefon, adres, il, ilce, aciklama FROM cariler WHERE tip = 'musteri' ORDER BY isim ASC");
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
        $aciklama = trim($_POST['aciklama'] ?? '');

        if ($name !== '') {
            try {
                if ($id) {
                    // Güncelleme
                    $stmt = $pdo->prepare("UPDATE cariler SET isim = ?, vergi_no = ?, email = ?, telefon = ?, adres = ?, il = ?, ilce = ?, aciklama = ? WHERE id = ?");
                    $stmt->execute([$name, $vergi_no, $email, $telefon, $adres, $il, $ilce, $aciklama, $id]);
                    header("Location: musteriler.php?updated=1");
                    exit;
                } else {
                    // Yeni ekleme
                    $stmt = $pdo->prepare("INSERT INTO cariler (isim, vergi_no, email, telefon, adres, il, ilce, aciklama, tip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'musteri')");
                    $stmt->execute([$name, $vergi_no, $email, $telefon, $adres, $il, $ilce, $aciklama]);
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

// Sayfalama ayarları
$limit = 5; // Her sayfada kaç kayıt gösterilecek
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // 1'den küçükse düzelt
$offset = ($page - 1) * $limit;

$totalRecords = 0;

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM cariler WHERE tip = 'calisan'");
    $totalRecords = (int)$stmt->fetch()['total'];
} catch (PDOException $e) {
    error_log("Toplam kayıt alınamadı: " . $e->getMessage());
}
$totalPages = ceil($totalRecords / $limit);




// Veritabanından müşteri verilerini çekiyoruz
$customers = [];
if (isset($pdo)) {
    try {
    $stmt = $pdo->prepare("SELECT id, isim, vergi_no, email, telefon, adres, il, ilce, aciklama 
                           FROM cariler 
                           WHERE tip = 'musteri' 
                           ORDER BY id ASC 
                           LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
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
    <link href="../../assets/cari/musteriler.css" rel="stylesheet">
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
            <input type="text" class="form-control search-box" id="customerSearch" placeholder="Müşteri Ara...">
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
                <table class="table table-bordered table-hover" id="customerTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Müşteri Adı</th>
                            <th>Vergi Numarası / TCKN</th>
                            <th>E-posta</th>
                            <th>Telefon</th>
                            <th>Adres</th>
                            <th>Açıklama</th>
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
                                    <td><?= htmlspecialchars($customer['aciklama']) ?></td>

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
                                        data-aciklama="<?= htmlspecialchars($customer['aciklama'] ?? '') ?>"
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
        <!-- Önceki -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>">Önceki</a>
        </li>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Sonraki -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>">Sonraki</a>
        </li>
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
                             <div class="mb-3">
                                <label for="customerNote" class="form-label">Açıklama</label>
                                <textarea class="form-control" id="customerNote" name="aciklama" rows="2"></textarea>
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
        document.getElementById("customerNote").value = button.dataset.aciklama || "";

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
 const input = document.getElementById("customerSearch");
    const tbody = document.querySelector("#customerTable tbody");

    input.addEventListener("input", function () {
        const query = this.value.toLowerCase();
        const rows = Array.from(tbody.querySelectorAll("tr"));

        rows.sort((a, b) => {
            const aText = a.textContent.toLowerCase();
            const bText = b.textContent.toLowerCase();

            const aIndex = aText.indexOf(query);
            const bIndex = bText.indexOf(query);

            // Uyum sırasına göre sırala (başta geçen yukarı)
            if (aIndex === -1) return 1;
            if (bIndex === -1) return -1;
            return aIndex - bIndex;
        });

        rows.forEach(row => tbody.appendChild(row));
    });

  });
</script>

</body>
</html>




