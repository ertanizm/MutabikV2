<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödemeler Raporu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/giderler/odemeler_raporu.css" rel="stylesheet">
  
</head>

<body>

    <?php include __DIR__ . '/../sidebar.php'; ?>
    <div class="main-content">

        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Ödemeler Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="container">
            <main>
                

                <div class="card">
                    <h2 class="card-title">Ödemelerin Dağılımı</h2>

                    <div class="payment-distribution-grid">
                        <div class="stat-item">
                            <div class="value zero">0,00₺</div>
                            <div class="label">PLANLANMIŞ</div>
                        </div>
                        <div class="stat-item">
                            <div class="value zero">0,00₺</div>
                            <div class="label">VADESİ GEÇEN</div>
                        </div>
                        <div class="stat-item">
                            <div class="value zero">0,00₺</div>
                            <div class="label">TOPLAM ÖDEME</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">0 GÜN</div>
                            <div class="label">ORT. VADE AŞIMI</div>
                        </div>
                    </div>

                    <div class="charts-container">

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-planned" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">PLANLANMIŞ</div>
                        </div>

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-current" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">GÜNCEL</div>
                        </div>

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-late-1" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">1-30 GÜN GECİKMİŞ</div>
                        </div>

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-late-2" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">31-60 GÜN GECİKMİŞ</div>
                        </div>

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-late-3" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">61-90 GÜN GECİKMİŞ</div>
                        </div>

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-late-4" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">91-120 GÜN GECİKMİŞ</div>
                        </div>

                        <div class="bar-chart">
                            <div class="chart-info">
                                <div class="value">0,00₺</div>
                                <div class="count">0 Adet</div>
                            </div>
                            <div class="chart-bar-wrapper">
                                <div class="chart-bar bar-late-5" style="height: 2px;"></div>
                            </div>
                            <div class="chart-title">120+ GÜN GECİKMİŞ</div>
                        </div>

                    </div>
                </div>

                ---

                <div class="card">
                    <h2 class="card-title">Gecikmiş Ödemeler</h2>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Müşteri / Tedarikçi</th>
                                    <th>Açıklama</th>
                                    <th>Vade Tarihi</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>ABC Teknoloji</td>
                                    <td>Sunucu Kiralama</td>
                                    <td>2025-07-01</td>
                                    <td>5.000,00₺</td>
                                    <td><span class="status-badge overdue">Gecikmiş (20 Gün)</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>X YAPI İnşaat</td>
                                    <td>Malzeme Tedariği</td>
                                    <td>2025-06-15</td>
                                    <td>12.500,00₺</td>
                                    <td><span class="status-badge overdue">Gecikmiş (35 Gün)</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Z Lojistik</td>
                                    <td>Sevkiyat Ücreti</td>
                                    <td>2025-07-10</td>
                                    <td>1.200,00₺</td>
                                    <td><span class="status-badge overdue">Gecikmiş (10 Gün)</span></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Global Pazarlama</td>
                                    <td>Reklam Hizmeti</td>
                                    <td>2025-05-20</td>
                                    <td>8.000,00₺</td>
                                    <td><span class="status-badge overdue">Gecikmiş (62 Gün)</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary">Tüm Gecikmiş Ödemeleri Gör</button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
</body>

</html>