<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmet ve Ürünler - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        /* CSS for the main content area of "Hizmet ve Ürünler" */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f4f8;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar'ın genişliği ve pozisyonu */
        .sidebar {
            width: 250px;
            flex-shrink: 0;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            overflow-y: auto;
            background-color: #395669;
            /* Sidebar rengi */
            color: white;
            z-index: 1000;
        }

        /* Sidebar menü öğeleri için hover ve aktif durumu */
        .sidebar .menu li a:hover,
        .sidebar .menu li a.active {
            background-color: #4a6a7c;
        }

        /* Logo altındaki çizgi rengi */
        .sidebar .logo {
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Menü toggle ve ayarlar menüsü hover renkleri */
        .sidebar .menu-toggle:hover,
        .sidebar .settings-menu li a:hover {
            background-color: #4a6a7c;
        }

        /* Main content wrapper */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px;
            box-sizing: border-box;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .content-area {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .filter-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
            /* Elemanları yaymak için */
        }

        .filter-dropdown-button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: #f8f8f8;
            color: #555;
            cursor: pointer;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            flex-grow: 1;
        }

        /* Yeni Ürün Ekle butonu için stil */
        .add-product-btn {
            background-color: #28a745;
            /* Yeşil renk (Bootstrap success) */
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
            /* Metnin tek satırda kalmasını sağlar */
            order: 2;
            /* Flex sırasını belirle, en sağda durması için */
        }

        /* Tablo başlıkları */
        .data-table-header {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-weight: bold;
            color: #666;
            gap: 10px;
            /* Sütun başlıkları arasına boşluk */
        }

        .data-table-header>div {
            padding: 0 5px;
            text-align: left;
            /* Tüm başlıklar sola hizalı */
        }

        /* Sütun genişlikleri ve hizalamaları */
        .data-table-header .col-name,
        .data-table-row .col-name {
            flex: 2;
            /* ADI sütununa daha fazla esneklik */
            min-width: 150px;
            /* Minimum genişlik */
            text-align: left;
            /* ADI sütunu soldan başlasın */
        }

        .data-table-header .col-stock,
        .data-table-row .col-stock {
            flex: 1.2;
            /* STOK MİKTARI */
            min-width: 100px;
            text-align: left;
            /* STOK MİKTARI soldan başlasın */
        }

        .data-table-header .col-price,
        .data-table-row .col-price {
            flex: 1.5;
            /* ALIŞ ve SATIŞ */
            min-width: 120px;
            text-align: left;
            /* Fiyat sütunları soldan başlasın */
        }

        .data-table-header .actions-column,
        .data-table-row .actions-column {
            flex: 0 0 80px;
            /* Sabit genişlik */
            text-align: left;
            /* İşlemler sütunu da soldan başlasın */
        }

        /* Tablo satırları */
        .data-table-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f2f2f2;
            background-color: #fff;
            gap: 10px;
            /* Satır sütunları arasına boşluk */
        }

        .data-table-row:last-child {
            border-bottom: none;
        }

        .data-table-row>div {
            padding: 0 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            direction: ltr;
            /* Yazıların soldan dolmasını sağlar */
        }

        /* 'İşlemler' sütunundaki butonlar için */
        .data-table-row .actions-column {
            display: flex;
            gap: 5px;
            justify-content: flex-start;
            /* Butonları soldan hizala */
        }

        /* Buton ikon boyutunu ayarlayabiliriz */
        .action-button {
            padding: 6px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            /* İkon boyutu */
            color: white;
            display: flex;
            /* İkonu ortalamak için */
            align-items: center;
            justify-content: center;
        }

        .update-btn {
            background-color: #ffc107;
            /* Sarı renk */
        }

        .delete-btn {
            background-color: #dc3545;
            /* Kırmızı renk */
        }

        .data-table-container {
            min-height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
            color: #777;
            font-size: 16px;
            padding: 20px 0;
        }

        .data-table-container.has-data {
            min-height: auto;
            padding: 0;
            display: block;
        }

        .data-table-container.has-data p {
            display: none;
        }

        .footer-actions {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .dropdown-btn {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f8f8;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .dropdown-btn::after {
            content: '▼';
            font-size: 10px;
            margin-left: 5px;
        }

        /* --- Modal ve Form Stilleri --- */
        .modal-header {
            background-color: #f8f9fa;
            /* Hafif gri başlık */
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .modal-body {
            padding: 20px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            /* Küçük ekranlarda alt alta geçsin */
            gap: 20px;
            /* Sütunlar arası boşluk */
            margin-bottom: 15px;
            /* Satırlar arası boşluk */
        }

        .form-group {
            flex: 1;
            /* Eşit genişlikte dağılsın */
            min-width: 250px;
            /* Minimum genişlik belirleyelim */
            display: flex;
            flex-direction: column;
            /* Label ve input alt alta */
        }

        /* İki sütunlu form grupları için (örn: Alış/Satış Fiyatları) */
        .form-group.half-width {
            flex: 0 0 calc(50% - 10px);
            /* İki grup yan yana gelince boşluğu düş */
        }

        /* Ürün fotoğrafı ve açıklama gibi tam genişlik alacak alanlar */
        .form-group.full-width {
            flex: 0 0 100%;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select,
        .form-textarea {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
            /* Padding genişliği etkilemesin */
        }

        .form-textarea {
            min-height: 80px;
            resize: vertical;
            /* Sadece dikeyde boyutlandırılabilir */
        }

        /* Checkbox ve switch stilleri */
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            /* Üstündeki elemanla boşluk */
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0;
        }

        .form-check-label {
            font-weight: normal;
            color: #333;
            margin-bottom: 0;
        }

        /* Modal footer butonları */
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            /* Butonları sağa yasla */
            gap: 10px;
            /* Butonlar arası boşluk */
        }

        .modal-footer .btn {
            padding: 8px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 4px;
        }

        .btn-secondary {
            background-color: #6c757d;
            /* Gri */
            border-color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            /* Mavi */
            border-color: #007bff;
        }

        /* Formdaki başlıklar için */
        .form-section-title {
            width: 100%;
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>

    <div class="main-content">
        <div class="header-section">
            <h1 class="page-title">Hizmet ve Ürünler</h1>
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

            <div class="data-table-container has-data">
                <div class="data-table-row">
                    <div class="col-name">Ürün A</div>
                    <div class="col-stock">150 Adet</div>
                    <div class="col-price">1.234.567,89 TL</div>
                    <div class="col-price">987.654,32 TL</div>
                    <div class="actions-column">
                        <button class="action-button update-btn" title="Güncelle" data-bs-toggle="modal" data-bs-target="#updateProductModal" data-modal-title="Ürün / Hizmet Güncelle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-button delete-btn" title="Sil">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="data-table-row">
                    <div class="col-name">Hizmet X</div>
                    <div class="col-stock">N/A</div>
                    <div class="col-price">50,00 TL</div>
                    <div class="col-price">90,00 TL</div>
                    <div class="actions-column">
                        <button class="action-button update-btn" title="Güncelle" data-bs-toggle="modal" data-bs-target="#updateProductModal" data-modal-title="Ürün / Hizmet Güncelle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-button delete-btn" title="Sil">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="data-table-row">
                    <div class="col-name">Ürün B</div>
                    <div class="col-stock">75 Adet</div>
                    <div class="col-price">25,00 TL</div>
                    <div class="col-price">45,00 TL</div>
                    <div class="actions-column">
                        <button class="action-button update-btn" title="Güncelle" data-bs-toggle="modal" data-bs-target="#updateProductModal" data-modal-title="Ürün / Hizmet Güncelle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-button delete-btn" title="Sil">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <p style="display: none;">Hizmet ve ürünler sayfasına hoş geldiniz!</p>
                <p style="display: none;">Hizmet ve ürünleri buradan takip edebilirsiniz.</p>
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
                    <h5 class="modal-title" id="updateProductModalLabel">Ürün / Hizmet Güncelle</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="productName" class="form-label">Ürün Adı / Hizmet Adı</label>
                                <input type="text" class="form-control" id="productName" placeholder="Ürün veya hizmet adını giriniz" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="stockCode" class="form-label">Stok Kodu</label>
                                <input type="text" class="form-control" id="stockCode" placeholder="Stok kodu">
                            </div>
                            <div class="form-group half-width">
                                <label for="barcodeNumber" class="form-label">Barkod Numarası</label>
                                <input type="text" class="form-control" id="barcodeNumber" placeholder="Barkod numarası">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="category" class="form-label">Kategori</label>
                                <select class="form-select" id="category">
                                    <option value="">Seçiniz...</option>
                                    <option value="elektronik">Elektronik</option>
                                    <option value="giyim">Giyim</option>
                                    <option value="hizmet">Hizmetler</option>
                                    <option value="diger">Diğer</option>
                                </select>
                            </div>
                            <div class="form-group half-width">
                                <label for="purchaseSaleUnit" class="form-label">Alış / Satış Birimi</label>
                                <select class="form-select" id="purchaseSaleUnit">
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
                                <input type="file" class="form-control" id="productPhoto" accept="image/*">
                            </div>
                        </div>

                        <h6 class="form-section-title">Fiyat Bilgileri</h6>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="purchasePriceExclTax" class="form-label">Alış Fiyatı (Vergiler Hariç)</label>
                                <input type="number" step="0.01" class="form-control" id="purchasePriceExclTax" placeholder="0.00">
                            </div>
                            <div class="form-group half-width">
                                <label for="purchasePriceInclTax" class="form-label">Alış Fiyatı (Vergiler Dahil)</label>
                                <input type="number" step="0.01" class="form-control" id="purchasePriceInclTax" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="salePriceExclTax" class="form-label">Satış Fiyatı (Vergiler Hariç)</label>
                                <input type="number" step="0.01" class="form-control" id="salePriceExclTax" placeholder="0.00">
                            </div>
                            <div class="form-group half-width">
                                <label for="salePriceInclTax" class="form-label">Satış Fiyatı (Vergiler Dahil)</label>
                                <input type="number" step="0.01" class="form-control" id="salePriceInclTax" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="kdvRate" class="form-label">KDV Oranı (%)</label>
                                <input type="number" step="0.01" class="form-control" id="kdvRate" placeholder="Örn: 18">
                            </div>
                            <div class="form-group half-width">
                                <label for="criticalStock" class="form-label">Kritik Stok Uyarısı</label>
                                <input type="number" class="form-control" id="criticalStock" placeholder="Minimum stok adedi">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stockTracking" checked>
                                    <label class="form-check-label" for="stockTracking">
                                        Stok Takibi Yapılsın
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="productDescription" class="form-label">Ürün Açıklaması</label>
                                <textarea class="form-control form-textarea" id="productDescription" rows="3" placeholder="Ürün veya hizmet hakkında detaylı açıklama"></textarea>
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
        // Modalı açan butonlara tıklandığında başlığı dinamik olarak değiştirme
        const updateProductModal = document.getElementById('updateProductModal');
        updateProductModal.addEventListener('show.bs.modal', event => {
            // Modalı tetikleyen butonu al
            const button = event.relatedTarget; // Bu, modali açan 'Güncelle' veya 'Ürün Ekle +' butonu olabilir.
            // Butonun data-modal-title özelliğinden başlığı al
            const modalTitleText = button.getAttribute('data-modal-title');
            // Modalın başlık elemanını bul
            const modalTitle = updateProductModal.querySelector('.modal-title');

            // Başlığı güncelle
            modalTitle.textContent = modalTitleText;

            // Eğer buton "Ürün Ekle +" butonu ise formu sıfırla
            if (button.classList.contains('add-product-btn')) {
                const productForm = document.getElementById('productForm');
                if (productForm) {
                    productForm.reset(); // Formu sıfırla
                    // Eğer 'Stok Takibi Yapılsın' checkbox'ı varsa, varsayılan olarak seçili yap
                    const stockTrackingCheckbox = document.getElementById('stockTracking');
                    if (stockTrackingCheckbox) {
                        stockTrackingCheckbox.checked = true;
                    }
                }
            }

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
</body>

</html>