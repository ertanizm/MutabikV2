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
$companyName = 'Deprem Yazılım';

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
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --sidebar-bg: #34495e;
            --card-bg: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-color: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        * {
            box-sizing: border-box;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 10px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .sidebar-header .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-header .logo i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            padding-right: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .menu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .menu-item i {
            margin-right: 15px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        .menu-section {
            margin-bottom: 10px;
        }

        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .menu-toggle:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .menu-toggle.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .submenu {
            display: block;
            padding-left: 20px;
            border-left: 1px solid rgba(255,255,255,0.1);
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .submenu-item:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left: 3px solid var(--secondary-color);
        }

        .submenu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid var(--secondary-color);
        }

        .submenu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 14px;
            text-align: center;
        }

        .menu-separator {
            height: 1px;
            background-color: rgba(255,255,255,0.1);
            margin: 20px 0;
        }

        /* Top Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0;
        }

        .header-left h1 {
            font-size: 20px;
            color: var(--text-primary);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-details {
            text-align: left;
        }

        .user-details h6 {
            margin: 0;
            font-size: 14px;
            color: var(--text-primary);
        }

        .user-details small {
            color: var(--text-secondary);
            font-size: 12px;
        }

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
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-calculator"></i>
                Mutabık
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="http://localhost/MutabikV2/dashboard/dashboard2.php" class="menu-item">
                <i class="fas fa-chart-line"></i>
                Güncel Durum
            </a>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle active">
                    <i class="fas fa-arrow-down"></i>
                    SATIŞLAR
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu">
                    <a href="teklifler.php" class="submenu-item active">
                        <i class="fas fa-file-alt"></i>
                        Teklifler
                    </a>
                    <a href="/MutabikV2/dashboard/reports/faturalar.php" class="submenu-item">
                        <i class="fas fa-file-invoice"></i>
                        Faturalar
                    </a>
                    <a href="/MutabikV2/dashboard/reports/musteriler.php" class="submenu-item">
                        <i class="fas fa-users"></i>
                        Müşteriler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Satışlar Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Tahsilatlar Raporu
                    </a>
                    <a href="/MutabikV2/dashboard/reports/gelir-gider.php" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Gelir Gider Raporu
                    </a>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle">
                    <i class="fas fa-arrow-up"></i>
                    GİDERLER
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" style="display: none;">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-alt"></i>
                        Gider Listesi
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        Tedarikçiler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-tie"></i>
                        Çalışanlar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Giderler Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Ödemeler Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        KDV Raporu
                    </a>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle">
                    <i class="fas fa-money-bill-wave"></i>
                    NAKİT
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" style="display: none;">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-university"></i>
                        Kasa ve Bankalar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Çekler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Kasa / Banka Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Nakit Akışı Raporu
                    </a>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle">
                    <i class="fas fa-boxes"></i>
                    STOK
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" style="display: none;">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-tags"></i>
                        Hizmet ve Ürünler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-map-marker-alt"></i>
                        Depolar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-exchange-alt"></i>
                        Depolar Arası Transfer
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        Giden İrsaliyeler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck fa-flip-horizontal"></i>
                        Gelen İrsaliyeler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-list"></i>
                        Fiyat Listeleri
                    </a>
                </div>
            </div>
            
            <div class="menu-separator"></div>
            
            <a href="#" class="menu-item">
                <i class="fas fa-star"></i>
                Uygulamalar
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                Pazaryeri
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-chevron-left"></i>
                Menüyü Sakla
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Ayarlar
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Teklifler > Yeni</h1>
            </div>
            
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        MD
                    </div>
                    <div class="user-details">
                        <h6><?php echo htmlspecialchars($userName); ?></h6>
                        <small><?php echo htmlspecialchars($companyName); ?></small>
                    </div>
                    <i class="fas fa-chevron-down" style="color: var(--text-secondary);"></i>
                </div>
                
                <div class="header-actions">
                    <a href="teklifler.php" class="btn-secondary">VAZGEÇ</a>
                    <div class="dropdown">
                        <a href="#" class="btn-primary" id="saveBtn">KAYDET</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="saveAndAddBtn">KAYDET VE YENİ EKLE</a>
                        </div>
                    </div>
                </div>
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
    <script>
        // Menu toggle functionality
        document.querySelectorAll('.menu-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                const toggleIcon = this.querySelector('.toggle-icon');

                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                toggleIcon.classList.toggle('fa-chevron-down');
                toggleIcon.classList.toggle('fa-chevron-up');
            });
        });

        // Quick date buttons
        document.querySelectorAll('.quick-date-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.quick-date-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const days = parseInt(this.dataset.days);
                const today = new Date();
                const targetDate = new Date(today);
                targetDate.setDate(today.getDate() + days);
                
                const day = String(targetDate.getDate()).padStart(2, '0');
                const month = String(targetDate.getMonth() + 1).padStart(2, '0');
                const year = targetDate.getFullYear();
                
                document.getElementById('vadeTarihi').value = `${day}.${month}.${year}`;
            });
        });

        // Add new row
        document.getElementById('addRowBtn').addEventListener('click', function() {
            const tbody = document.getElementById('productsTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'product-row';
            newRow.innerHTML = `
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
            `;
            tbody.appendChild(newRow);
        });

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;
            let totalVat = 0;
            
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const lineTotal = quantity * price;
                
                // Calculate VAT (assuming 20% for now)
                const vat = lineTotal * 0.20;
                
                subtotal += lineTotal;
                totalVat += vat;
                
                // Update row total
                row.querySelector('.total-cell').textContent = `${lineTotal.toFixed(2).replace('.', ',')} ₺`;
            });
            
            const grandTotal = subtotal + totalVat;
            
            // Update totals table
            document.querySelector('.totals-table tr:nth-child(1) td:last-child').textContent = `${subtotal.toFixed(2).replace('.', ',')}₺`;
            document.querySelector('.totals-table tr:nth-child(2) td:last-child').textContent = `${totalVat.toFixed(2).replace('.', ',')}₺`;
            document.querySelector('.totals-table tr:nth-child(3) td:last-child').textContent = `${grandTotal.toFixed(2).replace('.', ',')}₺`;
        }

        // Event listeners for calculation
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
                calculateTotals();
            }
        });

        // Save button
        document.getElementById('saveBtn').addEventListener('click', function() {
            alert('Teklif kaydedildi!');
            window.location.href = 'teklifler.php';
        });

        // Save and add new button
        document.getElementById('saveAndAddBtn').addEventListener('click', function() {
            alert('Teklif kaydedildi ve yeni teklif oluşturuluyor!');
            // Reset form
            document.getElementById('teklifIsmi').value = '';
            document.getElementById('musteri').value = '';
            document.getElementById('teklifKosullari').value = '';
            // Reset table
            document.getElementById('productsTableBody').innerHTML = `
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
            `;
            calculateTotals();
        });

        // Initialize totals
        calculateTotals();
    </script>
</body>
</html> 