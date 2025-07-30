<?php
session_start();

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'master_db';
$user = 'root';
$pass = '1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    $userEmail = 'miraçdeprem0@gmail.com';
    $userName = 'Miraç Deprem';
    $companyName = 'Deprem Yazılım';
}

// Kullanıcı bilgilerini al
$userEmail = $_SESSION['email'] ?? 'miraçdeprem0@gmail.com';
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';

// Veritabanı bağlantısı başarılıysa kullanıcı bilgilerini al
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
            $userName = $userData['username'] ?? 'Miraç Deprem';
            $companyName = $userData['company_name'] ?? 'Deprem Yazılım';
        }
    } catch (PDOException $e) {
        error_log("Kullanıcı bilgileri alınamadı: " . $e->getMessage());
    }
}

// Bugünün tarihi
$today = date('d.m.Y');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Teklif - Mutabık</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../dashboard.css" rel="stylesheet">
    <style>
        
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            position: relative;
        }

        .btn-primary:hover {
            background-color: #1a252f;
            color: white;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1000;
            border-radius: 5px;
            border: 1px solid var(--border-color);
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            color: var(--text-primary);
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: var(--border-color);
            text-decoration: none;
            color: var(--text-primary);
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .form-container {
            background-color: white;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 30px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-divider {
            border-top: 1px solid var(--border-color);
            margin: 30px 0;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label i {
            margin-right: 8px;
            color: var(--text-secondary);
            width: 16px;
            text-align: center;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background-color: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        /* Input with search icon */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon .form-control {
            padding-right: 40px;
        }

        .input-with-icon i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        /* Form hint */
        .form-hint {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 6px;
            line-height: 1.4;
        }

        .form-hint i {
            margin-right: 6px;
            color: var(--text-secondary);
        }

        /* Customer info box */
        .customer-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            margin-top: 15px;
        }

        .customer-info .form-label {
            margin-bottom: 10px;
        }

        .customer-info-content {
            border-top: 1px solid var(--border-color);
            padding-top: 10px;
            color: var(--text-secondary);
        }

        /* Date section */
        .date-section {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }

        .date-group {
            flex: 1;
        }

        /* Quick date buttons */
        .quick-date-buttons {
            display: flex;
            gap: 8px;
            margin: 12px 0;
            flex-wrap: wrap;
        }

        .quick-date-btn {
            background: none;
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-primary);
            font-weight: 500;
        }

        .quick-date-btn:hover {
            background-color: var(--border-color);
        }

        .quick-date-btn.active {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-outline {
            background: none;
            border: 1px solid var(--border-color);
            padding: 10px 16px;
            border-radius: 5px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background-color: var(--border-color);
            text-decoration: none;
            color: var(--text-primary);
        }

        .btn-outline i {
            margin-right: 8px;
            font-size: 13px;
        }

        /* Products table */
        .products-section {
            margin-top: 30px;
        }

        .products-section h3 {
            color: var(--text-primary);
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .products-table th {
            background-color: #f8f9fa;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
            font-size: 13px;
        }

        .products-table td {
            padding: 15px 12px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .products-table tr:last-child td {
            border-bottom: none;
        }

        .product-row {
            background-color: white;
        }

        .product-row:hover {
            background-color: #f8f9fa;
        }

        /* Table inputs */
        .quantity-input {
            width: 80px;
            padding: 10px 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }

        .unit-select {
            padding: 10px 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: white;
            font-size: 14px;
            min-width: 100px;
        }

        .price-input {
            width: 100px;
            padding: 10px 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: right;
            font-size: 14px;
        }

        .tax-select {
            padding: 10px 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: white;
            font-size: 14px;
            min-width: 120px;
        }

        .total-cell {
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
        }

        .action-buttons-cell {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .btn-icon {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background-color: var(--border-color);
            color: var(--text-primary);
        }

        /* Add row button */
        .btn-add-row {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-add-row:hover {
            background-color: #218838;
        }

        .btn-add-row i {
            margin-right: 8px;
        }

        /* Profit info */
        .profit-info {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Totals section */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid var(--border-color);
        }

        .totals-table {
            width: 320px;
        }

        .totals-table td {
            padding: 12px 0;
            border: none;
            font-size: 14px;
        }

        .totals-table td:first-child {
            text-align: left;
            color: var(--text-secondary);
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: 600;
            color: var(--text-primary);
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
            border-top: 2px solid var(--border-color);
            padding-top: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .form-container {
                margin: 10px;
                padding: 20px;
            }
            
            .date-section {
                flex-direction: column;
                gap: 20px;
            }
            
            .quick-date-buttons {
                flex-direction: column;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .totals-section {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Yeni Teklif</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
                    </div>
                </div>

        <!-- Form Container -->
        <div class="form-container">
            <!-- Teklif İsmi -->
            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i>
                        TEKLİF İSMİ
                    </label>
                    <input type="text" class="form-control" id="teklifIsmi" placeholder="Teklif ismi giriniz">
                </div>
            </div>

            <!-- Müşteri -->
            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-building"></i>
                        MÜŞTERİ
                    </label>
                    <div class="input-with-icon">
                        <input type="text" class="form-control" id="musteri" placeholder="Müşteri adı giriniz">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        Kayıtlı bir müşteri seçebilir veya yeni bir müşteri ismi yazabilirsiniz.
                    </div>
                </div>
                
                <div class="customer-info">
                    <label class="form-label">MÜŞTERİ BİLGİLERİ</label>
                    <div class="customer-info-content">
                        -
                    </div>
                </div>
            </div>

            <!-- Tarihler -->
            <div class="form-section">
                <div class="section-divider"></div>
                
                <div class="date-section">
                    <div class="date-group">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i>
                                DÜZENLEME TARİHİ
                            </label>
                            <div class="input-with-icon">
                                <input type="text" class="form-control" id="duzenlemeTarihi" value="<?php echo $today; ?>">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="date-group">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bell"></i>
                                VADE TARİHİ
                            </label>
                            <div class="quick-date-buttons">
                                <button type="button" class="quick-date-btn active" data-days="0">AYNI GÜN</button>
                                <button type="button" class="quick-date-btn" data-days="7">7 GÜN</button>
                                <button type="button" class="quick-date-btn" data-days="14">14 GÜN</button>
                                <button type="button" class="quick-date-btn" data-days="30">30 GÜN</button>
                                <button type="button" class="quick-date-btn" data-days="60">60 GÜN</button>
                            </div>
                            <div class="input-with-icon">
                                <input type="text" class="form-control" id="vadeTarihi" value="<?php echo $today; ?>">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn-outline">
                        <i class="fas fa-lira-sign"></i>
                        DÖVİZ DEĞİŞTİR
                    </button>
                    <button type="button" class="btn-outline">
                        <i class="fas fa-plus"></i>
                        SİPARİŞ BİLGİSİ EKLE
                    </button>
                </div>
            </div>

            <!-- Teklif Koşulları -->
            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-pencil-alt"></i>
                        TEKLİF KOŞULLARI
                    </label>
                    <textarea class="form-control" id="teklifKosullari" rows="4" placeholder="Teklifin geçerli olduğu süre, ödeme şartları vb. bilgiler için bu alanı kullanabilirsiniz."></textarea>
                </div>
            </div>

            <!-- Hizmet/Ürün Tablosu -->
            <div class="products-section">
                <h3>HİZMET / ÜRÜN</h3>
                
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>HİZMET / ÜRÜN</th>
                            <th>MİKTAR</th>
                            <th>BİRİM</th>
                            <th>BR. FİYAT</th>
                            <th>VERGİ</th>
                            <th>TOPLAM</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <tr class="product-row">
                            <td>
                                <div class="input-with-icon">
                                    <input type="text" class="form-control product-name" placeholder="Ürün/Hizmet adı" oninput="searchProducts(this)" onfocus="showProductDropdown(this)">
                                    <i class="fas fa-search"></i>
                                    <div class="product-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; z-index: 1000;"></div>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="quantity-input" value="1.00" step="0.01" min="0" oninput="updateProductData(this)">
                            </td>
                            <td>
                                <select class="unit-select" onchange="updateProductData(this)">
                                    <option>Adet</option>
                                    <option>Kg</option>
                                    <option>Metre</option>
                                    <option>Saat</option>
                                    <option>Gün</option>
                                    <option>Proje</option>
                                    <option>Aylık</option>
                                    <option>Paket</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="price-input" value="0.00" step="0.01" min="0" oninput="updateProductData(this)">
                                <span style="margin-left: 5px;">₺</span>
                            </td>
                            <td>
                                <select class="tax-select" onchange="updateProductData(this)">
                                    <option value="20">KDV %20</option>
                                    <option value="10">KDV %10</option>
                                    <option value="8">KDV %8</option>
                                    <option value="1">KDV %1</option>
                                    <option value="0">KDV %0</option>
                                </select>
                            </td>
                            <td class="total-cell">
                                0,00 ₺
                            </td>
                            <td class="action-buttons-cell">
                                <button type="button" class="btn-icon" onclick="addProductRow(this)">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn-icon" onclick="removeProductRow(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <button type="button" class="btn-add-row" id="addRowBtn">
                    <i class="fas fa-plus"></i> YENİ SATIR EKLE
                </button>
                
                <div class="profit-info">
                    Toplam Kâr: -
                </div>
            </div>

            <!-- Totals -->
            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td>ARA TOPLAM</td>
                        <td id="araToplam">0,00₺</td>
                    </tr>
                    <tr>
                        <td>TOPLAM KDV</td>
                        <td id="toplamKdv">0,00₺</td>
                    </tr>
                    <tr class="grand-total">
                        <td>GENEL TOPLAM</td>
                        <td id="genelToplam">0,00₺</td>
                    </tr>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons" style="margin-top: 30px; justify-content: center;">
                <button type="button" class="btn-outline" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                    GERİ DÖN
                </button>
                <button type="button" class="btn-primary" onclick="createTeklif()">
                    <i class="fas fa-check"></i>
                    TEKLİF OLUŞTUR
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
    
    <script>
        // Global değişkenler
        let teklifData = {
            isim: '',
            musteri: '',
            duzenlemeTarihi: '',
            vadeTarihi: '',
            kosullar: '',
            urunler: []
        };

        // Varsayılan ürün verileri
        const varsayilanUrunler = [
            { id: 1, isim: 'Web Sitesi Geliştirme', birim: 'Proje', fiyat: 5000.00, kdv: 20 },
            { id: 2, isim: 'Mobil Uygulama Geliştirme', birim: 'Proje', fiyat: 15000.00, kdv: 20 },
            { id: 3, isim: 'E-ticaret Sistemi', birim: 'Proje', fiyat: 8000.00, kdv: 20 },
            { id: 4, isim: 'SEO Hizmeti', birim: 'Aylık', fiyat: 1500.00, kdv: 20 },
            { id: 5, isim: 'Sosyal Medya Yönetimi', birim: 'Aylık', fiyat: 2000.00, kdv: 20 },
            { id: 6, isim: 'Logo Tasarımı', birim: 'Adet', fiyat: 500.00, kdv: 20 },
            { id: 7, isim: 'Kurumsal Kimlik Tasarımı', birim: 'Paket', fiyat: 2500.00, kdv: 20 },
            { id: 8, isim: 'Dijital Reklam Yönetimi', birim: 'Aylık', fiyat: 3000.00, kdv: 20 },
            { id: 9, isim: 'İçerik Yazarlığı', birim: 'Adet', fiyat: 200.00, kdv: 20 },
            { id: 10, isim: 'Video Düzenleme', birim: 'Saat', fiyat: 150.00, kdv: 20 },
            { id: 11, isim: 'Sistem Bakım ve Destek', birim: 'Aylık', fiyat: 1000.00, kdv: 20 },
            { id: 12, isim: 'Veritabanı Optimizasyonu', birim: 'Proje', fiyat: 3000.00, kdv: 20 },
            { id: 13, isim: 'Güvenlik Testi', birim: 'Proje', fiyat: 4000.00, kdv: 20 },
            { id: 14, isim: 'API Geliştirme', birim: 'Saat', fiyat: 250.00, kdv: 20 },
            { id: 15, isim: 'Cloud Sunucu Kurulumu', birim: 'Proje', fiyat: 2000.00, kdv: 20 }
        ];

        // Varsayılan müşteri verileri
        const varsayilanMusteriler = [
            { id: 1, ad: 'ABC Teknoloji A.Ş.', telefon: '0212 123 45 67', email: 'info@abc.com', adres: 'İstanbul, Türkiye' },
            { id: 2, ad: 'XYZ Yazılım Ltd.', telefon: '0216 987 65 43', email: 'contact@xyz.com', adres: 'Ankara, Türkiye' },
            { id: 3, ad: 'DEF Mağazaları', telefon: '0312 456 78 90', email: 'info@def.com', adres: 'İzmir, Türkiye' },
            { id: 4, ad: 'GHI Holding', telefon: '0224 111 22 33', email: 'info@ghi.com', adres: 'Bursa, Türkiye' },
            { id: 5, ad: 'JKL E-ticaret', telefon: '0232 444 55 66', email: 'info@jkl.com', adres: 'İzmir, Türkiye' },
            { id: 6, ad: 'MNO Reklam Ajansı', telefon: '0242 777 88 99', email: 'info@mno.com', adres: 'Antalya, Türkiye' },
            { id: 7, ad: 'PQR Danışmanlık', telefon: '0258 333 44 55', email: 'info@pqr.com', adres: 'Denizli, Türkiye' },
            { id: 8, ad: 'STU Eğitim Kurumu', telefon: '0262 666 77 88', email: 'info@stu.com', adres: 'Kocaeli, Türkiye' },
            { id: 9, ad: 'VWX Sağlık Hizmetleri', telefon: '0274 999 00 11', email: 'info@vwx.com', adres: 'Eskişehir, Türkiye' },
            { id: 10, ad: 'YZM Turizm', telefon: '0246 222 33 44', email: 'info@yzm.com', adres: 'Muğla, Türkiye' }
        ];

        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            initializeForm();
            setupEventListeners();
            updateTotals();
        });

        // Formu başlat
        function initializeForm() {
            // Bugünün tarihini Türkçe formatında ayarla
            const today = new Date();
            const gun = today.getDate().toString().padStart(2, '0');
            const ay = (today.getMonth() + 1).toString().padStart(2, '0');
            const yil = today.getFullYear();
            const todayFormatted = `${gun}.${ay}.${yil}`;
            
            document.getElementById('duzenlemeTarihi').value = todayFormatted;
            document.getElementById('vadeTarihi').value = todayFormatted;
            
            // İlk ürün satırını oluştur
            createProductRow();
        }

        // Event listener'ları ayarla
        function setupEventListeners() {
            // Vade tarihi butonları
            document.querySelectorAll('.quick-date-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.quick-date-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const days = parseInt(this.dataset.days);
                    const duzenlemeTarihiStr = document.getElementById('duzenlemeTarihi').value;
                    
                    // Türkçe tarih formatını parse et (dd.mm.yyyy)
                    const tarihParcalari = duzenlemeTarihiStr.split('.');
                    if (tarihParcalari.length === 3) {
                        const gun = parseInt(tarihParcalari[0]);
                        const ay = parseInt(tarihParcalari[1]) - 1; // Ay 0'dan başlar
                        const yil = parseInt(tarihParcalari[2]);
                        
                        const duzenlemeTarihi = new Date(yil, ay, gun);
                        const vadeTarihi = new Date(duzenlemeTarihi);
                        vadeTarihi.setDate(vadeTarihi.getDate() + days);
                        
                        // Türkçe formatında tarihi ayarla
                        const vadeGun = vadeTarihi.getDate().toString().padStart(2, '0');
                        const vadeAy = (vadeTarihi.getMonth() + 1).toString().padStart(2, '0');
                        const vadeYil = vadeTarihi.getFullYear();
                        
                        document.getElementById('vadeTarihi').value = `${vadeGun}.${vadeAy}.${vadeYil}`;
                    }
                });
            });

            // Yeni satır ekleme butonu
            document.getElementById('addRowBtn').addEventListener('click', createProductRow);

            // Form değişikliklerini dinle
            document.getElementById('teklifIsmi').addEventListener('input', updateTeklifData);
            document.getElementById('musteri').addEventListener('input', updateTeklifData);
            document.getElementById('duzenlemeTarihi').addEventListener('input', updateTeklifData);
            document.getElementById('vadeTarihi').addEventListener('input', updateTeklifData);
            document.getElementById('teklifKosullari').addEventListener('input', updateTeklifData);
        }

        // Ürün satırı oluştur
        function createProductRow() {
            const tbody = document.getElementById('productsTableBody');
            const row = document.createElement('tr');
            row.className = 'product-row';
            
            row.innerHTML = `
                <td>
                    <div class="input-with-icon">
                        <input type="text" class="form-control product-name" placeholder="Ürün/Hizmet adı" oninput="searchProducts(this)" onfocus="showProductDropdown(this)">
                        <i class="fas fa-search"></i>
                        <div class="product-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; z-index: 1000;"></div>
                    </div>
                </td>
                <td>
                    <input type="number" class="quantity-input" value="1.00" step="0.01" min="0" oninput="updateProductData(this)">
                </td>
                <td>
                    <select class="unit-select" onchange="updateProductData(this)">
                        <option>Adet</option>
                        <option>Kg</option>
                        <option>Metre</option>
                        <option>Saat</option>
                        <option>Gün</option>
                        <option>Proje</option>
                        <option>Aylık</option>
                        <option>Paket</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="price-input" value="0.00" step="0.01" min="0" oninput="updateProductData(this)">
                    <span style="margin-left: 5px;">₺</span>
                </td>
                <td>
                    <select class="tax-select" onchange="updateProductData(this)">
                        <option value="20">KDV %20</option>
                        <option value="10">KDV %10</option>
                        <option value="8">KDV %8</option>
                        <option value="1">KDV %1</option>
                        <option value="0">KDV %0</option>
                    </select>
                </td>
                <td class="total-cell">
                    0,00 ₺
                </td>
                <td class="action-buttons-cell">
                    <button type="button" class="btn-icon" onclick="addProductRow(this)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn-icon" onclick="removeProductRow(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
            updateTotals();
        }

        // Ürün arama fonksiyonu
        function searchProducts(input) {
            const searchTerm = input.value.toLowerCase();
            const dropdown = input.parentNode.querySelector('.product-dropdown');
            
            if (searchTerm.length < 2) {
                dropdown.style.display = 'none';
                return;
            }
            
            const filteredProducts = varsayilanUrunler.filter(urun => 
                urun.isim.toLowerCase().includes(searchTerm)
            );
            
            if (filteredProducts.length > 0) {
                dropdown.innerHTML = filteredProducts.map(urun => 
                    `<div class="product-option" onclick="selectProduct(this, '${urun.isim}', ${urun.fiyat}, '${urun.birim}', ${urun.kdv})" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;">
                        <strong>${urun.isim}</strong><br>
                        <small>${urun.birim} - ₺${urun.fiyat.toLocaleString('tr-TR')}</small>
                    </div>`
                ).join('');
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Ürün seçme fonksiyonu
        function selectProduct(element, isim, fiyat, birim, kdv) {
            const row = element.closest('tr');
            const nameInput = row.querySelector('.product-name');
            const priceInput = row.querySelector('.price-input');
            const unitSelect = row.querySelector('.unit-select');
            const taxSelect = row.querySelector('.tax-select');
            
            nameInput.value = isim;
            priceInput.value = fiyat.toFixed(2);
            
            // Birim seç
            const unitOptions = unitSelect.options;
            for (let i = 0; i < unitOptions.length; i++) {
                if (unitOptions[i].text === birim) {
                    unitSelect.selectedIndex = i;
                    break;
                }
            }
            
            // KDV seç
            const taxOptions = taxSelect.options;
            for (let i = 0; i < taxOptions.length; i++) {
                if (taxOptions[i].value == kdv) {
                    taxSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Dropdown'ı gizle
            element.closest('.product-dropdown').style.display = 'none';
            
            // Toplamı güncelle
            updateProductData(priceInput);
        }

        // Ürün dropdown'ını göster
        function showProductDropdown(input) {
            const dropdown = input.parentNode.querySelector('.product-dropdown');
            if (input.value.length >= 2) {
                dropdown.style.display = 'block';
            }
        }

        // Dropdown dışına tıklandığında gizle
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.input-with-icon')) {
                document.querySelectorAll('.product-dropdown').forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            }
        });

        // Ürün satırı ekle
        function addProductRow(button) {
            const row = button.closest('tr');
            const newRow = row.cloneNode(true);
            
            // Yeni satırın değerlerini temizle
            newRow.querySelector('.product-name').value = '';
            newRow.querySelector('.quantity-input').value = '1.00';
            newRow.querySelector('.unit-select').selectedIndex = 0;
            newRow.querySelector('.price-input').value = '0.00';
            newRow.querySelector('.tax-select').selectedIndex = 0;
            newRow.querySelector('.total-cell').textContent = '0,00 ₺';
            
            // Event listener'ları yeniden ekle
            newRow.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('input', function() { updateProductData(this); });
                input.addEventListener('change', function() { updateProductData(this); });
            });
            
            row.parentNode.insertBefore(newRow, row.nextSibling);
            updateTotals();
        }

        // Ürün satırı sil
        function removeProductRow(button) {
            const tbody = document.getElementById('productsTableBody');
            if (tbody.children.length > 1) {
                button.closest('tr').remove();
                updateTotals();
            }
        }

        // Ürün verilerini güncelle
        function updateProductData(element) {
            const row = element.closest('tr');
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const taxRate = parseFloat(row.querySelector('.tax-select').value) || 0;
            
            const subtotal = quantity * price;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;
            
            row.querySelector('.total-cell').textContent = total.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + ' ₺';
            
            updateTotals();
        }

        // Toplamları güncelle
        function updateTotals() {
            let araToplam = 0;
            let toplamKdv = 0;
            
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const taxRate = parseFloat(row.querySelector('.tax-select').value) || 0;
                
                const subtotal = quantity * price;
                const taxAmount = subtotal * (taxRate / 100);
                
                araToplam += subtotal;
                toplamKdv += taxAmount;
            });
            
            const genelToplam = araToplam + toplamKdv;
            
            document.getElementById('araToplam').textContent = araToplam.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + '₺';
            document.getElementById('toplamKdv').textContent = toplamKdv.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + '₺';
            document.getElementById('genelToplam').textContent = genelToplam.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + '₺';
        }

        // Teklif verilerini güncelle
        function updateTeklifData() {
            teklifData.isim = document.getElementById('teklifIsmi').value;
            teklifData.musteri = document.getElementById('musteri').value;
            teklifData.duzenlemeTarihi = document.getElementById('duzenlemeTarihi').value;
            teklifData.vadeTarihi = document.getElementById('vadeTarihi').value;
            teklifData.kosullar = document.getElementById('teklifKosullari').value;
        }

        // Ürün verilerini topla
        function collectProductData() {
            teklifData.urunler = [];
            
            document.querySelectorAll('.product-row').forEach(row => {
                const urun = {
                    isim: row.querySelector('.product-name').value,
                    miktar: parseFloat(row.querySelector('.quantity-input').value) || 0,
                    birim: row.querySelector('.unit-select').value,
                    birimFiyat: parseFloat(row.querySelector('.price-input').value) || 0,
                    vergi: parseFloat(row.querySelector('.tax-select').value) || 0,
                    toplam: parseFloat(row.querySelector('.total-cell').textContent.replace(/[^\d,]/g, '').replace(',', '.')) || 0
                };
                
                if (urun.isim && urun.miktar > 0) {
                    teklifData.urunler.push(urun);
                }
            });
        }

        // Teklif oluştur
        function createTeklif() {
            updateTeklifData();
            collectProductData();
            
            // Validasyon
            if (!teklifData.isim.trim()) {
                alert('Lütfen teklif ismini giriniz.');
                return;
            }
            
            if (!teklifData.musteri.trim()) {
                alert('Lütfen müşteri adını giriniz.');
                return;
            }
            
            if (teklifData.urunler.length === 0) {
                alert('Lütfen en az bir ürün/hizmet ekleyiniz.');
                return;
            }
            
            // Teklif verilerini localStorage'a kaydet (gerçek uygulamada veritabanına kaydedilir)
            const teklifler = JSON.parse(localStorage.getItem('teklifler') || '[]');
            const yeniTeklif = {
                id: Date.now(),
                ...teklifData,
                durum: 'awaiting',
                olusturmaTarihi: new Date().toISOString(),
                faturaNo: 'FAT-' + Date.now()
            };
            
            teklifler.push(yeniTeklif);
            localStorage.setItem('teklifler', JSON.stringify(teklifler));
            
            alert('Teklif başarıyla oluşturuldu!');
            window.location.href = 'teklifler.php';
        }

        // Müşteri arama (simülasyon)
        document.getElementById('musteri').addEventListener('input', function() {
            const musteriAdi = this.value.toLowerCase();
            const customerInfo = document.querySelector('.customer-info-content');
            
            const bulunanMusteri = varsayilanMusteriler.find(m => m.ad.toLowerCase().includes(musteriAdi));
            
            if (bulunanMusteri && musteriAdi.length > 2) {
                customerInfo.innerHTML = `
                    <strong>${bulunanMusteri.ad}</strong><br>
                    Tel: ${bulunanMusteri.telefon}<br>
                    Email: ${bulunanMusteri.email}<br>
                    Adres: ${bulunanMusteri.adres}
                `;
            } else {
                customerInfo.innerHTML = '-';
            }
        });
    </script>
</body>
</html> 