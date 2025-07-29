<?php
session_start();

// Statik veriler
$cekler = [
    ['id' => 1, 'cek_no' => 'CHK001', 'banka_adi' => 'Ziraat Bankası', 'sube_adi' => 'Merkez Şube', 'tutar' => 5000.00, 'vade_tarihi' => '2024-07-15', 'durum' => 'Beklemede', 'duzenleyen' => 'Ahmet Yılmaz', 'aciklama' => 'Müşteri ödemesi'],
    ['id' => 2, 'cek_no' => 'CHK002', 'banka_adi' => 'İş Bankası', 'sube_adi' => 'Kadıköy Şube', 'tutar' => 7500.00, 'vade_tarihi' => '2024-07-20', 'durum' => 'Tahsil Edildi', 'duzenleyen' => 'Fatma Demir', 'aciklama' => 'Fatura tahsilatı'],
    ['id' => 3, 'cek_no' => 'CHK003', 'banka_adi' => 'Garanti BBVA', 'sube_adi' => 'Beşiktaş Şube', 'tutar' => 12000.00, 'vade_tarihi' => '2024-08-01', 'durum' => 'Beklemede', 'duzenleyen' => 'Mehmet Kaya', 'aciklama' => 'Proje ödemesi'],
    ['id' => 4, 'cek_no' => 'CHK004', 'banka_adi' => 'Yapı Kredi', 'sube_adi' => 'Şişli Şube', 'tutar' => 3000.00, 'vade_tarihi' => '2024-06-30', 'durum' => 'İptal', 'duzenleyen' => 'Ayşe Özkan', 'aciklama' => 'İptal edildi']
];

// Filtreleme fonksiyonu
function filterCekler($cekler, $search, $filter) {
    $filtered = $cekler;
    if (!empty($search)) {
        $filtered = array_filter($filtered, function($cek) use ($search) {
            return stripos($cek['cek_no'], $search) !== false || stripos($cek['banka_adi'], $search) !== false || stripos($cek['duzenleyen'], $search) !== false;
        });
    }
    if (!empty($filter)) {
        $filtered = array_filter($filtered, function($cek) use ($filter) {
            return $cek['durum'] == $filter;
        });
    }
    return $filtered;
}

