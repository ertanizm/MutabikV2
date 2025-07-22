<?php
// Kullanıcı bilgileri
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasa / Banka Raporu - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/MutabikV2/dashboard/dashboard.css" rel="stylesheet">
</head>
<body>
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
                <!-- Kasa / Banka başlığı -->
                <div>
                    <h2 class="section-title mb-4">Kasa / Banka</h2>
                </div>
                <!-- Özet kutuları -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Toplam Nakit Girişi</div>
                            <div class="card-amount" style="color: var(--secondary-color);">0,00₺</div>
                            <div class="card-status">Toplam Nakit Girişi</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Toplam Nakit Çıkışı</div>
                            <div class="card-amount" style="color: #b07a4a;">150.000,00₺</div>
                            <div class="card-status">Toplam Nakit Çıkışı</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="financial-card text-center">
                            <div class="card-title">Net Nakit Akışı</div>
                            <div class="card-amount" style="color: #b07a4a;">-150.000,00₺</div>
                            <div class="card-status">Net Nakit Akışı</div>
                        </div>
                    </div>
                </div>
                <!-- Filtre butonları ve tarih -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <button class="btn btn-outline-primary active">NAKİT GİRİŞİ</button>
                    <button class="btn btn-outline-secondary">NAKİT ÇIKIŞI</button>
                    <button class="btn btn-outline-light">GÜN</button>
                    <button class="btn btn-outline-light">HAFTA</button>
                    <button class="btn btn-outline-light">AY</button>
                    <button class="btn btn-outline-light">YIL</button>
                    <div class="ms-auto">
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-calendar-alt"></i> 22 Nisan 2025 - 22 Temmuz 2025
                        </button>
                    </div>
                </div>
                <!-- Grafik alanı (şimdilik boş kutu) -->
                <div class="mb-4" style="background: #fff; border-radius: 8px; min-height: 260px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center;">
                    <span style="color: #bbb;">Burada grafik veya tablo yer alacak</span>
                </div>
                <!-- Tablo başlığı -->
                <div class="mb-2">
                    <h4 style="font-size: 20px; color: var(--text-primary); font-weight: 600;">22 Nisan 2025 - 22 Temmuz 2025 Arası Yapılan Tahsilat ve Ödemeler</h4>
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
                            <tr>
                                <td>Çalışana Ödeme</td>
                                <td>21 Temmuz 2025</td>
                                <td>miraç deprem</td>
                                <td>miraç deprem - Ödeme</td>
                                <td class="text-negative">-150.000,00₺</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/MutabikV2/dashboard/script2.js"></script>
</body>
</html> 