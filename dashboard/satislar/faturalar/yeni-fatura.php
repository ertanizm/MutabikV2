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

$today = date('d.m.Y');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Fatura - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../../assets/satislar/faturalar/yeni-fatura.css" rel="stylesheet">
    <link href="../../dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Yeni Fatura</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="form-container d-flex flex-wrap">
            <div style="flex: 1 1 600px; min-width: 350px;">
                <!-- Fatura İsmi -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-alt"></i>
                            <span>FATURA İSMİ</span>
                        </label>
                        <input type="text" class="form-control" id="faturaIsmi" placeholder="Fatura ismi giriniz">
                    </div>
                </div>
                <!-- Müşteri -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i>
                            <span>MÜŞTERİ</span>
                        </label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control" id="musteri" placeholder="Müşteri adı giriniz">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            <span>Kayıtlı bir müşteri seçebilir veya yeni bir müşteri ismi yazabilirsiniz.</span>
                        </div>
                    </div>
                    <div class="customer-info">
                        <label class="form-label">MÜŞTERİ BİLGİLERİ</label>
                        <div class="customer-info-content">-</div>
                    </div>
                </div>
                <!-- Tahsilat Durumu -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-question-circle"></i><span>TAHSİLAT DURUMU</span></label>
                        <div style="display: flex; gap: 0;">
                            <label style="flex:1; display:flex; align-items:center; background:#fafbfc; border:1px solid var(--border-color); border-radius:6px 0 0 6px; padding:12px 16px; cursor:pointer; font-weight:600;">
                                <input type="radio" name="tahsilat" checked style="margin-right:10px;">TAHSİL EDİLECEK
                            </label>
                            <label style="flex:1; display:flex; align-items:center; background:#fafbfc; border:1px solid var(--border-color); border-left:none; border-radius:0 6px 6px 0; padding:12px 16px; cursor:pointer; font-weight:600;">
                                <input type="radio" name="tahsilat" style="margin-right:10px;">TAHSİL EDİLDİ
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Düzenleme ve Vade Tarihi -->
                <div class="form-section">
                    <div class="date-section">
                        <div class="date-group">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-calendar"></i><span>DÜZENLEME TARİHİ</span></label>
                                <div class="input-with-icon">
                                    <input type="text" class="form-control" id="duzenlemeTarihi" value="<?php echo $today; ?>">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="date-group">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-bell"></i><span>VADE TARİHİ</span></label>
                                <div class="quick-date-buttons">
                                    <button type="button" class="quick-date-btn active" data-days="0">AYNI GÜN</button>
                                    <button type="button" class="quick-date-btn" data-days="7">7 GÜN</button>
                                    <button type="button" class="quick-date-btn" data-days="14">14 GÜN</button>
                                    <button type="button" class="quick-date-btn" data-days="30">30 GÜN</button>
                                    <button type="button" class="quick-date-btn" data-days="60">60 GÜN</button>
                                </div>
                                <div class="input-with-icon">
                                    <input type="text" class="form-control" id="vadeTarihi" value="<?php echo $today; ?>">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button type="button" class="btn-outline"><i class="fas fa-plus"></i><span>FATURA NO EKLE</span></button>
                        <button type="button" class="btn-outline"><i class="fas fa-lira-sign"></i><span>DÖVİZ DEĞİŞTİR</span></button>
                        <button type="button" class="btn-outline"><i class="fas fa-plus"></i><span>SİPARİŞ BİLGİSİ EKLE</span></button>
                    </div>
                </div>
                <!-- Stok Takibi -->
                <div class="form-section">
                    <label class="form-label"><i class="fas fa-layer-group"></i><span>STOK TAKİBİ</span></label>
                    <div style="display:flex; gap:0; max-width:700px;">
                        <label style="flex:1; display:flex; align-items:flex-start; background:#fafbfc; border:1px solid var(--border-color); border-radius:6px 0 0 6px; padding:16px 18px; cursor:pointer; font-weight:600;">
                            <input type="radio" name="stok" checked style="margin-right:10px; margin-top:3px;">STOK ÇIKIŞI YAPILSIN
                            <span style="font-weight:400; color:var(--text-secondary); font-size:13px; margin-left:8px;">Stok çıkışı fatura ile yapılır. Daha sonra faturadan irsaliye oluşturulamaz ve faturayla irsaliye eşleştirilemez.</span>
                        </label>
                        <label style="flex:1; display:flex; align-items:flex-start; background:#fafbfc; border:1px solid var(--border-color); border-left:none; border-radius:0 6px 6px 0; padding:16px 18px; cursor:pointer; font-weight:600;">
                            <input type="radio" name="stok" style="margin-right:10px; margin-top:3px;">STOK ÇIKIŞI YAPILMASIN
                            <span style="font-weight:400; color:var(--text-secondary); font-size:13px; margin-left:8px;">Stok takibi gerektirmeyen hizmet/ürünler için kullanılır. Daha sonra faturayla ilişkili irsaliye oluşturulabilir.</span>
                        </label>
                    </div>
                </div>
                <!-- Hizmet/Ürün Tablosu -->
                <div class="products-section">
                    <h3>HİZMET / ÜRÜN</h3>
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>HİZMET / ÜRÜN</th>
                                <th>MİKTAR</th>
                                <th>BİRİM</th>
                                <th>BR. FİYAT</th>
                                <th>VERGİ</th>
                                <th>TOPLAM</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <tr class="product-row">
                                <td><div class="input-with-icon"><input type="text" class="form-control" placeholder="Ürün/Hizmet adı"><i class="fas fa-search"></i></div></td>
                                <td><input type="number" class="quantity-input" value="1.00" step="0.01" min="0"></td>
                                <td><select class="unit-select"><option>Adet</option><option>Kg</option><option>Metre</option><option>Saat</option><option>Gün</option></select></td>
                                <td><input type="number" class="price-input" value="0.00" step="0.01" min="0"><span style="margin-left: 5px;">₺</span></td>
                                <td><select class="tax-select"><option>KDV %20</option><option>KDV %10</option><option>KDV %8</option><option>KDV %1</option><option>KDV %0</option></select></td>
                                <td class="total-cell">0,00 ₺</td>
                                <td class="action-buttons-cell"><button type="button" class="btn-icon"><i class="fas fa-plus"></i></button><button type="button" class="btn-icon"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn-add-row" id="addRowBtn"><i class="fas fa-plus"></i><span> YENİ SATIR EKLE</span></button>
                    <div class="profit-info">Toplam Kâr: -</div>
                </div>
                <!-- Totals -->
                <div class="totals-section">
                    <table class="totals-table">
                        <tr><td>ARA TOPLAM</td><td>0,00₺</td></tr>
                        <tr><td>TOPLAM KDV</td><td>0,00₺</td></tr>
                        <tr class="grand-total"><td>GENEL TOPLAM</td><td>0,00₺</td></tr>
                    </table>
                </div>
            </div>
            <!-- Sağ Panel: Kategori ve Etiketler -->
            <div class="right-panel">
                <div class="panel-group">
                    <div class="panel-title"><i class="fas fa-folder"></i><span>FATURA KATEGORİSİ</span></div>
                    <select><option>KATEGORİSİZ</option></select>
                    <div class="panel-hint">Faturaların kategorilere göre dağılımını satışlar raporunda takip edebilirsiniz.</div>
                </div>
                <div class="panel-group">
                    <div class="panel-title"><i class="fas fa-tags"></i><span>ETİKETLERİ</span></div>
                    <select><option>ETİKETSİZ</option></select>
                    <div class="panel-hint">Etiketler Gelir Gider Raporunda etiket bazında karlılığınızı görmenizi sağlar.</div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
</body>
</html> 