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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satış Faturaları - Mutabık</title>
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

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 20px 0;
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
            margin-bottom: 30px;
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

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Top Header */
        .top-header {
            background-color: white;
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 24px;
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

        /* Content Area */
        .content-area {
            padding: 25px;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }

        .filter-btn {
            background-color: white;
            border: 1px solid var(--border-color);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .search-box {
            flex: 1;
            max-width: 300px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 8px 35px 8px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .create-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
        }

        .create-btn:hover {
            background-color: #1a252f;
            color: white;
            text-decoration: none;
        }

        /* Table */
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table {
            margin: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border: none;
            padding: 15px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .table td {
            border: none;
            padding: 15px;
            vertical-align: middle;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .empty-state p {
            max-width: 500px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        /* Bottom Bar */
        .bottom-bar {
            background-color: white;
            border-top: 1px solid var(--border-color);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .records-dropdown {
            background: none;
            border: 1px solid var(--border-color);
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .summary-stats {
            display: flex;
            gap: 20px;
        }

        /* Sortable table headers */
        .sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        .sortable:hover {
            background-color: #e9ecef;
        }

        .sort-icon {
            margin-left: 5px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .sortable.asc .sort-icon::before {
            content: "\f0de";
            color: var(--primary-color);
        }

        .sortable.desc .sort-icon::before {
            content: "\f0dd";
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-280px);
            }
            .main-content {
                margin-left: 0;
            }
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                max-width: none;
            }
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
    <?php include __DIR__ . '/../../sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Faturalar</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Action Bar -->
            <div class="action-bar">
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    ▼ FİLTRELE
                </button>
                
                <div class="search-box">
                    <input type="text" placeholder="Ara...">
                    <i class="fas fa-search"></i>
                </div>
                
                <a href="#" class="create-btn">YENİ FATURA OLUŞTUR</a>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="name">
                                FATURA İSMİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="number">
                                FATURA NO
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="date">
                                DÜZENLEME TARİHİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="due">
                                VADE TARİHİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="amount">
                                KALAN MEBLAĞ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="faturaTableBody">
                        <!-- Boş durum -->
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div class="empty-state">
                    <h3>Satışlar sayfasına hoş geldiniz!</h3>
                    <p>Mutabık'e kaydedeceğiniz tüm satış faturalarınıza bu sayfadan ulaşacaksınız.</p>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div style="display: flex; gap: 15px;">
                <div class="records-dropdown">
                    TÜM KAYITLAR ▼
                </div>
                <button class="records-dropdown">
                    İÇE/DIŞA AKTAR
                </button>
            </div>
            
            <div class="summary-stats">
                <span>0 Kayıt</span>
                <span>0,00₺</span>
                <span>Tahsil Edilecek 0,00₺</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
</body>
</html> 