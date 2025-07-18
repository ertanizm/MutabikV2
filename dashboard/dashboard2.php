<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Ön Muhasebe Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --sidebar-bg: #34495e;
            --card-bg: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-color: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #2c3e50 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .logo i {
            background: linear-gradient(45deg, #e74c3c, #f39c12);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 10px;
            font-size: 28px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .menu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .menu-item .badge {
            margin-left: auto;
            background-color: var(--accent-color);
            font-size: 10px;
        }

        .menu-separator {
            height: 1px;
            background-color: rgba(255,255,255,0.1);
            margin: 10px 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            margin: 0;
            color: var(--text-primary);
            font-size: 28px;
            font-weight: 600;
        }

        .trial-info {
            color: var(--text-secondary);
            font-size: 14px;
            margin-top: 5px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .user-details h6 {
            margin: 0;
            color: var(--text-primary);
            font-size: 14px;
        }

        .user-details small {
            color: var(--text-secondary);
            font-size: 12px;
        }

        /* Financial Cards */
        .financial-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .financial-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .financial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .card-icon.income {
            background: linear-gradient(45deg, var(--success-color), #2ecc71);
        }

        .card-icon.expense {
            background: linear-gradient(45deg, var(--accent-color), #e67e22);
        }

        .card-icon.pending {
            background: linear-gradient(45deg, var(--warning-color), #f1c40f);
        }

        .card-amount {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .card-status {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .progress-ring {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            position: relative;
        }

        .progress-ring svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .progress-ring circle {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .progress-ring .bg {
            stroke: #ecf0f1;
        }

        .progress-ring .progress {
            stroke: var(--success-color);
            stroke-dasharray: 251.2;
            stroke-dashoffset: 251.2;
            transition: stroke-dashoffset 0.5s ease;
        }

        .status-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: var(--success-color);
        }

        /* Small Cards */
        .small-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .small-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .small-card:hover {
            transform: translateY(-3px);
        }

        .small-card i {
            font-size: 24px;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        .small-card .number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .small-card .label {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .cards-row {
                grid-template-columns: 1fr;
            }

            .small-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .submenu {
                padding-left: 15px;
            }

            .submenu-item {
                padding: 8px 15px;
                font-size: 13px;
            }
        }

        /* Toggle Button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }

        /* Submenu Styles */
        .menu-section {
            margin-bottom: 10px;
        }

        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-toggle:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .menu-toggle.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary-color);
        }

        .menu-toggle .toggle-icon {
            transition: transform 0.3s ease;
        }

        .menu-toggle.active .toggle-icon {
            transform: rotate(180deg);
        }

        .submenu {
            display: none;
            padding-left: 20px;
            border-left: 1px solid rgba(255,255,255,0.1);
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .submenu-item:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left: 3px solid var(--secondary-color);
        }

        .submenu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid var(--secondary-color);
        }

        .submenu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle for Mobile -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-calculator"></i>
                Mutabık
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="fas fa-chart-line"></i>
                Güncel Durum
            </a>
            
            <!-- SATIŞLAR Section -->
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
                    <a href="#" class="submenu-item">
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
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Gelir Gider Raporu
                    </a>
                </div>
            </div>
            
            <!-- GİDERLER Section -->
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
            
            <!-- NAKİT Section -->
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
            
            <!-- STOK Section -->
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

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Güncel Durum</h1>
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

        <!-- Tahsilatlar Section -->
        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-hand-holding-usd"></i>
                Tahsilatlar
            </h2>
            
            <div class="cards-row">
                <!-- Toplam Tahsilat -->
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Toplam Tahsilat Edilecek</div>
                        <div class="card-icon income">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Tahsilat Yok</div>
                    <div class="progress-ring">
                        <svg>
                            <circle class="bg" cx="40" cy="40" r="36"></circle>
                            <circle class="progress" cx="40" cy="40" r="36" style="stroke-dashoffset: 251.2;"></circle>
                        </svg>
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Gecikmiş Tahsilat -->
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Gecikmiş</div>
                        <div class="card-icon pending">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Tahsilat Yok</div>
                    <div class="progress-ring">
                        <svg>
                            <circle class="bg" cx="40" cy="40" r="36"></circle>
                            <circle class="progress" cx="40" cy="40" r="36" style="stroke-dashoffset: 0;"></circle>
                        </svg>
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Planlanmamış -->
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Planlanmamış</div>
                        <div class="card-icon expense">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Fatura Yok</div>
                    <div class="progress-ring">
                        <svg>
                            <circle class="bg" cx="40" cy="40" r="36"></circle>
                            <circle class="progress" cx="40" cy="40" r="36" style="stroke-dashoffset: 0;"></circle>
                        </svg>
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Small Cards for Tahsilatlar -->
            <div class="small-cards">
                <div class="small-card">
                    <i class="fas fa-print"></i>
                    <div class="number">0</div>
                    <div class="label">Yazdırılmamış</div>
                </div>
                
                <div class="small-card">
                    <i class="fas fa-redo"></i>
                    <div class="number">0</div>
                    <div class="label">Tekrarlayan</div>
                </div>
                
                <div class="small-card">
                    <i class="fas fa-file-invoice"></i>
                    <div class="number">0</div>
                    <div class="label">Bekleyen Fatura</div>
                </div>
                
                <div class="small-card">
                    <i class="fas fa-clock"></i>
                    <div class="number">0</div>
                    <div class="label">Vadesi Yaklaşan</div>
                </div>
            </div>
        </div>

        <!-- Ödemeler Section -->
        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-credit-card"></i>
                Ödemeler
            </h2>
            
            <div class="cards-row">
                <!-- Toplam Ödeme -->
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Toplam Ödenecek</div>
                        <div class="card-icon expense">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Ödeme Yok</div>
                    <div class="progress-ring">
                        <svg>
                            <circle class="bg" cx="40" cy="40" r="36"></circle>
                            <circle class="progress" cx="40" cy="40" r="36" style="stroke-dashoffset: 251.2;"></circle>
                        </svg>
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Gecikmiş Ödeme -->
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Gecikmiş</div>
                        <div class="card-icon pending">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Ödeme Yok</div>
                    <div class="progress-ring">
                        <svg>
                            <circle class="bg" cx="40" cy="40" r="36"></circle>
                            <circle class="progress" cx="40" cy="40" r="36" style="stroke-dashoffset: 0;"></circle>
                        </svg>
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Planlanmamış Ödeme -->
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Planlanmamış</div>
                        <div class="card-icon income">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Fatura Yok</div>
                    <div class="progress-ring">
                        <svg>
                            <circle class="bg" cx="40" cy="40" r="36"></circle>
                            <circle class="progress" cx="40" cy="40" r="36" style="stroke-dashoffset: 0;"></circle>
                        </svg>
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Small Cards for Ödemeler -->
            <div class="small-cards">
                <div class="small-card">
                    <i class="fas fa-percentage"></i>
                    <div class="number">₺0,00</div>
                    <div class="label">Bu Ay Oluşan KDV</div>
                    <small style="color: var(--text-secondary);">(₺0,00 Geçen Ay)</small>
                </div>
                
                <div class="small-card">
                    <i class="fas fa-redo"></i>
                    <div class="number">0</div>
                    <div class="label">Tekrarlayan</div>
                </div>
                
                <div class="small-card">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <div class="number">0</div>
                    <div class="label">Bekleyen Fatura</div>
                </div>
                
                <div class="small-card">
                    <i class="fas fa-calendar-alt"></i>
                    <div class="number">0</div>
                    <div class="label">Vadesi Yaklaşan</div>
                </div>
            </div>
        </div>

        <!-- Nakit Durumu Section -->
        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-wallet"></i>
                Nakit Durumu
            </h2>
            
            <div class="cards-row">
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Toplam Nakit</div>
                        <div class="card-icon income">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Güncel Durum</div>
                </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Bu Ay Gelir</div>
                        <div class="card-icon success">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Geçen Ay: ₺0,00</div>
                </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Bu Ay Gider</div>
                        <div class="card-icon expense">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺0,00</div>
                    <div class="card-status">Geçen Ay: ₺0,00</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Menu item click handler
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all menu items
                document.querySelectorAll('.menu-item').forEach(menuItem => {
                    menuItem.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Close sidebar on mobile after menu selection
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });

        // Submenu toggle handler
        document.querySelectorAll('.menu-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                const toggleIcon = this.querySelector('.toggle-icon');

                // Toggle submenu visibility
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';

                // Toggle arrow icon
                toggleIcon.classList.toggle('fa-chevron-down');
                toggleIcon.classList.toggle('fa-chevron-up');
            });
        });

        // Animate progress rings on page load
        window.addEventListener('load', function() {
            const progressRings = document.querySelectorAll('.progress-ring .progress');
            progressRings.forEach(ring => {
                const currentOffset = ring.style.strokeDashoffset;
                ring.style.strokeDashoffset = '251.2';
                
                setTimeout(() => {
                    ring.style.strokeDashoffset = currentOffset;
                }, 500);
            });
        });
    </script>
</body>
</html> 