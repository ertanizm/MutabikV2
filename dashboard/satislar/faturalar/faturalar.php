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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satış Faturaları - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../dashboard.css" rel="stylesheet">
    <link href="../../../assets/satislar/faturalar/faturalar.css" rel="stylesheet">
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
                
                <a href="/MutabikV2/dashboard/satislar/faturalar/yeni-fatura.php" class="create-btn">YENİ FATURA OLUŞTUR</a>
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