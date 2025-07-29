<?php
session_start();

$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';

// Statik gider verileri
$giderler = [
    ['id' => 1, 'kayit_ismi' => 'Ofis Kirası - Temmuz 2024', 'kategori' => 'Kira', 'tutar' => 5000.00, 'tarih' => '2024-07-01', 'aciklama' => 'Temmuz ayı ofis kira ödemesi', 'durum' => 'Ödendi'],
    ['id' => 2, 'kayit_ismi' => 'Elektrik Faturası - Temmuz', 'kategori' => 'Fatura', 'tutar' => 1200.00, 'tarih' => '2024-07-05', 'aciklama' => 'TEDAŞ elektrik faturası', 'durum' => 'Ödendi'],
    ['id' => 3, 'kayit_ismi' => 'İnternet Faturası - Temmuz', 'kategori' => 'Fatura', 'tutar' => 450.00, 'tarih' => '2024-07-08', 'aciklama' => 'TurkNet internet faturası', 'durum' => 'Ödendi'],
    ['id' => 4, 'kayit_ismi' => 'Personel Maaşı - Ahmet', 'kategori' => 'Maaş', 'tutar' => 8000.00, 'tarih' => '2024-07-10', 'aciklama' => 'Ahmet Yılmaz Temmuz maaşı', 'durum' => 'Ödendi'],
    ['id' => 5, 'kayit_ismi' => 'Tedarikçi Ödemesi - XYZ', 'kategori' => 'Tedarikçi', 'tutar' => 15000.00, 'tarih' => '2024-07-12', 'aciklama' => 'XYZ Tedarik malzeme ödemesi', 'durum' => 'Ödendi'],
    ['id' => 6, 'kayit_ismi' => 'Personel Maaşı - Fatma', 'kategori' => 'Maaş', 'tutar' => 7500.00, 'tarih' => '2024-07-15', 'aciklama' => 'Fatma Demir Temmuz maaşı', 'durum' => 'Ödendi'],
    ['id' => 7, 'kayit_ismi' => 'Su Faturası - Temmuz', 'kategori' => 'Fatura', 'tutar' => 350.00, 'tarih' => '2024-07-18', 'aciklama' => 'İSKİ su faturası', 'durum' => 'Bekliyor'],
    ['id' => 8, 'kayit_ismi' => 'Ofis Malzemeleri', 'kategori' => 'Malzeme', 'tutar' => 850.00, 'tarih' => '2024-07-20', 'aciklama' => 'Kırtasiye ve ofis malzemeleri', 'durum' => 'Ödendi'],
    ['id' => 9, 'kayit_ismi' => 'Sigorta Ödemesi', 'kategori' => 'Sigorta', 'tutar' => 2800.00, 'tarih' => '2024-07-22', 'aciklama' => 'İşyeri sigortası ödemesi', 'durum' => 'Ödendi'],
    ['id' => 10, 'kayit_ismi' => 'Vergi Ödemesi - KDV', 'kategori' => 'Vergi', 'tutar' => 8500.00, 'tarih' => '2024-07-25', 'aciklama' => 'KDV ödemesi - Temmuz', 'durum' => 'Bekliyor'],
    ['id' => 11, 'kayit_ismi' => 'Yazılım Lisansı', 'kategori' => 'Lisans', 'tutar' => 12000.00, 'tarih' => '2024-07-28', 'aciklama' => 'Yıllık yazılım lisans ödemesi', 'durum' => 'Ödendi'],
    ['id' => 12, 'kayit_ismi' => 'Temizlik Hizmeti', 'kategori' => 'Hizmet', 'tutar' => 600.00, 'tarih' => '2024-07-30', 'aciklama' => 'Aylık temizlik hizmeti', 'durum' => 'Bekliyor']
];

// CRUD işlemleri
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            $giderler[] = [
                'id' => max(array_column($giderler, 'id')) + 1,
                'kayit_ismi' => $_POST['kayit_ismi'],
                'kategori' => $_POST['kategori'],
                'tutar' => floatval($_POST['tutar']),
                'tarih' => $_POST['tarih'],
                'aciklama' => $_POST['aciklama'],
                'durum' => $_POST['durum']
            ];
            break;
        case 'delete':
            $giderler = array_filter($giderler, fn($g) => $g['id'] != $_POST['id']);
            break;
        case 'update':
            foreach ($giderler as &$gider) {
                if ($gider['id'] == $_POST['id']) {
                    $gider = array_merge($gider, [
                        'kayit_ismi' => $_POST['kayit_ismi'],
                        'kategori' => $_POST['kategori'],
                        'tutar' => floatval($_POST['tutar']),
                        'tarih' => $_POST['tarih'],
                        'aciklama' => $_POST['aciklama'],
                        'durum' => $_POST['durum']
                    ]);
                    break;
                }
            }
            break;
    }
}

