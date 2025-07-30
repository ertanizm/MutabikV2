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

// Örnek teklif verileri (local olarak)
$teklifler = [
    [
        'id' => 1,
        'isim' => 'Web Sitesi Geliştirme Projesi',
        'fatura' => 'FAT-2024-001',
        'tarih' => '2024-01-15',
        'toplam' => 25000.00,
        'durum' => 'awaiting',
        'musteri' => 'ABC Şirketi'
    ],
    [
        'id' => 2,
        'isim' => 'Mobil Uygulama Geliştirme',
        'fatura' => 'FAT-2024-002',
        'tarih' => '2024-01-10',
        'toplam' => 45000.00,
        'durum' => 'accepted',
        'musteri' => 'XYZ Teknoloji'
    ],
    [
        'id' => 3,
        'isim' => 'E-ticaret Sistemi Kurulumu',
        'fatura' => 'FAT-2024-003',
        'tarih' => '2024-01-08',
        'toplam' => 18000.00,
        'durum' => 'rejected',
        'musteri' => 'DEF Mağazası'
    ],
    [
        'id' => 4,
        'isim' => 'Veritabanı Optimizasyonu',
        'fatura' => 'FAT-2024-004',
        'tarih' => '2024-01-12',
        'toplam' => 12000.00,
        'durum' => 'awaiting',
        'musteri' => 'GHI Holding'
    ],
    [
        'id' => 5,
        'isim' => 'Sistem Bakım ve Destek',
        'fatura' => 'FAT-2024-005',
        'tarih' => '2024-01-05',
        'toplam' => 8000.00,
        'durum' => 'accepted',
        'musteri' => 'JKL Yazılım'
    ],
    [
        'id' => 6,
        'isim' => 'SEO ve Dijital Pazarlama',
        'fatura' => 'FAT-2024-006',
        'tarih' => '2024-01-20',
        'toplam' => 15000.00,
        'durum' => 'awaiting',
        'musteri' => 'MNO Reklam'
    ]
];

// Filtreleme işlemi
$teklifDurumu = $_GET['teklif-durumu'] ?? 'awaiting';
$filtrelenmisTeklifler = [];

if ($teklifDurumu === 'all') {
    $filtrelenmisTeklifler = $teklifler;
} else {
    $filtrelenmisTeklifler = array_filter($teklifler, function($teklif) use ($teklifDurumu) {
        return $teklif['durum'] === $teklifDurumu;
    });
}