// Export işlemleri
if (isset($_GET['export'])) {
    $export_cekler = filterCekler($cekler, $_GET['search'] ?? '', $_GET['filter'] ?? '');
    if ($_GET['export'] == 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="cekler_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['Çek No', 'Banka Adı', 'Şube Adı', 'Tutar (₺)', 'Vade Tarihi', 'Durum', 'Düzenleyen', 'Açıklama']);
        foreach ($export_cekler as $cek) {
            fputcsv($output, [$cek['cek_no'], $cek['banka_adi'], $cek['sube_adi'], number_format($cek['tutar'], 2, ',', '.'), date('d.m.Y', strtotime($cek['vade_tarihi'])), $cek['durum'], $cek['duzenleyen'], $cek['aciklama']]);
        }
        fclose($output);
        exit();
    } elseif ($_GET['export'] == 'pdf') {
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Çekler Raporu</title><style>body{font-family:Arial,sans-serif;margin:20px}.header{text-align:center;margin-bottom:30px;border-bottom:2px solid #333;padding-bottom:10px}.header h1{color:#333;margin:0}.header p{color:#666;margin:5px 0}table{width:100%;border-collapse:collapse;margin-top:20px}th,td{border:1px solid #ddd;padding:8px;text-align:left;font-size:12px}th{background-color:#f2f2f2;font-weight:bold}.summary{margin-top:20px;padding:10px;background-color:#f9f9f9;border-radius:5px}.status-beklemede{color:#856404;background-color:#fff3cd;padding:2px 6px;border-radius:3px}.status-tahsil{color:#155724;background-color:#d4edda;padding:2px 6px;border-radius:3px}.status-iptal{color:#721c24;background-color:#f8d7da;padding:2px 6px;border-radius:3px}@media print{body{margin:0}.no-print{display:none}}</style></head><body><div class="header"><h1>Çekler Raporu</h1><p>Şirket: Şirket</p><p>Tarih: ' . date('d.m.Y H:i') . '</p><p>Toplam Kayıt: ' . count($export_cekler) . '</p></div><table><thead><tr><th>Çek No</th><th>Banka</th><th>Şube</th><th>Tutar (₺)</th><th>Vade Tarihi</th><th>Durum</th><th>Düzenleyen</th><th>Açıklama</th></tr></thead><tbody>';
        $toplamBeklemede = 0;
        $toplamTahsilEdilecek = 0;
        foreach ($export_cekler as $cek) {
            $statusClass = '';
            if ($cek['durum'] == 'Beklemede') {
                $statusClass = 'status-beklemede';
                $toplamBeklemede += $cek['tutar'];
                $toplamTahsilEdilecek += $cek['tutar'];
            } elseif ($cek['durum'] == 'Tahsil Edildi') {
                $statusClass = 'status-tahsil';
                $toplamTahsilEdilecek += $cek['tutar'];
            } elseif ($cek['durum'] == 'İptal') {
                $statusClass = 'status-iptal';
            }
            $html .= '<tr><td>' . htmlspecialchars($cek['cek_no']) . '</td><td>' . htmlspecialchars($cek['banka_adi']) . '</td><td>' . htmlspecialchars($cek['sube_adi']) . '</td><td>₺' . number_format($cek['tutar'], 2, ',', '.') . '</td><td>' . date('d.m.Y', strtotime($cek['vade_tarihi'])) . '</td><td><span class="' . $statusClass . '">' . $cek['durum'] . '</span></td><td>' . htmlspecialchars($cek['duzenleyen']) . '</td><td>' . htmlspecialchars($cek['aciklama']) . '</td></tr>';
        }
        $html .= '</tbody></table><div class="summary"><h3>Özet Bilgiler</h3><p><strong>Beklemede:</strong> ₺' . number_format($toplamBeklemede, 2, ',', '.') . '</p><p><strong>Tahsil Edilecek:</strong> ₺' . number_format($toplamTahsilEdilecek, 2, ',', '.') . '</p></div><div class="no-print" style="margin-top:30px;text-align:center"><button onclick="window.print()" style="padding:10px 20px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer"><i class="fas fa-print"></i> Yazdır / PDF Olarak Kaydet</button><button onclick="window.close()" style="padding:10px 20px;background:#6c757d;color:white;border:none;border-radius:5px;cursor:pointer;margin-left:10px"><i class="fas fa-times"></i> Kapat</button></div></body></html>';
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit();
    }
}

// CRUD İşlemleri
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $cekler[] = [
            'id' => count($cekler) + 1,
            'cek_no' => trim($_POST['cek_no']),
            'banka_adi' => trim($_POST['banka_adi']),
            'sube_adi' => trim($_POST['sube_adi']),
            'tutar' => floatval($_POST['tutar']),
            'vade_tarihi' => $_POST['vade_tarihi'],
            'durum' => $_POST['durum'],
            'duzenleyen' => trim($_POST['duzenleyen']),
            'aciklama' => trim($_POST['aciklama'])
        ];
        $message = "Çek başarıyla eklendi!";
        $messageType = "success";
    } elseif ($_POST['action'] == 'update') {
        foreach ($cekler as &$cek) {
            if ($cek['id'] == intval($_POST['id'])) {
                $cek['cek_no'] = trim($_POST['cek_no']);
                $cek['banka_adi'] = trim($_POST['banka_adi']);
                $cek['sube_adi'] = trim($_POST['sube_adi']);
                $cek['tutar'] = floatval($_POST['tutar']);
                $cek['vade_tarihi'] = $_POST['vade_tarihi'];
                $cek['durum'] = $_POST['durum'];
                $cek['duzenleyen'] = trim($_POST['duzenleyen']);
                $cek['aciklama'] = trim($_POST['aciklama']);
                break;
            }
        }
        $message = "Çek başarıyla güncellendi!";
        $messageType = "success";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $cekler = array_filter($cekler, function($cek) {
        return $cek['id'] != intval($_GET['id']);
    });
    $message = "Çek başarıyla silindi!";
    $messageType = "success";
}

// Filtreleme
$cekler = filterCekler($cekler, $_GET['search'] ?? '', $_GET['filter'] ?? '');

