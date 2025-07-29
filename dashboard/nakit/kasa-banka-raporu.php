<?php
session_start();

// Statik kullanıcı bilgileri
$userName = 'Kullanıcı';
$companyName = 'Şirket';

// Statik nakit akış verileri
$nakit_akislari = [
    // Temmuz 2024
    ['id' => 1, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-07-15', 'musteri_tedarikci' => 'ABC Şirketi', 'kayit_ismi' => 'Fatura Tahsilatı - INV001', 'meblag' => 25000.00, 'tip' => 'giris'],
    ['id' => 2, 'islem_turu' => 'Tedarikçi Ödemesi', 'islem_tarihi' => '2024-07-16', 'musteri_tedarikci' => 'XYZ Tedarik', 'kayit_ismi' => 'Malzeme Ödemesi - PO001', 'meblag' => -15000.00, 'tip' => 'cikis'],
    ['id' => 3, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-07-17', 'musteri_tedarikci' => 'DEF Ltd.', 'kayit_ismi' => 'Hizmet Ödemesi - INV002', 'meblag' => 18000.00, 'tip' => 'giris'],
    ['id' => 4, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-07-18', 'musteri_tedarikci' => 'Ahmet Yılmaz', 'kayit_ismi' => 'Maaş Ödemesi - Temmuz', 'meblag' => -8000.00, 'tip' => 'cikis'],
    ['id' => 5, 'islem_turu' => 'Kira Ödemesi', 'islem_tarihi' => '2024-07-19', 'musteri_tedarikci' => 'Emlak Sahibi', 'kayit_ismi' => 'Ofis Kira Ödemesi', 'meblag' => -5000.00, 'tip' => 'cikis'],
    ['id' => 6, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-07-20', 'musteri_tedarikci' => 'GHI Şirketi', 'kayit_ismi' => 'Proje Ödemesi - PRJ001', 'meblag' => 35000.00, 'tip' => 'giris'],
    ['id' => 7, 'islem_turu' => 'Elektrik Faturası', 'islem_tarihi' => '2024-07-22', 'musteri_tedarikci' => 'TEDAŞ', 'kayit_ismi' => 'Elektrik Faturası - Temmuz', 'meblag' => -1200.00, 'tip' => 'cikis'],
    ['id' => 8, 'islem_turu' => 'İnternet Faturası', 'islem_tarihi' => '2024-07-23', 'musteri_tedarikci' => 'TurkNet', 'kayit_ismi' => 'İnternet Faturası - Temmuz', 'meblag' => -450.00, 'tip' => 'cikis'],
    ['id' => 9, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-07-25', 'musteri_tedarikci' => 'JKL Teknoloji', 'kayit_ismi' => 'Yazılım Lisans Ödemesi', 'meblag' => 42000.00, 'tip' => 'giris'],
    ['id' => 10, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-07-26', 'musteri_tedarikci' => 'Fatma Demir', 'kayit_ismi' => 'Maaş Ödemesi - Temmuz', 'meblag' => -7500.00, 'tip' => 'cikis'],
    
    // Haziran 2024
    ['id' => 11, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-06-05', 'musteri_tedarikci' => 'MNO Holding', 'kayit_ismi' => 'Danışmanlık Hizmeti', 'meblag' => 55000.00, 'tip' => 'giris'],
    ['id' => 12, 'islem_turu' => 'Tedarikçi Ödemesi', 'islem_tarihi' => '2024-06-10', 'musteri_tedarikci' => 'PQR Malzeme', 'kayit_ismi' => 'Ofis Malzemeleri', 'meblag' => -3200.00, 'tip' => 'cikis'],
    ['id' => 13, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-06-15', 'musteri_tedarikci' => 'RST Şirketi', 'kayit_ismi' => 'Web Sitesi Geliştirme', 'meblag' => 28000.00, 'tip' => 'giris'],
    ['id' => 14, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-06-18', 'musteri_tedarikci' => 'Mehmet Kaya', 'kayit_ismi' => 'Maaş Ödemesi - Haziran', 'meblag' => -6500.00, 'tip' => 'cikis'],
    ['id' => 15, 'islem_turu' => 'Vergi Ödemesi', 'islem_tarihi' => '2024-06-20', 'musteri_tedarikci' => 'Gelir İdaresi', 'kayit_ismi' => 'KDV Ödemesi - Haziran', 'meblag' => -8500.00, 'tip' => 'cikis'],
    ['id' => 16, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-06-25', 'musteri_tedarikci' => 'UVW Ltd.', 'kayit_ismi' => 'Mobil Uygulama Geliştirme', 'meblag' => 38000.00, 'tip' => 'giris'],
    ['id' => 17, 'islem_turu' => 'Sigorta Ödemesi', 'islem_tarihi' => '2024-06-28', 'musteri_tedarikci' => 'Axa Sigorta', 'kayit_ismi' => 'İşyeri Sigortası', 'meblag' => -2800.00, 'tip' => 'cikis'],
    
    // Mayıs 2024
    ['id' => 18, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-05-03', 'musteri_tedarikci' => 'XYZ Teknoloji', 'kayit_ismi' => 'E-Ticaret Sistemi', 'meblag' => 45000.00, 'tip' => 'giris'],
    ['id' => 19, 'islem_turu' => 'Tedarikçi Ödemesi', 'islem_tarihi' => '2024-05-08', 'musteri_tedarikci' => 'ABC Yazılım', 'kayit_ismi' => 'Yazılım Lisansları', 'meblag' => -12000.00, 'tip' => 'cikis'],
    ['id' => 20, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-05-12', 'musteri_tedarikci' => 'DEF Danışmanlık', 'kayit_ismi' => 'Proje Yönetimi Hizmeti', 'meblag' => 22000.00, 'tip' => 'giris'],
    ['id' => 21, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-05-15', 'musteri_tedarikci' => 'Ayşe Özkan', 'kayit_ismi' => 'Maaş Ödemesi - Mayıs', 'meblag' => -7200.00, 'tip' => 'cikis'],
    ['id' => 22, 'islem_turu' => 'Su Faturası', 'islem_tarihi' => '2024-05-18', 'musteri_tedarikci' => 'İSKİ', 'kayit_ismi' => 'Su Faturası - Mayıs', 'meblag' => -350.00, 'tip' => 'cikis'],
    ['id' => 23, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-05-22', 'musteri_tedarikci' => 'GHI Holding', 'kayit_ismi' => 'CRM Sistemi Geliştirme', 'meblag' => 52000.00, 'tip' => 'giris'],
    ['id' => 24, 'islem_turu' => 'Vergi Ödemesi', 'islem_tarihi' => '2024-05-25', 'musteri_tedarikci' => 'Gelir İdaresi', 'kayit_ismi' => 'Gelir Vergisi - Mayıs', 'meblag' => -9500.00, 'tip' => 'cikis'],
    
    // Nisan 2024
    ['id' => 25, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-04-02', 'musteri_tedarikci' => 'JKL Şirketi', 'kayit_ismi' => 'ERP Sistemi Kurulumu', 'meblag' => 68000.00, 'tip' => 'giris'],
    ['id' => 26, 'islem_turu' => 'Tedarikçi Ödemesi', 'islem_tarihi' => '2024-04-05', 'musteri_tedarikci' => 'MNO Bilgisayar', 'kayit_ismi' => 'Donanım Alımı', 'meblag' => -18500.00, 'tip' => 'cikis'],
    ['id' => 27, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-04-10', 'musteri_tedarikci' => 'PQR Teknoloji', 'kayit_ismi' => 'Mobil Uygulama Geliştirme', 'meblag' => 32000.00, 'tip' => 'giris'],
    ['id' => 28, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-04-12', 'musteri_tedarikci' => 'Can Yıldız', 'kayit_ismi' => 'Maaş Ödemesi - Nisan', 'meblag' => -6800.00, 'tip' => 'cikis'],
    ['id' => 29, 'islem_turu' => 'Kira Ödemesi', 'islem_tarihi' => '2024-04-15', 'musteri_tedarikci' => 'Emlak Sahibi', 'kayit_ismi' => 'Ofis Kira Ödemesi - Nisan', 'meblag' => -5000.00, 'tip' => 'cikis'],
    ['id' => 30, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-04-20', 'musteri_tedarikci' => 'RST Danışmanlık', 'kayit_ismi' => 'İş Süreçleri Analizi', 'meblag' => 28000.00, 'tip' => 'giris'],
    ['id' => 31, 'islem_turu' => 'Elektrik Faturası', 'islem_tarihi' => '2024-04-22', 'musteri_tedarikci' => 'TEDAŞ', 'kayit_ismi' => 'Elektrik Faturası - Nisan', 'meblag' => -1100.00, 'tip' => 'cikis'],
    ['id' => 32, 'islem_turu' => 'Vergi Ödemesi', 'islem_tarihi' => '2024-04-25', 'musteri_tedarikci' => 'Gelir İdaresi', 'kayit_ismi' => 'KDV Ödemesi - Nisan', 'meblag' => -7800.00, 'tip' => 'cikis'],
    
    // Mart 2024
    ['id' => 33, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-03-05', 'musteri_tedarikci' => 'UVW Holding', 'kayit_ismi' => 'Dijital Dönüşüm Projesi', 'meblag' => 75000.00, 'tip' => 'giris'],
    ['id' => 34, 'islem_turu' => 'Tedarikçi Ödemesi', 'islem_tarihi' => '2024-03-08', 'musteri_tedarikci' => 'XYZ Yazılım', 'kayit_ismi' => 'Yazılım Lisansları', 'meblag' => -15000.00, 'tip' => 'cikis'],
    ['id' => 35, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-03-12', 'musteri_tedarikci' => 'ABC Teknoloji', 'kayit_ismi' => 'Web Sitesi Yenileme', 'meblag' => 18000.00, 'tip' => 'giris'],
    ['id' => 36, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-03-15', 'musteri_tedarikci' => 'Zeynep Kaya', 'kayit_ismi' => 'Maaş Ödemesi - Mart', 'meblag' => -7000.00, 'tip' => 'cikis'],
    ['id' => 37, 'islem_turu' => 'İnternet Faturası', 'islem_tarihi' => '2024-03-18', 'musteri_tedarikci' => 'TurkNet', 'kayit_ismi' => 'İnternet Faturası - Mart', 'meblag' => -450.00, 'tip' => 'cikis'],
    ['id' => 38, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-03-22', 'musteri_tedarikci' => 'DEF Şirketi', 'kayit_ismi' => 'E-Ticaret Entegrasyonu', 'meblag' => 25000.00, 'tip' => 'giris'],
    ['id' => 39, 'islem_turu' => 'Vergi Ödemesi', 'islem_tarihi' => '2024-03-25', 'musteri_tedarikci' => 'Gelir İdaresi', 'kayit_ismi' => 'Gelir Vergisi - Mart', 'meblag' => -8200.00, 'tip' => 'cikis'],
    
    // Şubat 2024
    ['id' => 40, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-02-03', 'musteri_tedarikci' => 'GHI Danışmanlık', 'kayit_ismi' => 'Stratejik Planlama', 'meblag' => 42000.00, 'tip' => 'giris'],
    ['id' => 41, 'islem_turu' => 'Tedarikçi Ödemesi', 'islem_tarihi' => '2024-02-06', 'musteri_tedarikci' => 'JKL Malzeme', 'kayit_ismi' => 'Ofis Mobilyaları', 'meblag' => -8500.00, 'tip' => 'cikis'],
    ['id' => 42, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-02-10', 'musteri_tedarikci' => 'MNO Teknoloji', 'kayit_ismi' => 'Mobil Uygulama Geliştirme', 'meblag' => 38000.00, 'tip' => 'giris'],
    ['id' => 43, 'islem_turu' => 'Çalışana Ödeme', 'islem_tarihi' => '2024-02-12', 'musteri_tedarikci' => 'Burak Demir', 'kayit_ismi' => 'Maaş Ödemesi - Şubat', 'meblag' => -6500.00, 'tip' => 'cikis'],
    ['id' => 44, 'islem_turu' => 'Kira Ödemesi', 'islem_tarihi' => '2024-02-15', 'musteri_tedarikci' => 'Emlak Sahibi', 'kayit_ismi' => 'Ofis Kira Ödemesi - Şubat', 'meblag' => -5000.00, 'tip' => 'cikis'],
    ['id' => 45, 'islem_turu' => 'Müşteri Ödemesi', 'islem_tarihi' => '2024-02-20', 'musteri_tedarikci' => 'PQR Holding', 'kayit_ismi' => 'Sistem Entegrasyonu', 'meblag' => 55000.00, 'tip' => 'giris'],
    ['id' => 46, 'islem_turu' => 'Elektrik Faturası', 'islem_tarihi' => '2024-02-22', 'musteri_tedarikci' => 'TEDAŞ', 'kayit_ismi' => 'Elektrik Faturası - Şubat', 'meblag' => -980.00, 'tip' => 'cikis'],
    ['id' => 47, 'islem_turu' => 'Vergi Ödemesi', 'islem_tarihi' => '2024-02-25', 'musteri_tedarikci' => 'Gelir İdaresi', 'kayit_ismi' => 'KDV Ödemesi - Şubat', 'meblag' => -7200.00, 'tip' => 'cikis']
];

// Filtreleme fonksiyonu
function filterNakitAkisi($akislar, $tip, $tarih_baslangic, $tarih_bitis) {
    $filtered = $akislar;
    
    // Tip filtresi
    if (!empty($tip)) {
        $filtered = array_filter($filtered, function($akis) use ($tip) {
            return $akis['tip'] == $tip;
        });
    }
    
    // Tarih filtresi
    if (!empty($tarih_baslangic) && !empty($tarih_bitis)) {
        $filtered = array_filter($filtered, function($akis) use ($tarih_baslangic, $tarih_bitis) {
            $akis_tarihi = strtotime($akis['islem_tarihi']);
            $baslangic = strtotime($tarih_baslangic);
            $bitis = strtotime($tarih_bitis);
            return $akis_tarihi >= $baslangic && $akis_tarihi <= $bitis;
        });
    }
    
    return $filtered;
}

// Export işlemleri
if (isset($_GET['export'])) {
    $tip = $_GET['tip'] ?? '';
    $tarih_baslangic = $_GET['tarih_baslangic'] ?? '';
    $tarih_bitis = $_GET['tarih_bitis'] ?? '';
    
    $export_akislar = filterNakitAkisi($nakit_akislari, $tip, $tarih_baslangic, $tarih_bitis);
    
    if ($_GET['export'] == 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="nakit_akisi_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['İşlem Türü', 'İşlem Tarihi', 'Müşteri/Tedarikçi', 'Kayıt İsmi', 'Meblağ (₺)']);
        foreach ($export_akislar as $akis) {
            fputcsv($output, [
                $akis['islem_turu'],
                date('d.m.Y', strtotime($akis['islem_tarihi'])),
                $akis['musteri_tedarikci'],
                $akis['kayit_ismi'],
                number_format($akis['meblag'], 2, ',', '.')
            ]);
        }
        fclose($output);
        exit();
    }
}

// Filtreleme parametreleri
$tip = $_GET['tip'] ?? '';
$tarih_baslangic = $_GET['tarih_baslangic'] ?? '2024-07-01'; // Temmuz 2024 başı
$tarih_bitis = $_GET['tarih_bitis'] ?? '2024-07-31'; // Temmuz 2024 sonu

// Filtreleme uygula
$filtered_akislar = filterNakitAkisi($nakit_akislari, $tip, $tarih_baslangic, $tarih_bitis);

// Toplam hesaplamalar
$toplam_giris = 0;
$toplam_cikis = 0;
$net_akis = 0;

foreach ($filtered_akislar as $akis) {
    if ($akis['meblag'] > 0) {
        $toplam_giris += $akis['meblag'];
    } else {
        $toplam_cikis += abs($akis['meblag']);
    }
}

$net_akis = $toplam_giris - $toplam_cikis;

// Grafik verileri (sabit 2024 Şubat-Temmuz, net nakit akışı)
$grafik_verileri = [];
$aylar = ['2024-02', '2024-03', '2024-04', '2024-05', '2024-06', '2024-07'];
foreach ($aylar as $ay) {
    $ay_net = 0;
    foreach ($nakit_akislari as $akis) {
        if (date('Y-m', strtotime($akis['islem_tarihi'])) == $ay) {
            $ay_net += $akis['meblag'];
        }
    }
    $grafik_verileri[] = [
        'ay' => date('M Y', strtotime($ay)),
        'net' => $ay_net
    ];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasa / Banka Raporu - Mutabık</title>
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
                <h1>Kasa / Banka Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        
        <div class="content-area">
            <div class="d-flex flex-column gap-4">
                <!-- Özet kutuları -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Toplam Nakit Girişi</div>
                            <div class="card-amount" style="color: var(--secondary-color);">₺<?php echo number_format($toplam_giris, 2, ',', '.'); ?></div>
                            <div class="card-status">Toplam Nakit Girişi</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Toplam Nakit Çıkışı</div>
                            <div class="card-amount" style="color: #b07a4a;">₺<?php echo number_format($toplam_cikis, 2, ',', '.'); ?></div>
                            <div class="card-status">Toplam Nakit Çıkışı</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Net Nakit Akışı</div>
                            <div class="card-amount" style="color: <?php echo $net_akis >= 0 ? 'var(--secondary-color)' : '#b07a4a'; ?>;">₺<?php echo number_format($net_akis, 2, ',', '.'); ?></div>
                            <div class="card-status">Net Nakit Akışı</div>
                        </div>
                    </div>
                </div>
                
                <!-- Filtre butonları ve tarih -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <a href="?tip=giris&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>"
                       class="btn btn-outline-primary btn-sm <?php echo $tip == 'giris' ? 'active' : ''; ?>">NAKİT GİRİŞİ</a>
                    <a href="?tip=cikis&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>"
                       class="btn btn-outline-secondary btn-sm <?php echo $tip == 'cikis' ? 'active' : ''; ?>">NAKİT ÇIKIŞI</a>
                    <a href="?tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>"
                       class="btn btn-dark btn-sm fw-bold <?php echo empty($tip) ? 'active' : ''; ?>">TÜMÜ</a>
                    <form method="GET" class="d-flex align-items-center gap-2 ms-auto">
                        <?php if (!empty($tip)): ?>
                            <input type="hidden" name="tip" value="<?php echo htmlspecialchars($tip); ?>">
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
                <div class="mb-4" style="background: #fff; border-radius: 8px; min-height: 260px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid var(--border-color); padding: 20px;">
                    <canvas id="nakitAkisiGrafik" width="400" height="200"></canvas>
                </div>
                
                <!-- Export butonları -->
                <div class="d-flex justify-content-end mb-3">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Dışarı Aktar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?export=csv&tip=<?php echo urlencode($tip); ?>&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>">
                                <i class="fas fa-file-csv"></i> CSV İndir
                            </a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Tablo başlığı -->
                <div class="mb-2">
                    <h4 style="font-size: 20px; color: var(--text-primary); font-weight: 600;">
                        <?php echo date('d F Y', strtotime($tarih_baslangic)); ?> - <?php echo date('d F Y', strtotime($tarih_bitis)); ?> Arası Yapılan Tahsilat ve Ödemeler
                    </h4>
                </div>
                
                <!-- Tablo -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>İŞLEM TÜRÜ</th>
                                <th>İŞLEM TARİHİ</th>
                                <th>MÜŞTERİ / TEDARİKÇİ / ÇALIŞAN</th>
                                <th>KAYIT İSMİ</th>
                                <th>MEBLAĞ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($filtered_akislar)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Seçilen tarih aralığında işlem bulunamadı</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($filtered_akislar as $akis): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($akis['islem_turu']); ?></td>
                                        <td><?php echo date('d F Y', strtotime($akis['islem_tarihi'])); ?></td>
                                        <td><?php echo htmlspecialchars($akis['musteri_tedarikci']); ?></td>
                                        <td><?php echo htmlspecialchars($akis['kayit_ismi']); ?></td>
                                        <td class="<?php echo $akis['meblag'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo ($akis['meblag'] > 0 ? '+' : '') . number_format($akis['meblag'], 2, ',', '.') . '₺'; ?>
                                        </td>
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
        // Grafik oluştur (Chart.js)
        const ctx = document.getElementById('nakitAkisiGrafik').getContext('2d');
        const grafikVerileri = <?php echo json_encode($grafik_verileri); ?>;
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: grafikVerileri.map(item => item.ay),
                datasets: [{
                    label: 'Net Nakit Akışı',
                    data: grafikVerileri.map(item => item.net),
                    backgroundColor: grafikVerileri.map(item => item.net >= 0 ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)'),
                    borderColor: grafikVerileri.map(item => item.net >= 0 ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)'),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return '₺' + value.toLocaleString('tr-TR');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Son 6 Ay Net Nakit Akışı' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                const sign = value >= 0 ? '+' : '';
                                return 'Net Nakit: ' + sign + '₺' + value.toLocaleString('tr-TR');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html> 
