<?php
session_start();

// Session kontrolü
if (!isset($_SESSION['email'])) {
    header("Location: ../../authentication/login.php");
    exit();
}

// Kullanıcı bilgilerini veritabanından al
$host = 'localhost';
$dbname = 'deneme_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT u.email, u.username, c.name as company_name 
                           FROM users u 
                           JOIN companies c ON u.company_id = c.id 
                           WHERE u.email = ?");
    $stmt->execute([$_SESSION['email']]);
    $userData = $stmt->fetch();

    if ($userData) {
        $userName = $userData['username'] ?? 'Kullanıcı';
        $companyName = $userData['company_name'] ?? 'Şirket';
    } else {
        $userName = 'Kullanıcı';
        $companyName = 'Şirket';
    }
} catch (PDOException $e) {
    $userName = 'Kullanıcı';
    $companyName = 'Şirket';
}

// CRUD İşlemleri
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
        try {
            $stmt = $pdo->prepare("INSERT INTO kasa_bankalar (hesap_adi, tur, bakiye, banka_adi, iban, son_islem_tarihi, aciklama) VALUES (?, ?, ?, ?, ?, CURDATE(), ?)");
            $stmt->execute([$hesap_adi, $tur, $bakiye, $banka_adi, $iban, $aciklama]);
            $message = "Hesap başarıyla eklendi!";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Hata: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// Silme İşlemi
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM kasa_bankalar WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Hesap başarıyla silindi!";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Hata: " . $e->getMessage();
        $messageType = "error";
    }
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
        try {
            $stmt = $pdo->prepare("UPDATE kasa_bankalar SET hesap_adi = ?, tur = ?, bakiye = ?, banka_adi = ?, iban = ?, son_islem_tarihi = CURDATE(), aciklama = ? WHERE id = ?");
            $stmt->execute([$hesap_adi, $tur, $bakiye, $banka_adi, $iban, $aciklama, $id]);
            $message = "Hesap başarıyla güncellendi!";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Hata: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// Verileri çek
try {
    $stmt = $pdo->query("SELECT * FROM kasa_bankalar ORDER BY tur, hesap_adi");
    $hesaplar = $stmt->fetchAll();
    
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
    
} catch (PDOException $e) {
    $hesaplar = [];
    $toplamKasa = 0;
    $toplamBanka = 0;
    $toplamVarlik = 0;
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
        // AJAX ile hesap bilgilerini getir
        fetch(`get_account_deneme_db.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_hesap_adi').value = data.hesap_adi;
                document.getElementById('edit_tur').value = data.tur;
                document.getElementById('edit_bakiye').value = data.bakiye;
                document.getElementById('edit_banka_adi').value = data.banka_adi || '';
                document.getElementById('edit_iban').value = data.iban || '';
                document.getElementById('edit_aciklama').value = data.aciklama || '';
                
                new bootstrap.Modal(document.getElementById('editAccountModal')).show();
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Hesap bilgileri alınamadı!');
            });
    }
    </script>
</body>
</html> 
