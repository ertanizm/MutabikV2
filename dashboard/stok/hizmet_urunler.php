<?php
require_once __DIR__ . '/../../config/db_connect.php';

// ===============================================
// ÜRÜN EKLEME VEYA GÜNCELLEME İŞLEMİ (POST isteği ile)
// ===============================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_or_update_product') {
    $product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $stok_adi = $_POST['productName'];
    $stok_kodu = $_POST['stockCode'] ?? null;
    $barkod_no = $_POST['barcodeNumber'] ?? null;
    $kategori = $_POST['category'] ?? null;
    $birim = $_POST['purchaseSaleUnit'] ?? null;

    $alis_fiyat = $_POST['purchasePriceExclTax'] !== '' ? floatval($_POST['purchasePriceExclTax']) : null;
    $alis_fiyat_kdvli = $_POST['purchasePriceInclTax'] !== '' ? floatval($_POST['purchasePriceInclTax']) : null;
    $satis_fiyat = $_POST['salePriceExclTax'] !== '' ? floatval($_POST['salePriceExclTax']) : null;
    $satis_fiyat_kdvli = $_POST['salePriceInclTax'] !== '' ? floatval($_POST['salePriceInclTax']) : null;
    $kdv_orani = $_POST['kdvRate'] !== '' ? floatval($_POST['kdvRate']) : null;

    $kritik_stok = $_POST['criticalStock'] !== '' ? intval($_POST['criticalStock']) : null;
    $stok_takip = isset($_POST['stockTracking']) ? 1 : 0;
    $aciklama = $_POST['productDescription'] ?? null;
    $miktar = $_POST['miktar'] !== '' ? intval($_POST['miktar']) : null;

    $urun_fotografi_yolu = null;
    if (isset($_FILES['productPhoto']) && $_FILES['productPhoto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = uniqid() . "_" . basename($_FILES["productPhoto"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["productPhoto"]["tmp_name"]);
        if ($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["productPhoto"]["tmp_name"], $target_file)) {
                $urun_fotografi_yolu = $file_name;
            }
        }
    }

    if ($product_id == 0) {
        $stmt = $pdo->prepare("INSERT INTO stoklar (stok_adi, stok_kodu, barkod_no, kategori, birim, foto, alis_fiyat, alis_fiyat_kdvli, satis_fiyat, satis_fiyat_kdvli, kdv_orani, kritik_stok, stok_takip, aciklama, miktar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$stok_adi, $stok_kodu, $barkod_no, $kategori, $birim, $urun_fotografi_yolu, $alis_fiyat, $alis_fiyat_kdvli, $satis_fiyat, $satis_fiyat_kdvli, $kdv_orani, $kritik_stok, $stok_takip, $aciklama, $miktar]);
        echo "<script>alert('Ekleme başarılı'); window.location.href = 'hizmet_urunler.php';</script>";
    } else {
        $sql = "UPDATE stoklar SET stok_adi=?, stok_kodu=?, barkod_no=?, kategori=?, birim=?, alis_fiyat=?, alis_fiyat_kdvli=?, satis_fiyat=?, satis_fiyat_kdvli=?, kdv_orani=?, kritik_stok=?, stok_takip=?, aciklama=?, miktar=?";
        $params = [$stok_adi, $stok_kodu, $barkod_no, $kategori, $birim, $alis_fiyat, $alis_fiyat_kdvli, $satis_fiyat, $satis_fiyat_kdvli, $kdv_orani, $kritik_stok, $stok_takip, $aciklama, $miktar];

        if ($urun_fotografi_yolu) {
            $sql .= ", foto=?";
            $params[] = $urun_fotografi_yolu;
        }
        $sql .= " WHERE id=?";
        $params[] = $product_id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo "<script>alert('Güncelleme başarılı'); window.location.href = 'hizmet_urunler.php';</script>";
    }
}

// ===============================================
// ÜRÜN SİLME İŞLEMİ
// ===============================================
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM stoklar WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Silme başarılı'); window.location.href = 'hizmet_urunler.php';</script>";
}

// ===============================================
// ÜRÜN VERİSİ GETİRME
// ===============================================
if (isset($_GET['action']) && $_GET['action'] == 'fetch_product' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM stoklar WHERE id = ?");
    $stmt->execute([$id]);
    $product_data = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($product_data);
    exit;
}

