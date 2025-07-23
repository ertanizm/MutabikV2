<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmet ve Ürünler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        /* Ekstra özel CSS stilleri buraya eklenebilir */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }

        .container {
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        /* search-filter-section'daki buton stilleri */
        .search-filter-section button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
        }

        .search-filter-section .stock-update {
            background-color: #007bff;
            color: white;
        }

        .search-filter-section .add-item {
            background-color: #28a745;
            color: white;
        }


        .search-filter-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            gap: 10px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-button {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        /* Dropdown button for 'Adet' and other generic dropdowns with text */
        .dropdown-button.text-only {
            background-color: #fff;
            /* Beyaz arka plan */
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            white-space: nowrap;
        }


        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            top: 100%;
            left: 0;
            margin-top: 5px;
        }

        /* Footer'daki dropdown'lar için yukarı açılma */
        .footer-actions .dropdown-content {
            top: auto;
            bottom: 100%;
            margin-top: auto;
            margin-bottom: 5px;
        }

        /* Sağ hizalı dropdown için (Kaydet butonu) */
        .header-actions .dropdown .dropdown-content {
            left: auto;
            right: 0;
        }


        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown.active .dropdown-content {
            display: block;
        }

        .search-filter-section .search-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-filter-section .search-icon {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }


        /* Genel buton stilleri (tüm sayfalarda kullanılabilir) */
        .button-primary {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
            background-color: #007bff;
            color: white;
        }

        .button-secondary {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
            background-color: #6c757d;
            /* Gri */
            color: white;
        }

        .button-success {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
            background-color: #28a745;
            /* Yeşil */
            color: white;
        }


        .table-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }

        .no-data-message {
            text-align: center;
            padding: 50px 20px;
            color: #777;
        }

        .footer-actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .footer-actions .dropdown-button {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trial-info {
            background-color: #ffc107;
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        /* Sol menü stilleri (dashboard.css'den gelebilir, ancak burada da tanımlanabilir) */
        .sidebar {
            width: 250px;
            /* Increased width for a wider sidebar */
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px 20px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .main-content {
            margin-left: 270px;
            /* Adjusted margin to accommodate wider sidebar (250px + 20px padding/gap) */
            padding: 20px;
        }

        /* Stok Güncelleme Sayfası Stilleri */
        .stok-guncelleme-search-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stok-guncelleme-search-filter .search-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .stok-guncelleme-search-filter .search-icon {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .stok-guncelleme-table-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .stok-guncelleme-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stok-guncelleme-table th,
        .stok-guncelleme-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .stok-guncelleme-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }

        .stok-guncelleme-table .stok-input {
            width: 80px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        .stok-guncelleme-footer-actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stok-guncelleme-footer-actions .dropdown-content {
            top: auto;
            bottom: 100%;
            margin-top: auto;
            margin-bottom: 5px;
        }

        /* Hizmet/Ürün Ekle Form Sayfası Stilleri */
        .hizmet-urun-form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .hizmet-urun-form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            /* Changed to flex for better alignment */
            gap: 15px;
            /* Label and input spacing */
        }

        .hizmet-urun-form-group label {
            min-width: 180px;
            /* Increased label width for better alignment */
            font-weight: bold;
            color: #555;
            text-align: right;
            /* Right-align labels */
            padding-right: 10px;
            /* Space between label and input */
        }

        .hizmet-urun-form-group .form-control {
            flex-grow: 1;
            /* Inputs take remaining space */
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            /* Include padding and border in the element's total width and height */
        }

        .hizmet-urun-form-group .radio-group {
            display: flex;
            align-items: center;
            /* Radio butonları ve metinleri dikeyde ortala */
            gap: 5px;
            /* Radio butonu ve label arasındaki boşluğu azaltıldı */
        }

        .hizmet-urun-form-group .radio-group input[type="radio"] {
            margin-right: 0px;
            /* Radio butonu ile label arasındaki varsayılan boşluğu kaldırıldı */
        }

        .hizmet-urun-form-group .upload-button,
        .hizmet-urun-form-group .add-code-button {
            background-color: #e9ecef;
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            flex-grow: 0;
            /* Do not grow, maintain natural size */
            white-space: nowrap;
        }


        .hizmet-urun-form-group .category-text {
            /* Changed from button to text */
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #555;
            flex-grow: 1;
            /* Make it take available space like an input */
        }


        /* Price Input Group */
        .price-input-group {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            flex-grow: 1;
        }

        .price-input-group .currency-icon {
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-right: 1px solid #ccc;
            color: #555;
            font-weight: bold;
        }

        .price-input-group input {
            border: none;
            outline: none;
            padding: 8px 12px;
            flex-grow: 1;
            text-align: right;
        }

        .price-input-group .tax-percentage-icon {
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-left: 1px solid #ccc;
            color: #555;
            font-weight: bold;
        }

        .hizmet-urun-form-group .tax-select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex-grow: 1;
            /* Allow it to grow like other inputs */
            /* Removed fixed width to match other inputs */
        }

        .hizmet-urun-form-group .tax-note {
            font-size: 0.85em;
            color: #888;
            margin-left: 195px;
            /* Adjusted margin-left based on new label width (180px + 15px gap) */
            flex-basis: 100%;
            /* Start on its own line */
            text-align: left;
            margin-top: -10px;
            margin-bottom: 5px;
        }

        .hizmet-urun-form-group .description-textarea {
            flex-grow: 1;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            min-height: 80px;
            resize: vertical;
        }

        /* Adjust header for "Hizmet ve Ürünler > Yeni" page */
        #hizmetUrunEkleSayfasi .header {
            justify-content: flex-start;
            /* Align items to the start */
            gap: 20px;
            /* Space between title and actions */
        }

        #hizmetUrunEkleSayfasi .header h1 {
            flex-grow: 1;
            /* Allow title to take available space */
        }

        /* Yeni oluşturulan buton alanı */
        .form-top-actions {
            display: flex;
            justify-content: flex-end;
            /* Butonları sağa hizala */
            gap: 10px;
            margin-bottom: 20px;
            /* Form ile arasında boşluk bırak */
            padding: 0 20px;
            /* Kenar boşlukları */
        }

        /* Stok Güncelleme Tablosundaki Butonlar */
        .stok-guncelleme-table .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
            /* Butonları hücre içinde ortala */
            align-items: center;
        }

        .stok-guncelleme-table .action-buttons button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            font-size: 16px;
            /* İkon boyutu */
        }

        .stok-guncelleme-table .action-buttons .btn-edit {
            color: #ffc107;
            /* Sarı renk */
        }

        .stok-guncelleme-table .action-buttons .btn-delete {
            color: #dc3545;
            /* Kırmızı renk */
        }

        .stok-guncelleme-table .action-buttons button:hover {
            opacity: 0.8;
            /* Hover efekti */
        }

        /* Sütun genişliği ayarı */
        .stok-guncelleme-table th:nth-child(2),
        .stok-guncelleme-table td:nth-child(2) {
            width: 150px;
            /* ANA DEPO sütununu biraz daha genişlet */
            text-align: center;
        }

        .stok-guncelleme-table th:last-child,
        .stok-guncelleme-table td:last-child {
            width: 80px;
            /* Eylem butonları için küçük sütun */
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>

    <div class="main-content">


        <div id="hizmetUrunlerListesi">
            <div class="header">
                <h1>Hizmet ve Ürünler</h1>
                <div class="header-right">
                    <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
                </div>
            </div>

            <div class="search-filter-section">
                <div class="dropdown" id="filterDropdown">
                    <button class="dropdown-button">FİLTRELE <span style="margin-left: 5px;">&#9660;</span></button>
                    <div class="dropdown-content">
                        <a href="#">Tüm Ürünler</a>
                        <a href="#">Hizmetler</a>
                        <a href="#">Ürünler</a>
                        <a href="#">Stokta Olanlar</a>
                        <a href="#">Stokta Olmayanlar</a>
                    </div>
                </div>
                <input type="text" class="search-input" placeholder="Ara...">
                <button class="search-icon">🔍</button>
                <button class="stock-update" id="goToStockUpdate">STOK GÜNCELLE</button>
                <button class="add-item" id="goToAddItem">HİZMET / ÜRÜN EKLE</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ADI</th>
                            <th>STOK MİKTARI</th>
                            <th>ALIŞ (VERGİLER HARİÇ)</th>
                            <th>SATIŞ (VERGİLER HARİÇ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">
                                <div class="no-data-message">
                                    <p>Hizmet ve ürünler sayfasına hoş geldiniz!</p>
                                    <p>Hizmet ve ürünleri buradan takip edebileceksiniz.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="footer-actions">
                <div class="dropdown" id="allRecordsDropdown">
                    <button class="dropdown-button">TÜM KAYITLAR <span style="margin-left: 5px;">&#9660;</span></button>
                    <div class="dropdown-content">
                        <a href="#">10 Kayıt</a>
                        <a href="#">25 Kayıt</a>
                        <a href="#">50 Kayıt</a>
                        <a href="#">Tümünü Göster</a>
                    </div>
                </div>
                <div class="dropdown" id="exportImportDropdown">
                    <button class="dropdown-button">İÇE/DIŞA AKTAR <span style="margin-left: 5px;">&#9660;</span></button>
                    <div class="dropdown-content">
                        <a href="#">İçe Aktar (CSV)</a>
                        <a href="#">Dışa Aktar (CSV)</a>
                        <a href="#">Dışa Aktar (Excel)</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="stokGuncellemeSayfasi" style="display: none;">
            <div class="header">
                <h1>Stok Güncelleme</h1>
                <div class="header-actions">
                    <div class="header-right">
                        <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
                    </div>
                </div>
            </div>

            <div class="stok-guncelleme-search-filter search-filter-section">
                <div class="dropdown" id="stockFilterDropdown">
                    <button class="dropdown-button">FİLTRELE <span style="margin-left: 5px;">&#9660;</span></button>
                    <div class="dropdown-content">
                        <a href="#">Tüm Ürünler</a>
                        <a href="#">Hizmetler</a>
                        <a href="#">Stokta Olanlar</a>
                        <a href="#">Stokta Olmayanlar</a>
                    </div>
                </div>
                <input type="text" class="search-input" placeholder="Ara...">
                <button class="search-icon">🔍</button>
                <button class="button-secondary" id="btnVazgecStokGuncelle">VAZGEÇ</button>
                <button class="button-primary">KAYDET</button>
            </div>

            <div class="stok-guncelleme-table-container table-container">
                <table class="stok-guncelleme-table">
                    <thead>
                        <tr>
                            <th>ÜRÜN / BİLGİ</th>
                            <th>ANA DEPO</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-product-name="Ürün A" data-stock="10" data-stock-code="PRD001" data-barcode="1234567890123" data-category="Elektronik" data-unit="Adet" data-buy-price="100.00" data-sell-price="150.00" data-tax="20" data-desc="Bu bir örnek ürün açıklamasıdır.">
                            <td>Ürün A</td>
                            <td><input type="number" class="stok-input" value="10"></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" title="Güncelle"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete" title="Sil"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr data-product-name="Hizmet B" data-stock="5" data-stock-code="HZM001" data-barcode="" data-category="Danışmanlık" data-unit="Saat" data-buy-price="50.00" data-sell-price="75.00" data-tax="18" data-desc="Bu bir örnek hizmet açıklamasıdır.">
                            <td>Hizmet B</td>
                            <td><input type="number" class="stok-input" value="5"></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" title="Güncelle"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete" title="Sil"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr data-product-name="Ürün C" data-stock="20" data-stock-code="PRD002" data-barcode="9876543210987" data-category="Giyim" data-unit="Adet" data-buy-price="20.00" data-sell-price="30.00" data-tax="8" data-desc="Bu da başka bir örnek ürün açıklamasıdır.">
                            <td>Ürün C</td>
                            <td><input type="number" class="stok-input" value="20"></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" title="Güncelle"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete" title="Sil"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="no-data-message" style="display: none;">
                                    <p>Stokta ürün bulunmamaktadır.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer-actions stok-guncelleme-footer-actions">
                <div class="dropdown" id="stockExportImportDropdown">
                    <button class="dropdown-button">DIŞARI AKTAR / STOK GÜNCELLE <span style="margin-left: 5px;">&#9660;</span></button>
                    <div class="dropdown-content">
                        <a href="#">Dışarı Aktar (CSV)</a>
                        <a href="#">Dışa Aktar (Excel)</a>
                        <a href="#">Stok Güncelle (Seçili)</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="hizmetUrunEkleSayfasi" style="display: none;">
            <div class="header">
                <h1>Hizmet ve Ürünler <span style="font-weight: normal; color: #666;"> > Yeni</span></h1>
                <div class="header-actions">
                    <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
                </div>
            </div>

            <div class="form-top-actions">
                <button class="button-secondary" id="btnVazgecHizmetUrunEkle">VAZGEÇ</button>
                <div class="dropdown">
                    <button class="button-primary dropdown-button">KAYDET <span style="margin-left: 5px;">&#9660;</span></button>
                    <div class="dropdown-content" style="left: auto; right: 0;">
                        <a href="#">Kaydet ve Yeni Ekle</a>
                        <a href="#">Kaydet ve Listeye Dön</a>
                    </div>
                </div>
            </div>
            <div class="hizmet-urun-form-container">
                <div class="hizmet-urun-form-group">
                    <label for="urunAdi">ADI</label>
                    <input type="text" id="urunAdi" class="form-control" placeholder="Hizmet/Ürün Adı">
                </div>
                <div class="hizmet-urun-form-group">
                    <label for="urunStokKodu">ÜRÜN / STOK KODU</label>
                    <input type="text" id="urunStokKodu" class="form-control" placeholder="Otomatik Oluşturulacak">
                </div>
                <div class="hizmet-urun-form-group">
                    <label for="barkodNumarasi">BARKOD NUMARASI</label>
                    <input type="text" id="barkodNumarasi" class="form-control" placeholder="Barkod Numarası Girin">
                </div>
                <div class="hizmet-urun-form-group">
                    <label>KATEGORİSİ</label>
                    <span class="category-text form-control">Kategorisiz</span>
                </div>
                <div class="hizmet-urun-form-group">
                    <label>ÜRÜN FOTOĞRAFI</label>
                    <button class="upload-button">FOTOĞRAF YÜKLE</button>
                </div>
                <div class="hizmet-urun-form-group">
                    <label>ALIŞ / SATIŞ BİRİMİ</label>
                    <div class="dropdown" style="flex-grow: 1;">
                        <button class="dropdown-button text-only" id="alisSatisBirimiDropdown">Adet <span style="margin-left: 5px;">&#9660;</span></button>
                        <div class="dropdown-content">
                            <a href="#" data-value="Adet">Adet</a>
                            <a href="#" data-value="Kutu">Kutu</a>
                            <a href="#" data-value="Paket">Paket</a>
                            <a href="#" data-value="Metre">Metre</a>
                            <a href="#" data-value="Litre">Litre</a>
                            <a href="#" data-value="Kilogram">Kilogram</a>
                        </div>
                    </div>
                </div>

                <div class="hizmet-urun-form-group">
                    <label for="alisFiyati">ALIŞ FİYATI (VERGİLER HARİÇ)</label>
                    <div class="price-input-group">
                        <span class="currency-icon">₺</span>
                        <input type="number" id="alisFiyati" value="0.00">
                    </div>
                </div>

                <div class="hizmet-urun-form-group">
                    <label for="satisFiyati">SATIŞ FİYATI (VERGİLER HARİÇ)</label>
                    <div class="price-input-group">
                        <span class="currency-icon">₺</span>
                        <input type="number" id="satisFiyati" value="0.00">
                    </div>
                </div>

                <div class="hizmet-urun-form-group">
                    <label for="kdvOrani">KDV ORANI</label>
                    <select id="kdvOrani" class="form-control tax-select">
                        <option>%20 KDV</option>
                        <option>%18 KDV</option>
                        <option>%8 KDV</option>
                        <option>%1 KDV</option>
                        <option>Vergisiz</option>
                    </select>
                </div>

                <div class="hizmet-urun-form-group">
                    <label for="satisDahilAlisFiyati">SATIŞLAR DAHİL ALIŞ FİYATI</label>
                    <div class="price-input-group">
                        <span class="currency-icon">₺</span>
                        <input type="number" id="satisDahilAlisFiyati" value="0.00">
                    </div>
                </div>
                <div class="hizmet-urun-form-group">
                    <label for="satisDahilSatisFiyati">SATIŞLAR DAHİL SATIŞ FİYATI</label>
                    <div class="price-input-group">
                        <span class="currency-icon">₺</span>
                        <input type="number" id="satisDahilSatisFiyati" value="0.00">
                    </div>
                </div>
                <div class="hizmet-urun-form-group">
                    <label></label>
                    <div class="radio-group">
                        <input type="radio" id="vergilerDahil" name="vergiDurumu" checked>
                        <label for="vergilerDahil">Birim değeri vergiyi içeren olarak faturalanır.</label>
                    </div>
                </div>
                <div class="hizmet-urun-form-group">
                    <label></label>
                    <button class="add-code-button">+ GTİP KODU EKLE</button>
                </div>
                <div class="hizmet-urun-form-group">
                    <label>STOK TAKİBİ</label>
                    <div class="radio-group">
                        <input type="radio" id="stokTakibiYapilsin" name="stokTakibi" checked>
                        <label for="stokTakibiYapilsin">YAPILSIN</label>
                        <input type="radio" id="stokTakibiYapilmasin" name="stokTakibi">
                        <label for="stokTakibiYapilmasin">YAPILMASIN</label>
                    </div>
                </div>
                <div class="hizmet-urun-form-group">
                    <label for="baslangicStokMiktari">BAŞLANGIÇ STOK MİKTARI</label>
                    <input type="number" id="baslangicStokMiktari" class="form-control" value="0.00">
                </div>
                <div class="hizmet-urun-form-group">
                    <label>KRİTİK STOK UYARISI</label>
                    <div class="radio-group">
                        <input type="radio" id="kritikStokAktif" name="kritikStok" checked>
                        <label for="kritikStokAktif">Etkinleştir</label>
                        <input type="radio" id="kritikStokPasif" name="kritikStok">
                        <label for="kritikStokPasif">Devre Dışı Bırak</label>
                    </div>
                </div>
                <div class="hizmet-urun-form-group">
                    <label for="urunAciklamasi">ÜRÜN AÇIKLAMASI</label>
                    <textarea id="urunAciklamasi" class="form-control description-textarea" placeholder="Ürün veya hizmetinize ait bir açıklama yazın..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailModalLabel">Ürün Detaylarını Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="hizmet-urun-form-container" style="box-shadow: none; padding: 0;">
                        <div class="hizmet-urun-form-group">
                            <label for="modalUrunAdi">ADI</label>
                            <input type="text" id="modalUrunAdi" class="form-control" placeholder="Hizmet/Ürün Adı">
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label for="modalUrunStokKodu">ÜRÜN / STOK KODU</label>
                            <input type="text" id="modalUrunStokKodu" class="form-control" placeholder="Otomatik Oluşturulacak">
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label for="modalBarkodNumarasi">BARKOD NUMARASI</label>
                            <input type="text" id="modalBarkodNumarasi" class="form-control" placeholder="Barkod Numarası Girin">
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label>KATEGORİSİ</label>
                            <span class="category-text form-control" id="modalKategorisi">Kategorisiz</span>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label>ÜRÜN FOTOĞRAFI</label>
                            <button class="upload-button">FOTOĞRAF YÜKLE</button>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label>ALIŞ / SATIŞ BİRİMİ</label>
                            <div class="dropdown" style="flex-grow: 1;">
                                <button class="dropdown-button text-only" id="modalAlisSatisBirimiDropdown">Adet <span style="margin-left: 5px;">&#9660;</span></button>
                                <div class="dropdown-content">
                                    <a href="#" data-value="Adet">Adet</a>
                                    <a href="#" data-value="Kutu">Kutu</a>
                                    <a href="#" data-value="Paket">Paket</a>
                                    <a href="#" data-value="Metre">Metre</a>
                                    <a href="#" data-value="Litre">Litre</a>
                                    <a href="#" data-value="Kilogram">Kilogram</a>
                                </div>
                            </div>
                        </div>

                        <div class="hizmet-urun-form-group">
                            <label for="modalAlisFiyati">ALIŞ FİYATI (VERGİLER HARİÇ)</label>
                            <div class="price-input-group">
                                <span class="currency-icon">₺</span>
                                <input type="number" id="modalAlisFiyati" value="0.00">
                            </div>
                        </div>

                        <div class="hizmet-urun-form-group">
                            <label for="modalSatisFiyati">SATIŞ FİYATI (VERGİLER HARİÇ)</label>
                            <div class="price-input-group">
                                <span class="currency-icon">₺</span>
                                <input type="number" id="modalSatisFiyati" value="0.00">
                            </div>
                        </div>

                        <div class="hizmet-urun-form-group">
                            <label for="modalKdvOrani">KDV ORANI</label>
                            <select id="modalKdvOrani" class="form-control tax-select">
                                <option>%20 KDV</option>
                                <option>%18 KDV</option>
                                <option>%8 KDV</option>
                                <option>%1 KDV</option>
                                <option>Vergisiz</option>
                            </select>
                        </div>

                        <div class="hizmet-urun-form-group">
                            <label for="modalSatisDahilAlisFiyati">SATIŞLAR DAHİL ALIŞ FİYATI</label>
                            <div class="price-input-group">
                                <span class="currency-icon">₺</span>
                                <input type="number" id="modalSatisDahilAlisFiyati" value="0.00">
                            </div>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label for="modalSatisDahilSatisFiyati">SATIŞLAR DAHİL SATIŞ FİYATI</label>
                            <div class="price-input-group">
                                <span class="currency-icon">₺</span>
                                <input type="number" id="modalSatisDahilSatisFiyati" value="0.00">
                            </div>
                        </div>

                        <div class="hizmet-urun-form-group">
                            <label></label>
                            <div class="radio-group">
                                <input type="radio" id="modalVergilerDahil" name="modalVergiDurumu" checked>
                                <label for="modalVergilerDahil">Birim değeri vergiyi içeren olarak faturalanır.</label>
                            </div>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label></label>
                            <button class="add-code-button">+ GTİP KODU EKLE</button>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label>STOK TAKİBİ</label>
                            <div class="radio-group">
                                <input type="radio" id="modalStokTakibiYapilsin" name="modalStokTakibi" checked>
                                <label for="modalStokTakibiYapilsin">YAPILSIN</label>
                                <input type="radio" id="modalStokTakibiYapilmasin" name="modalStokTakibi">
                                <label for="modalStokTakibiYapilmasin">YAPILMASIN</label>
                            </div>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label for="modalBaslangicStokMiktari">BAŞLANGIÇ STOK MİKTARI</label>
                            <input type="number" id="modalBaslangicStokMiktari" class="form-control" value="0.00">
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label>KRİTİK STOK UYARISI</label>
                            <div class="radio-group">
                                <input type="radio" id="modalKritikStokAktif" name="modalKritikStok" checked>
                                <label for="modalKritikStokAktif">Etkinleştir</label>
                                <input type="radio" id="modalKritikStokPasif" name="modalKritikStok">
                                <label for="modalKritikStokPasif">Devre Dışı Bırak</label>
                            </div>
                        </div>
                        <div class="hizmet-urun-form-group">
                            <label for="modalUrunAciklamasi">ÜRÜN AÇIKLAMASI</label>
                            <textarea id="modalUrunAciklamasi" class="form-control description-textarea" placeholder="Ürün veya hizmetinize ait bir açıklama yazın..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="button" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sayfa elementleri
            const hizmetUrunlerListesi = document.getElementById('hizmetUrunlerListesi');
            const stokGuncellemeSayfasi = document.getElementById('stokGuncellemeSayfasi');
            const hizmetUrunEkleSayfasi = document.getElementById('hizmetUrunEkleSayfasi');

            const goToStockUpdateButton = document.getElementById('goToStockUpdate');
            const goToAddItemButton = document.getElementById('goToAddItem');

            const btnVazgecStokGuncelle = document.getElementById('btnVazgecStokGuncelle');
            const btnVazgecHizmetUrunEkle = document.getElementById('btnVazgecHizmetUrunEkle');

            const alisSatisBirimiDropdownButton = document.getElementById('alisSatisBirimiDropdown'); // Adet dropdown butonu

            // Modal elementleri
            const productDetailModal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            const modalUrunAdi = document.getElementById('modalUrunAdi');
            const modalUrunStokKodu = document.getElementById('modalUrunStokKodu');
            const modalBarkodNumarasi = document.getElementById('modalBarkodNumarasi');
            const modalKategorisi = document.getElementById('modalKategorisi');
            const modalAlisSatisBirimiDropdown = document.getElementById('modalAlisSatisBirimiDropdown');
            const modalAlisFiyati = document.getElementById('modalAlisFiyati');
            const modalSatisFiyati = document.getElementById('modalSatisFiyati');
            const modalKdvOrani = document.getElementById('modalKdvOrani');
            const modalSatisDahilAlisFiyati = document.getElementById('modalSatisDahilAlisFiyati');
            const modalSatisDahilSatisFiyati = document.getElementById('modalSatisDahilSatisFiyati');
            const modalVergilerDahil = document.getElementById('modalVergilerDahil');
            const modalStokTakibiYapilsin = document.getElementById('modalStokTakibiYapilsin');
            const modalStokTakibiYapilmasin = document.getElementById('modalStokTakibiYapilmasin');
            const modalBaslangicStokMiktari = document.getElementById('modalBaslangicStokMiktari');
            const modalKritikStokAktif = document.getElementById('modalKritikStokAktif');
            const modalKritikStokPasif = document.getElementById('modalKritikStokPasif');
            const modalUrunAciklamasi = document.getElementById('modalUrunAciklamasi');


            // Fonksiyon: Tüm sayfaları gizle
            function hideAllPages() {
                hizmetUrunlerListesi.style.display = 'none';
                stokGuncellemeSayfasi.style.display = 'none';
                hizmetUrunEkleSayfasi.style.display = 'none';
            }

            // "Stok Güncelle" butonuna basıldığında
            goToStockUpdateButton.addEventListener('click', function() {
                hideAllPages();
                stokGuncellemeSayfasi.style.display = 'block';
            });

            // "Hizmet / Ürün Ekle" butonuna basıldığında
            goToAddItemButton.addEventListener('click', function() {
                hideAllPages();
                hizmetUrunEkleSayfasi.style.display = 'block';
            });

            // "Stok Güncelleme" sayfasındaki "Vazgeç" butonuna basıldığında
            btnVazgecStokGuncelle.addEventListener('click', function() {
                hideAllPages();
                hizmetUrunlerListesi.style.display = 'block';
            });

            // "Hizmet/Ürün Ekle" sayfasındaki "Vazgeç" butonuna basıldığında
            btnVazgecHizmetUrunEkle.addEventListener('click', function() {
                hideAllPages();
                hizmetUrunlerListesi.style.display = 'block';
            });

            // Adet dropdown seçimi (Hizmet/Ürün Ekle sayfası için)
            if (alisSatisBirimiDropdownButton) {
                alisSatisBirimiDropdownButton.closest('.dropdown').querySelectorAll('.dropdown-content a').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const selectedValue = this.getAttribute('data-value');
                        alisSatisBirimiDropdownButton.innerHTML = selectedValue + ' <span style="margin-left: 5px;">&#9660;</span>';
                        alisSatisBirimiDropdownButton.closest('.dropdown').classList.remove('active');
                    });
                });
            }

            // Modal içindeki Alış/Satış Birimi dropdown seçimi
            if (modalAlisSatisBirimiDropdown) {
                modalAlisSatisBirimiDropdown.closest('.dropdown').querySelectorAll('.dropdown-content a').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const selectedValue = this.getAttribute('data-value');
                        modalAlisSatisBirimiDropdown.innerHTML = selectedValue + ' <span style="margin-left: 5px;">&#9660;</span>';
                        modalAlisSatisBirimiDropdown.closest('.dropdown').classList.remove('active');
                    });
                });
            }


            // --- Mevcut Dropdown İşlevleri (genelleştirildi) ---

            document.querySelectorAll('.dropdown').forEach(dropdown => {
                const dropdownButton = dropdown.querySelector('.dropdown-button');
                if (dropdownButton) {
                    dropdownButton.addEventListener('click', function(event) {
                        event.stopPropagation(); // Dropdown'ın kapanmasını engelle
                        // Diğer tüm aktif dropdown'ları kapat
                        document.querySelectorAll('.dropdown.active').forEach(openDropdown => {
                            if (openDropdown !== dropdown) { // Tıklanan dropdown değilse kapat
                                openDropdown.classList.remove('active');
                            }
                        });
                        dropdown.classList.toggle('active');
                    });
                }
            });

            // Dropdown dışına tıklanınca kapanmasını sağla
            window.addEventListener('click', function(event) {
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    // Eğer tıklanan element, dropdown'ın kendisi veya içindeki bir element değilse kapat
                    if (!dropdown.contains(event.target)) {
                        dropdown.classList.remove('active');
                    }
                });
            });

            // "Stok Güncelleme" sayfasındaki "Güncelle" butonlarına tıklama olayı
            document.querySelectorAll('.stok-guncelleme-table .btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const productName = row.dataset.productName;
                    const stock = row.dataset.stock;
                    const stockCode = row.dataset.stockCode;
                    const barcode = row.dataset.barcode;
                    const category = row.dataset.category;
                    const unit = row.dataset.unit;
                    const buyPrice = row.dataset.buyPrice;
                    const sellPrice = row.dataset.sellPrice;
                    const tax = row.dataset.tax;
                    const description = row.dataset.desc;

                    // Modal'daki input alanlarını doldur
                    modalUrunAdi.value = productName;
                    modalUrunStokKodu.value = stockCode;
                    modalBarkodNumarasi.value = barcode;
                    modalKategorisi.textContent = category; // span olduğu için textContent
                    modalAlisSatisBirimiDropdown.innerHTML = unit + ' <span style="margin-left: 5px;">&#9660;</span>';
                    modalAlisFiyati.value = parseFloat(buyPrice).toFixed(2);
                    modalSatisFiyati.value = parseFloat(sellPrice).toFixed(2);
                    modalKdvOrani.value = `%${tax} KDV`; // Select elementini seçili yap
                    // modalSatisDahilAlisFiyati ve modalSatisDahilSatisFiyati için hesaplama veya veri seti eklenebilir
                    modalBaslangicStokMiktari.value = parseFloat(stock).toFixed(2);
                    modalUrunAciklamasi.value = description;

                    // Radio butonlarını ayarla (örneğin sadece stok takibi yapilsin seçili varsayalım)
                    modalStokTakibiYapilsin.checked = true;
                    modalKritikStokAktif.checked = true;
                    modalVergilerDahil.checked = true; // Varsayılan olarak işaretli gelmesi için

                    // Modalı aç
                    productDetailModal.show();
                });
            });

            // "Sil" butonlarına tıklama olayı
            document.querySelectorAll('.stok-guncelleme-table .btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const productName = row.dataset.productName;
                    if (confirm(`${productName} adlı ürünü silmek istediğinize emin misiniz?`)) {
                        row.remove(); // Satırı tablodan kaldır
                        // Burada veritabanından silme işlemi için AJAX çağrısı yapılabilir
                        alert(`${productName} silindi.`);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
</body>

</html>