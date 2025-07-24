<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödemeler Raporu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">

    <style>
        /* Genel Sayfa Stilleri */
        :root {
            --page-bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --bar-bg: #e9ecef;

            /* Grafik Renkleri */
            --color-planned: #007bff;
            --color-current: #28a745;
            --color-late-1: #ffc107;
            /* 1-30 Gün */
            --color-late-2: #fd7e14;
            /* 31-60 Gün */
            --color-late-3: #dc3545;
            /* 61-90 Gün */
            --color-late-4: #b02a37;
            /* 91-120 Gün */
            --color-late-5: #8b222c;
            /* 120+ Gün */
        }

        /* Paylaştığınız CSS'den kopyalanmıştır. `dashboard.css` dosyanızda da bu stiller olmalı. */
        /* Eğer bu stiller `dashboard.css`'te varsa, <style> etiketi içinden kaldırabilirsiniz. */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            /* Varolan --page-bg değişkeniyle tutarlılık için */
            margin: 0;
            padding: 0;
            display: flex;
            /* Sidebar ve main-content'i yan yana dizmek için */
            min-height: 100vh;
            overflow-x: hidden;
            /* Yatay kaydırmayı engeller */
        }

        /* Sidebar Styles (dashboard.css'den kopyalandı) */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            /* Sabit sidebar için */
            height: 100%;
            left: 0;
            top: 0;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        /* Main Content Styles (dashboard.css'den kopyalandı) */
        .main-content {
            flex-grow: 1;
            /* Kalan tüm alanı kaplar */
            margin-left: 280px;
            /* Sidebar genişliği kadar boşluk bırakır */
            padding: 20px;
            /* İç dolgu */
            transition: margin-left 0.3s ease-in-out;
        }

        /* Responsive Styles (dashboard.css'den kopyalandı) */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
                /* Mobil görünümde dikey hizalama */
            }

            .sidebar {
                transform: translateX(-280px);
                /* Sidebar başlangıçta gizli */
                position: relative;
                /* Mobil görünümde akışta olması için */
                width: 100%;
                height: auto;
                /* Yüksekliği içeriğine göre ayarla */
                box-shadow: none;
                /* Mobil görünümde gölge kaldırılabilir */
            }

            .sidebar.show {
                transform: translateX(0);
                /* Sidebar açık */
                position: fixed;
                /* Açıkken sabit kalması için */
            }

            .main-content {
                margin-left: 0 !important;
                /* Mobil görünümde sol boşluğu sıfırla */
                padding: 15px;
            }
        }

        /* Sidebar daraltılmış stilinde main-content */
        .sidebar.collapsed~.main-content {
            margin-left: 60px;
            /* Daraltılmış sidebar genişliği kadar boşluk */
        }

        /* Ödemeler Raporu'na özgü diğer stiller */
        .container {
            max-width: 1200px;
            /* Bootstrap'in varsayılan max-width'ini ezer, eğer gerekliyse */
            /* margin: 0 auto; /* Eğer .main-content içindeyseniz, Bootstrap'in .container'ı bunu zaten yapacaktır */
        }

        .main-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 25px 0;
        }

        .payment-distribution-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: left;
        }

        .stat-item .value {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .stat-item .value.zero {
            color: var(--text-secondary);
        }

        .stat-item .label {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .bar-chart {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chart-bar-wrapper {
            width: 50px;
            height: 150px;
            background-color: var(--bar-bg);
            border-radius: 5px 5px 0 0;
            margin-top: 10px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .chart-bar {
            width: 100%;
            background-color: var(--text-secondary);
            height: 2px;
            transition: height 0.5s ease-out;
        }

        .chart-info .value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .chart-info .count {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .chart-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-top: 10px;
        }

        /* Grafik Renkleri */
        .bar-planned {
            background-color: var(--color-planned);
        }

        .bar-current {
            background-color: var(--color-current);
        }

        .bar-late-1 {
            background-color: var(--color-late-1);
        }

        .bar-late-2 {
            background-color: var(--color-late-2);
        }

        .bar-late-3 {
            background-color: var(--color-late-3);
        }

        .bar-late-4 {
            background-color: var(--color-late-4);
        }

        .bar-late-5 {
            background-color: var(--color-late-5);
        }

        /* Responsive Tasarım (Ödemeler Raporu'na özgü, mevcut stillerinizden) */
        @media (max-width: 992px) {
            .payment-distribution-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-notifications {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 480px) {
            .payment-distribution-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Gecikmiş Ödemeler Tablosu Stilleri */
        .table-responsive {
            margin-top: 20px;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .table-custom th,
        .table-custom td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom th {
            background-color: #f8f9fa;
            /* Hafif bir arka plan */
            color: var(--text-primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f3f5;
            /* Hover efekti */
        }

        .table-custom td {
            color: var(--text-primary);
            font-size: 15px;
        }

        .table-custom .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 13px;
        }

        .status-badge.overdue {
            background-color: #ffe0e6;
            color: #dc3545;
        }

        .status-badge.paid {
            background-color: #e6ffed;
            color: #28a745;
        }

        .status-badge.pending {
            background-color: #fff3cd;
            color: #ffc107;
        }
    </style>
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