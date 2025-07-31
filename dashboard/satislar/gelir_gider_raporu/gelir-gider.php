<?php
require_once __DIR__ . '/../../../config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Gelir Gider Raporu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../dashboard.css" rel="stylesheet">
    <link href="../../../assets/satislar/gelir_gider_raporu/gelir_gider.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Gelir Gider Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>
       
        <div class="report-filter-bar">
            <select class="form-select" aria-label="Dönem Seçimi">
                <option selected>Bu Ay</option>
                <option value="1">Geçen Ay</option>
                <option value="3">Son 3 Ay</option>
                <option value="6">Son 6 Ay</option>
                <option value="12">Bu Yıl</option>
                <option value="custom">Özel Tarih Aralığı</option>
            </select>
            <input type="date" class="form-control" placeholder="Başlangıç Tarihi">
            <input type="date" class="form-control" placeholder="Bitiş Tarihi">
            <button class="btn btn-primary">
                <i class="fas fa-filter"></i> Filtrele
            </button>
            <button class="btn btn-info text-white">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-chart-pie"></i>
                Genel Bakış
            </h2>
            <div class="cards-row">
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Toplam Gelir</div>
                        <div class="card-icon income">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺<?php echo number_format(15000.75, 2, ',', '.'); ?></div>
                    <div class="card-status">Bu Dönem</div>
                    </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Toplam Gider</div>
                        <div class="card-icon expense">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺<?php echo number_format(8000.50, 2, ',', '.'); ?></div>
                    <div class="card-status">Bu Dönem</div>
                </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Net Kar/Zarar</div>
                        <div class="card-icon success">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺<?php echo number_format(7000.25, 2, ',', '.'); ?></div>
                    <div class="card-status">Bu Dönem</div>
                </div>
            </div>
        </div>

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-chart-area"></i>
                Gelir Gider Grafiği
            </h2>
            <div class="chart-placeholder">
                <p>Gelir ve Gider verilerini gösteren interaktif grafik alanı (Chart.js veya ApexCharts gibi kütüphanelerle entegre edilebilir)</p>
            </div>
        </div>

        <div class="financial-section report-table-section">
            <h2 class="section-title">
                <i class="fas fa-arrow-down"></i>
                Gelir Detayları
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Açıklama</th>
                            <th>Kategori</th>
                            <th>Miktar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>15.07.2025</td>
                            <td>Web Sitesi Tasarım Hizmeti</td>
                            <td>Hizmet Satışı</td>
                            <td class="text-success">₺5.000,00</td>
                        </tr>
                        <tr>
                            <td>10.07.2025</td>
                            <td>Yazılım Lisans Satışı</td>
                            <td>Ürün Satışı</td>
                            <td class="text-success">₺7.500,00</td>
                        </tr>
                        <tr>
                            <td>01.07.2025</td>
                            <td>Danışmanlık Ücreti</td>
                            <td>Danışmanlık</td>
                            <td class="text-success">₺2.500,00</td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>

        <div class="financial-section report-table-section">
            <h2 class="section-title">
                <i class="fas fa-arrow-up"></i>
                Gider Detayları
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Açıklama</th>
                            <th>Kategori</th>
                            <th>Miktar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12.07.2025</td>
                            <td>Ofis Kirası</td>
                            <td>Sabit Giderler</td>
                            <td class="text-danger">₺3.000,00</td>
                        </tr>
                        <tr>
                            <td>08.07.2025</td>
                            <td>Yazılım Lisans Bedeli</td>
                            <td>İşletme Gideri</td>
                            <td class="text-danger">₺1.500,00</td>
                        </tr>
                        <tr>
                            <td>05.07.2025</td>
                            <td>Pazarlama Giderleri</td>
                            <td>Reklam</td>
                            <td class="text-danger">₺1.000,00</td>
                        </tr>
                        <tr>
                            <td>01.07.2025</td>
                            <td>İnternet Faturası</td>
                            <td>Fatura</td>
                            <td class="text-danger">₺500,00</td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
   
</body>
</html>