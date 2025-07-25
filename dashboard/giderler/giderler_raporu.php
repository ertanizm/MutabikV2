<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giderler Raporu - Mutabık</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/giderler/giderler_raporu.css" rel="stylesheet">
    
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>
    <main class="main-content">
        <header class="header-section">
            <h1>Giderler Raporu</h1>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </header>

        <div class="content-area">
            <div class="filter-section">
                <div class="filter-left">
                    <button class="filter-button"><i class="fas fa-filter"></i> FİLTRELE <i class="fas fa-caret-down"></i></button>
                    <div class="date-range-inputs">
                        <input type="date" id="startDate" value="2025-07-01">
                        <span>-</span>
                        <input type="date" id="endDate" value="2025-07-22">
                    </div>
                </div>
                <div class="tax-toggle">
                    <label for="tax-include">VERGİLER DAHİL</label>
                    <label class="switch">
                        <input type="checkbox" id="tax-include">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="tabs-container">
                <button class="tab-button active" data-tab="giderler">Giderler</button>
                <button class="tab-button" data-tab="tedarikciler">Tedarikçiler</button>
                <button class="tab-button" data-tab="hizmet-urunler">Hizmet ve Ürünler</button>
                <button class="tab-button" data-tab="harcananlar">Harcananlar</button>
            </div>

            <div class="report-section">
                <div class="section-header">
                    <h2>GİDER KATEGORİLERİ</h2>
                    <div class="section-header-buttons">
                        <span class="inactive-header-text">KATEGORİLERİN DAĞILIMI</span>
                        <span class="inactive-header-text">KATEGORİLERİN DEĞİŞİMİ</span>
                    </div>
                </div>
                <?php if (empty($giderKategorileri)): ?>
                    <div class="empty-message">
                        Belirtilen tarih aralığında gideriniz yok.
                    </div>
                <?php else: ?>
                    <div class="category-grid">
                        <?php foreach ($giderKategorileri as $category): ?>
                            <div class="category-item">
                                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p><?php echo htmlspecialchars($category['value']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="report-section">
                <div class="section-header">
                    <h2>TÜM GİDER KAYITLARI</h2>
                    <div class="section-header-buttons">
                        <button class="primary">GİDER KAYITLARI</button>
                        <button>TEDARİKÇİLER</button>
                        <button>HİZMET / ÜRÜNLER</button>
                    </div>
                </div>

                <div class="report-table-container">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>KAYIT İSMİ</th>
                                <th>DÜZENLEME TARİHİ</th>
                                <th>BAKİYE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tumGiderKayitlari)): ?>
                                <tr>
                                    <td colspan="3" class="empty-message-table">Veri bulunamadı.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tumGiderKayitlari as $kayit): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($kayit['kayit_ismi']); ?></td>
                                        <td><?php htmlspecialchars($kayit['duzenleme_tarihi']); ?></td>
                                        <td><?php htmlspecialchars($kayit['bakiye']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="export-button-container">
                    <button class="export-button">DIŞARI AKTAR</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // JavaScript Başlangıcı

        // Tab butonları için JavaScript (Fazla tekrar eden kodları birleştirdim)
        document.querySelectorAll('.tabs-container .tab-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tabs-container .tab-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // Burada ilgili sekmenin içeriğini dinamik olarak yükleyebilirsiniz veya gösterebilirsiniz.
            });
        });

        // Toggle switch için basit JavaScript (Fazla tekrar eden kodu birleştirdim)
        document.getElementById('tax-include').addEventListener('change', function() {
            console.log('Vergiler Dahil:', this.checked);
        });

        // Tarih seçiciler için JavaScript
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        // Başlangıç ve bitiş tarihi varsayılan değerlerini bugünün tarihi olarak ayarla (veya istediğiniz gibi)
        // Şu anki tarih 22 Temmuz 2025 olduğu için başlangıcı 1 Temmuz, bitişi 22 Temmuz olarak tutalım.
        // startDateInput.value = "2025-07-01"; // İlk yüklemede varsayılan değer
        // endDateInput.value = "2025-07-22";   // İlk yüklemede varsayılan değer

        function updateDateRange() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                console.log('Seçilen Tarih Aralığı:', startDate, 'ile', endDate);
                // Burada seçilen tarih aralığına göre verileri filtreleme veya PHP'ye gönderme
                // işlemini yapabilirsiniz. Örneğin, AJAX ile sunucuya istek gönderme:
                /*
                fetch(`gider_verileri.php?startDate=${startDate}&endDate=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        // Verileri tabloya veya diğer alanlara yerleştir
                        console.log('Yeni veriler:', data);
                    })
                    .catch(error => console.error('Veri çekme hatası:', error));
                */
            }
        }

        // Tarih değiştiğinde updateDateRange fonksiyonunu çağır
        startDateInput.addEventListener('change', updateDateRange);
        endDateInput.addEventListener('change', updateDateRange);

        // Sayfa yüklendiğinde bir kez tarihleri güncelle (opsiyonel)
        // updateDateRange(); // Eğer varsayılan değerlerle hemen bir işlem yapmak isterseniz
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>

</body>

</html>