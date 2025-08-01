<?php

require '../../config/config.php';
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';

// Statik nakit akış verileri (detaylı)
$nakit_akislari = [
    // Temmuz 2024
    ['id' => 1, 'tarih' => '2024-07-01', 'aciklama' => 'Başlangıç Bakiyesi', 'kategori' => 'Genel', 'giris' => 10000.00, 'cikis' => 0.00, 'bakiye' => 10000.00, 'not' => 'Ay başı bakiyesi'],
    ['id' => 2, 'tarih' => '2024-07-03', 'aciklama' => 'Müşteri Ödemesi', 'kategori' => 'Gelir', 'giris' => 25000.00, 'cikis' => 0.00, 'bakiye' => 35000.00, 'not' => 'ABC Şirketi - Fatura #001'],
    ['id' => 3, 'tarih' => '2024-07-05', 'aciklama' => 'Ofis Kirası', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 5000.00, 'bakiye' => 30000.00, 'not' => 'Temmuz ayı kira'],
    ['id' => 4, 'tarih' => '2024-07-08', 'aciklama' => 'Hizmet Geliri', 'kategori' => 'Gelir', 'giris' => 18000.00, 'cikis' => 0.00, 'bakiye' => 48000.00, 'not' => 'DEF Ltd. - Proje teslimatı'],
    ['id' => 5, 'tarih' => '2024-07-10', 'aciklama' => 'Personel Maaşı', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 8000.00, 'bakiye' => 40000.00, 'not' => 'Ahmet Yılmaz maaş'],
    ['id' => 6, 'tarih' => '2024-07-12', 'aciklama' => 'Tedarikçi Ödemesi', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 15000.00, 'bakiye' => 25000.00, 'not' => 'XYZ Tedarik - Malzeme'],
    ['id' => 7, 'tarih' => '2024-07-15', 'aciklama' => 'Proje Ödemesi', 'kategori' => 'Gelir', 'giris' => 35000.00, 'cikis' => 0.00, 'bakiye' => 60000.00, 'not' => 'GHI Şirketi - PRJ001'],
    ['id' => 8, 'tarih' => '2024-07-18', 'aciklama' => 'Elektrik Faturası', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 1200.00, 'bakiye' => 58800.00, 'not' => 'TEDAŞ - Temmuz'],
    ['id' => 9, 'tarih' => '2024-07-20', 'aciklama' => 'İnternet Faturası', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 450.00, 'bakiye' => 58350.00, 'not' => 'TurkNet - Temmuz'],
    ['id' => 10, 'tarih' => '2024-07-22', 'aciklama' => 'Yazılım Lisansı', 'kategori' => 'Gelir', 'giris' => 42000.00, 'cikis' => 0.00, 'bakiye' => 100350.00, 'not' => 'JKL Teknoloji'],
    ['id' => 11, 'tarih' => '2024-07-25', 'aciklama' => 'İkinci Personel Maaşı', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 7500.00, 'bakiye' => 92850.00, 'not' => 'Fatma Demir maaş'],
    
    // Haziran 2024
    ['id' => 12, 'tarih' => '2024-06-01', 'aciklama' => 'Başlangıç Bakiyesi', 'kategori' => 'Genel', 'giris' => 8000.00, 'cikis' => 0.00, 'bakiye' => 8000.00, 'not' => 'Ay başı bakiyesi'],
    ['id' => 13, 'tarih' => '2024-06-05', 'aciklama' => 'Danışmanlık Hizmeti', 'kategori' => 'Gelir', 'giris' => 55000.00, 'cikis' => 0.00, 'bakiye' => 63000.00, 'not' => 'MNO Holding'],
    ['id' => 14, 'tarih' => '2024-06-10', 'aciklama' => 'Ofis Malzemeleri', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 3200.00, 'bakiye' => 59800.00, 'not' => 'PQR Malzeme'],
    ['id' => 15, 'tarih' => '2024-06-15', 'aciklama' => 'Web Sitesi Geliştirme', 'kategori' => 'Gelir', 'giris' => 28000.00, 'cikis' => 0.00, 'bakiye' => 87800.00, 'not' => 'RST Şirketi'],
    ['id' => 16, 'tarih' => '2024-06-18', 'aciklama' => 'Personel Maaşı', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 6500.00, 'bakiye' => 81300.00, 'not' => 'Mehmet Kaya maaş'],
    ['id' => 17, 'tarih' => '2024-06-20', 'aciklama' => 'KDV Ödemesi', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 8500.00, 'bakiye' => 72800.00, 'not' => 'Gelir İdaresi'],
    ['id' => 18, 'tarih' => '2024-06-25', 'aciklama' => 'Mobil Uygulama Geliştirme', 'kategori' => 'Gelir', 'giris' => 38000.00, 'cikis' => 0.00, 'bakiye' => 110800.00, 'not' => 'UVW Ltd.'],
    ['id' => 19, 'tarih' => '2024-06-28', 'aciklama' => 'İşyeri Sigortası', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 2800.00, 'bakiye' => 108000.00, 'not' => 'Axa Sigorta'],
    
    // Mayıs 2024
    ['id' => 20, 'tarih' => '2024-05-01', 'aciklama' => 'Başlangıç Bakiyesi', 'kategori' => 'Genel', 'giris' => 6000.00, 'cikis' => 0.00, 'bakiye' => 6000.00, 'not' => 'Ay başı bakiyesi'],
    ['id' => 21, 'tarih' => '2024-05-03', 'aciklama' => 'E-Ticaret Sistemi', 'kategori' => 'Gelir', 'giris' => 45000.00, 'cikis' => 0.00, 'bakiye' => 51000.00, 'not' => 'XYZ Teknoloji'],
    ['id' => 22, 'tarih' => '2024-05-08', 'aciklama' => 'Yazılım Lisansları', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 12000.00, 'bakiye' => 39000.00, 'not' => 'ABC Yazılım'],
    ['id' => 23, 'tarih' => '2024-05-12', 'aciklama' => 'Proje Yönetimi Hizmeti', 'kategori' => 'Gelir', 'giris' => 22000.00, 'cikis' => 0.00, 'bakiye' => 61000.00, 'not' => 'DEF Danışmanlık'],
    ['id' => 24, 'tarih' => '2024-05-15', 'aciklama' => 'Personel Maaşı', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 7200.00, 'bakiye' => 53800.00, 'not' => 'Ayşe Özkan maaş'],
    ['id' => 25, 'tarih' => '2024-05-18', 'aciklama' => 'Su Faturası', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 350.00, 'bakiye' => 53450.00, 'not' => 'İSKİ'],
    ['id' => 26, 'tarih' => '2024-05-22', 'aciklama' => 'CRM Sistemi Geliştirme', 'kategori' => 'Gelir', 'giris' => 52000.00, 'cikis' => 0.00, 'bakiye' => 105450.00, 'not' => 'GHI Holding'],
    ['id' => 27, 'tarih' => '2024-05-25', 'aciklama' => 'Gelir Vergisi', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 9500.00, 'bakiye' => 95950.00, 'not' => 'Gelir İdaresi'],
    
    // Nisan 2024
    ['id' => 28, 'tarih' => '2024-04-01', 'aciklama' => 'Başlangıç Bakiyesi', 'kategori' => 'Genel', 'giris' => 4000.00, 'cikis' => 0.00, 'bakiye' => 4000.00, 'not' => 'Ay başı bakiyesi'],
    ['id' => 29, 'tarih' => '2024-04-02', 'aciklama' => 'ERP Sistemi Kurulumu', 'kategori' => 'Gelir', 'giris' => 68000.00, 'cikis' => 0.00, 'bakiye' => 72000.00, 'not' => 'JKL Şirketi'],
    ['id' => 30, 'tarih' => '2024-04-05', 'aciklama' => 'Donanım Alımı', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 18500.00, 'bakiye' => 53500.00, 'not' => 'MNO Bilgisayar'],
    ['id' => 31, 'tarih' => '2024-04-10', 'aciklama' => 'Mobil Uygulama Geliştirme', 'kategori' => 'Gelir', 'giris' => 32000.00, 'cikis' => 0.00, 'bakiye' => 85500.00, 'not' => 'PQR Teknoloji'],
    ['id' => 32, 'tarih' => '2024-04-12', 'aciklama' => 'Personel Maaşı', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 6800.00, 'bakiye' => 78700.00, 'not' => 'Can Yıldız maaş'],
    ['id' => 33, 'tarih' => '2024-04-15', 'aciklama' => 'Ofis Kira Ödemesi', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 5000.00, 'bakiye' => 73700.00, 'not' => 'Emlak Sahibi'],
    ['id' => 34, 'tarih' => '2024-04-20', 'aciklama' => 'İş Süreçleri Analizi', 'kategori' => 'Gelir', 'giris' => 28000.00, 'cikis' => 0.00, 'bakiye' => 101700.00, 'not' => 'RST Danışmanlık'],
    ['id' => 35, 'tarih' => '2024-04-22', 'aciklama' => 'Elektrik Faturası', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 1100.00, 'bakiye' => 100600.00, 'not' => 'TEDAŞ'],
    ['id' => 36, 'tarih' => '2024-04-25', 'aciklama' => 'KDV Ödemesi', 'kategori' => 'Gider', 'giris' => 0.00, 'cikis' => 7800.00, 'bakiye' => 92800.00, 'not' => 'Gelir İdaresi']
];

