<?php
require '../../../config/config.php';

// Teklif ID'sini al
$teklifId = $_GET['id'] ?? null;

// PHP'deki örnek teklif verileri
$phpTeklifler = [
    1 => [
        'id' => 1,
        'isim' => 'Web Sitesi Geliştirme Projesi',
        'musteri' => 'ABC Şirketi',
        'duzenlemeTarihi' => '15.01.2024',
        'vadeTarihi' => '15.02.2024',
        'durum' => 'Cevap Bekleniyor',
        'toplamTutar' => 20000.00,
        'kdvTutari' => 4000.00,
        'genelToplam' => 24000.00,
        'urunler' => [
            [
                'isim' => 'Web Sitesi Tasarımı',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 12000.00,
                'kdvOrani' => 20,
                'toplam' => 14400.00
            ],
            [
                'isim' => 'SEO Optimizasyonu',
                'miktar' => 6,
                'birim' => 'Aylık',
                'birimFiyat' => 1000.00,
                'kdvOrani' => 20,
                'toplam' => 7200.00
            ],
            [
                'isim' => 'Domain ve Hosting',
                'miktar' => 1,
                'birim' => 'Yıllık',
                'birimFiyat' => 2400.00,
                'kdvOrani' => 20,
                'toplam' => 2880.00
            ]
        ]
    ],
    2 => [
        'id' => 2,
        'isim' => 'Mobil Uygulama Geliştirme',
        'musteri' => 'XYZ Teknoloji',
        'duzenlemeTarihi' => '10.01.2024',
        'vadeTarihi' => '10.02.2024',
        'durum' => 'Kabul Edildi',
        'toplamTutar' => 37500.00,
        'kdvTutari' => 7500.00,
        'genelToplam' => 45000.00,
        'urunler' => [
            [
                'isim' => 'iOS Uygulama Geliştirme',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 20000.00,
                'kdvOrani' => 20,
                'toplam' => 24000.00
            ],
            [
                'isim' => 'Android Uygulama Geliştirme',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 15000.00,
                'kdvOrani' => 20,
                'toplam' => 18000.00
            ],
            [
                'isim' => 'Backend API Geliştirme',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 2500.00,
                'kdvOrani' => 20,
                'toplam' => 3000.00
            ]
        ]
    ],
    3 => [
        'id' => 3,
        'isim' => 'E-ticaret Sistemi Kurulumu',
        'musteri' => 'DEF Mağazası',
        'duzenlemeTarihi' => '08.01.2024',
        'vadeTarihi' => '08.02.2024',
        'durum' => 'Reddedildi',
        'toplamTutar' => 15000.00,
        'kdvTutari' => 3000.00,
        'genelToplam' => 18000.00,
        'urunler' => [
            [
                'isim' => 'E-ticaret Platformu',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 10000.00,
                'kdvOrani' => 20,
                'toplam' => 12000.00
            ],
            [
                'isim' => 'Ödeme Sistemi Entegrasyonu',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 3000.00,
                'kdvOrani' => 20,
                'toplam' => 3600.00
            ],
            [
                'isim' => 'Kargo Entegrasyonu',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 2000.00,
                'kdvOrani' => 20,
                'toplam' => 2400.00
            ]
        ]
    ],
    4 => [
        'id' => 4,
        'isim' => 'Veritabanı Optimizasyonu',
        'musteri' => 'GHI Holding',
        'duzenlemeTarihi' => '12.01.2024',
        'vadeTarihi' => '12.02.2024',
        'durum' => 'Cevap Bekleniyor',
        'toplamTutar' => 10000.00,
        'kdvTutari' => 2000.00,
        'genelToplam' => 12000.00,
        'urunler' => [
            [
                'isim' => 'Veritabanı Analizi',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 5000.00,
                'kdvOrani' => 20,
                'toplam' => 6000.00
            ],
            [
                'isim' => 'Performans Optimizasyonu',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 3000.00,
                'kdvOrani' => 20,
                'toplam' => 3600.00
            ],
            [
                'isim' => 'Güvenlik Güncellemeleri',
                'miktar' => 1,
                'birim' => 'Proje',
                'birimFiyat' => 2000.00,
                'kdvOrani' => 20,
                'toplam' => 2400.00
            ]
        ]
    ],
    5 => [
        'id' => 5,
        'isim' => 'Sistem Bakım ve Destek',
        'musteri' => 'JKL Yazılım',
        'duzenlemeTarihi' => '05.01.2024',
        'vadeTarihi' => '05.02.2024',
        'durum' => 'Kabul Edildi',
        'toplamTutar' => 6666.67,
        'kdvTutari' => 1333.33,
        'genelToplam' => 8000.00,
        'urunler' => [
            [
                'isim' => 'Sistem Bakımı',
                'miktar' => 12,
                'birim' => 'Aylık',
                'birimFiyat' => 500.00,
                'kdvOrani' => 20,
                'toplam' => 6000.00
            ],
            [
                'isim' => 'Teknik Destek',
                'miktar' => 12,
                'birim' => 'Aylık',
                'birimFiyat' => 166.67,
                'kdvOrani' => 20,
                'toplam' => 2000.00
            ]
        ]
    ],
    6 => [
        'id' => 6,
        'isim' => 'SEO ve Dijital Pazarlama',
        'musteri' => 'MNO Reklam',
        'duzenlemeTarihi' => '20.01.2024',
        'vadeTarihi' => '20.02.2024',
        'durum' => 'Cevap Bekleniyor',
        'toplamTutar' => 12500.00,
        'kdvTutari' => 2500.00,
        'genelToplam' => 15000.00,
        'urunler' => [
            [
                'isim' => 'SEO Optimizasyonu',
                'miktar' => 6,
                'birim' => 'Aylık',
                'birimFiyat' => 1500.00,
                'kdvOrani' => 20,
                'toplam' => 9000.00
            ],
            [
                'isim' => 'Google Ads Yönetimi',
                'miktar' => 6,
                'birim' => 'Aylık',
                'birimFiyat' => 2000.00,
                'kdvOrani' => 20,
                'toplam' => 12000.00
            ],
            [
                'isim' => 'Sosyal Medya Yönetimi',
                'miktar' => 6,
                'birim' => 'Aylık',
                'birimFiyat' => 1000.00,
                'kdvOrani' => 20,
                'toplam' => 6000.00
            ]
        ]
    ]
];