// Filtreleme fonksiyonu
function filterGiderler($giderler, $arama, $kategori, $durum, $tarih_baslangic, $tarih_bitis) {
    $filtered = $giderler;
    
    if (!empty($arama)) {
        $filtered = array_filter($filtered, fn($g) => 
            stripos($g['kayit_ismi'], $arama) !== false || stripos($g['aciklama'], $arama) !== false
        );
    }
    if (!empty($kategori)) {
        $filtered = array_filter($filtered, fn($g) => $g['kategori'] == $kategori);
    }
    if (!empty($durum)) {
        $filtered = array_filter($filtered, fn($g) => $g['durum'] == $durum);
    }
    if (!empty($tarih_baslangic) && !empty($tarih_bitis)) {
        $filtered = array_filter($filtered, fn($g) => 
            strtotime($g['tarih']) >= strtotime($tarih_baslangic) && 
            strtotime($g['tarih']) <= strtotime($tarih_bitis)
        );
    }
    return $filtered;
}

// Export işlemleri
if (isset($_GET['export'])) {
    $export_giderler = filterGiderler($giderler, $_GET['arama'] ?? '', $_GET['kategori'] ?? '', $_GET['durum'] ?? '', $_GET['tarih_baslangic'] ?? '', $_GET['tarih_bitis'] ?? '');
    
    if ($_GET['export'] == 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="giderler_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['Kayıt İsmi', 'Kategori', 'Tutar (₺)', 'Tarih', 'Açıklama', 'Durum']);
        foreach ($export_giderler as $gider) {
            fputcsv($output, [
                $gider['kayit_ismi'], $gider['kategori'], number_format($gider['tutar'], 2, ',', '.'),
                date('d.m.Y', strtotime($gider['tarih'])), $gider['aciklama'], $gider['durum']
            ]);
        }
        fclose($output);
        exit();
    }
    
    if ($_GET['export'] == 'pdf') {
        exportToPDF($export_giderler, $_GET['arama'] ?? '', $_GET['kategori'] ?? '', $_GET['durum'] ?? '', $_GET['tarih_baslangic'] ?? '', $_GET['tarih_bitis'] ?? '');
        exit();
    }
}