// Filtreleme fonksiyonu
function filterNakitAkisi($akislar, $kategori, $tarih_baslangic, $tarih_bitis) {
    $filtered = $akislar;
    
    // Kategori filtresi
    if (!empty($kategori) && $kategori != 'tumu') {
        $filtered = array_filter($filtered, function($akis) use ($kategori) {
            return $akis['kategori'] == $kategori;
        });
    }
    
    // Tarih filtresi
    if (!empty($tarih_baslangic) && !empty($tarih_bitis)) {
        $filtered = array_filter($filtered, function($akis) use ($tarih_baslangic, $tarih_bitis) {
            $akis_tarihi = strtotime($akis['tarih']);
            $baslangic = strtotime($tarih_baslangic);
            $bitis = strtotime($tarih_bitis);
            return $akis_tarihi >= $baslangic && $akis_tarihi <= $bitis;
        });
    }
    
    return $filtered;
}

// Export işlemleri
if (isset($_GET['export'])) {
    $kategori = $_GET['kategori'] ?? '';
    $tarih_baslangic = $_GET['tarih_baslangic'] ?? '';
    $tarih_bitis = $_GET['tarih_bitis'] ?? '';
    
    $export_akislar = filterNakitAkisi($nakit_akislari, $kategori, $tarih_baslangic, $tarih_bitis);
    
    if ($_GET['export'] == 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="nakit_akis_raporu_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['Tarih', 'Açıklama', 'Kategori', 'Giriş (₺)', 'Çıkış (₺)', 'Bakiye (₺)', 'Not']);
        foreach ($export_akislar as $akis) {
            fputcsv($output, [
                date('d.m.Y', strtotime($akis['tarih'])),
                $akis['aciklama'],
                $akis['kategori'],
                number_format($akis['giris'], 2, ',', '.'),
                number_format($akis['cikis'], 2, ',', '.'),
                number_format($akis['bakiye'], 2, ',', '.'),
                $akis['not']
            ]);
        }
        fclose($output);
        exit();
    }
}

