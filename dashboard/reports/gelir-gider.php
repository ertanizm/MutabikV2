<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Gelir Gider Raporu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        /* Gelir-Gider özel stil */
        .report-filter-bar {
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .report-filter-bar .form-select,
        .report-filter-bar .form-control {
            max-width: 200px;
        }
        .chart-placeholder {
            background-color: var(--card-bg);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 1.2rem;
            border: 1px dashed var(--border-color);
        }
        .report-table-section .table {
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden; /* Köşeleri yuvarlatmak için */
        }
        .report-table-section .table thead {
            background-color: var(--sidebar-bg);
            color: white;
        }
        .report-table-section .table th,
        .report-table-section .table td {
            vertical-align: middle;
        }
        .report-table-section .table tbody tr:nth-child(even) {
            background-color: #f0f2f5;
        }
        .report-table-section .table-bordered {
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-calculator"></i>
                Mutabık
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="../dashboard2.php" class="menu-item">
                <i class="fas fa-chart-line"></i>
                Güncel Durum
            </a>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="sales-submenu">
                    <i class="fas fa-arrow-down"></i>
                    SATIŞLAR
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="sales-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-alt"></i>
                        Teklifler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-invoice"></i>
                        Faturalar
                    </a>
                    <a href="../reports/musteriler.php" class="submenu-item">
                        <i class="fas fa-users"></i>
                        Müşteriler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Satışlar Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Tahsilatlar Raporu
                    </a>
                    <a href="../reports/gelir-gider.php" class="submenu-item active">
                        <i class="fas fa-chart-bar"></i>
                        Gelir Gider Raporu
                    </a>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="expenses-submenu">
                    <i class="fas fa-arrow-up"></i>
                    GİDERLER
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="expenses-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-alt"></i>
                        Gider Listesi
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        Tedarikçiler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-tie"></i>
                        Çalışanlar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Giderler Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Ödemeler Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        KDV Raporu
                    </a>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="cash-submenu">
                    <i class="fas fa-money-bill-wave"></i>
                    NAKİT
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="cash-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-university"></i>
                        Kasa ve Bankalar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Çekler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Kasa / Banka Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Nakit Akışı Raporu
                    </a>
                </div>
            </div>
            
            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="stock-submenu">
                    <i class="fas fa-boxes"></i>
                    STOK
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="stock-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-tags"></i>
                        Hizmet ve Ürünler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-map-marker-alt"></i>
                        Depolar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-exchange-alt"></i>
                        Depolar Arası Transfer
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        Giden İrsaliyeler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck fa-flip-horizontal"></i>
                        Gelen İrsaliyeler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-list"></i>
                        Fiyat Listeleri
                    </a>
                </div>
            </div>
            
            <div class="menu-separator"></div>
            
            <a href="#" class="menu-item">
                <i class="fas fa-star"></i>
                Uygulamalar
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                Pazaryeri
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-chevron-left"></i>
                Menüyü Sakla
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Ayarlar
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Gelir Gider Raporu</h1>
            </div>
            
            <div class="header-right">
                <button class="btn btn-success">
                    <i class="fas fa-gift"></i>
                    Tavsiye Et, Kazan!
                </button>
                
                <div class="user-info">
                    <div class="user-avatar">
                        MD
                    </div>
                    <div class="user-details">
                        <h6>Miraç Deprem</h6>
                        <small>Deprem Yazılım</small>
                    </div>
                </div>
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
    <script src="../script2.js"></script>
   
</body>
</html>