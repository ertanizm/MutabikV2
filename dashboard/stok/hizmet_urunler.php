<?php
// Veritabanı bağlantı bilgileri
$host = 'localhost';
$dbname = 'zhrashn_db';
$user = 'root';
$pass = '';

// Veritabanı bağlantısını oluştur
$conn = new mysqli($host, $user, $pass, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// ===============================================
// ÜRÜN EKLEME VEYA GÜNCELLEME İŞLEMİ (POST isteği ile)
// ===============================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_or_update_product') {
    $product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $stok_adi = $conn->real_escape_string($_POST['productName']);
    $stok_kodu = isset($_POST['stockCode']) ? $conn->real_escape_string($_POST['stockCode']) : null;
    $barkod_no = isset($_POST['barcodeNumber']) ? $conn->real_escape_string($_POST['barcodeNumber']) : null;
    $kategori = isset($_POST['category']) ? $conn->real_escape_string($_POST['category']) : null;
    $birim = isset($_POST['purchaseSaleUnit']) ? $conn->real_escape_string($_POST['purchaseSaleUnit']) : null;

    // Fiyat bilgileri ve KDV oranları
    $alis_fiyat = isset($_POST['purchasePriceExclTax']) && $_POST['purchasePriceExclTax'] !== '' ? floatval($_POST['purchasePriceExclTax']) : null;
    $alis_fiyat_kdvli = isset($_POST['purchasePriceInclTax']) && $_POST['purchasePriceInclTax'] !== '' ? floatval($_POST['purchasePriceInclTax']) : null;
    $satis_fiyat = isset($_POST['salePriceExclTax']) && $_POST['salePriceExclTax'] !== '' ? floatval($_POST['salePriceExclTax']) : null;
    $satis_fiyat_kdvli = isset($_POST['salePriceInclTax']) && $_POST['salePriceInclTax'] !== '' ? floatval($_POST['salePriceInclTax']) : null;
    $kdv_orani = isset($_POST['kdvRate']) && $_POST['kdvRate'] !== '' ? floatval($_POST['kdvRate']) : null;

    $kritik_stok = isset($_POST['criticalStock']) && $_POST['criticalStock'] !== '' ? intval($_POST['criticalStock']) : null;
    $stok_takip = isset($_POST['stockTracking']) ? 1 : 0;
    $aciklama = isset($_POST['productDescription']) ? $conn->real_escape_string($_POST['productDescription']) : null;
    $miktar = isset($_POST['miktar']) && $_POST['miktar'] !== '' ? intval($_POST['miktar']) : null;

    $urun_fotografi_yolu = null;
    // Fotoğraf yükleme işlemi
    if (isset($_FILES['productPhoto']) && $_FILES['productPhoto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = uniqid() . "_" . basename($_FILES["productPhoto"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Dosya türünü kontrol et
        $check = getimagesize($_FILES["productPhoto"]["tmp_name"]);
        if ($check !== false) {
            // Belirli dosya formatlarına izin ver
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "<script>alert('Sadece JPG, JPEG, PNG & GIF dosyalarına izin verilir.');</script>";
            } else {
                if (move_uploaded_file($_FILES["productPhoto"]["tmp_name"], $target_file)) {
                    $urun_fotografi_yolu = $file_name;
                } else {
                    echo "<script>alert('Üzgünüz, dosyanız yüklenirken bir hata oluştu.');</script>";
                }
            }
        } else {
            echo "<script>alert('Yüklenen dosya bir resim değil.');</script>";
        }
    }

    if ($product_id == 0) {
        // YENİ ÜRÜN EKLEME
        $stmt = $conn->prepare("INSERT INTO stoklar (stok_adi, stok_kodu, barkod_no, kategori, birim, foto, alis_fiyat, alis_fiyat_kdvli, satis_fiyat, satis_fiyat_kdvli, kdv_orani, kritik_stok, stok_takip, aciklama, miktar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("ssssssddddiiisi", $stok_adi, $stok_kodu, $barkod_no, $kategori, $birim, $urun_fotografi_yolu, $alis_fiyat, $alis_fiyat_kdvli, $satis_fiyat, $satis_fiyat_kdvli, $kdv_orani, $kritik_stok, $stok_takip, $aciklama, $miktar);

        if ($stmt->execute()) {
            echo "<script>alert('Ürün/Hizmet başarıyla eklendi!'); window.location.href = 'hizmet_urunler.php';</script>";
        } else {
            echo "<script>alert('Hata: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        $stmt->close();
    } else {
        // MEVCUT ÜRÜN GÜNCELLEME
        $sql = "UPDATE stoklar SET stok_adi=?";
        //        -- stok_kodu=?, barkod_no=?, kategori=?, birim=?, alis_fiyat=?, alis_fiyat_kdvli=?, satis_fiyat=?, satis_fiyat_kdvli=?, kdv_orani=?, kritik_stok=?, stok_takip=?, aciklama=?, miktar=?";

        if ($urun_fotografi_yolu) {
            $sql .= ", foto=?";
        }
        $sql .= " WHERE id=?";
        //
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        if ($urun_fotografi_yolu) {
            // 103. SATIR: Fotoğraf yolu varken (16 değişken, 16 tip karakteri)
            // tipler: stok_adi(s), stok_kodu(s), barkod_no(s), kategori(s), birim(s), 
            // alis_fiyat(d), alis_fiyat_kdvli(d), satis_fiyat(d), satis_fiyat_kdvli(d), kdv_orani(d), 
            // kritik_stok(i), stok_takip(i), aciklama(s), miktar(i), urun_fotografi_yolu(s), product_id(i)
            $stmt->bind_param($stok_adi, $stok_kodu, $barkod_no, $kategori, $birim, $alis_fiyat, $alis_fiyat_kdvli, $satis_fiyat, $satis_fiyat_kdvli, $kdv_orani, $kritik_stok, $stok_takip, $aciklama, $miktar, $urun_fotografi_yolu, $product_id);
        } else {
            // 109. SATIR: Fotoğraf yolu yokken (15 değişken, 15 tip karakteri)
            // tipler: stok_adi(s), stok_kodu(s), barkod_no(s), kategori(s), birim(s), 
            // alis_fiyat(d), alis_fiyat_kdvli(d), satis_fiyat(d), satis_fiyat_kdvli(d), kdv_orani(d), 
            // kritik_stok(i), stok_takip(i), aciklama(s), miktar(i), product_id(i)

            $sql = "UPDATE stoklar SET 
                    stok_adi='$stok_adi', 
                    stok_kodu='$stok_kodu', 
                    barkod_no='$barkod_no', 
                    kategori='$kategori', 
                    birim='$birim', 
                    alis_fiyat=$alis_fiyat, 
                    alis_fiyat_kdvli=$alis_fiyat_kdvli, 
                    satis_fiyat=$satis_fiyat, 
                    satis_fiyat_kdvli=$satis_fiyat_kdvli, 
                    kdv_orani=$kdv_orani, 
                    kritik_stok=$kritik_stok, 
                    stok_takip=$stok_takip, 
                    aciklama='$aciklama', 
                    miktar=$miktar";

            if ($urun_fotografi_yolu != null) { // Sadece yeni fotoğraf yüklendiyse foto sütununu güncelle
                $sql .= ", foto=$urun_fotografi_yolu";
            }

            $sql .= " WHERE id=$product_id"; // product_id zaten intval ile temizlendi

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Ürün/Hizmet başarıyla güncellendi!'); window.location.href = 'hizmet_urunler.php';</script>";
            } else {
                echo "<script>alert('Hata: " . htmlspecialchars($conn->error) . "');</script>";
            }
            //$stmt->bind_param("sssssddddiiisiii", $stok_adi, $product_id);
            //  $stok_kodu, $barkod_no, $kategori, $birim, $alis_fiyat, $alis_fiyat_kdvli, $satis_fiyat, $satis_fiyat_kdvli, $kdv_orani, $kritik_stok, $stok_takip, $aciklama, $miktar, "foto", $product_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Ürün/Hizmet başarıyla güncellendi!'); window.location.href = 'hizmet_urunler.php';</script>";
        } else {
            echo "<script>alert('Hata: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        $stmt->close();
    }
}

// ===============================================
// ÜRÜN SİLME İŞLEMİ (GET isteği ile)
// ===============================================
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM stoklar WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Ürün/Hizmet başarıyla silindi!'); window.location.href = 'hizmet_urunler.php';</script>";
    } else {
        echo "<script>alert('Hata: " . htmlspecialchars($stmt->error) . "');</script>";
    }
    $stmt->close();
}

// ===============================================
// ÜRÜN VERİLERİNİ ÇEKME (JSON olarak döndürmek için AJAX isteği)
// ===============================================
if (isset($_GET['action']) && $_GET['action'] == 'fetch_product' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM stoklar WHERE id = ?");
    if ($stmt === false) {
        // Hata durumunda boş JSON döndür
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Prepare failed: ' . htmlspecialchars($conn->error)]);
        exit;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product_data = $result->fetch_assoc();
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($product_data);
    exit; // Sadece JSON çıktısı ver ve çık
}


// ===============================================
// ÜRÜNLERİ LİSTELEME
// ===============================================
$sql = "SELECT id, stok_adi, miktar, alis_fiyat, satis_fiyat FROM stoklar";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close(); // Bağlantıyı kapat
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