// Toplam hesaplamalar
$toplamBeklemede = 0;
$toplamTahsilEdilecek = 0;
foreach ($cekler as $cek) {
    if ($cek['durum'] == 'Beklemede') {
        $toplamBeklemede += $cek['tutar'];
        $toplamTahsilEdilecek += $cek['tutar'];
    } elseif ($cek['durum'] == 'Tahsil Edildi') {
        $toplamTahsilEdilecek += $cek['tutar'];
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Çekler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/finans/cekler.css" rel="stylesheet">
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Çekler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="content">
            <div class="mb-3 d-flex justify-content-between align-items-center gap-3">
                <form method="GET" class="d-flex gap-2 flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Çek no, banka, düzenleyen ara..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <select name="filter" class="form-select" style="width: auto;">
                        <option value="">Tüm Durumlar</option>
                        <option value="Beklemede" <?php echo ($_GET['filter'] ?? '') == 'Beklemede' ? 'selected' : ''; ?>>Beklemede</option>
                        <option value="Tahsil Edildi" <?php echo ($_GET['filter'] ?? '') == 'Tahsil Edildi' ? 'selected' : ''; ?>>Tahsil Edildi</option>
                        <option value="İptal" <?php echo ($_GET['filter'] ?? '') == 'İptal' ? 'selected' : ''; ?>>İptal</option>
                    </select>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Filtrele</button>
                    <a href="?" class="btn btn-outline-primary btn-sm">Temizle</a>
                </form>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCheckModal">
                    <i class="fas fa-plus"></i> Yeni Çek Ekle
                </button>
            </div>

            <?php if (empty($cekler)): ?>
                <div class="empty-message">
                    <p>Kayıtlı bir çekiniz yok</p>
                    <p><small>Yeni Çek Ekle butonunu kullanarak oluşturacağınız çekleriniz burada listelenecek</small></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Çek No</th>
                                <th>Banka</th>
                                <th>Şube</th>
                                <th>Tutar (₺)</th>
                                <th>Vade Tarihi</th>
                                <th>Durum</th>
                                <th>Düzenleyen</th>
                                <th>Açıklama</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cekler as $cek): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cek['cek_no']); ?></td>
                                    <td><?php echo htmlspecialchars($cek['banka_adi']); ?></td>
                                    <td><?php echo htmlspecialchars($cek['sube_adi']); ?></td>
                                    <td>₺<?php echo number_format($cek['tutar'], 2, ',', '.'); ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($cek['vade_tarihi'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $cek['durum'] == 'Beklemede' ? 'warning' : ($cek['durum'] == 'Tahsil Edildi' ? 'success' : 'danger'); ?>">
                                            <?php echo $cek['durum']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($cek['duzenleyen']); ?></td>
                                    <td><?php echo htmlspecialchars($cek['aciklama']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editCheck(<?php echo $cek['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?action=delete&id=<?php echo $cek['id']; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Bu çeki silmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="export-buttons">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Dışarı Aktar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?export=csv&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>">
                                    <i class="fas fa-file-csv"></i> CSV İndir
                                </a></li>
                                <li><a class="dropdown-item" href="?export=pdf&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>" target="_blank">
                                    <i class="fas fa-file-pdf"></i> PDF Görüntüle
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    <span><?php echo count($cekler); ?> Kayıt &nbsp;|&nbsp; Beklemede: ₺<?php echo number_format($toplamBeklemede, 2, ',', '.'); ?> &nbsp;|&nbsp; Tahsil Edilecek: ₺<?php echo number_format($toplamTahsilEdilecek, 2, ',', '.'); ?></span>
                </div>
            </div>
        </div>

        <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCheckModal">+</button>
    </div>

    <!-- Çek Ekleme Modal -->
    <div class="modal fade" id="addCheckModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Çek Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Çek No *</label>
                            <input type="text" class="form-control" name="cek_no" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banka Adı *</label>
                            <input type="text" class="form-control" name="banka_adi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şube Adı</label>
                            <input type="text" class="form-control" name="sube_adi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tutar (₺) *</label>
                            <input type="number" class="form-control" name="tutar" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vade Tarihi *</label>
                            <input type="date" class="form-control" name="vade_tarihi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durum *</label>
                            <select class="form-select" name="durum" required>
                                <option value="Beklemede">Beklemede</option>
                                <option value="Tahsil Edildi">Tahsil Edildi</option>
                                <option value="İptal">İptal</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Düzenleyen *</label>
                            <input type="text" class="form-control" name="duzenleyen" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="aciklama" rows="3"></textarea>
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

    <!-- Çek Düzenleme Modal -->
    <div class="modal fade" id="editCheckModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Çek Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Çek No *</label>
                            <input type="text" class="form-control" name="cek_no" id="edit_cek_no" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banka Adı *</label>
                            <input type="text" class="form-control" name="banka_adi" id="edit_banka_adi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şube Adı</label>
                            <input type="text" class="form-control" name="sube_adi" id="edit_sube_adi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tutar (₺) *</label>
                            <input type="number" class="form-control" name="tutar" id="edit_tutar" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vade Tarihi *</label>
                            <input type="date" class="form-control" name="vade_tarihi" id="edit_vade_tarihi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durum *</label>
                            <select class="form-select" name="durum" id="edit_durum" required>
                                <option value="Beklemede">Beklemede</option>
                                <option value="Tahsil Edildi">Tahsil Edildi</option>
                                <option value="İptal">İptal</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Düzenleyen *</label>
                            <input type="text" class="form-control" name="duzenleyen" id="edit_duzenleyen" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="aciklama" id="edit_aciklama" rows="3"></textarea>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
    <script>
        function editCheck(id) {
            const cekData = <?php echo json_encode($cekler); ?>;
            const cek = cekData.find(c => c.id == id);
            if (cek) {
                document.getElementById('edit_id').value = cek.id;
                document.getElementById('edit_cek_no').value = cek.cek_no;
                document.getElementById('edit_banka_adi').value = cek.banka_adi;
                document.getElementById('edit_sube_adi').value = cek.sube_adi || '';
                document.getElementById('edit_tutar').value = cek.tutar;
                document.getElementById('edit_vade_tarihi').value = cek.vade_tarihi;
                document.getElementById('edit_durum').value = cek.durum;
                document.getElementById('edit_duzenleyen').value = cek.duzenleyen;
                document.getElementById('edit_aciklama').value = cek.aciklama || '';
                new bootstrap.Modal(document.getElementById('editCheckModal')).show();
            } else {
                alert('Çek bulunamadı!');
            }
        }
    </script>
</body>
</html>