// ===============================================
// ÜRÜNLERİ LİSTELEME
// ===============================================
$sql = "SELECT id, stok_adi, miktar FROM stoklar";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmet ve Ürünler - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/stok/hizmet-urunler.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>

   <div class="main-content p-4">
        <!-- Üst Header -->
        <div class="top-header">
            <div class="header-left">
            <h1>Hizmet ve Ürünler</h1>
        </div>
            <div class="header-right">
        <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
        </div>
    </div>

        <div class="content-area">
            <div class="filter-section">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle filter-dropdown-button" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        FİLTRELE
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item" href="#">Tümü</a></li>
                        <li><a class="dropdown-item" href="#">Aktif</a></li>
                        <li><a class="dropdown-item" href="#">Pasif</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Hizmet</a></li>
                        <li><a class="dropdown-item" href="#">Ürün</a></li>
                    </ul>
                </div>
                <input type="text" class="search-input" placeholder="Ara...">

                <button type="button" class="add-product-btn" data-bs-toggle="modal" data-bs-target="#updateProductModal" data-modal-title="Yeni Ürün / Hizmet Ekle">
                    Ürün Ekle +
                </button>
            </div>

            <div class="data-table-header">
                <div class="col-name">ADI</div>
                <div class="col-stock">STOK MİKTARI</div>
                <div class="col-price">ALIŞ (VERGİLER HARİÇ)</div>
                <div class="col-price">SATIŞ (VERGİLER HARİÇ)</div>
                <div class="actions-column">İŞLEMLER</div>
            </div>

            <div class="data-table-container <?php echo count($products) > 0 ? 'has-data' : ''; ?>">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="data-table-row">
                            <div class="col-name"><?php echo htmlspecialchars($product['stok_adi']); ?></div>
                            <div class="col-stock"><?php echo htmlspecialchars($product['miktar'] ?? '0'); ?> Adet</div>
                            <div class="col-price"><?php echo number_format($product['alis_fiyat'] ?? 0, 2, ',', '.') . ' TL'; ?></div>
                            <div class="col-price"><?php echo number_format($product['satis_fiyat'] ?? 0, 2, ',', '.') . ' TL'; ?></div>
                            <div class="actions-column">
                                <button class="action-button update-btn" title="Güncelle" data-bs-toggle="modal" data-bs-target="#updateProductModal" data-modal-title="Ürün / Hizmet Güncelle" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?action=delete&id=<?php echo $product['id']; ?>" class="action-button delete-btn" title="Sil" onclick="return confirm('Bu ürünü/hizmeti silmek istediğinizden emin misiniz?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Henüz kayıtlı hizmet veya ürün bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
            <div class="footer-actions">
                <button class="dropdown-btn">
                    TÜM KAYITLAR
                </button>
                <button class="dropdown-btn">
                    İÇE/DIŞA AKTAR
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProductModalLabel">Ürün / Hizmet Güncelle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm" method="POST" enctype="multipart/form-data" action="hizmet_urunler.php">
                        <input type="hidden" name="action" value="add_or_update_product">
                        <input type="hidden" name="id" id="productId">

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="productName" class="form-label">Ürün Adı / Hizmet Adı</label>
                                <input type="text" class="form-control" id="productName" name="productName" placeholder="Ürün veya hizmet adını giriniz" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="stockCode" class="form-label">Stok Kodu</label>
                                <input type="text" class="form-control" id="stockCode" name="stockCode" placeholder="Stok kodu">
                            </div>
                            <div class="form-group half-width">
                                <label for="barcodeNumber" class="form-label">Barkod Numarası</label>
                                <input type="text" class="form-control" id="barcodeNumber" name="barcodeNumber" placeholder="Barkod numarası">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Seçiniz...</option>
                                    <option value="elektronik">Elektronik</option>
                                    <option value="giyim">Giyim</option>
                                    <option value="hizmet">Hizmetler</option>
                                    <option value="diger">Diğer</option>
                                </select>
                            </div>
                            <div class="form-group half-width">
                                <label for="purchaseSaleUnit" class="form-label">Alış / Satış Birimi</label>
                                <select class="form-select" id="purchaseSaleUnit" name="purchaseSaleUnit">
                                    <option value="">Seçiniz...</option>
                                    <option value="adet">Adet</option>
                                    <option value="kg">KG</option>
                                    <option value="metre">Metre</option>
                                    <option value="saat">Saat</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="productPhoto" class="form-label">Ürün Fotoğrafı</label>
                                <input type="file" class="form-control" id="productPhoto" name="productPhoto" accept="image/*">
                                <small class="text-muted" id="currentProductPhoto">Mevcut fotoğraf: Yok</small>
                            </div>
                        </div>

                        <div class="form-row" id="stockQuantityRow">
                            <div class="form-group full-width">
                                <label for="stockQuantity" class="form-label">Stok Miktarı</label>
                                <input type="number" class="form-control" id="stockQuantity" name="miktar" placeholder="Stok miktarı" min="0">
                            </div>
                        </div>

                        <h6 class="form-section-title">Fiyat Bilgileri</h6>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="purchasePriceExclTax" class="form-label">Alış Fiyatı (Vergiler Hariç)</label>
                                <input type="number" step="0.01" class="form-control" id="purchasePriceExclTax" name="purchasePriceExclTax" placeholder="0.00">
                            </div>
                            <div class="form-group half-width">
                                <label for="purchasePriceInclTax" class="form-label">Alış Fiyatı (Vergiler Dahil)</label>
                                <input type="number" step="0.01" class="form-control" id="purchasePriceInclTax" name="purchasePriceInclTax" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="salePriceExclTax" class="form-label">Satış Fiyatı (Vergiler Hariç)</label>
                                <input type="number" step="0.01" class="form-control" id="salePriceExclTax" name="salePriceExclTax" placeholder="0.00">
                            </div>
                            <div class="form-group half-width">
                                <label for="salePriceInclTax" class="form-label">Satış Fiyatı (Vergiler Dahil)</label>
                                <input type="number" step="0.01" class="form-control" id="salePriceInclTax" name="salePriceInclTax" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="kdvRate" class="form-label">KDV Oranı (%)</label>
                                <input type="number" step="0.01" class="form-control" id="kdvRate" name="kdvRate" placeholder="Örn: 18">
                            </div>
                            <div class="form-group half-width">
                                <label for="criticalStock" class="form-label">Kritik Stok Uyarısı</label>
                                <input type="number" class="form-control" id="criticalStock" name="criticalStock" placeholder="Minimum stok adedi" min="0">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stockTracking" name="stockTracking" value="1" checked>
                                    <label class="form-check-label" for="stockTracking">
                                        Stok Takibi Yapılsın
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="productDescription" class="form-label">Ürün Açıklaması</label>
                                <textarea class="form-control form-textarea" id="productDescription" name="productDescription" rows="3" placeholder="Ürün veya hizmet hakkında detaylı açıklama"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary" form="productForm">Güncelle</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var stockTrackingCheckbox = document.getElementById('stockTracking');
            var stockQuantityRow = document.getElementById('stockQuantityRow');

            function toggleStockQuantityVisibility() {
                stockQuantityRow.style.display = 'flex';
            }

            toggleStockQuantityVisibility();
            stockTrackingCheckbox.addEventListener('change', toggleStockQuantityVisibility);

            var updateProductModal = document.getElementById('updateProductModal');
            updateProductModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var productId = button.getAttribute('data-product-id');
                var modalTitle = button.getAttribute('data-modal-title');
                var modalLabel = updateProductModal.querySelector('.modal-title');
                var productForm = document.getElementById('productForm');
                var currentProductPhoto = document.getElementById('currentProductPhoto');
                var stockTrackingCheckbox = document.getElementById('stockTracking');

                modalLabel.textContent = modalTitle;
                document.getElementById('productId').value = productId;
                productForm.reset();

                if (productId) {
                    fetch('hizmet_urunler.php?action=fetch_product&id=' + productId)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                document.getElementById('productName').value = data.stok_adi || '';
                                document.getElementById('stockCode').value = data.stok_kodu || '';
                                document.getElementById('barcodeNumber').value = data.barkod_no || '';
                                document.getElementById('category').value = data.kategori || '';
                                document.getElementById('purchaseSaleUnit').value = data.birim || ''; // Burası zaten doğru
                                document.getElementById('stockQuantity').value = data.miktar || '0';
                                document.getElementById('purchasePriceExclTax').value = data.alis_fiyat || '';
                                document.getElementById('purchasePriceInclTax').value = data.alis_fiyat_kdvli || '';
                                document.getElementById('salePriceExclTax').value = data.satis_fiyat || '';
                                document.getElementById('salePriceInclTax').value = data.satis_fiyat_kdvli || '';
                                document.getElementById('kdvRate').value = data.kdv_orani || '';
                                document.getElementById('criticalStock').value = data.kritik_stok || '';
                                stockTrackingCheckbox.checked = (data.stok_takip == 1);
                                document.getElementById('productDescription').value = data.aciklama || '';

                                if (data.foto) {
                                    currentProductPhoto.textContent = 'Mevcut fotoğraf: ' + data.foto;
                                } else {
                                    currentProductPhoto.textContent = 'Mevcut fotoğraf: Yok';
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching product data:', error));
                } else {
                    currentProductPhoto.textContent = 'Mevcut fotoğraf: Yok';
                    stockTrackingCheckbox.checked = true;
                }
            });

            updateProductModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('productForm').reset();
                document.getElementById('productId').value = '';
                document.getElementById('currentProductPhoto').textContent = 'Mevcut fotoğraf: Yok';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar .menu a');

            sidebarLinks.forEach(link => {
                if (currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <script src="../script2.js"></script>
</body>

</html>