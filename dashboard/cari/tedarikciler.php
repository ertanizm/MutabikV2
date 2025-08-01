<?php

require '../../config/config.php';
try {
   $pdo = getTenantPDO();
} catch (Exception $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

try {
   
    // Sayfalama ayarları
    $limit = 2; // Her sayfada gösterilecek kayıt sayısı
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // Toplam kayıt sayısı
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM cariler WHERE tip = 'tedarikci'");
    $total = $totalStmt->fetchColumn();
    $pages = ceil($total / $limit);

    // Sayfaya göre tedarikçileri getir
    $stmt = $pdo->prepare("SELECT * FROM cariler WHERE tip = 'tedarikci' ORDER BY id ASC LIMIT :start, :limit");
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['export']) && $_GET['export'] == 'csv') {
        $stmt = $pdo->prepare("SELECT isim, vergi_no, email, telefon, adres, il, ilce, aciklama FROM cariler WHERE tip = 'tedarikci'");
        $stmt->execute();
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=tedarikciler.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Adı', 'Vergi No', 'E-posta', 'Telefon', 'Adres', 'İl', 'İlçe', 'Açıklama']);

        foreach ($suppliers as $supplier) {
            fputcsv($output, $supplier);
        }

        fclose($output);
        exit;
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
// Tedarikçi silme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_supplier'])) {
    $id = $_POST['supplier_id'];
    $stmt = $pdo->prepare("DELETE FROM cariler WHERE id = ?");
    $stmt->execute([$id]);

    echo "<script>alert('Tedarikçi başarıyla silindi!'); window.location.href='tedarikciler.php';</script>";
    exit;
}

// Tedarikçi ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supplier'])) {
    $name = $_POST['customerName'] ?? '';
    $tax_id = $_POST['customerTaxId'] ?? '';
    $email = $_POST['customerEmail'] ?? '';
    $phone = $_POST['customerPhone'] ?? '';
    $address = $_POST['customerAddress'] ?? '';
    $city = $_POST['customerCity'] ?? '';
    $district = $_POST['customerDistrict'] ?? '';
    $note = $_POST['customerNote'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO cariler (isim, vergi_no, email, telefon, adres, il, ilce,aciklama,tip) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?,'tedarikci')");
    $stmt->execute([$name, $tax_id, $email, $phone, $address, $city, $district, $note]);

    echo "<script>alert('Tedarikçi başarıyla eklendi!'); window.location.href='tedarikciler.php';</script>";
    exit;
}

