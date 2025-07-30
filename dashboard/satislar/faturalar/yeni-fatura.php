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
        <div class="form-container">
            <!-- Sol Panel: Ana Form -->
            <div class="main-form">
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
                            <input type="radio" name="tahsilat" value="Tahsil Edilecek" checked style="margin-right:10px;">TAHSİL EDİLECEK
                        </label>
                        <label style="flex:1; display:flex; align-items:center; background:#fafbfc; border:1px solid var(--border-color); border-left:none; border-radius:0 6px 6px 0; padding:12px 16px; cursor:pointer; font-weight:600;">
                            <input type="radio" name="tahsilat" value="Tahsil Edildi" style="margin-right:10px;">TAHSİL EDİLDİ
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
        
        <!-- Tam Genişlik Alanlar -->
        <div class="full-width-container">
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
                            <td><div class="input-with-icon"><input type="text" class="form-control product-name" placeholder="Ürün/Hizmet adı" oninput="searchProducts(this)" onfocus="showProductDropdown(this)"><i class="fas fa-search"></i><div class="product-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; z-index: 1000;"></div></div></td>
                            <td><input type="number" class="quantity-input" value="1.00" step="0.01" min="0" oninput="updateProductData(this)"></td>
                            <td><select class="unit-select" onchange="updateProductData(this)"><option>Adet</option><option>Kg</option><option>Metre</option><option>Saat</option><option>Gün</option><option>Proje</option><option>Aylık</option><option>Paket</option></select></td>
                            <td><input type="number" class="price-input" value="0.00" step="0.01" min="0" oninput="updateProductData(this)"><span style="margin-left: 5px;">₺</span></td>
                            <td><select class="tax-select" onchange="updateProductData(this)"><option value="20">KDV %20</option><option value="10">KDV %10</option><option value="8">KDV %8</option><option value="1">KDV %1</option><option value="0">KDV %0</option></select></td>
                            <td class="total-cell">0,00 ₺</td>
                            <td class="action-buttons-cell"><button type="button" class="btn-icon" onclick="addProductRow(this)"><i class="fas fa-plus"></i></button><button type="button" class="btn-icon" onclick="removeProductRow(this)"><i class="fas fa-times"></i></button></td>
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
            
            <!-- Action Buttons -->
            <div class="action-buttons-section">
                <a href="faturalar.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    GERİ DÖN
                </a>
                <button type="button" class="btn-primary" onclick="createFatura()">
                    <i class="fas fa-check"></i>
                    FATURA OLUŞTUR
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
    
    <script>
        // Global değişkenler
        let faturaData = {
            isim: '',
            musteri: '',
            tahsilatDurumu: 'Tahsil Edilecek',
            duzenlemeTarihi: '',
            vadeTarihi: '',
            stokTakibi: 'Stok Çıkışı Yapılsın',
            kategori: 'Kategorisiz',
            etiket: 'Etiketsiz',
            urunler: []
        };

        // Varsayılan ürün verileri
        const varsayilanUrunler = [
            { id: 1, isim: 'Web Sitesi Geliştirme', birim: 'Proje', fiyat: 5000.00, kdv: 20 },
            { id: 2, isim: 'Mobil Uygulama Geliştirme', birim: 'Proje', fiyat: 15000.00, kdv: 20 },
            { id: 3, isim: 'E-ticaret Sistemi', birim: 'Proje', fiyat: 8000.00, kdv: 20 },
            { id: 4, isim: 'SEO Hizmeti', birim: 'Aylık', fiyat: 1500.00, kdv: 20 },
            { id: 5, isim: 'Sosyal Medya Yönetimi', birim: 'Aylık', fiyat: 2000.00, kdv: 20 },
            { id: 6, isim: 'Logo Tasarımı', birim: 'Adet', fiyat: 500.00, kdv: 20 },
            { id: 7, isim: 'Kurumsal Kimlik Tasarımı', birim: 'Paket', fiyat: 2500.00, kdv: 20 },
            { id: 8, isim: 'Dijital Reklam Yönetimi', birim: 'Aylık', fiyat: 3000.00, kdv: 20 },
            { id: 9, isim: 'İçerik Yazarlığı', birim: 'Adet', fiyat: 200.00, kdv: 20 },
            { id: 10, isim: 'Video Düzenleme', birim: 'Saat', fiyat: 150.00, kdv: 20 },
            { id: 11, isim: 'Sistem Bakım ve Destek', birim: 'Aylık', fiyat: 1000.00, kdv: 20 },
            { id: 12, isim: 'Veritabanı Optimizasyonu', birim: 'Proje', fiyat: 3000.00, kdv: 20 },
            { id: 13, isim: 'Güvenlik Testi', birim: 'Proje', fiyat: 4000.00, kdv: 20 },
            { id: 14, isim: 'API Geliştirme', birim: 'Saat', fiyat: 250.00, kdv: 20 },
            { id: 15, isim: 'Cloud Sunucu Kurulumu', birim: 'Proje', fiyat: 2000.00, kdv: 20 }
        ];

        // Varsayılan müşteri verileri
        const varsayilanMusteriler = [
            { id: 1, ad: 'ABC Teknoloji A.Ş.', telefon: '0212 123 45 67', email: 'info@abc.com', adres: 'İstanbul, Türkiye' },
            { id: 2, ad: 'XYZ Yazılım Ltd.', telefon: '0216 987 65 43', email: 'contact@xyz.com', adres: 'Ankara, Türkiye' },
            { id: 3, ad: 'DEF Mağazaları', telefon: '0312 456 78 90', email: 'info@def.com', adres: 'İzmir, Türkiye' },
            { id: 4, ad: 'GHI Holding', telefon: '0224 111 22 33', email: 'info@ghi.com', adres: 'Bursa, Türkiye' },
            { id: 5, ad: 'JKL E-ticaret', telefon: '0232 444 55 66', email: 'info@jkl.com', adres: 'İzmir, Türkiye' },
            { id: 6, ad: 'MNO Reklam Ajansı', telefon: '0242 777 88 99', email: 'info@mno.com', adres: 'Antalya, Türkiye' },
            { id: 7, ad: 'PQR Danışmanlık', telefon: '0258 333 44 55', email: 'info@pqr.com', adres: 'Denizli, Türkiye' },
            { id: 8, ad: 'STU Eğitim Kurumu', telefon: '0262 666 77 88', email: 'info@stu.com', adres: 'Kocaeli, Türkiye' },
            { id: 9, ad: 'VWX Sağlık Hizmetleri', telefon: '0274 999 00 11', email: 'info@vwx.com', adres: 'Eskişehir, Türkiye' },
            { id: 10, ad: 'YZM Turizm', telefon: '0246 222 33 44', email: 'info@yzm.com', adres: 'Muğla, Türkiye' }
        ];

        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            initializeForm();
            setupEventListeners();
            updateTotals();
        });

        // Formu başlat
        function initializeForm() {
            // Bugünün tarihini Türkçe formatında ayarla
            const today = new Date();
            const gun = today.getDate().toString().padStart(2, '0');
            const ay = (today.getMonth() + 1).toString().padStart(2, '0');
            const yil = today.getFullYear();
            const todayFormatted = `${gun}.${ay}.${yil}`;
            
            document.getElementById('duzenlemeTarihi').value = todayFormatted;
            document.getElementById('vadeTarihi').value = todayFormatted;
            
            // İlk ürün satırını oluştur
            createProductRow();
        }

        // Event listener'ları ayarla
        function setupEventListeners() {
            // Vade tarihi butonları
            document.querySelectorAll('.quick-date-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.quick-date-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const days = parseInt(this.dataset.days);
                    const duzenlemeTarihiStr = document.getElementById('duzenlemeTarihi').value;
                    
                    // Türkçe tarih formatını parse et (dd.mm.yyyy)
                    const tarihParcalari = duzenlemeTarihiStr.split('.');
                    if (tarihParcalari.length === 3) {
                        const gun = parseInt(tarihParcalari[0]);
                        const ay = parseInt(tarihParcalari[1]) - 1; // Ay 0'dan başlar
                        const yil = parseInt(tarihParcalari[2]);
                        
                        const duzenlemeTarihi = new Date(yil, ay, gun);
                        const vadeTarihi = new Date(duzenlemeTarihi);
                        vadeTarihi.setDate(vadeTarihi.getDate() + days);
                        
                        // Türkçe formatında tarihi ayarla
                        const vadeGun = vadeTarihi.getDate().toString().padStart(2, '0');
                        const vadeAy = (vadeTarihi.getMonth() + 1).toString().padStart(2, '0');
                        const vadeYil = vadeTarihi.getFullYear();
                        
                        document.getElementById('vadeTarihi').value = `${vadeGun}.${vadeAy}.${vadeYil}`;
                    }
                });
            });

            // Yeni satır ekleme butonu
            document.getElementById('addRowBtn').addEventListener('click', createProductRow);

            // Form değişikliklerini dinle
            document.getElementById('faturaIsmi').addEventListener('input', updateFaturaData);
            document.getElementById('musteri').addEventListener('input', updateFaturaData);
            document.getElementById('duzenlemeTarihi').addEventListener('input', updateFaturaData);
            document.getElementById('vadeTarihi').addEventListener('input', updateFaturaData);

            // Müşteri arama
            document.getElementById('musteri').addEventListener('input', function() {
                const musteriAdi = this.value.toLowerCase();
                const customerInfo = document.querySelector('.customer-info-content');
                
                const bulunanMusteri = varsayilanMusteriler.find(m => m.ad.toLowerCase().includes(musteriAdi));
                
                if (bulunanMusteri && musteriAdi.length > 2) {
                    customerInfo.innerHTML = `
                        <strong>${bulunanMusteri.ad}</strong><br>
                        Tel: ${bulunanMusteri.telefon}<br>
                        Email: ${bulunanMusteri.email}<br>
                        Adres: ${bulunanMusteri.adres}
                    `;
                } else {
                    customerInfo.innerHTML = '-';
                }
            });

            // Tahsilat durumu
            document.querySelectorAll('input[name="tahsilat"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    faturaData.tahsilatDurumu = this.value;
                });
            });

            // Stok takibi
            document.querySelectorAll('input[name="stok"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    faturaData.stokTakibi = this.parentElement.textContent.trim();
                });
            });
        }

        // Ürün satırı oluştur
        function createProductRow() {
            const tbody = document.getElementById('productsTableBody');
            const row = document.createElement('tr');
            row.className = 'product-row';
            
            row.innerHTML = `
                <td>
                    <div class="input-with-icon">
                        <input type="text" class="form-control product-name" placeholder="Ürün/Hizmet adı" oninput="searchProducts(this)" onfocus="showProductDropdown(this)">
                        <i class="fas fa-search"></i>
                        <div class="product-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; z-index: 1000;"></div>
                    </div>
                </td>
                <td>
                    <input type="number" class="quantity-input" value="1.00" step="0.01" min="0" oninput="updateProductData(this)">
                </td>
                <td>
                    <select class="unit-select" onchange="updateProductData(this)">
                        <option>Adet</option>
                        <option>Kg</option>
                        <option>Metre</option>
                        <option>Saat</option>
                        <option>Gün</option>
                        <option>Proje</option>
                        <option>Aylık</option>
                        <option>Paket</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="price-input" value="0.00" step="0.01" min="0" oninput="updateProductData(this)">
                    <span style="margin-left: 5px;">₺</span>
                </td>
                <td>
                    <select class="tax-select" onchange="updateProductData(this)">
                        <option value="20">KDV %20</option>
                        <option value="10">KDV %10</option>
                        <option value="8">KDV %8</option>
                        <option value="1">KDV %1</option>
                        <option value="0">KDV %0</option>
                    </select>
                </td>
                <td class="total-cell">
                    0,00 ₺
                </td>
                <td class="action-buttons-cell">
                    <button type="button" class="btn-icon" onclick="addProductRow(this)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn-icon" onclick="removeProductRow(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
            updateTotals();
        }

        // Ürün arama fonksiyonu
        function searchProducts(input) {
            const searchTerm = input.value.toLowerCase();
            const dropdown = input.parentNode.querySelector('.product-dropdown');
            
            if (searchTerm.length < 2) {
                dropdown.style.display = 'none';
                return;
            }
            
            const filteredProducts = varsayilanUrunler.filter(urun => 
                urun.isim.toLowerCase().includes(searchTerm)
            );
            
            if (filteredProducts.length > 0) {
                dropdown.innerHTML = filteredProducts.map(urun => 
                    `<div class="product-option" onclick="selectProduct(this, '${urun.isim}', ${urun.fiyat}, '${urun.birim}', ${urun.kdv})" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;">
                        <strong>${urun.isim}</strong><br>
                        <small>${urun.birim} - ₺${urun.fiyat.toLocaleString('tr-TR')}</small>
                    </div>`
                ).join('');
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Ürün seçme fonksiyonu
        function selectProduct(element, isim, fiyat, birim, kdv) {
            const row = element.closest('tr');
            const nameInput = row.querySelector('.product-name');
            const priceInput = row.querySelector('.price-input');
            const unitSelect = row.querySelector('.unit-select');
            const taxSelect = row.querySelector('.tax-select');
            
            nameInput.value = isim;
            priceInput.value = fiyat.toFixed(2);
            
            // Birim seç
            const unitOptions = unitSelect.options;
            for (let i = 0; i < unitOptions.length; i++) {
                if (unitOptions[i].text === birim) {
                    unitSelect.selectedIndex = i;
                    break;
                }
            }
            
            // KDV seç
            const taxOptions = taxSelect.options;
            for (let i = 0; i < taxOptions.length; i++) {
                if (taxOptions[i].value == kdv) {
                    taxSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Dropdown'ı gizle
            element.closest('.product-dropdown').style.display = 'none';
            
            // Toplamı güncelle
            updateProductData(priceInput);
        }

        // Ürün dropdown'ını göster
        function showProductDropdown(input) {
            const dropdown = input.parentNode.querySelector('.product-dropdown');
            if (input.value.length >= 2) {
                dropdown.style.display = 'block';
            }
        }

        // Dropdown dışına tıklandığında gizle
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.input-with-icon')) {
                document.querySelectorAll('.product-dropdown').forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            }
        });

        // Ürün satırı ekle
        function addProductRow(button) {
            const tbody = document.getElementById('productsTableBody');
            const newRow = button.closest('tr').cloneNode(true);
            
            // Yeni satırın değerlerini temizle
            newRow.querySelectorAll('input').forEach(input => {
                if (input.type === 'number') {
                    input.value = input.type === 'number' ? '1.00' : '0.00';
                } else {
                    input.value = '';
                }
            });
            
            newRow.querySelector('.total-cell').textContent = '0,00 ₺';
            
            // Event listener'ları yeniden ekle
            newRow.querySelectorAll('.quantity-input, .price-input').forEach(input => {
                input.addEventListener('input', function() { updateProductData(this); });
            });
            
            newRow.querySelectorAll('.unit-select, .tax-select').forEach(select => {
                select.addEventListener('change', function() { updateProductData(this); });
            });
            
            tbody.appendChild(newRow);
            updateTotals();
        }

        // Ürün satırı sil
        function removeProductRow(button) {
            const tbody = document.getElementById('productsTableBody');
            const rows = tbody.querySelectorAll('.product-row');
            
            if (rows.length > 1) {
                button.closest('tr').remove();
                updateTotals();
            }
        }

        // Ürün verilerini güncelle
        function updateProductData(element) {
            const row = element.closest('tr');
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const taxRate = parseFloat(row.querySelector('.tax-select').value) || 0;
            
            const subtotal = quantity * price;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;
            
            row.querySelector('.total-cell').textContent = total.toLocaleString('tr-TR', { minimumFractionDigits: 2 }) + ' ₺';
            
            updateTotals();
        }

        // Toplamları güncelle
        function updateTotals() {
            const rows = document.querySelectorAll('.product-row');
            let araToplam = 0;
            let toplamKdv = 0;
            
            rows.forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const taxRate = parseFloat(row.querySelector('.tax-select').value) || 0;
                
                const subtotal = quantity * price;
                const taxAmount = subtotal * (taxRate / 100);
                
                araToplam += subtotal;
                toplamKdv += taxAmount;
            });
            
            const genelToplam = araToplam + toplamKdv;
            
            // Toplamları güncelle
            document.querySelector('.totals-table tr:nth-child(1) td:last-child').textContent = araToplam.toLocaleString('tr-TR', { minimumFractionDigits: 2 }) + '₺';
            document.querySelector('.totals-table tr:nth-child(2) td:last-child').textContent = toplamKdv.toLocaleString('tr-TR', { minimumFractionDigits: 2 }) + '₺';
            document.querySelector('.totals-table tr:nth-child(3) td:last-child').textContent = genelToplam.toLocaleString('tr-TR', { minimumFractionDigits: 2 }) + '₺';
        }

        // Fatura verilerini güncelle
        function updateFaturaData() {
            faturaData.isim = document.getElementById('faturaIsmi').value;
            faturaData.musteri = document.getElementById('musteri').value;
            faturaData.duzenlemeTarihi = document.getElementById('duzenlemeTarihi').value;
            faturaData.vadeTarihi = document.getElementById('vadeTarihi').value;
        }

        // Ürün verilerini topla
        function collectProductData() {
            const products = [];
            document.querySelectorAll('.product-row').forEach(row => {
                const name = row.querySelector('.product-name').value;
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const unit = row.querySelector('.unit-select').value;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const taxRate = parseFloat(row.querySelector('.tax-select').value) || 0;
                
                if (name && quantity > 0) {
                    products.push({
                        isim: name,
                        miktar: quantity,
                        birim: unit,
                        birimFiyat: price,
                        kdvOrani: taxRate,
                        toplam: quantity * price * (1 + taxRate / 100)
                    });
                }
            });
            return products;
        }

        // Fatura oluştur
        function createFatura() {
            // Fatura verilerini güncelle
            updateFaturaData();
            
            // Validasyon
            if (!faturaData.isim.trim()) {
                alert('Lütfen fatura ismi giriniz!');
                return;
            }
            
            if (!faturaData.musteri.trim()) {
                alert('Lütfen müşteri bilgisi giriniz!');
                return;
            }
            
            const products = collectProductData();
            
            if (products.length === 0) {
                alert('Lütfen en az bir ürün/hizmet ekleyiniz!');
                return;
            }
            
            // Fatura verilerini topla
            const fatura = {
                id: Date.now(),
                ...faturaData,
                urunler: products,
                toplamTutar: products.reduce((sum, p) => sum + p.toplam, 0),
                kalanMeblag: faturaData.tahsilatDurumu === 'Tahsil Edildi' ? 0 : products.reduce((sum, p) => sum + p.toplam, 0),
                durum: faturaData.tahsilatDurumu === 'Tahsil Edildi' ? 'Ödendi' : 'Ödenmedi',
                faturaNo: 'FAT-' + new Date().getFullYear() + '-' + String(Date.now()).slice(-6),
                olusturmaTarihi: new Date().toISOString()
            };
            
            // LocalStorage'a kaydet
            const mevcutFaturalar = JSON.parse(localStorage.getItem('faturalar') || '[]');
            mevcutFaturalar.push(fatura);
            localStorage.setItem('faturalar', JSON.stringify(mevcutFaturalar));
            
            alert('Fatura başarıyla oluşturuldu!');
            window.location.href = 'faturalar.php';
        }
    </script>
</body>
</html> 