// Toplam hesaplamaları
$toplamKayit = count($filtrelenmisTeklifler);
$toplamTutar = array_sum(array_column($filtrelenmisTeklifler, 'toplam'));
$faturaEdilenTutar = array_sum(array_column(array_filter($filtrelenmisTeklifler, function($t) { return $t['durum'] === 'accepted'; }), 'toplam'));
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
                <div class="filter-container">
                    <button class="filter-btn" id="filterBtn">
                        <i class="fas fa-filter"></i>
                        ▼ FİLTRELE
                    </button>
                    
                    <!-- Filtreleme Modal -->
                    <div class="filter-modal" id="filterModal">
                        <div class="filter-section">
                            <h4>Tarih Aralığı</h4>
                            <div class="filter-date-range">
                                <input type="date" id="filter-start-date" placeholder="Başlangıç">
                                <span>-</span>
                                <input type="date" id="filter-end-date" placeholder="Bitiş">
                            </div>
                        </div>
                        
                        <div class="filter-section">
                            <h4>Tutar Aralığı</h4>
                            <div class="filter-date-range">
                                <input type="number" id="filter-min-amount" placeholder="Min Tutar" min="0">
                                <span>-</span>
                                <input type="number" id="filter-max-amount" placeholder="Max Tutar" min="0">
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button class="filter-btn-secondary" id="clearFilters">TEMİZLE</button>
                            <button class="filter-btn-primary" id="applyFilters">UYGULA</button>
                        </div>
                    </div>
                </div>
                
                <div class="search-box">
                    <input type="text" placeholder="Ara..." id="searchInput">
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
                        <?php if (empty($filtrelenmisTeklifler)): ?>
                            <!-- Boş durum -->
                        <?php else: ?>
                            <?php foreach ($filtrelenmisTeklifler as $teklif): ?>
                                <tr class="teklif-row clickable" data-id="<?php echo $teklif['id']; ?>" 
                                    data-name="<?php echo htmlspecialchars($teklif['isim']); ?>"
                                    data-invoice="<?php echo htmlspecialchars($teklif['fatura']); ?>"
                                    data-date="<?php echo $teklif['tarih']; ?>"
                                    data-total="<?php echo $teklif['toplam']; ?>"
                                    data-status="<?php echo $teklif['durum']; ?>"
                                    onclick="window.location.href='teklif-detay.php?id=<?php echo $teklif['id']; ?>'">
                                    <td>
                                        <div class="teklif-info">
                                            <div class="teklif-name"><?php echo htmlspecialchars($teklif['isim']); ?></div>
                                            <div class="teklif-customer"><?php echo htmlspecialchars($teklif['musteri']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fatura-no"><?php echo htmlspecialchars($teklif['fatura']); ?></span>
                                    </td>
                                    <td>
                                        <span class="tarih"><?php echo date('d.m.Y', strtotime($teklif['tarih'])); ?></span>
                                    </td>
                                    <td>
                                        <span class="tutar">₺<?php echo number_format($teklif['toplam'], 2, ',', '.'); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <?php if (empty($filtrelenmisTeklifler)): ?>
                <div class="empty-state">
                    <h3>Teklifler sayfasına hoş geldiniz!</h3>
                    <p>Mutabık'e kaydettiğiniz tekliflerinize buradan ulaşabilirsiniz. Tekliflerinizi durumlarına göre listelemek için CEVAP BEKLENENLER, ONAYLANANLAR ve REDDEDİLENLER butonlarını kullanabilir ya da tüm tekliflerinize ulaşmak için TÜMÜ butonunu kullanabilirsiniz.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div class="records-dropdown">
                TÜM KAYITLAR ▼
            </div>
            
            <div class="summary-stats">
                <span><?php echo $toplamKayit; ?> Kayıt</span>
                <span>₺<?php echo number_format($toplamTutar, 2, ',', '.'); ?></span>
                <span>Fatura Edilen ₺<?php echo number_format($faturaEdilenTutar, 2, ',', '.'); ?></span>
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
    
    <script>
        // Global değişkenler
        let currentSort = { column: null, direction: 'asc' };
        let originalData = <?php echo json_encode($teklifler); ?>;
        let filteredData = [];

        // URL'den teklif durumunu al
        function getTeklifDurumu() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('teklif-durumu') || 'awaiting';
        }

        // Teklif durumuna göre veriyi filtrele
        function filterByStatus() {
            const teklifDurumu = getTeklifDurumu();
            
            // localStorage'dan teklifleri al
            const localStorageTeklifler = JSON.parse(localStorage.getItem('teklifler') || '[]');
            
            // Mevcut verilerle birleştir
            const allTeklifler = [...originalData, ...localStorageTeklifler];
            
            if (teklifDurumu === 'all') {
                filteredData = allTeklifler;
            } else {
                filteredData = allTeklifler.filter(teklif => teklif.durum === teklifDurumu);
            }
            
            // Eğer önceden sıralama yapılmışsa, tekrar uygula
            if (currentSort.column) {
                sortTable(currentSort.column, currentSort.direction);
            } else {
                renderTable();
                updateStats();
            }
        }

        // Filtreleme modal kontrolü
        const filterBtn = document.getElementById('filterBtn');
        const filterModal = document.getElementById('filterModal');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const applyFiltersBtn = document.getElementById('applyFilters');

        filterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterModal.classList.toggle('active');
            filterBtn.classList.toggle('active');
        });

        // Modal dışına tıklandığında kapat
        document.addEventListener('click', function(e) {
            if (!filterModal.contains(e.target) && !filterBtn.contains(e.target)) {
                filterModal.classList.remove('active');
                filterBtn.classList.remove('active');
            }
        });

        // Filtreleri temizle
        clearFiltersBtn.addEventListener('click', function() {
            document.getElementById('filter-start-date').value = '';
            document.getElementById('filter-end-date').value = '';
            document.getElementById('filter-min-amount').value = '';
            document.getElementById('filter-max-amount').value = '';
        });

        // Filtreleri uygula
        applyFiltersBtn.addEventListener('click', function() {
            applyFilters();
            filterModal.classList.remove('active');
            filterBtn.classList.remove('active');
        });

        function applyFilters() {
            const startDate = document.getElementById('filter-start-date').value;
            const endDate = document.getElementById('filter-end-date').value;
            const minAmount = document.getElementById('filter-min-amount').value;
            const maxAmount = document.getElementById('filter-max-amount').value;

            // Önce duruma göre filtrele
            const teklifDurumu = getTeklifDurumu();
            let statusFilteredData;
            
            if (teklifDurumu === 'all') {
                statusFilteredData = [...originalData];
            } else {
                statusFilteredData = originalData.filter(teklif => teklif.durum === teklifDurumu);
            }

            // Sonra diğer filtreleri uygula
            filteredData = statusFilteredData.filter(teklif => {
                // Tarih filtresi
                if (startDate && teklif.tarih < startDate) return false;
                if (endDate && teklif.tarih > endDate) return false;

                // Tutar filtresi
                if (minAmount && teklif.toplam < parseFloat(minAmount)) return false;
                if (maxAmount && teklif.toplam > parseFloat(maxAmount)) return false;

                return true;
            });

            renderTable();
            updateStats();
        }

        // Arama fonksiyonu
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.teklif-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Sıralama fonksiyonu
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const sortBy = this.dataset.sort;
                const isAsc = this.classList.contains('asc');
                
                // Tüm header'lardan sort class'larını kaldır
                document.querySelectorAll('.sortable').forEach(h => {
                    h.classList.remove('asc', 'desc');
                });
                
                // Bu header'a sort class'ı ekle
                this.classList.add(isAsc ? 'desc' : 'asc');
                
                // Sıralama işlemi
                sortTable(sortBy, isAsc ? 'desc' : 'asc');
            });
        });

        function sortTable(column, direction) {
            currentSort = { column, direction };
            
            filteredData.sort((a, b) => {
                let aVal, bVal;
                
                switch(column) {
                    case 'name':
                        aVal = a.isim.toLowerCase();
                        bVal = b.isim.toLowerCase();
                        break;
                    case 'invoice':
                        aVal = a.fatura.toLowerCase();
                        bVal = b.fatura.toLowerCase();
                        break;
                    case 'date':
                        aVal = new Date(a.tarih);
                        bVal = new Date(b.tarih);
                        break;
                    case 'total':
                        aVal = parseFloat(a.toplam);
                        bVal = parseFloat(b.toplam);
                        break;
                    default:
                        return 0;
                }
                
                if (direction === 'asc') {
                    return aVal > bVal ? 1 : -1;
                } else {
                    return aVal < bVal ? 1 : -1;
                }
            });
            
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('teklifTableBody');
            tbody.innerHTML = '';
            
            if (filteredData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">Filtrelenmiş veri bulunamadı</td></tr>';
                return;
            }
            
            filteredData.forEach(teklif => {
                const row = document.createElement('tr');
                row.className = 'teklif-row clickable';
                row.setAttribute('data-id', teklif.id);
                row.setAttribute('data-name', teklif.isim);
                row.setAttribute('data-invoice', teklif.fatura || teklif.faturaNo);
                row.setAttribute('data-date', teklif.tarih || teklif.duzenlemeTarihi);
                row.setAttribute('data-total', teklif.toplam || 0);
                row.setAttribute('data-status', teklif.durum);
                
                // Tıklanabilir link ekle
                row.onclick = function() {
                    window.location.href = `teklif-detay.php?id=${teklif.id}`;
                };
                
                // Toplam tutarı hesapla (yeni teklifler için)
                let toplamTutar = teklif.toplam || 0;
                if (teklif.urunler && teklif.urunler.length > 0) {
                    toplamTutar = teklif.urunler.reduce((sum, urun) => sum + urun.toplam, 0);
                }
                
                row.innerHTML = `
                    <td>
                        <div class="teklif-info">
                            <div class="teklif-name">${teklif.isim || 'İsimsiz Teklif'}</div>
                            <div class="teklif-customer">${teklif.musteri || teklif.musteri || 'Müşteri Belirtilmemiş'}</div>
                        </div>
                    </td>
                    <td>
                        <span class="fatura-no">${teklif.fatura || teklif.faturaNo || '-'}</span>
                    </td>
                    <td>
                        <span class="tarih">${formatDate(teklif.tarih || teklif.duzenlemeTarihi || teklif.olusturmaTarihi)}</span>
                    </td>
                    <td>
                        <span class="tutar">₺${toplamTutar.toLocaleString('tr-TR', {minimumFractionDigits: 2})}</span>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('tr-TR');
            } catch (e) {
                return dateString;
            }
        }

        function updateStats() {
            const totalRecords = filteredData.length;
            const totalAmount = filteredData.reduce((sum, teklif) => sum + parseFloat(teklif.toplam), 0);
            const invoicedAmount = filteredData
                .filter(teklif => teklif.durum === 'accepted')
                .reduce((sum, teklif) => sum + parseFloat(teklif.toplam), 0);
            
            const statsElements = document.querySelectorAll('.summary-stats span');
            if (statsElements.length >= 3) {
                statsElements[0].textContent = `${totalRecords} Kayıt`;
                statsElements[1].textContent = `₺${totalAmount.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
                statsElements[2].textContent = `Fatura Edilen ₺${invoicedAmount.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
            }
        }

        // URL değişikliklerini dinle
        function handleUrlChange() {
            // Mevcut sıralama durumunu koru
            const currentSortState = { ...currentSort };
            
            // Veriyi yeniden filtrele
            filterByStatus();
            
            // Sıralama durumunu geri yükle
            if (currentSortState.column) {
                currentSort = currentSortState;
                document.querySelectorAll('.sortable').forEach(header => {
                    header.classList.remove('asc', 'desc');
                    if (header.dataset.sort === currentSortState.column) {
                        header.classList.add(currentSortState.direction);
                    }
                });
            }
        }

        // Sayfa yüklendiğinde ve URL değişikliklerinde çalıştır
        document.addEventListener('DOMContentLoaded', function() {
            filterByStatus();
        });

        // URL değişikliklerini dinle (popstate event)
        window.addEventListener('popstate', handleUrlChange);

        // Tab'lara tıklandığında da çalıştır
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                // Kısa bir gecikme ile URL değişikliğini bekle
                setTimeout(handleUrlChange, 100);
            });
        });
    </script>
</body>
</html> 