// Tedarikçi güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_supplier'])) {
    $id = $_POST['supplier_id'];
    $name = $_POST['customerName'] ?? '';
    $tax_id = $_POST['customerTaxId'] ?? '';
    $email = $_POST['customerEmail'] ?? '';
    $phone = $_POST['customerPhone'] ?? '';
    $address = $_POST['customerAddress'] ?? '';
    $city = $_POST['customerCity'] ?? '';
    $district = $_POST['customerDistrict'] ?? '';
    $note = $_POST['customerNote'] ?? '';

    $stmt = $pdo->prepare("UPDATE cariler 
                           SET isim = ?, vergi_no = ?, email = ?, telefon = ?, adres = ?, il = ?, ilce = ?, aciklama = ? 
                           WHERE id = ?");
    $stmt->execute([$name, $tax_id, $email, $phone, $address, $city, $district, $note, $id]);

    echo "<script>alert('Tedarikçi başarıyla güncellendi!'); window.location.href='tedarikciler.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Müşteriler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/cari/tedarikciler.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Tedarikçiler</h1>
            </div>

            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="customer-actions-bar">
            <input type="text" id="searchInput" class="form-control search-box" placeholder="Tedarikçi Ara...">
            <div>
                <a href="tedarikciler.php?export=csv" class="btn btn-info text-white me-2">
                    <i class="fas fa-file-export"></i> Dışa Aktar
                </a>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus"></i> Yeni Tedarikçi Ekle
                </button>
            </div>
        </div>

        <div class="financial-section customer-list-section">
            <h2 class="section-title">
                <i class="fas fa-users"></i>
                Tedarikçi Listesi
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="supplierTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tedarikçi Adı</th>
                            <th>Vergi Numarası / TCKN</th>
                            <th>E-posta</th>
                            <th>Telefon</th>
                            <th>Adres</th>
                            <th>AÇIKLAMA</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($suppliers as $index => $supplier): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($supplier['isim']) ?></td>
                                <td><?= htmlspecialchars($supplier['vergi_no']) ?></td>
                                <td><?= htmlspecialchars($supplier['email']) ?></td>
                                <td><?= htmlspecialchars($supplier['telefon']) ?></td>
                                <td><?= htmlspecialchars($supplier['adres']) ?></td>
                                <td><?= htmlspecialchars($supplier['aciklama']) ?></td>
                                <td class="action-buttons">
                                    <!-- Düzenle Butonu -->
                                    <button class="btn btn-sm btn-warning me-1" title="Düzenle" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $supplier['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Silme Formu -->
                                    <form method="POST" action="tedarikciler.php"
                                        onsubmit="return confirm('Bu tedarikçiyi silmek istediğinize emin misiniz?');"
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
                    <!-- Düzenleme Modalı -->
                    <?php foreach ($suppliers as $supplier): ?>
                        <div class="modal fade" id="editModal<?= $supplier['id'] ?>" tabindex="-1"
                            aria-labelledby="editModalLabel<?= $supplier['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form method="POST" action="tedarikciler.php">
                                        <input type="hidden" name="edit_supplier" value="1">
                                        <input type="hidden" name="supplier_id" value="<?= $supplier['id'] ?>">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?= $supplier['id'] ?>">Tedarikçi
                                                Düzenle</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Kapat"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Tedarikçi Adı / Unvanı</label>
                                                <input type="text" class="form-control" name="customerName"
                                                    value="<?= htmlspecialchars($supplier['isim']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Vergi No / TCKN</label>
                                                <input type="text" class="form-control" name="customerTaxId"
                                                    value="<?= htmlspecialchars($supplier['vergi_no']) ?>">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">E-posta</label>
                                                    <input type="email" class="form-control" name="customerEmail"
                                                        value="<?= htmlspecialchars($supplier['email']) ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Telefon</label>
                                                    <input type="text" class="form-control" name="customerPhone"
                                                        value="<?= htmlspecialchars($supplier['telefon']) ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Adres</label>
                                                <textarea class="form-control"
                                                    name="customerAddress"><?= htmlspecialchars($supplier['adres']) ?></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">İl</label>
                                                    <input type="text" class="form-control" name="customerCity"
                                                        value="<?= htmlspecialchars($supplier['il']) ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">İlçe</label>
                                                    <input type="text" class="form-control" name="customerDistrict"
                                                        value="<?= htmlspecialchars($supplier['ilce']) ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">AÇIKLAMA</label>
                                                <textarea class="form-control"
                                                    name="customerNote"><?= htmlspecialchars($supplier['aciklama']) ?></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">İptal</button>
                                            <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
            <nav aria-label="Müşteri Sayfalama" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Önceki</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Sonraki</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>

    <!-- Tedarikçi Ekle Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="tedarikciler.php">
                    <input type="hidden" name="add_supplier" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Yeni Tedarikçi Ekle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Tedarikçi Adı / Unvanı <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" name="customerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerTaxId" class="form-label">Vergi Numarası / T.C. Kimlik Numarası</label>
                            <input type="text" class="form-control" id="customerTaxId" name="customerTaxId">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerEmail" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="customerEmail" name="customerEmail">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customerPhone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="customerPhone" name="customerPhone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Adres</label>
                            <textarea class="form-control" id="customerAddress" name="customerAddress"
                                rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerCity" class="form-label">İl</label>
                                <input type="text" class="form-control" id="customerCity" name="customerCity">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customerDistrict" class="form-label">İlçe</label>
                                <input type="text" class="form-control" id="customerDistrict" name="customerDistrict">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerNote" class="form-label">AÇIKLAMA</label>
                            <textarea class="form-control" id="customerNote" name="customerNote" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Tedarikçiyi Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#supplierTable tbody tr");
            let firstMatchFound = false;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const match = text.includes(filter);
                row.style.display = match ? "" : "none";

                // İlk eşleşen satıra scroll yap
                if (match && !firstMatchFound) {
                    row.scrollIntoView({ behavior: "smooth", block: "center" });
                    firstMatchFound = true;
                }
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>

  

</body>

</html>
