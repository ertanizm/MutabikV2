<?php

require '../../config/config.php';
// Statik kullanıcı bilgileri
$userName = 'Kullanıcı';
$companyName = 'Şirket';

// Statik veriler
$hesaplar = [
    [
        'id' => 1,
        'hesap_adi' => 'Merkez Kasa',
        'tur' => 'Kasa',
        'bakiye' => 5000.00,
        'banka_adi' => null,
        'iban' => null,
        'son_islem_tarihi' => '2024-06-28',
        'aciklama' => 'Ana kasa hesabı'
    ],
    [
        'id' => 2,
        'hesap_adi' => 'Şube Kasa',
        'tur' => 'Kasa',
        'bakiye' => 2500.00,
        'banka_adi' => null,
        'iban' => null,
        'son_islem_tarihi' => '2024-06-27',
        'aciklama' => 'Şube kasa hesabı'
    ],
    [
        'id' => 3,
        'hesap_adi' => 'Ziraat Bankası',
        'tur' => 'Banka',
        'bakiye' => 15000.00,
        'banka_adi' => 'Ziraat Bankası',
        'iban' => 'TR12 0001 0000 1234 5678 0000 01',
        'son_islem_tarihi' => '2024-06-26',
        'aciklama' => 'Ana banka hesabı'
    ],
    [
        'id' => 4,
        'hesap_adi' => 'İş Bankası',
        'tur' => 'Banka',
        'bakiye' => 12000.00,
        'banka_adi' => 'İş Bankası',
        'iban' => 'TR33 0006 1005 1978 6457 8412 34',
        'son_islem_tarihi' => '2024-06-25',
        'aciklama' => 'İş bankası hesabı'
    ],
    [
        'id' => 5,
        'hesap_adi' => 'Garanti Bankası',
        'tur' => 'Banka',
        'bakiye' => 8000.00,
        'banka_adi' => 'Garanti BBVA',
        'iban' => 'TR66 0006 2000 0000 0000 0000 00',
        'son_islem_tarihi' => '2024-06-24',
        'aciklama' => 'Garanti hesabı'
    ]
];

// Toplam bakiyeleri hesapla
$toplamKasa = 0;
$toplamBanka = 0;
foreach ($hesaplar as $hesap) {
    if ($hesap['tur'] == 'Kasa') {
        $toplamKasa += $hesap['bakiye'];
    } else {
        $toplamBanka += $hesap['bakiye'];
    }
}
$toplamVarlik = $toplamKasa + $toplamBanka;

// CRUD İşlemleri (Statik)
$message = '';
$messageType = '';

// Ekleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $hesap_adi = trim($_POST['hesap_adi']);
    $tur = $_POST['tur'];
    $bakiye = floatval($_POST['bakiye']);
    $banka_adi = trim($_POST['banka_adi'] ?? '');
    $iban = trim($_POST['iban'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    
    if (!empty($hesap_adi)) {
        // Yeni hesap ekle
        $yeniHesap = [
            'id' => count($hesaplar) + 1,
            'hesap_adi' => $hesap_adi,
            'tur' => $tur,
            'bakiye' => $bakiye,
            'banka_adi' => $banka_adi ?: null,
            'iban' => $iban ?: null,
            'son_islem_tarihi' => date('Y-m-d'),
            'aciklama' => $aciklama
        ];
        $hesaplar[] = $yeniHesap;
        
        // Toplam bakiyeleri güncelle
        $toplamKasa = 0;
        $toplamBanka = 0;
        foreach ($hesaplar as $hesap) {
            if ($hesap['tur'] == 'Kasa') {
                $toplamKasa += $hesap['bakiye'];
            } else {
                $toplamBanka += $hesap['bakiye'];
            }
        }
        $toplamVarlik = $toplamKasa + $toplamBanka;
        
        $message = "Hesap başarıyla eklendi!";
        $messageType = "success";
    }
}

// Silme İşlemi
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $hesaplar = array_filter($hesaplar, function($hesap) use ($id) {
        return $hesap['id'] != $id;
    });
    
    // Toplam bakiyeleri güncelle
    $toplamKasa = 0;
    $toplamBanka = 0;
    foreach ($hesaplar as $hesap) {
        if ($hesap['tur'] == 'Kasa') {
            $toplamKasa += $hesap['bakiye'];
        } else {
            $toplamBanka += $hesap['bakiye'];
        }
    }
    $toplamVarlik = $toplamKasa + $toplamBanka;
    
    $message = "Hesap başarıyla silindi!";
    $messageType = "success";
}

// Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['id']);
    $hesap_adi = trim($_POST['hesap_adi']);
    $tur = $_POST['tur'];
    $bakiye = floatval($_POST['bakiye']);
    $banka_adi = trim($_POST['banka_adi'] ?? '');
    $iban = trim($_POST['iban'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    
    if (!empty($hesap_adi)) {
        // Hesabı güncelle
        foreach ($hesaplar as &$hesap) {
            if ($hesap['id'] == $id) {
                $hesap['hesap_adi'] = $hesap_adi;
                $hesap['tur'] = $tur;
                $hesap['bakiye'] = $bakiye;
                $hesap['banka_adi'] = $banka_adi ?: null;
                $hesap['iban'] = $iban ?: null;
                $hesap['son_islem_tarihi'] = date('Y-m-d');
                $hesap['aciklama'] = $aciklama;
                break;
            }
        }
        
        // Toplam bakiyeleri güncelle
        $toplamKasa = 0;
        $toplamBanka = 0;
        foreach ($hesaplar as $hesap) {
            if ($hesap['tur'] == 'Kasa') {
                $toplamKasa += $hesap['bakiye'];
            } else {
                $toplamBanka += $hesap['bakiye'];
            }
        }
        $toplamVarlik = $toplamKasa + $toplamBanka;
        
        $message = "Hesap başarıyla güncellendi!";
        $messageType = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Kasa ve Bankalar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet"> 
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Kasa ve Bankalar</h1>
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

        <div class="financial-section">
            <div class="row mb-4">
                <div class="col-md-4 col-12 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Kasa Bakiyesi</div>
                            <div class="card-icon income">
                                <i class="fas fa-cash-register"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺<?php echo number_format($toplamKasa, 2, ',', '.'); ?></div>
                        <div class="card-status">Güncel</div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Banka Bakiyesi</div>
                            <div class="card-icon success">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺<?php echo number_format($toplamBanka, 2, ',', '.'); ?></div>
                        <div class="card-status">Güncel</div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Varlık</div>
                            <div class="card-icon pending">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺<?php echo number_format($toplamVarlik, 2, ',', '.'); ?></div>
                        <div class="card-status">Kasa + Banka</div>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <canvas id="bankCashChart" height="80"></canvas>
            </div>
        </div>

        <div class="financial-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">
                    <i class="fas fa-list"></i>
                    Kasa ve Banka Hesapları Detayı
                </h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                    <i class="fas fa-plus"></i> Yeni Hesap Ekle
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hesap Adı</th>
                            <th>Tür</th>
                            <th>Bakiye (₺)</th>
                            <th>Banka Adı</th>
                            <th>IBAN</th>
                            <th>Son İşlem</th>
                            <th>Açıklama</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($hesaplar)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Henüz hesap bulunmamaktadır.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($hesaplar as $hesap): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hesap['hesap_adi']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $hesap['tur'] == 'Kasa' ? 'success' : 'primary'; ?>">
                                            <?php echo $hesap['tur']; ?>
                                        </span>
                                    </td>
                                    <td>₺<?php echo number_format($hesap['bakiye'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($hesap['banka_adi'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($hesap['iban'] ?? '-'); ?></td>
                                    <td><?php echo $hesap['son_islem_tarihi']; ?></td>
                                    <td><?php echo htmlspecialchars($hesap['aciklama'] ?? '-'); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editAccount(<?php echo $hesap['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?action=delete&id=<?php echo $hesap['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Bu hesabı silmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hesap Ekleme Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Hesap Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Hesap Adı *</label>
                            <input type="text" class="form-control" name="hesap_adi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tür *</label>
                            <select class="form-select" name="tur" required>
                                <option value="Kasa">Kasa</option>
                                <option value="Banka">Banka</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bakiye (₺)</label>
                            <input type="number" class="form-control" name="bakiye" step="0.01" value="0.00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banka Adı</label>
                            <input type="text" class="form-control" name="banka_adi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">IBAN</label>
                            <input type="text" class="form-control" name="iban">
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

    <!-- Hesap Düzenleme Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hesap Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Hesap Adı *</label>
                            <input type="text" class="form-control" name="hesap_adi" id="edit_hesap_adi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tür *</label>
                            <select class="form-select" name="tur" id="edit_tur" required>
                                <option value="Kasa">Kasa</option>
                                <option value="Banka">Banka</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bakiye (₺)</label>
                            <input type="number" class="form-control" name="bakiye" id="edit_bakiye" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banka Adı</label>
                            <input type="text" class="form-control" name="banka_adi" id="edit_banka_adi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">IBAN</label>
                            <input type="text" class="form-control" name="iban" id="edit_iban">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../script2.js"></script>
    <script>
    // Grafik için veri
    const ctx = document.getElementById('bankCashChart');
    if (ctx) {
        const labels = <?php echo json_encode(array_column($hesaplar, 'hesap_adi')); ?>;
        const data = <?php echo json_encode(array_column($hesaplar, 'bakiye')); ?>;
        const colors = ['#27ae60', '#2ecc71', '#2980b9', '#3498db', '#9b59b6', '#e74c3c', '#f39c12', '#1abc9c'];
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Bakiye (₺)',
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Kasa ve Banka Hesapları Dağılımı',
                        color: '#2c3e50',
                        font: { size: 18 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#2c3e50' }
                    },
                    x: {
                        ticks: { color: '#2c3e50' }
                    }
                }
            }
        });
    }

    // Hesap düzenleme fonksiyonu
    function editAccount(id) {
        // Statik verilerden hesap bilgilerini bul
        const hesapData = <?php echo json_encode($hesaplar); ?>;
        const hesap = hesapData.find(h => h.id == id);
        
        if (hesap) {
            document.getElementById('edit_id').value = hesap.id;
            document.getElementById('edit_hesap_adi').value = hesap.hesap_adi;
            document.getElementById('edit_tur').value = hesap.tur;
            document.getElementById('edit_bakiye').value = hesap.bakiye;
            document.getElementById('edit_banka_adi').value = hesap.banka_adi || '';
            document.getElementById('edit_iban').value = hesap.iban || '';
            document.getElementById('edit_aciklama').value = hesap.aciklama || '';
            
            new bootstrap.Modal(document.getElementById('editAccountModal')).show();
        } else {
            alert('Hesap bulunamadı!');
        }
    }
    </script>
</body>
</html>