// Teklifi bul
$teklif = null;
if (isset($phpTeklifler[$teklifId])) {
    $teklif = $phpTeklifler[$teklifId];
} else {
    // Teklif bulunamadıysa varsayılan değerler
    $teklif = [
        'id' => $teklifId,
        'isim' => 'Teklif Bulunamadı',
        'musteri' => 'Bilinmeyen Müşteri',
        'duzenlemeTarihi' => '-',
        'vadeTarihi' => '-',
        'durum' => 'Bilinmiyor',
        'toplamTutar' => 0,
        'kdvTutari' => 0,
        'genelToplam' => 0,
        'urunler' => []
    ];
}

$today = date('d.m.Y');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teklif Detayı - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../../assets/satislar/teklifler/teklif-detay.css" rel="stylesheet">
    <link href="../../dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1><i class="fas fa-file-alt"></i> <?php echo htmlspecialchars($teklif['isim']); ?></h1>
            </div>
            <div class="header-right">
                <a href="teklifler.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    GERİ DÖN
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        DÜZENLE <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Düzenle</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-copy"></i> Kopyala</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-trash"></i> Sil</a></li>
                    </ul>
                </div>
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Ana İçerik -->
            <div class="main-content-area">
                <!-- Teklif Bilgileri -->
                <div class="teklif-bilgileri">
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file-alt"></i>
                                <span>TEKLİF İSMİ</span>
                            </label>
                            <input type="text" class="form-control" placeholder="Fatura ismi giriniz" value="<?php echo htmlspecialchars($teklif['isim']); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building"></i>
                                <span>MÜŞTERİ</span>
                            </label>
                            <input type="text" class="form-control" placeholder="Müşteri adı giriniz" value="<?php echo htmlspecialchars($teklif['musteri']); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="date-section">
                            <div class="date-group">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-calendar"></i><span>DÜZENLEME TARİHİ</span></label>
                                    <input type="text" class="form-control" value="<?php echo $teklif['duzenlemeTarihi']; ?>" readonly>
                                </div>
                            </div>
                            <div class="date-group">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-bell"></i><span>VADE TARİHİ</span></label>
                                    <input type="text" class="form-control" value="<?php echo $teklif['vadeTarihi']; ?>" readonly>
                                </div>
                            </div>
                        </div>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($teklif['urunler'] as $urun): ?>
                            <tr class="product-row">
                                <td>
                                    <div class="product-name"><?php echo htmlspecialchars($urun['isim']); ?></div>
                                </td>
                                <td><?php echo $urun['miktar']; ?></td>
                                <td><?php echo htmlspecialchars($urun['birim']); ?></td>
                                <td><?php echo number_format($urun['birimFiyat'], 2, ',', '.'); ?> ₺</td>
                                <td>KDV %<?php echo $urun['kdvOrani']; ?></td>
                                <td class="total-cell"><?php echo number_format($urun['toplam'], 2, ',', '.'); ?> ₺</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Toplam Hesaplama -->
                <div class="totals-section">
                    <table class="totals-table">
                        <tr><td>ARA TOPLAM</td><td><?php echo number_format($teklif['toplamTutar'], 2, ',', '.'); ?>₺</td></tr>
                        <tr><td>TOPLAM KDV</td><td><?php echo number_format($teklif['kdvTutari'], 2, ',', '.'); ?>₺</td></tr>
                        <tr class="grand-total"><td>GENEL TOPLAM</td><td><?php echo number_format($teklif['genelToplam'], 2, ',', '.'); ?>₺</td></tr>
                    </table>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons-section">
                    <!-- Buttons removed - moved to header -->
                </div>
            </div>
            
            <!-- Sağ Sidebar -->
            <div class="right-sidebar">
                <!-- Action Buttons -->
                <div class="sidebar-section">
                    <button class="btn-sidebar">
                        <i class="fas fa-envelope"></i>
                        PAYLAŞ
                    </button>
                    <button class="btn-sidebar">
                        <i class="fas fa-print"></i>
                        YAZDIR
                    </button>
                </div>
                
                <!-- Fatura Oluştur -->
                <div class="sidebar-section">
                    <button class="btn-sidebar-primary" onclick="createFatura()">
                        <i class="fas fa-file-invoice"></i>
                        FATURA OLUŞTUR
                    </button>
                </div>
                
                <!-- Durum -->
                <div class="sidebar-section">
                    <h4>DURUM</h4>
                    <div class="status-indicator">
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $teklif['durum'])); ?>">
                            <?php echo $teklif['durum']; ?>
                        </span>
                    </div>
                    
                    <div class="status-options">
                        <label class="radio-option">
                            <input type="radio" name="status" value="Cevap Bekleniyor" <?php echo $teklif['durum'] === 'Cevap Bekleniyor' ? 'checked' : ''; ?>>
                            <span>Cevap Bekleniyor</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="status" value="Kabul Edildi" <?php echo $teklif['durum'] === 'Kabul Edildi' ? 'checked' : ''; ?>>
                            <span>Kabul Edildi</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="status" value="Reddedildi" <?php echo $teklif['durum'] === 'Reddedildi' ? 'checked' : ''; ?>>
                            <span>Reddedildi</span>
                        </label>
                    </div>
                    
                    <button class="btn-save">KAYDET</button>
                </div>
                
                <!-- Teklif Geçmişi -->
                <div class="sidebar-section">
                    <h4>Teklif Geçmişi</h4>
                    <div class="history-tabs">
                        <button class="tab-btn active" data-tab="all">Tümü</button>
                        <button class="tab-btn" data-tab="notes">Notlar</button>
                    </div>
                    
                    <div class="history-content" id="historyContent">
                        <div class="history-item">
                            <div class="history-text">Teklif oluşturuldu.</div>
                            <div class="history-date"><?php echo $teklif['duzenlemeTarihi']; ?> - 15:54 / <?php echo $userName; ?></div>
                        </div>
                    </div>
                    
                    <!-- Not Ekleme Formu (Gizli) -->
                    <div class="note-form" id="noteForm" style="display: none;">
                        <textarea id="noteText" placeholder="Notunuzu yazın..." rows="3" class="form-control"></textarea>
                        <div class="note-actions">
                            <button class="btn-save" onclick="saveNote()">KAYDET</button>
                            <button class="btn-cancel" onclick="cancelNote()">İPTAL</button>
                        </div>
                    </div>
                    
                    <!-- Not Ekleme Butonu -->
                    <button class="btn-add-note" id="addNoteBtn" style="display: none;">
                        <i class="fas fa-plus"></i> YENİ NOT EKLE
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
    
    <script>
        // Teklif ID'sini al
        const teklifId = <?php echo $teklifId ?? 'null'; ?>;
        
        // LocalStorage'dan teklifleri kontrol et
        function checkLocalStorageTeklif() {
            const storedTeklifler = JSON.parse(localStorage.getItem('teklifler') || '[]');
            const localTeklif = storedTeklifler.find(t => t.id == teklifId);
            
            if (localTeklif) {
                // LocalStorage'dan gelen teklifi göster
                updateTeklifDisplay(localTeklif);
            }
        }
        
        // Teklif görünümünü güncelle
        function updateTeklifDisplay(teklif) {
            // Başlık
            document.querySelector('.header-left h1').innerHTML = `<i class="fas fa-file-alt"></i> ${teklif.isim}`;
            
            // Form alanları - ID'lerle seç
            const isimInput = document.querySelector('input[placeholder="Fatura ismi giriniz"]');
            const musteriInput = document.querySelector('input[placeholder="Müşteri adı giriniz"]');
            const duzenlemeInput = document.querySelector('input[value="<?php echo $teklif['duzenlemeTarihi']; ?>"]');
            const vadeInput = document.querySelector('input[value="<?php echo $teklif['vadeTarihi']; ?>"]');
            
            if (isimInput) isimInput.value = teklif.isim;
            if (musteriInput) musteriInput.value = teklif.musteri;
            if (duzenlemeInput) duzenlemeInput.value = teklif.duzenlemeTarihi;
            if (vadeInput) vadeInput.value = teklif.vadeTarihi;
            
            // Ürün tablosu
            if (teklif.urunler && teklif.urunler.length > 0) {
                const tbody = document.querySelector('.products-table tbody');
                tbody.innerHTML = teklif.urunler.map(urun => `
                    <tr class="product-row">
                        <td>
                            <div class="product-name">${urun.isim}</div>
                        </td>
                        <td>${urun.miktar}</td>
                        <td>${urun.birim}</td>
                        <td>${urun.birimFiyat.toLocaleString('tr-TR', {minimumFractionDigits: 2})} ₺</td>
                        <td>KDV %${urun.kdvOrani}</td>
                        <td class="total-cell">${urun.toplam.toLocaleString('tr-TR', {minimumFractionDigits: 2})} ₺</td>
                    </tr>
                `).join('');
            }
            
            // Toplamlar
            const toplamTutar = teklif.urunler ? teklif.urunler.reduce((sum, urun) => sum + urun.toplam, 0) : 0;
            const kdvTutari = toplamTutar * 0.2; // %20 KDV
            const genelToplam = toplamTutar + kdvTutari;
            
            document.querySelector('.totals-table tr:nth-child(1) td:last-child').textContent = 
                toplamTutar.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + '₺';
            document.querySelector('.totals-table tr:nth-child(2) td:last-child').textContent = 
                kdvTutari.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + '₺';
            document.querySelector('.totals-table tr:nth-child(3) td:last-child').textContent = 
                genelToplam.toLocaleString('tr-TR', {minimumFractionDigits: 2}) + '₺';
        }
        
        // Sayfa yüklendiğinde localStorage'ı kontrol et
        document.addEventListener('DOMContentLoaded', function() {
            checkLocalStorageTeklif();
        });
        
        // Durum değişikliği
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const statusBadge = document.querySelector('.status-badge');
                statusBadge.textContent = this.value;
                statusBadge.className = `status-badge status-${this.value.toLowerCase().replace(' ', '-')}`;
            });
        });
        
        // Kaydet butonu
        document.querySelector('.btn-save').addEventListener('click', function() {
            const selectedStatus = document.querySelector('input[name="status"]:checked').value;
            
            // LocalStorage'daki teklifleri güncelle
            const storedTeklifler = JSON.parse(localStorage.getItem('teklifler') || '[]');
            const teklifIndex = storedTeklifler.findIndex(t => t.id == teklifId);
            
            if (teklifIndex !== -1) {
                storedTeklifler[teklifIndex].durum = selectedStatus;
                localStorage.setItem('teklifler', JSON.stringify(storedTeklifler));
            }
            
            // Geçmişe durum değişikliği ekle
            const historyItem = document.createElement('div');
            historyItem.className = 'history-item';
            historyItem.innerHTML = `
                <div class="history-text">Durum güncellendi: ${selectedStatus}</div>
                <div class="history-date">${new Date().toLocaleDateString('tr-TR')} - ${new Date().toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'})} / <?php echo $userName; ?></div>
            `;
            document.getElementById('historyContent').appendChild(historyItem);
            
            alert('Durum başarıyla güncellendi: ' + selectedStatus);
        });
        
        // Tab değişikliği
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const tabType = this.getAttribute('data-tab');
                if (tabType === 'notes') {
                    document.getElementById('addNoteBtn').style.display = 'block';
                    showNotes();
                } else {
                    document.getElementById('addNoteBtn').style.display = 'none';
                    document.getElementById('noteForm').style.display = 'none';
                    showAllHistory();
                }
            });
        });
        
        // Not ekleme butonu
        document.getElementById('addNoteBtn').addEventListener('click', function() {
            document.getElementById('noteForm').style.display = 'block';
            document.getElementById('noteText').focus();
        });
        
        // Not kaydetme
        function saveNote() {
            const noteText = document.getElementById('noteText').value.trim();
            if (noteText) {
                const noteItem = document.createElement('div');
                noteItem.className = 'history-item note-item';
                noteItem.innerHTML = `
                    <div class="history-text">${noteText}</div>
                    <div class="history-date">${new Date().toLocaleDateString('tr-TR')} - ${new Date().toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'})} / <?php echo $userName; ?></div>
                `;
                
                document.getElementById('historyContent').appendChild(noteItem);
                document.getElementById('noteText').value = '';
                document.getElementById('noteForm').style.display = 'none';
                
                // LocalStorage'a kaydet
                saveNoteToStorage(noteText);
            }
        }
        
        // Not iptal etme
        function cancelNote() {
            document.getElementById('noteText').value = '';
            document.getElementById('noteForm').style.display = 'none';
        }
        
        // Notları göster
        function showNotes() {
            const historyContent = document.getElementById('historyContent');
            const allItems = historyContent.querySelectorAll('.history-item');
            
            allItems.forEach(item => {
                if (item.classList.contains('note-item')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        // Tüm geçmişi göster
        function showAllHistory() {
            const historyContent = document.getElementById('historyContent');
            const allItems = historyContent.querySelectorAll('.history-item');
            
            allItems.forEach(item => {
                item.style.display = 'block';
            });
        }
        
        // Notu localStorage'a kaydet
        function saveNoteToStorage(noteText) {
            const teklifId = <?php echo $teklifId ?? 'null'; ?>;
            const notes = JSON.parse(localStorage.getItem(`teklif_notes_${teklifId}`) || '[]');
            notes.push({
                text: noteText,
                date: new Date().toISOString(),
                user: '<?php echo $userName; ?>'
            });
            localStorage.setItem(`teklif_notes_${teklifId}`, JSON.stringify(notes));
        }
        
        // Fatura oluşturma fonksiyonu
        function createFatura() {
            const teklifId = <?php echo $teklifId ?? 'null'; ?>;
            const teklif = {
                id: teklifId,
                isim: document.querySelector('input[placeholder="Fatura ismi giriniz"]').value,
                musteri: document.querySelector('input[placeholder="Müşteri adı giriniz"]').value,
                duzenlemeTarihi: document.querySelector('input[value="<?php echo $teklif['duzenlemeTarihi']; ?>"]').value,
                vadeTarihi: document.querySelector('input[value="<?php echo $teklif['vadeTarihi']; ?>"]').value,
                urunler: []
            };
            
            // Ürünleri topla
            document.querySelectorAll('.product-row').forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    teklif.urunler.push({
                        isim: cells[0].textContent.trim(),
                        miktar: parseFloat(cells[1].textContent) || 0,
                        birim: cells[2].textContent.trim(),
                        birimFiyat: parseFloat(cells[3].textContent.replace(/[^\d,]/g, '').replace(',', '.')) || 0,
                        kdvOrani: parseFloat(cells[4].textContent.match(/\d+/)[0]) || 0,
                        toplam: parseFloat(cells[5].textContent.replace(/[^\d,]/g, '').replace(',', '.')) || 0
                    });
                }
            });
            
            // LocalStorage'a fatura olarak kaydet
            const faturalar = JSON.parse(localStorage.getItem('faturalar') || '[]');
            const fatura = {
                id: Date.now(),
                ...teklif,
                faturaNo: 'FAT-' + new Date().getFullYear() + '-' + String(Date.now()).slice(-6),
                olusturmaTarihi: new Date().toISOString(),
                toplamTutar: teklif.urunler.reduce((sum, urun) => sum + urun.toplam, 0),
                kalanMeblag: teklif.urunler.reduce((sum, urun) => sum + urun.toplam, 0),
                durum: 'Ödenmedi'
            };
            
            faturalar.push(fatura);
            localStorage.setItem('faturalar', JSON.stringify(faturalar));
            
            alert('Fatura başarıyla oluşturuldu!');
            window.location.href = '../faturalar/faturalar.php';
        }
    </script>
</body>
</html> 