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
    $companyName = 'Atia Yazılım';
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
            $companyName = $userData['company_name'] ?? 'Atia Yazılım';
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
    <link href="../../dashboard.css" rel="stylesheet">
    <link href="../../../assets/satislar/teklifler/teklifler.css" rel="stylesheet">
   
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
                
                <a href="/MutabikV2/dashboard/satislar/teklifler/yeni-teklif.php" class="create-btn">YENİ TEKLİF OLUŞTUR</a>
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