// Filtreleme parametreleri ve uygulama
$arama = $_GET['arama'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$durum = $_GET['durum'] ?? '';
$tarih_baslangic = $_GET['tarih_baslangic'] ?? '';
$tarih_bitis = $_GET['tarih_bitis'] ?? '';

$filtered_giderler = filterGiderler($giderler, $arama, $kategori, $durum, $tarih_baslangic, $tarih_bitis);

// Toplam hesaplamalar
$toplam_gider = array_sum(array_column($filtered_giderler, 'tutar'));
$odenen_gider = array_sum(array_column(array_filter($filtered_giderler, fn($g) => $g['durum'] == 'Ödendi'), 'tutar'));
$bekleyen_gider = $toplam_gider - $odenen_gider;

$kategoriler = array_unique(array_column($giderler, 'kategori'));

// PDF Export fonksiyonu
function exportToPDF($giderler, $arama, $kategori, $durum, $tarih_baslangic, $tarih_bitis) {
    $companyName = 'Atia Yazılım';
    $reportDate = date('d.m.Y H:i');
    
    $toplam_gider = array_sum(array_column($giderler, 'tutar'));
    $odenen_gider = array_sum(array_column(array_filter($giderler, fn($g) => $g['durum'] == 'Ödendi'), 'tutar'));
    $bekleyen_gider = $toplam_gider - $odenen_gider;
    
    $filter_info = [];
    if (!empty($arama)) $filter_info[] = "Arama: $arama";
    if (!empty($kategori)) $filter_info[] = "Kategori: $kategori";
    if (!empty($durum)) $filter_info[] = "Durum: $durum";
    if (!empty($tarih_baslangic) && !empty($tarih_bitis)) {
        $filter_info[] = "Tarih: " . date('d.m.Y', strtotime($tarih_baslangic)) . " - " . date('d.m.Y', strtotime($tarih_bitis));
    }
    
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gider Listesi Raporu</title>
        <style>
            @media print { body { margin: 0; padding: 20px; } .no-print { display: none !important; } }
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background: white; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #3498db; padding-bottom: 20px; margin-bottom: 30px; }
            .company-name { font-size: 24px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
            .report-title { font-size: 18px; color: #3498db; margin-bottom: 10px; }
            .report-date { font-size: 14px; color: #7f8c8d; }
            .summary-section { display: flex; justify-content: space-between; margin-bottom: 30px; flex-wrap: wrap; }
            .summary-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; text-align: center; min-width: 150px; margin: 5px; }
            .summary-title { font-size: 12px; color: #6c757d; margin-bottom: 5px; }
            .summary-amount { font-size: 18px; font-weight: bold; color: #2c3e50; }
            .filter-info { background: #e9ecef; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
            .filter-info strong { color: #495057; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; font-size: 14px; }
            th { background-color: #f8f9fa; font-weight: bold; color: #495057; }
            .status-paid { background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
            .status-pending { background-color: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
            .category-badge { background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #dee2e6; padding-top: 20px; }
            .print-buttons { position: fixed; top: 20px; right: 20px; z-index: 1000; }
            .print-btn { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px; font-size: 14px; }
            .print-btn:hover { background: #2980b9; }
        </style>
    </head>
    <body>
        <div class="print-buttons no-print">
            <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Yazdır</button>
            <button class="print-btn" onclick="window.close()"><i class="fas fa-times"></i> Kapat</button>
        </div>
        
        <div class="header">
            <div class="company-name"><?php echo $companyName; ?></div>
            <div class="report-title">GİDER LİSTESİ RAPORU</div>
            <div class="report-date">Rapor Tarihi: <?php echo $reportDate; ?></div>
        </div>
        
        <div class="summary-section">
            <div class="summary-card">
                <div class="summary-title">Toplam Gider</div>
                <div class="summary-amount">₺<?php echo number_format($toplam_gider, 2, ',', '.'); ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-title">Ödenen Gider</div>
                <div class="summary-amount">₺<?php echo number_format($odenen_gider, 2, ',', '.'); ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-title">Bekleyen Gider</div>
                <div class="summary-amount">₺<?php echo number_format($bekleyen_gider, 2, ',', '.'); ?></div>
            </div>
        </div>
        
        <?php if (!empty($filter_info)): ?>
        <div class="filter-info">
            <strong>Filtre Bilgileri:</strong> <?php echo implode(' | ', $filter_info); ?>
        </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr><th>Kayıt İsmi</th><th>Kategori</th><th>Tutar (₺)</th><th>Tarih</th><th>Durum</th></tr>
            </thead>
            <tbody>
                <?php if (empty($giderler)): ?>
                    <tr><td colspan="5" style="text-align: center; color: #6c757d;">Gider bulunamadı</td></tr>
                <?php else: ?>
                    <?php foreach ($giderler as $gider): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($gider['kayit_ismi']); ?></strong><br>
                                <small style="color: #6c757d;"><?php echo htmlspecialchars($gider['aciklama']); ?></small>
                            </td>
                            <td><span class="category-badge"><?php echo htmlspecialchars($gider['kategori']); ?></span></td>
                            <td style="font-weight: bold;">₺<?php echo number_format($gider['tutar'], 2, ',', '.'); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($gider['tarih'])); ?></td>
                            <td>
                                <span class="<?php echo $gider['durum'] == 'Ödendi' ? 'status-paid' : 'status-pending'; ?>">
                                    <?php echo $gider['durum']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <p>Bu rapor <?php echo $companyName; ?> tarafından <?php echo $reportDate; ?> tarihinde oluşturulmuştur.</p>
            <p>Toplam <?php echo count($giderler); ?> gider kaydı listelenmiştir.</p>
        </div>
    </body>
    </html>
    <?php
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gider Listesi - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/giderler/gider_listesi.css" rel="stylesheet">
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Gider Listesi</h1>
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
                            <div class="card-title">Toplam Gider</div>
                            <div class="card-amount" style="color: #b07a4a;">₺<?php echo number_format($toplam_gider, 2, ',', '.'); ?></div>
                            <div class="card-status">Toplam Gider Tutarı</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Ödenen Gider</div>
                            <div class="card-amount" style="color: var(--secondary-color);">₺<?php echo number_format($odenen_gider, 2, ',', '.'); ?></div>
                            <div class="card-status">Ödenmiş Giderler</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Bekleyen Gider</div>
                            <div class="card-amount" style="color: var(--accent-color);">₺<?php echo number_format($bekleyen_gider, 2, ',', '.'); ?></div>
                            <div class="card-status">Ödenmemiş Giderler</div>
                        </div>
                    </div>
                </div>
                
                <!-- Aksiyon butonları -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#giderEkleModal">
                        <i class="fas fa-plus"></i> Yeni Gider Ekle
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Dışarı Aktar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?export=csv&arama=<?php echo urlencode($arama); ?>&kategori=<?php echo urlencode($kategori); ?>&durum=<?php echo urlencode($durum); ?>&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>">
                                <i class="fas fa-file-csv"></i> CSV İndir
                            </a></li>
                            <li><a class="dropdown-item" href="?export=pdf&arama=<?php echo urlencode($arama); ?>&kategori=<?php echo urlencode($kategori); ?>&durum=<?php echo urlencode($durum); ?>&tarih_baslangic=<?php echo urlencode($tarih_baslangic); ?>&tarih_bitis=<?php echo urlencode($tarih_bitis); ?>" target="_blank">
                                <i class="fas fa-file-pdf"></i> PDF Görüntüle
                            </a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Filtre bölümü -->
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="arama" value="<?php echo htmlspecialchars($arama); ?>" class="form-control form-control-sm" placeholder="Ara...">
                            </div>
                            <div class="col-md-2">
                                <select name="kategori" class="form-select form-select-sm">
                                    <option value="">Tüm Kategoriler</option>
                                    <?php foreach ($kategoriler as $kat): ?>
                                        <option value="<?php echo $kat; ?>" <?php echo $kategori == $kat ? 'selected' : ''; ?>><?php echo $kat; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="durum" class="form-select form-select-sm">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="Ödendi" <?php echo $durum == 'Ödendi' ? 'selected' : ''; ?>>Ödendi</option>
                                    <option value="Bekliyor" <?php echo $durum == 'Bekliyor' ? 'selected' : ''; ?>>Bekliyor</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="tarih_baslangic" value="<?php echo $tarih_baslangic; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="tarih_bitis" value="<?php echo $tarih_bitis; ?>" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Gider tablosu -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>KAYIT İSMİ</th>
                                        <th>KATEGORİ</th>
                                        <th>TUTAR (₺)</th>
                                        <th>TARİH</th>
                                        <th>DURUM</th>
                                        <th>İŞLEMLER</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($filtered_giderler)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Gider bulunamadı</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($filtered_giderler as $gider): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($gider['kayit_ismi']); ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($gider['aciklama']); ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($gider['kategori']); ?></span>
                                                </td>
                                                <td class="fw-bold">₺<?php echo number_format($gider['tutar'], 2, ',', '.'); ?></td>
                                                <td><?php echo date('d.m.Y', strtotime($gider['tarih'])); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $gider['durum'] == 'Ödendi' ? 'bg-success' : 'bg-warning'; ?>">
                                                        <?php echo $gider['durum']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary btn-sm" onclick="editGider(<?php echo $gider['id']; ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteGider(<?php echo $gider['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
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
        </div>
    </div>
    
    <!-- Gider Ekleme Modal -->
    <div class="modal fade" id="giderEkleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Gider Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kayıt İsmi</label>
                            <input type="text" name="kayit_ismi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <?php foreach ($kategoriler as $kat): ?>
                                    <option value="<?php echo $kat; ?>"><?php echo $kat; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tutar (₺)</label>
                            <input type="number" name="tutar" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tarih</label>
                            <input type="date" name="tarih" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durum</label>
                            <select name="durum" class="form-select" required>
                                <option value="Bekliyor">Bekliyor</option>
                                <option value="Ödendi">Ödendi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Gider Düzenleme Modal -->
    <div class="modal fade" id="giderDuzenleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gider Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="giderDuzenleForm">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kayıt İsmi</label>
                            <input type="text" name="kayit_ismi" id="edit_kayit_ismi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" id="edit_kategori" class="form-select" required>
                                <?php foreach ($kategoriler as $kat): ?>
                                    <option value="<?php echo $kat; ?>"><?php echo $kat; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tutar (₺)</label>
                            <input type="number" name="tutar" id="edit_tutar" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tarih</label>
                            <input type="date" name="tarih" id="edit_tarih" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" id="edit_aciklama" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durum</label>
                            <select name="durum" id="edit_durum" class="form-select" required>
                                <option value="Bekliyor">Bekliyor</option>
                                <option value="Ödendi">Ödendi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Silme Onay Modal -->
    <div class="modal fade" id="silmeOnayModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gider Sil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bu gideri silmek istediğinizden emin misiniz?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-danger">Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
    <script>
        function editGider(id) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_kayit_ismi').value = 'Gider ' + id;
            document.getElementById('edit_kategori').value = 'Kira';
            document.getElementById('edit_tutar').value = '1000';
            document.getElementById('edit_tarih').value = '2024-07-01';
            document.getElementById('edit_aciklama').value = 'Açıklama';
            document.getElementById('edit_durum').value = 'Bekliyor';
            new bootstrap.Modal(document.getElementById('giderDuzenleModal')).show();
        }
        
        function deleteGider(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('silmeOnayModal')).show();
        }
    </script>
</body>
</html>