// Filtreleme parametreleri
$kategori = $_GET['kategori'] ?? '';
$tarih_baslangic = $_GET['tarih_baslangic'] ?? '2024-07-01';
$tarih_bitis = $_GET['tarih_bitis'] ?? '2024-07-31';

// Filtreleme uygula
$filtered_akislar = filterNakitAkisi($nakit_akislari, $kategori, $tarih_baslangic, $tarih_bitis);

// Toplam hesaplamalar
$toplam_giris = 0;
$toplam_cikis = 0;
$baslangic_bakiye = 0;
$bitis_bakiye = 0;

if (!empty($filtered_akislar)) {
    // Başlangıç bakiyesi: İlk işlemden önceki bakiye
    $ilk_islem = reset($filtered_akislar);
    $baslangic_bakiye = $ilk_islem['bakiye'] - $ilk_islem['giris'] + $ilk_islem['cikis'];
    
    // Bitiş bakiyesi: Son işlemden sonraki bakiye
    $bitis_bakiye = end($filtered_akislar)['bakiye'];
    
    // Toplam giriş/çıkış hesapla
    foreach ($filtered_akislar as $akis) {
        $toplam_giris += $akis['giris'];
        $toplam_cikis += $akis['cikis'];
    }
}

$net_akis = $toplam_giris - $toplam_cikis;

