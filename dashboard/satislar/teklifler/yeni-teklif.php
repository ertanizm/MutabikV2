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
                                    <input type="text" class="form-control" placeholder="Ürün/Hizmet adı">
                                    <i class="fas fa-search"></i>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="quantity-input" value="1.00" step="0.01" min="0">
                            </td>
                            <td>
                                <select class="unit-select">
                                    <option>Adet</option>
                                    <option>Kg</option>
                                    <option>Metre</option>
                                    <option>Saat</option>
                                    <option>Gün</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="price-input" value="0.00" step="0.01" min="0">
                                <span style="margin-left: 5px;">₺</span>
                            </td>
                            <td>
                                <select class="tax-select">
                                    <option>KDV %20</option>
                                    <option>KDV %10</option>
                                    <option>KDV %8</option>
                                    <option>KDV %1</option>
                                    <option>KDV %0</option>
                                </select>
                            </td>
                            <td class="total-cell">
                                0,00 ₺
                            </td>
                            <td class="action-buttons-cell">
                                <button type="button" class="btn-icon">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn-icon">
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
                        <td>0,00₺</td>
                    </tr>
                    <tr>
                        <td>TOPLAM KDV</td>
                        <td>0,00₺</td>
                    </tr>
                    <tr class="grand-total">
                        <td>GENEL TOPLAM</td>
                        <td>0,00₺</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
</body>
</html> 