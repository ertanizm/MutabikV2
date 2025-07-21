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
    // Hata mesajını logla ama kullanıcıya gösterme
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    // Kullanıcı bilgilerini varsayılan değerlerle ayarla
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
        // Hata durumunda varsayılan değerler kullanılır
        error_log("Kullanıcı bilgileri alınamadı: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teklifler - Mutabık</title>
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
            --yellow-alert: #fff3cd;
            --yellow-border: #ffeaa7;
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

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Alert Bar */
        .alert-bar {
            background-color: var(--yellow-alert);
            border: 1px solid var(--yellow-border);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .alert-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-right {
            display: flex;
            gap: 10px;
        }

        .alert-btn {
            background: none;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
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

        .trial-info {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .referral-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
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

        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 20px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--text-secondary);
            padding: 12px 20px;
            font-weight: 500;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--secondary-color);
            background: none;
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

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 1000;
            text-decoration: none;
        }

        .fab:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
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
        /* Profil Dropdown ve Widget */
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
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">


        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Teklifler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="teklifTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="?teklif-durumu=awaiting" class="nav-link <?php echo ($_GET['teklif-durumu'] ?? 'awaiting') === 'awaiting' ? 'active' : ''; ?>">
                        CEVAP BEKLENENLER
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="?teklif-durumu=accepted" class="nav-link <?php echo ($_GET['teklif-durumu'] ?? '') === 'accepted' ? 'active' : ''; ?>">
                        ONAYLANANLAR
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="?teklif-durumu=rejected" class="nav-link <?php echo ($_GET['teklif-durumu'] ?? '') === 'rejected' ? 'active' : ''; ?>">
                        REDDEDİLENLER
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="?teklif-durumu=all" class="nav-link <?php echo ($_GET['teklif-durumu'] ?? '') === 'all' ? 'active' : ''; ?>">
                        TÜMÜ
                    </a>
                </li>
            </ul>

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
                
                <a href="#" class="create-btn">YENİ TEKLİF OLUŞTUR</a>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="name">
                                TEKLİF İSMİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="invoice">
                                FATURA
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="date">
                                DÜZENLEME TARİHİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="total">
                                TEKLİF TOPLAMI
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="teklifTableBody">
                        <!-- Boş durum -->
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div class="empty-state">
                    <h3>Teklifler sayfasına hoş geldiniz!</h3>
                    <p>Mutabık'e kaydettiğiniz tekliflerinize buradan ulaşabilirsiniz. Tekliflerinizi durumlarına göre listelemek için CEVAP BEKLENENLER, ONAYLANANLAR ve REDDEDİLENLER butonlarını kullanabilir ya da tüm tekliflerinize ulaşmak için TÜMÜ butonunu kullanabilirsiniz.</p>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div class="records-dropdown">
                TÜM KAYITLAR ▼
            </div>
            
            <div class="summary-stats">
                <span>0 Kayıt</span>
                <span>₺0,00</span>
                <span>Fatura Edilen ₺0,00</span>
            </div>
        </div>
    </div>

    <!-- Profil Dropdown ve Widget -->
    <div id="profileDropdown" class="profile-dropdown">
        <ul>
            <li><button type="button" id="openProfileWidget">Profilim</button></li>
            <li><a href="#" id="logoutBtn">Çıkış Yap</a></li>
        </ul>
    </div>
    <div id="profileWidget" class="profile-widget">
        <button class="close-btn" onclick="document.getElementById('profileWidget').classList.remove('active')">&times;</button>
        <div class="profile-title">Profilim</div>
        <div class="profile-row"><i class="fas fa-user"></i><span class="profile-label">Adı Soyadı:</span><span class="profile-value"><?php echo htmlspecialchars($userName); ?></span></div>
        <div class="profile-row"><i class="fas fa-envelope"></i><span class="profile-label">E-posta Adresi:</span><span class="profile-value"><?php echo htmlspecialchars($userEmail); ?></span></div>
        <div class="profile-row"><i class="fas fa-phone"></i><span class="profile-label">Telefon:</span><span class="profile-value"><?php echo isset($userPhone) ? htmlspecialchars($userPhone) : '-'; ?></span></div>
        <div class="profile-row"><i class="fas fa-briefcase"></i><span class="profile-label">Unvan:</span><span class="profile-value"><?php echo htmlspecialchars($companyName); ?></span></div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
</body>
</html> 