// Grafik verileri (günlük giriş/çıkış ve net akış)
$grafik_verileri = [];
$gunluk_veriler = [];

// Günlük verileri grupla
foreach ($filtered_akislar as $akis) {
    $gun = date('d.m', strtotime($akis['tarih']));
    if (!isset($gunluk_veriler[$gun])) {
        $gunluk_veriler[$gun] = ['giris' => 0, 'cikis' => 0];
    }
    $gunluk_veriler[$gun]['giris'] += $akis['giris'];
    $gunluk_veriler[$gun]['cikis'] += $akis['cikis'];
}

// Grafik için veri hazırla
foreach ($gunluk_veriler as $gun => $veriler) {
    $grafik_verileri[] = [
        'tarih' => $gun,
        'giris' => $veriler['giris'],
        'cikis' => $veriler['cikis'],
        'net' => $veriler['giris'] - $veriler['cikis']
    ];
}

// Kategori istatistikleri
$kategori_istatistikleri = [];
foreach ($filtered_akislar as $akis) {
    $kat = $akis['kategori'];
    if (!isset($kategori_istatistikleri[$kat])) {
        $kategori_istatistikleri[$kat] = ['giris' => 0, 'cikis' => 0];
    }
    $kategori_istatistikleri[$kat]['giris'] += $akis['giris'];
    $kategori_istatistikleri[$kat]['cikis'] += $akis['cikis'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nakit Akış Raporu - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Nakit Akış Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        
        <div class="content-area">
            <div class="d-flex flex-column gap-4">
                <!-- Özet kutuları -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="financial-card text-center">
                            <div class="card-title">Başlangıç Bakiyesi</div>
                            <div class="card-amount" style="color: var(--secondary-color);">₺<?php echo number_format($baslangic_bakiye, 2, ',', '.'); ?></div>
                            <div class="card-status"><?php echo date('d.m.Y', strtotime($tarih_baslangic)); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="financial-card text-center">
                            <div class="card-title">Toplam Giriş</div>
                            <div class="card-amount" style="color: var(--secondary-color);">₺<?php echo number_format($toplam_giris, 2, ',', '.'); ?></div>
                            <div class="card-status">Toplam Nakit Girişi</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="financial-card text-center">
                            <div class="card-title">Toplam Çıkış</div>
                            <div class="card-amount" style="color: #b07a4a;">₺<?php echo number_format($toplam_cikis, 2, ',', '.'); ?></div>
                            <div class="card-status">Toplam Nakit Çıkışı</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="financial-card text-center">
                            <div class="card-title">Net Akış</div>
                            <div class="card-amount" style="color: <?php echo $net_akis >= 0 ? 'var(--secondary-color)' : '#b07a4a'; ?>;">₺<?php echo number_format($net_akis, 2, ',', '.'); ?></div>
                            <div class="card-status">Giriş - Çıkış</div>
                        </div>
                    </div>
                </div>
                
                <!-- Filtre butonları ve tarih -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <a href="?kategori=Gelir&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>"
                       class="btn btn-outline-primary btn-sm <?php echo $kategori == 'Gelir' ? 'active' : ''; ?>">GELİR</a>
                    <a href="?kategori=Gider&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>"
                       class="btn btn-outline-secondary btn-sm <?php echo $kategori == 'Gider' ? 'active' : ''; ?>">GİDER</a>
                    <a href="?tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>"
                       class="btn btn-dark btn-sm fw-bold <?php echo empty($kategori) ? 'active' : ''; ?>">TÜMÜ</a>
                    <form method="GET" class="d-flex align-items-center gap-2 ms-auto">
                        <?php if (!empty($kategori)): ?>
                            <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>">
                        <?php endif; ?>
                        <input type="date" name="tarih_baslangic" value="<?php echo $tarih_baslangic; ?>" class="form-control form-control-sm">
                        <span class="align-self-center">-</span>
                        <input type="date" name="tarih_bitis" value="<?php echo $tarih_bitis; ?>" class="form-control form-control-sm">
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-calendar-alt"></i> Filtrele
                        </button>
                    </form>
                </div>
                
                <!-- Grafik alanı -->
                <div class="mb-4" style="background: #fff; border-radius: 8px; height: 400px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid var(--border-color); padding: 20px;">
                    <h5 class="text-center mb-3" style="color: var(--text-primary); font-weight: 600;">Günlük Nakit Akışı</h5>
                    <div style="height: 320px; position: relative;">
                        <canvas id="nakitAkisiGrafik"></canvas>
                    </div>
                </div>
                
                <!-- Kategori istatistikleri -->
                <?php if (!empty($kategori_istatistikleri)): ?>
                <div class="mb-4">
                    <h5 style="color: var(--text-primary); font-weight: 600; margin-bottom: 15px;">
                        <i class="fas fa-chart-pie"></i> Kategori Bazında Analiz
                    </h5>
                    <div class="row g-3">
                        <?php foreach ($kategori_istatistikleri as $kat => $stats): ?>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><?php echo $kat; ?></h6>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-success">Giriş: ₺<?php echo number_format($stats['giris'], 2, ',', '.'); ?></span>
                                        <span class="text-danger">Çıkış: ₺<?php echo number_format($stats['cikis'], 2, ',', '.'); ?></span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-muted">Net: ₺<?php echo number_format($stats['giris'] - $stats['cikis'], 2, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Export butonları -->
                <div class="d-flex justify-content-end mb-3">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Dışarı Aktar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?export=csv&kategori=<?php echo urlencode($kategori); ?>&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>">
                                <i class="fas fa-file-csv"></i> CSV İndir
                            </a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Tablo başlığı -->
                <div class="mb-2">
                    <h4 style="font-size: 20px; color: var(--text-primary); font-weight: 600;">
                        <?php echo date('d F Y', strtotime($tarih_baslangic)); ?> - <?php echo date('d F Y', strtotime($tarih_bitis)); ?> Arası Nakit Akışı Detayı
                    </h4>
                    <div class="mt-2">
                        <small class="text-muted">
                            <strong>Başlangıç:</strong> ₺<?php echo number_format($baslangic_bakiye, 2, ',', '.'); ?> | 
                            <strong>Net Akış:</strong> ₺<?php echo number_format($net_akis, 2, ',', '.'); ?> | 
                            <strong>Bitiş:</strong> ₺<?php echo number_format($baslangic_bakiye + $net_akis, 2, ',', '.'); ?>
                        </small>
                    </div>
                </div>
                
                <!-- Tablo -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>TARİH</th>
                                <th>AÇIKLAMA</th>
                                <th>KATEGORİ</th>
                                <th>GİRİŞ (₺)</th>
                                <th>ÇIKIŞ (₺)</th>
                                <th>BAKİYE (₺)</th>
                                <th>NOT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($filtered_akislar)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Seçilen tarih aralığında işlem bulunamadı</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($filtered_akislar as $akis): ?>
                                    <tr>
                                        <td><?php echo date('d.m.Y', strtotime($akis['tarih'])); ?></td>
                                        <td><?php echo htmlspecialchars($akis['aciklama']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $akis['kategori'] == 'Gelir' ? 'bg-success' : ($akis['kategori'] == 'Gider' ? 'bg-danger' : 'bg-secondary'); ?>">
                                                <?php echo htmlspecialchars($akis['kategori']); ?>
                                            </span>
                                        </td>
                                        <td class="text-success"><?php echo $akis['giris'] > 0 ? '₺' . number_format($akis['giris'], 2, ',', '.') : '-'; ?></td>
                                        <td class="text-danger"><?php echo $akis['cikis'] > 0 ? '₺' . number_format($akis['cikis'], 2, ',', '.') : '-'; ?></td>
                                        <td class="fw-bold">₺<?php echo number_format($akis['bakiye'], 2, ',', '.'); ?></td>
                                        <td class="text-muted small"><?php echo htmlspecialchars($akis['not']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
    <script>
        // Günlük nakit akışı grafiği
        const ctx = document.getElementById('nakitAkisiGrafik').getContext('2d');
        const grafikVerileri = <?php echo json_encode($grafik_verileri); ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: grafikVerileri.map(item => item.tarih),
                datasets: [{
                    label: 'Giriş (₺)',
                    data: grafikVerileri.map(item => item.giris),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Çıkış (₺)',
                    data: grafikVerileri.map(item => item.cikis),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₺' + value.toLocaleString('tr-TR');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                const label = context.dataset.label;
                                return label + ': ₺' + value.toLocaleString('tr-TR');
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    </script>
</body>
</html>
