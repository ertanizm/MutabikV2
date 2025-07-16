<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clarity Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5A67D8;
            --primary-light: #7F9CF5;
            --secondary: #4A5568;
            --bg-light: #F7FAFC;
            --bg-dark: #1A202C;
            --text-color: #2D3748;
            --card-bg: #FFFFFF;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 75px;
            background-color: var(--card-bg);
            border-right: 1px solid #E2E8F0;
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: width 0.3s ease-in-out;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
        }

        .sidebar:hover {
            width: 220px;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 2rem;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .logo {
            opacity: 1;
        }

        .sidebar .menu-item {
            width: 100%;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            color: var(--secondary);
            text-decoration: none;
            transition: background-color 0.2s, color 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar .menu-item i {
            width: 24px;
            height: 24px;
            font-size: 1.25rem;
            text-align: center;
            margin-right: 0;
            transition: margin-right 0.3s ease-in-out;
        }

        .sidebar:hover .menu-item i {
            margin-right: 1rem;
        }

        .sidebar .menu-item.active,
        .sidebar .menu-item:hover {
            background-color: #E2E8F0;
            color: var(--primary);
        }

        .sidebar .menu-item span {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar:hover .menu-item span {
            opacity: 1;
        }

        .main-content-wrapper {
            margin-left: 75px;
            flex: 1;
            padding: 1.5rem;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar:hover ~ .main-content-wrapper {
            margin-left: 220px;
        }
        
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar {
            background-color: var(--card-bg);
            border-bottom: 1px solid #E2E8F0;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .search-box {
            position: relative;
            width: 300px;
        }

        .navbar .search-box input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #CBD5E0;
            border-radius: 9999px;
            background-color: var(--bg-light);
        }

        .navbar .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }

        .nav-icons .btn-icon {
            color: var(--secondary);
            font-size: 1.25rem;
        }

        .card-summary {
            border-radius: 0.75rem;
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-summary h5 {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }
        .card-summary .value {
            font-size: 2.25rem;
            font-weight: 700;
        }
        .card-summary.income { background-image: linear-gradient(to right top, #2d7334, #2da275); }
        .card-summary.expense { background-image: linear-gradient(to right top, #cd373f, #c74577); }
        .card-summary.receivable { background-image: linear-gradient(to right top, #48bb78, #2a945d); }
        .card-summary.payable { background-image: linear-gradient(to right top, #F6AD55, #ED8936); }
        .card-summary.balance { background-image: linear-gradient(to right top, #5A67D8, #7F9CF5); }
        .card-summary.primary-card { background-image: linear-gradient(to right top, #5A67D8, #7F9CF5); }
        .card-summary.green-card { background-image: linear-gradient(to right top, #48bb78, #2a945d); }
        .card-summary.orange-card { background-image: linear-gradient(to right top, #F6AD55, #ED8936); }
        .card-summary.red-card { background-image: linear-gradient(to right top, #F56565, #E53E3E); }
        .card-summary .card-icon {
            position: absolute;
            bottom: -15px;
            right: -15px;
            font-size: 5rem;
            opacity: 0.1;
        }

        .dashboard-card {
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: var(--bg-dark);
            color: #E2E8F0;
        }
        body.dark-mode .sidebar,
        body.dark-mode .navbar,
        body.dark-mode .dashboard-card {
            background-color: #2D3748;
            border-color: #4A5568;
            color: #E2E8F0;
        }
        body.dark-mode .sidebar .menu-item {
            color: #A0AEC0;
        }
        body.dark-mode .sidebar .menu-item.active,
        body.dark-mode .sidebar .menu-item:hover {
            background-color: #4A5568;
            color: var(--primary-light);
        }
        body.dark-mode .navbar .search-box input {
            background-color: #4A5568;
            border-color: #4A5568;
            color: #E2E8F0;
        }
        body.dark-mode .table,
        body.dark-mode .table thead,
        body.dark-mode .list-group-item {
            background-color: transparent !important;
            color: #E2E8F0;
        }
        body.dark-mode .table-hover tbody tr:hover {
            background-color: #4A5568 !important;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="#" class="logo d-none d-md-block">Clarity</a>
            <div class="d-flex flex-column align-items-center mt-3" style="width:100%;">
                <a href="#" class="menu-item active" data-tab="dashboard">
                    <i class="fas fa-home"></i>
                    <span class="ms-3">Dashboard</span>
                </a>
                <a href="#" class="menu-item" data-tab="accounts">
                    <i class="fas fa-users"></i>
                    <span class="ms-3">Cari Hesaplar</span>
                </a>
                <a href="#" class="menu-item" data-tab="invoices">
                    <i class="fas fa-file-invoice"></i>
                    <span class="ms-3">Faturalar</span>
                </a>
                <a href="#" class="menu-item" data-tab="stock">
                    <i class="fas fa-boxes"></i>
                    <span class="ms-3">Stok Yönetimi</span>
                </a>
                <a href="#" class="menu-item" data-tab="reports">
                    <i class="fas fa-chart-line"></i>
                    <span class="ms-3">Raporlar</span>
                </a>
                <a href="#" class="menu-item" data-tab="settings">
                    <i class="fas fa-cog"></i>
                    <span class="ms-3">Ayarlar</span>
                </a>
            </div>
        </div>

        <div class="main-content-wrapper">
            <div class="main-content">
                <nav class="navbar d-flex justify-content-between align-items-center rounded-pill px-4 shadow-sm">
                    <h2 class="fs-4 m-0 fw-bold">Dashboard</h2>
                    <div class="d-flex align-items-center gap-3">
                        <div class="search-box d-none d-md-block">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="Ara...">
                        </div>
                        <div class="nav-icons d-flex gap-3">
                            <button class="btn btn-link p-0 position-relative" id="notificationBtn"><i class="fas fa-bell btn-icon"></i><span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">3</span></button>
                            <button class="btn btn-link p-0" id="themeToggle"><i class="fas fa-sun btn-icon"></i></button>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><h6 class="dropdown-header">Ahmet Yılmaz</h6></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>Profilim</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fas fa-cog me-2"></i>Ayarlar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div id="dashboard" class="tab-section active">
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary balance">
                                <i class="fas fa-wallet card-icon"></i>
                                <h5 class="fw-normal">Net Bakiye</h5>
                                <div class="value">₺ 62.500</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary receivable">
                                <i class="fas fa-arrow-down card-icon"></i>
                                <h5 class="fw-normal">Toplam Alacaklar</h5>
                                <div class="value">₺ 145.000</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary payable">
                                <i class="fas fa-arrow-up card-icon"></i>
                                <h5 class="fw-normal">Toplam Borçlar</h5>
                                <div class="value">₺ 82.500</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary income">
                                <i class="fas fa-money-bill-wave card-icon"></i>
                                <h5 class="fw-normal">Bu Ayki Tahsilat</h5>
                                <div class="value">₺ 21.300</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-lg-7">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Finansal Akış (Son 6 Ay)</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="financialFlowChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Kritik Uyarılar</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Vadesi geçmiş 2 fatura var!
                                        <span class="badge bg-danger rounded-pill">2</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Stokta kritik seviyede olan 3 ürün var.
                                        <span class="badge bg-warning text-dark rounded-pill">3</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Tekno Bilgisayar için ödeme yaklaşıyor.
                                        <span class="badge bg-info rounded-pill">1</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Bu ay 5 yeni cari eklendi.
                                        <span class="badge bg-success rounded-pill">5</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-12">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Son İşlemler</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tarih</th>
                                                <th>İşlem Tipi</th>
                                                <th>Cari</th>
                                                <th>Tutar</th>
                                                <th>Durum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>15 Tem 2025</td>
                                                <td>Tahsilat</td>
                                                <td>Alpha Yazılım</td>
                                                <td>₺ 5.000</td>
                                                <td><span class="badge bg-success">Tamamlandı</span></td>
                                            </tr>
                                            <tr>
                                                <td>14 Tem 2025</td>
                                                <td>Ödeme</td>
                                                <td>Ofis Market</td>
                                                <td>₺ 1.200</td>
                                                <td><span class="badge bg-danger">Yapıldı</span></td>
                                            </tr>
                                            <tr>
                                                <td>13 Tem 2025</td>
                                                <td>Fatura Kesimi</td>
                                                <td>Beta Ltd.</td>
                                                <td>₺ 8.500</td>
                                                <td><span class="badge bg-warning text-dark">Bekliyor</span></td>
                                            </tr>
                                            <tr>
                                                <td>12 Tem 2025</td>
                                                <td>Stok Girişi</td>
                                                <td>Tekno Bilgisayar</td>
                                                <td>₺ 10.000</td>
                                                <td><span class="badge bg-info">Tamamlandı</span></td>
                                            </tr>
                                            <tr>
                                                <td>11 Tem 2025</td>
                                                <td>Tahsilat</td>
                                                <td>Gamma A.Ş.</td>
                                                <td>₺ 3.000</td>
                                                <td><span class="badge bg-success">Tamamlandı</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="accounts" class="tab-section d-none">
                    <h2 class="fs-4 m-0 fw-bold mb-4">Cari Hesaplar</h2>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary primary-card">
                                <i class="fas fa-users card-icon"></i>
                                <h5 class="fw-normal">Toplam Cari</h5>
                                <div class="value">250</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary green-card">
                                <i class="fas fa-user-plus card-icon"></i>
                                <h5 class="fw-normal">Yeni Cari (Bu Ay)</h5>
                                <div class="value">15</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary orange-card">
                                <i class="fas fa-user-check card-icon"></i>
                                <h5 class="fw-normal">Aktif Cari</h5>
                                <div class="value">210</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-lg-6">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Aktif / Pasif Cari Durumu</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="activePassiveChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Cari Türüne Göre Dağılım</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="accountTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mt-4">
                        <div class="col-12">
                            <div class="dashboard-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="m-0">Cari Listesi</h5>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newAccountModal"><i class="fas fa-plus me-2"></i>Yeni Cari Ekle</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Cari Kodu</th>
                                                <th>Cari Adı</th>
                                                <th>Tipi</th>
                                                <th>Son İşlem</th>
                                                <th>Bakiye</th>
                                                <th>Durum</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="accountTableBody">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="invoices" class="tab-section d-none">
                    <h2 class="fs-4 m-0 fw-bold mb-4">Faturalar</h2>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary primary-card">
                                <i class="fas fa-file-invoice card-icon"></i>
                                <h5 class="fw-normal">Toplam Fatura</h5>
                                <div class="value">85</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary green-card">
                                <i class="fas fa-check-circle card-icon"></i>
                                <h5 class="fw-normal">Ödenmiş</h5>
                                <div class="value">68</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary orange-card">
                                <i class="fas fa-clock card-icon"></i>
                                <h5 class="fw-normal">Ödenmemiş</h5>
                                <div class="value">15</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-summary red-card">
                                <i class="fas fa-exclamation-triangle card-icon"></i>
                                <h5 class="fw-normal">Gecikmiş</h5>
                                <div class="value">2</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-lg-7">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Son 6 Aylık Fatura Miktarı</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="invoiceAmountChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Fatura Durumları</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="invoiceStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mt-4">
                        <div class="col-12">
                            <div class="dashboard-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="m-0">Fatura Listesi</h5>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newInvoiceModal"><i class="fas fa-plus me-2"></i>Yeni Fatura Ekle</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Fatura No</th>
                                                <th>Cari Adı</th>
                                                <th>Tarih</th>
                                                <th>Vade Tarihi</th>
                                                <th>Tutar</th>
                                                <th>Durum</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="invoiceTableBody">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="stock" class="tab-section d-none">
                    <h2 class="fs-4 m-0 fw-bold mb-4">Stok Yönetimi</h2>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary primary-card">
                                <i class="fas fa-boxes card-icon"></i>
                                <h5 class="fw-normal">Toplam Ürün Sayısı</h5>
                                <div class="value">125</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary red-card">
                                <i class="fas fa-exclamation-circle card-icon"></i>
                                <h5 class="fw-normal">Kritik Stok</h5>
                                <div class="value">5</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary green-card">
                                <i class="fas fa-dollar-sign card-icon"></i>
                                <h5 class="fw-normal">Stok Değeri</h5>
                                <div class="value">₺ 350.000</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-4">
                        <div class="col-lg-7">
                            <div class="dashboard-card">
                                <h5 class="mb-4">En Çok Satan Ürünler</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="bestSellingChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Kategoriye Göre Stok Dağılımı</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="stockCategoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-4">
                        <div class="col-12">
                            <div class="dashboard-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="m-0">Stok Listesi</h5>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newProductModal"><i class="fas fa-plus me-2"></i>Yeni Ürün Ekle</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Ürün Kodu</th>
                                                <th>Ürün Adı</th>
                                                <th>Kategori</th>
                                                <th>Miktar</th>
                                                <th>Birim Fiyatı</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="stockTableBody">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="reports" class="tab-section d-none">
                    <h2 class="fs-4 m-0 fw-bold mb-4">Raporlar</h2>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary green-card">
                                <i class="fas fa-hand-holding-usd card-icon"></i>
                                <h5 class="fw-normal">Toplam Gelir</h5>
                                <div class="value">₺ 145.000</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary red-card">
                                <i class="fas fa-coins card-icon"></i>
                                <h5 class="fw-normal">Toplam Gider</h5>
                                <div class="value">₺ 82.500</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card-summary primary-card">
                                <i class="fas fa-chart-bar card-icon"></i>
                                <h5 class="fw-normal">Net Kâr</h5>
                                <div class="value">₺ 62.500</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-4">
                        <div class="col-lg-7">
                            <div class="dashboard-card">
                                <h5 class="mb-4">Gelir & Gider Trendi (Son 6 Ay)</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="revenueExpenseChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="dashboard-card">
                                <h5 class="mb-4">En Çok Fatura Kesilen Müşteriler</h5>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="topCustomersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-4">
                        <div class="col-12">
                            <div class="dashboard-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="m-0">En Son Oluşturulan Raporlar</h5>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal"><i class="fas fa-plus me-2"></i>Yeni Rapor Oluştur</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Rapor Adı</th>
                                                <th>Tipi</th>
                                                <th>Tarih</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Temmuz 2025 Gelir-Gider Özeti</td>
                                                <td>Finansal</td>
                                                <td>15.07.2025</td>
                                                <td>
                                                    <button class="btn btn-sm btn-secondary me-2" onclick="downloadReport('Temmuz 2025 Gelir-Gider Özeti')"><i class="fas fa-file-download"></i></button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewReport('Temmuz 2025 Gelir-Gider Özeti')"><i class="fas fa-eye"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Stok Seviye Raporu</td>
                                                <td>Stok</td>
                                                <td>14.07.2025</td>
                                                <td>
                                                    <button class="btn btn-sm btn-secondary me-2" onclick="downloadReport('Stok Seviye Raporu')"><i class="fas fa-file-download"></i></button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewReport('Stok Seviye Raporu')"><i class="fas fa-eye"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Cari Hesap Bakiyeleri</td>
                                                <td>Cari</td>
                                                <td>13.07.2025</td>
                                                <td>
                                                    <button class="btn btn-sm btn-secondary me-2" onclick="downloadReport('Cari Hesap Bakiyeleri')"><i class="fas fa-file-download"></i></button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewReport('Cari Hesap Bakiyeleri')"><i class="fas fa-eye"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="settings" class="tab-section d-none">
                    <h2 class="fs-4 m-0 fw-bold mb-4">Ayarlar</h2>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="dashboard-card">
                                <h5 class="d-flex align-items-center gap-2 mb-4"><i class="fas fa-sliders-h text-primary"></i> Genel Ayarlar</h5>
                                <form id="generalSettingsForm">
                                    <div class="mb-3">
                                        <label for="companyName" class="form-label">Firma Adı</label>
                                        <input type="text" class="form-control" id="companyName" value="Clarity Yazılım">
                                    </div>
                                    <div class="mb-3">
                                        <label for="companyAddress" class="form-label">Adres</label>
                                        <textarea class="form-control" id="companyAddress" rows="3">Örnek Mah. Örnek Cad. No: 123, Örnek İlçe, Örnek İl</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="currency" class="form-label">Varsayılan Para Birimi</label>
                                        <select class="form-select" id="currency">
                                            <option>₺ - Türk Lirası</option>
                                            <option>$ - Amerikan Doları</option>
                                            <option>€ - Euro</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="saveGeneralSettings()">Değişiklikleri Kaydet</button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="dashboard-card">
                                <h5 class="d-flex align-items-center gap-2 mb-4"><i class="fas fa-user-shield text-primary"></i> Kullanıcı Yönetimi</h5>
                                <p class="text-muted">Kullanıcıları ve yetkilerini yönetin.</p>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kullanıcı Adı</th>
                                                <th>Rol</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTableBody">
                                            <tr>
                                                <td>admin</td>
                                                <td>Yönetici</td>
                                                <td><button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-name="admin" data-user-role="Yönetici">Düzenle</button></td>
                                            </tr>
                                            <tr>
                                                <td>ahmet.yılmaz</td>
                                                <td>Personel</td>
                                                <td><button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-name="ahmet.yılmaz" data-user-role="Personel">Düzenle</button></td>
                                            </tr>
                                            <tr>
                                                <td>ayse.demir</td>
                                                <td>Personel</td>
                                                <td><button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-name="ayse.demir" data-user-role="Personel">Düzenle</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-user-plus me-2"></i>Yeni Kullanıcı Ekle</button>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="dashboard-card">
                                <h5 class="d-flex align-items-center gap-2 mb-4"><i class="fas fa-bell text-primary"></i> Bildirim Ayarları</h5>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="invoiceNotifications" checked>
                                    <label class="form-check-label" for="invoiceNotifications">
                                        Yeni fatura oluşturulduğunda e-posta gönder
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="stockAlerts" checked>
                                    <label class="form-check-label" for="stockAlerts">
                                        Stok kritik seviyenin altına düştüğünde bildirim al
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="paymentReminders">
                                    <label class="form-check-label" for="paymentReminders">
                                        Vadesi yaklaşan ödemeler için hatırlatıcı gönder
                                    </label>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="saveNotificationSettings()">Ayarları Kaydet</button>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="dashboard-card">
                                <h5 class="d-flex align-items-center gap-2 mb-4"><i class="fas fa-database text-primary"></i> Veri Yönetimi</h5>
                                <p>Tüm verilerinizi yedeklemek veya dışa aktarmak için aşağıdaki seçenekleri kullanın.</p>
                                <button class="btn btn-secondary me-2" onclick="exportData('csv')"><i class="fas fa-file-csv me-2"></i> CSV Olarak Aktar</button>
                                <button class="btn btn-secondary me-2" onclick="exportData('excel')"><i class="fas fa-file-excel me-2"></i> Excel Olarak Aktar</button>
                                <button class="btn btn-danger" onclick="confirmBackup()"><i class="fas fa-hdd me-2"></i> Veritabanı Yedeklemesi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Yeni Kullanıcı Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="newUsername" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="newUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserRole" class="form-label">Rol</label>
                            <select class="form-select" id="newUserRole">
                                <option>Yönetici</option>
                                <option>Personel</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" onclick="addNewUser()">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Kullanıcı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="editUsername" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Rol</label>
                            <select class="form-select" id="editUserRole">
                                <option>Yönetici</option>
                                <option>Personel</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="changePasswordCheck">
                            <label class="form-check-label" for="changePasswordCheck">Şifreyi değiştir</label>
                        </div>
                        <div class="mb-3" id="passwordFields" style="display: none;">
                            <label for="editPassword" class="form-label">Yeni Şifre</label>
                            <input type="password" class="form-control" id="editPassword">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="newAccountModal" tabindex="-1" aria-labelledby="newAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAccountModalLabel">Yeni Cari Hesap Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="accountName" class="form-label">Cari Adı</label>
                            <input type="text" class="form-control" id="accountName" placeholder="Örn: Xyz Ltd. Şti.">
                        </div>
                        <div class="mb-3">
                            <label for="accountType" class="form-label">Cari Tipi</label>
                            <select class="form-select" id="accountType">
                                <option selected>Seçiniz...</option>
                                <option value="Müşteri">Müşteri</option>
                                <option value="Tedarikçi">Tedarikçi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="accountStatus" class="form-label">Durum</label>
                            <select class="form-select" id="accountStatus">
                                <option selected>Seçiniz...</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Pasif">Pasif</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewAccountModal" tabindex="-1" aria-labelledby="viewAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAccountModalLabel">Cari Hesap Detayları</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Cari Kodu:</strong> <span id="viewCode"></span></li>
                        <li class="list-group-item"><strong>Cari Adı:</strong> <span id="viewName"></span></li>
                        <li class="list-group-item"><strong>Tipi:</strong> <span id="viewType"></span></li>
                        <li class="list-group-item"><strong>Bakiye:</strong> <span id="viewBalance"></span></li>
                        <li class="list-group-item"><strong>Durum:</strong> <span id="viewStatus"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel">Cari Hesabı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="editCode">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Cari Adı</label>
                            <input type="text" class="form-control" id="editName">
                        </div>
                        <div class="mb-3">
                            <label for="editType" class="form-label">Cari Tipi</label>
                            <select class="form-select" id="editType">
                                <option value="Müşteri">Müşteri</option>
                                <option value="Tedarikçi">Tedarikçi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Durum</label>
                            <select class="form-select" id="editStatus">
                                <option value="Aktif">Aktif</option>
                                <option value="Pasif">Pasif</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Silme Onayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bu cari hesabı (<span id="deleteAccountName" class="fw-bold"></span>) silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-danger">Sil</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="newInvoiceModal" tabindex="-1" aria-labelledby="newInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newInvoiceModalLabel">Yeni Fatura Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="invoiceNumber" class="form-label">Fatura Numarası</label>
                            <input type="text" class="form-control" id="invoiceNumber" placeholder="Örn: 20250001">
                        </div>
                        <div class="mb-3">
                            <label for="invoiceAccount" class="form-label">Cari Hesap</label>
                            <select class="form-select" id="invoiceAccount">
                                <option selected>Seçiniz...</option>
                                <option value="Alpha Yazılım Ltd.">Alpha Yazılım Ltd.</option>
                                <option value="Ofis Market A.Ş.">Ofis Market A.Ş.</option>
                                <option value="Gamma İnşaat">Gamma İnşaat</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="invoiceDate" class="form-label">Tarih</label>
                            <input type="date" class="form-control" id="invoiceDate">
                        </div>
                        <div class="mb-3">
                            <label for="dueDate" class="form-label">Vade Tarihi</label>
                            <input type="date" class="form-control" id="dueDate">
                        </div>
                        <div class="mb-3">
                            <label for="invoiceAmount" class="form-label">Tutar (₺)</label>
                            <input type="number" class="form-control" id="invoiceAmount" placeholder="Örn: 1500.00">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewInvoiceModalLabel">Fatura Detayları</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Fatura No:</strong> <span id="viewInvoiceNumber"></span></li>
                        <li class="list-group-item"><strong>Cari Adı:</strong> <span id="viewInvoiceAccount"></span></li>
                        <li class="list-group-item"><strong>Tarih:</strong> <span id="viewInvoiceDate"></span></li>
                        <li class="list-group-item"><strong>Vade Tarihi:</strong> <span id="viewInvoiceDueDate"></span></li>
                        <li class="list-group-item"><strong>Tutar:</strong> <span id="viewInvoiceAmount"></span></li>
                        <li class="list-group-item"><strong>Durum:</strong> <span id="viewInvoiceStatus"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInvoiceModalLabel">Faturayı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="editInvoiceCode">
                        <div class="mb-3">
                            <label for="editInvoiceNumber" class="form-label">Fatura Numarası</label>
                            <input type="text" class="form-control" id="editInvoiceNumber">
                        </div>
                        <div class="mb-3">
                            <label for="editInvoiceAccount" class="form-label">Cari Hesap</label>
                            <select class="form-select" id="editInvoiceAccount">
                                <option value="Alpha Yazılım Ltd.">Alpha Yazılım Ltd.</option>
                                <option value="Ofis Market A.Ş.">Ofis Market A.Ş.</option>
                                <option value="Gamma İnşaat">Gamma İnşaat</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editInvoiceDate" class="form-label">Tarih</label>
                            <input type="date" class="form-control" id="editInvoiceDate">
                        </div>
                        <div class="mb-3">
                            <label for="editInvoiceDueDate" class="form-label">Vade Tarihi</label>
                            <input type="date" class="form-control" id="editInvoiceDueDate">
                        </div>
                        <div class="mb-3">
                            <label for="editInvoiceAmount" class="form-label">Tutar (₺)</label>
                            <input type="number" class="form-control" id="editInvoiceAmount">
                        </div>
                        <div class="mb-3">
                            <label for="editInvoiceStatus" class="form-label">Durum</label>
                            <select class="form-select" id="editInvoiceStatus">
                                <option value="Ödenmiş">Ödenmiş</option>
                                <option value="Ödenmemiş">Ödenmemiş</option>
                                <option value="Gecikmiş">Gecikmiş</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteInvoiceModal" tabindex="-1" aria-labelledby="deleteInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteInvoiceModalLabel">Silme Onayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bu faturayı (<span id="deleteInvoiceNumber" class="fw-bold"></span>) silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-danger">Sil</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProductModalLabel">Yeni Ürün Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="productCode" class="form-label">Ürün Kodu</label>
                            <input type="text" class="form-control" id="productCode" placeholder="Örn: PR-001">
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="productName" placeholder="Örn: Kablosuz Klavye">
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="productCategory">
                                <option selected>Seçiniz...</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Ofis Malzemeleri">Ofis Malzemeleri</option>
                                <option value="Yazılım">Yazılım</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Miktar</label>
                            <input type="number" class="form-control" id="productQuantity" placeholder="Örn: 50">
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Birim Fiyatı (₺)</label>
                            <input type="number" class="form-control" id="productPrice" placeholder="Örn: 250.00">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProductModalLabel">Ürün Detayları</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Ürün Kodu:</strong> <span id="viewProductCode"></span></li>
                        <li class="list-group-item"><strong>Ürün Adı:</strong> <span id="viewProductName"></span></li>
                        <li class="list-group-item"><strong>Kategori:</strong> <span id="viewProductCategory"></span></li>
                        <li class="list-group-item"><strong>Miktar:</strong> <span id="viewProductQuantity"></span></li>
                        <li class="list-group-item"><strong>Birim Fiyatı:</strong> <span id="viewProductPrice"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Ürünü Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="editProductCode">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="editProductName">
                        </div>
                        <div class="mb-3">
                            <label for="editProductCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="editProductCategory">
                                <option value="Elektronik">Elektronik</option>
                                <option value="Ofis Malzemeleri">Ofis Malzemeleri</option>
                                <option value="Yazılım">Yazılım</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editProductQuantity" class="form-label">Miktar</label>
                            <input type="number" class="form-control" id="editProductQuantity">
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Birim Fiyatı (₺)</label>
                            <input type="number" class="form-control" id="editProductPrice">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Silme Onayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bu ürünü (<span id="deleteProductName" class="fw-bold"></span>) silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-danger">Sil</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewReportModal" tabindex="-1" aria-labelledby="viewReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewReportModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="reportContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Yeni Rapor Oluştur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createReportForm">
                        <div class="mb-3">
                            <label for="reportType" class="form-label">Rapor Tipi</label>
                            <select class="form-select" id="reportType" required>
                                <option selected disabled value="">Seçiniz...</option>
                                <option value="finansal">Finansal Rapor</option>
                                <option value="stok">Stok Raporu</option>
                                <option value="cari">Cari Hesap Raporu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Başlangıç Tarihi</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">Bitiş Tarihi</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" onclick="handleCreateReport()">Oluştur</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function downloadReport(reportName) {
            alert(reportName + ' raporu indiriliyor...');
        }

        function viewReport(reportName) {
            const reportModal = new bootstrap.Modal(document.getElementById('viewReportModal'));
            const reportTitle = document.getElementById('viewReportModalLabel');
            const reportContent = document.getElementById('reportContent');

            reportTitle.textContent = reportName;
            reportContent.innerHTML = ''; // Önceki içeriği temizle

            let contentHtml = '';
            if (reportName.includes('Gelir-Gider Özeti')) {
                contentHtml = `
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card-summary green-card mb-3">
                                <h5 class="fw-normal">Toplam Gelir</h5>
                                <div class="value">₺ 21.300</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-summary red-card mb-3">
                                <h5 class="fw-normal">Toplam Gider</h5>
                                <div class="value">₺ 11.000</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-summary primary-card mb-3">
                                <h5 class="fw-normal">Net Kar</h5>
                                <div class="value">₺ 10.300</div>
                            </div>
                        </div>
                    </div>
                    <h6>Detaylı Gelir Listesi</h6>
                    <table class="table table-striped table-hover mb-4">
                        <thead>
                            <tr>
                                <th>Kaynak</th>
                                <th>Tarih</th>
                                <th>Tutar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Alpha Yazılım Tahsilatı</td><td>15.07.2025</td><td>₺ 5.000</td></tr>
                            <tr><td>Gamma A.Ş. Tahsilatı</td><td>11.07.2025</td><td>₺ 3.000</td></tr>
                            <tr><td>İnternet Satışı</td><td>10.07.2025</td><td>₺ 13.300</td></tr>
                        </tbody>
                    </table>
                    <h6>Detaylı Gider Listesi</h6>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kalem</th>
                                <th>Tarih</th>
                                <th>Tutar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Ofis Market Ödemesi</td><td>14.07.2025</td><td>₺ 1.200</td></tr>
                            <tr><td>Personel Maaşı</td><td>05.07.2025</td><td>₺ 8.000</td></tr>
                            <tr><td>Elektrik Faturası</td><td>01.07.2025</td><td>₺ 1.800</td></tr>
                        </tbody>
                    </table>
                `;
            } else if (reportName.includes('Stok Seviye Raporu')) {
                contentHtml = `
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card-summary primary-card mb-3">
                                <h5 class="fw-normal">Toplam Ürün</h5>
                                <div class="value">125</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-summary red-card mb-3">
                                <h5 class="fw-normal">Kritik Seviyedeki Ürün</h5>
                                <div class="value">5</div>
                            </div>
                        </div>
                    </div>
                    <h6>Kritik Stok Seviyesindeki Ürünler</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Ürün Kodu</th>
                                    <th>Ürün Adı</th>
                                    <th>Kategori</th>
                                    <th>Miktar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>EL-005</td><td>Monitör</td><td>Elektronik</td><td>5</td></tr>
                                <tr><td>OF-012</td><td>Kartuş</td><td>Ofis Malzemeleri</td><td>2</td></tr>
                                <tr><td>EL-015</td><td>Yazıcı</td><td>Elektronik</td><td>3</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <h6>Tüm Ürünlerin Stok Listesi</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Ürün Kodu</th>
                                    <th>Ürün Adı</th>
                                    <th>Kategori</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyatı</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>PR-001</td><td>Gaming Mouse</td><td>Elektronik</td><td>15</td><td>₺ 750</td></tr>
                                <tr><td>PR-002</td><td>Kablosuz Klavye</td><td>Elektronik</td><td>50</td><td>₺ 250</td></tr>
                                <tr><td>PR-003</td><td>A4 Kağıt Paketi</td><td>Ofis Malzemeleri</td><td>250</td><td>₺ 85</td></tr>
                                <tr><td>PR-004</td><td>Web Tasarım Yazılımı</td><td>Yazılım</td><td>10</td><td>₺ 5.000</td></tr>
                                <tr><td>PR-005</td><td>Monitör</td><td>Elektronik</td><td>5</td><td>₺ 3.200</td></tr>
                            </tbody>
                        </table>
                    </div>
                `;
            } else if (reportName.includes('Cari Hesap Bakiyeleri')) {
                contentHtml = `
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card-summary green-card mb-3">
                                <h5 class="fw-normal">Toplam Alacak</h5>
                                <div class="value">₺ 145.000</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-summary red-card mb-3">
                                <h5 class="fw-normal">Toplam Borç</h5>
                                <div class="value">₺ 82.500</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-summary primary-card mb-3">
                                <h5 class="fw-normal">Net Bakiye</h5>
                                <div class="value">₺ 62.500</div>
                            </div>
                        </div>
                    </div>
                    <h6>Detaylı Cari Hesap Bakiyeleri</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cari Adı</th>
                                    <th>Tipi</th>
                                    <th>Bakiye</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Alpha Yazılım Ltd.</td><td>Müşteri</td><td><span class="text-success fw-bold">+₺ 12.500</span></td><td><span class="badge bg-success">Aktif</span></td></tr>
                                <tr><td>Ofis Market A.Ş.</td><td>Tedarikçi</td><td><span class="text-danger fw-bold">-₺ 5.300</span></td><td><span class="badge bg-danger">Aktif</span></td></tr>
                                <tr><td>Gamma İnşaat</td><td>Müşteri</td><td><span class="text-success fw-bold">+₺ 8.200</span></td><td><span class="badge bg-success">Aktif</span></td></tr>
                                <tr><td>Hızlı Kargo Lojistik</td><td>Tedarikçi</td><td><span class="text-danger fw-bold">-₺ 1.500</span></td><td><span class="badge bg-danger">Pasif</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                contentHtml = `<p>Bu rapor için içerik bulunamadı.</p>`;
            }

            reportContent.innerHTML = contentHtml;
            reportModal.show();
        }

        function handleCreateReport() {
            const reportType = document.getElementById('reportType').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!reportType || !startDate || !endDate) {
                alert('Lütfen tüm alanları doldurun.');
                return;
            }

            // Simulate report creation
            console.log(`Yeni rapor oluşturuluyor: Tip: ${reportType}, Başlangıç: ${startDate}, Bitiş: ${endDate}`);
            alert(`"${reportType.charAt(0).toUpperCase() + reportType.slice(1)} Raporu" başarıyla oluşturuldu!`);
            
            // Close the modal
            const createReportModal = bootstrap.Modal.getInstance(document.getElementById('createReportModal'));
            createReportModal.hide();
        }

        // Yeni Ayarlar Fonksiyonları
        function saveGeneralSettings() {
            alert('Genel ayarlar kaydedildi!');
        }

        function saveNotificationSettings() {
            alert('Bildirim ayarları kaydedildi!');
        }
        
        function exportData(format) {
            alert(`Veriler ${format.toUpperCase()} formatında dışa aktarılıyor...`);
        }

        function confirmBackup() {
            const isConfirmed = confirm('Veritabanını yedeklemek istediğinizden emin misiniz? Bu işlem biraz zaman alabilir.');
            if (isConfirmed) {
                alert('Veritabanı yedeklemesi başlatıldı...');
            }
        }
        
        // Yeni Kullanıcı Yönetimi Fonksiyonları
        function addNewUser() {
            const newUsername = document.getElementById('newUsername').value;
            if (newUsername.trim() === '') {
                alert('Kullanıcı adı boş bırakılamaz.');
                return;
            }
            alert(`Yeni kullanıcı "${newUsername}" başarıyla eklendi!`);
            const addUserModal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
            addUserModal.hide();
        }

        function saveUserChanges() {
            const username = document.getElementById('editUsername').value;
            const newRole = document.getElementById('editUserRole').value;
            const changePassword = document.getElementById('changePasswordCheck').checked;
            
            let message = `Kullanıcı "${username}" için değişiklikler kaydedildi.\n`;
            message += `Yeni Rol: ${newRole}`;

            if (changePassword) {
                message += `\nŞifre de değiştirildi.`;
            }

            alert(message);
            const editUserModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            editUserModal.hide();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Tab Switching
            const menuItems = document.querySelectorAll('.sidebar .menu-item');
            const tabSections = document.querySelectorAll('.tab-section');

            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    const targetTab = this.getAttribute('data-tab');
                    tabSections.forEach(section => {
                        if (section.id === targetTab) {
                            section.classList.add('active');
                            section.classList.remove('d-none');
                        } else {
                            section.classList.remove('active');
                            section.classList.add('d-none');
                        }
                    });
                });
            });
            
            // Notification Button
            const notificationBtn = document.getElementById('notificationBtn');
            notificationBtn.addEventListener('click', function() {
                alert('3 yeni bildiriminiz var: Vadesi geçmiş fatura, kritik stok seviyesi, yaklaşan ödeme.');
            });

            // Edit User Modal populating
            const editUserModalElement = document.getElementById('editUserModal');
            editUserModalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const username = button.getAttribute('data-user-name');
                const role = button.getAttribute('data-user-role');
                
                document.getElementById('editUsername').value = username;
                document.getElementById('editUserRole').value = role;
                
                document.getElementById('changePasswordCheck').checked = false;
                document.getElementById('passwordFields').style.display = 'none';
            });
            
            document.getElementById('changePasswordCheck').addEventListener('change', function() {
                const passwordFields = document.getElementById('passwordFields');
                if (this.checked) {
                    passwordFields.style.display = 'block';
                } else {
                    passwordFields.style.display = 'none';
                }
            });


            // Sample data for tables and charts
            const accounts = [
                { code: 'CR-001', name: 'Alpha Yazılım Ltd.', type: 'Müşteri', lastTransaction: '20.06.2025', balance: '+₺ 12.500', status: 'Aktif' },
                { code: 'CR-002', name: 'Ofis Market A.Ş.', type: 'Tedarikçi', lastTransaction: '14.07.2025', balance: '-₺ 5.300', status: 'Aktif' },
                { code: 'CR-003', name: 'Gamma İnşaat', type: 'Müşteri', lastTransaction: '11.07.2025', balance: '+₺ 8.200', status: 'Aktif' },
                { code: 'CR-004', name: 'Hızlı Kargo Lojistik', type: 'Tedarikçi', lastTransaction: '25.05.2025', balance: '-₺ 1.500', status: 'Pasif' },
            ];

            const invoices = [
                { code: 'FAT-100', account: 'Alpha Yazılım Ltd.', date: '15.07.2025', dueDate: '30.07.2025', amount: '₺ 7.500', status: 'Ödenmemiş' },
                { code: 'FAT-099', account: 'Ofis Market A.Ş.', date: '10.07.2025', dueDate: '10.07.2025', amount: '₺ 1.200', status: 'Gecikmiş' },
                { code: 'FAT-098', account: 'Gamma İnşaat', date: '05.07.2025', dueDate: '20.07.2025', amount: '₺ 8.200', status: 'Ödenmiş' },
                { code: 'FAT-097', account: 'Alpha Yazılım Ltd.', date: '25.06.2025', dueDate: '25.06.2025', amount: '₺ 5.000', status: 'Ödenmiş' },
                { code: 'FAT-096', account: 'Tekno Bilgisayar', date: '20.06.2025', dueDate: '01.07.2025', amount: '₺ 10.000', status: 'Ödenmiş' }
            ];

            const products = [
                { code: 'PR-001', name: 'Gaming Mouse', category: 'Elektronik', quantity: 15, price: '₺ 750' },
                { code: 'PR-002', name: 'Kablosuz Klavye', category: 'Elektronik', quantity: 50, price: '₺ 250' },
                { code: 'PR-003', name: 'A4 Kağıt Paketi', category: 'Ofis Malzemeleri', quantity: 250, price: '₺ 85' },
                { code: 'PR-004', name: 'Web Tasarım Yazılımı', category: 'Yazılım', quantity: 10, price: '₺ 5.000' },
                { code: 'PR-005', name: 'Monitör', category: 'Elektronik', quantity: 5, price: '₺ 3.200' },
            ];

            // Function to render accounts table rows from data
            function renderAccountsTable() {
                const tableBody = document.getElementById('accountTableBody');
                tableBody.innerHTML = '';
                accounts.forEach(account => {
                    const row = document.createElement('tr');
                    const statusClass = account.status === 'Aktif' ? 'bg-success' : 'bg-danger';
                    const balanceClass = account.balance.includes('+') ? 'text-success' : 'text-danger';
                    row.innerHTML = `
                        <td>${account.code}</td>
                        <td>${account.name}</td>
                        <td>${account.type}</td>
                        <td>${account.lastTransaction}</td>
                        <td><span class="${balanceClass} fw-bold">${account.balance}</span></td>
                        <td><span class="badge ${statusClass}">${account.status}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link position-relative p-1" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Bildirimler">
                                    <i class="fas fa-bell fa-lg"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">3</span>
                                </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                        <li><h6 class="dropdown-header">Bildirimler</h6></li>
                                        <li><a class="dropdown-item" href="#">Yeni mesajınız var.</a></li>
                                        <li><a class="dropdown-item" href="#">Stokta kritik seviyede ürün var.</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-center text-primary" href="#">Tümünü Gör</a></li>
                                    </ul>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            // Function to render invoices table rows from data
            function renderInvoicesTable() {
                const tableBody = document.getElementById('invoiceTableBody');
                tableBody.innerHTML = '';
                invoices.forEach(invoice => {
                    const row = document.createElement('tr');
                    let statusClass = '';
                    if (invoice.status === 'Ödenmiş') statusClass = 'bg-success';
                    else if (invoice.status === 'Ödenmemiş') statusClass = 'bg-warning text-dark';
                    else if (invoice.status === 'Gecikmiş') statusClass = 'bg-danger';
                    
                    row.innerHTML = `
                        <td>${invoice.code}</td>
                        <td>${invoice.account}</td>
                        <td>${invoice.date}</td>
                        <td>${invoice.dueDate}</td>
                        <td>${invoice.amount}</td>
                        <td><span class="badge ${statusClass}">${invoice.status}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item view-invoice-btn" href="#" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal" data-invoice-code="${invoice.code}">Görüntüle</a></li>
                                    <li><a class="dropdown-item edit-invoice-btn" href="#" data-bs-toggle="modal" data-bs-target="#editInvoiceModal" data-invoice-code="${invoice.code}">Düzenle</a></li>
                                    <li><a class="dropdown-item text-danger delete-invoice-btn" href="#" data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal" data-invoice-code="${invoice.code}">Sil</a></li>
                                </ul>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }
            
            // Function to render stock table rows from data
            function renderStockTable() {
                const tableBody = document.getElementById('stockTableBody');
                tableBody.innerHTML = '';
                products.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.code}</td>
                        <td>${product.name}</td>
                        <td>${product.category}</td>
                        <td>${product.quantity}</td>
                        <td>${product.price}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item view-product-btn" href="#" data-bs-toggle="modal" data-bs-target="#viewProductModal" data-product-code="${product.code}">Görüntüle</a></li>
                                    <li><a class="dropdown-item edit-product-btn" href="#" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-code="${product.code}">Düzenle</a></li>
                                    <li><a class="dropdown-item text-danger delete-product-btn" href="#" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-product-code="${product.code}">Sil</a></li>
                                </ul>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            // Call the render functions on page load
            renderAccountsTable();
            renderInvoicesTable();
            renderStockTable();

            // Handle View Button Clicks (Accounts)
            document.getElementById('accountTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.view-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.view-btn').dataset.accountCode;
                    const account = accounts.find(acc => acc.code === code);
                    if (account) {
                        document.getElementById('viewCode').textContent = account.code;
                        document.getElementById('viewName').textContent = account.name;
                        document.getElementById('viewType').textContent = account.type;
                        document.getElementById('viewBalance').textContent = account.balance;
                        document.getElementById('viewStatus').textContent = account.status;
                    }
                }
            });

            // Handle Edit Button Clicks (Accounts)
            document.getElementById('accountTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.edit-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.edit-btn').dataset.accountCode;
                    const account = accounts.find(acc => acc.code === code);
                    if (account) {
                        document.getElementById('editCode').value = account.code;
                        document.getElementById('editName').value = account.name;
                        document.getElementById('editType').value = account.type;
                        document.getElementById('editStatus').value = account.status;
                    }
                }
            });

            // Handle Delete Button Clicks (Accounts)
            document.getElementById('accountTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.delete-btn').dataset.accountCode;
                    const account = accounts.find(acc => acc.code === code);
                    if (account) {
                        document.getElementById('deleteAccountName').textContent = account.name;
                    }
                }
            });
            
            // Handle View Button Clicks (Invoices)
            document.getElementById('invoiceTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.view-invoice-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.view-invoice-btn').dataset.invoiceCode;
                    const invoice = invoices.find(inv => inv.code === code);
                    if (invoice) {
                        document.getElementById('viewInvoiceNumber').textContent = invoice.code;
                        document.getElementById('viewInvoiceAccount').textContent = invoice.account;
                        document.getElementById('viewInvoiceDate').textContent = invoice.date;
                        document.getElementById('viewInvoiceDueDate').textContent = invoice.dueDate;
                        document.getElementById('viewInvoiceAmount').textContent = invoice.amount;
                        document.getElementById('viewInvoiceStatus').textContent = invoice.status;
                    }
                }
            });

            // Handle Edit Button Clicks (Invoices)
            document.getElementById('invoiceTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.edit-invoice-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.edit-invoice-btn').dataset.invoiceCode;
                    const invoice = invoices.find(inv => inv.code === code);
                    if (invoice) {
                        document.getElementById('editInvoiceCode').value = invoice.code;
                        document.getElementById('editInvoiceNumber').value = invoice.code;
                        document.getElementById('editInvoiceAccount').value = invoice.account;
                        document.getElementById('editInvoiceDate').value = new Date(invoice.date.split('.').reverse().join('-')).toISOString().slice(0, 10);
                        document.getElementById('editInvoiceDueDate').value = new Date(invoice.dueDate.split('.').reverse().join('-')).toISOString().slice(0, 10);
                        document.getElementById('editInvoiceAmount').value = parseFloat(invoice.amount.replace('₺', '').replace('.', '').replace(',', '.'));
                        document.getElementById('editInvoiceStatus').value = invoice.status;
                    }
                }
            });

            // Handle Delete Button Clicks (Invoices)
            document.getElementById('invoiceTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.delete-invoice-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.delete-invoice-btn').dataset.invoiceCode;
                    const invoice = invoices.find(inv => inv.code === code);
                    if (invoice) {
                        document.getElementById('deleteInvoiceNumber').textContent = invoice.code;
                    }
                }
            });

            // Handle View Button Clicks (Stock)
            document.getElementById('stockTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.view-product-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.view-product-btn').dataset.productCode;
                    const product = products.find(prod => prod.code === code);
                    if (product) {
                        document.getElementById('viewProductCode').textContent = product.code;
                        document.getElementById('viewProductName').textContent = product.name;
                        document.getElementById('viewProductCategory').textContent = product.category;
                        document.getElementById('viewProductQuantity').textContent = product.quantity;
                        document.getElementById('viewProductPrice').textContent = product.price;
                    }
                }
            });

            // Handle Edit Button Clicks (Stock)
            document.getElementById('stockTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.edit-product-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.edit-product-btn').dataset.productCode;
                    const product = products.find(prod => prod.code === code);
                    if (product) {
                        document.getElementById('editProductCode').value = product.code;
                        document.getElementById('editProductName').value = product.name;
                        document.getElementById('editProductCategory').value = product.category;
                        document.getElementById('editProductQuantity').value = product.quantity;
                        document.getElementById('editProductPrice').value = parseFloat(product.price.replace('₺', '').replace('.', '').replace(',', '.'));
                    }
                }
            });

            // Handle Delete Button Clicks (Stock)
            document.getElementById('stockTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.delete-product-btn')) {
                    e.preventDefault();
                    const code = e.target.closest('.delete-product-btn').dataset.productCode;
                    const product = products.find(prod => prod.code === code);
                    if (product) {
                        document.getElementById('deleteProductName').textContent = product.name;
                    }
                }
            });

            // Financial Flow Chart (Dashboard)
            const financialFlowCtx = document.getElementById('financialFlowChart');
            new Chart(financialFlowCtx, {
                type: 'bar',
                data: {
                    labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
                    datasets: [
                        {
                            label: 'Gelir',
                            data: [18000, 22000, 25000, 19000, 28000, 21300],
                            backgroundColor: '#48BB78', // Yeşil
                            borderRadius: 4
                        },
                        {
                            label: 'Gider',
                            data: [12000, 15000, 14500, 10500, 17000, 11000],
                            backgroundColor: '#F56565', // Kırmızı
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });

            // Active/Passive Chart (Accounts)
            const activePassiveCtx = document.getElementById('activePassiveChart');
            new Chart(activePassiveCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Pasif'],
                    datasets: [{
                        data: [210, 40], // Veriler: 210 aktif, 40 pasif
                        backgroundColor: ['#48BB78', '#E53E3E'], // Yeşil ve Kırmızı
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Account Type Chart (Accounts)
            const accountTypeCtx = document.getElementById('accountTypeChart');
            new Chart(accountTypeCtx, {
                type: 'pie',
                data: {
                    labels: ['Müşteri', 'Tedarikçi'],
                    datasets: [{
                        data: [150, 100], // Örnek Veri: 150 Müşteri, 100 Tedarikçi
                        backgroundColor: ['#5A67D8', '#F6AD55'], // Mavi ve Turuncu
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Invoice Amount Chart (Invoices)
            const invoiceAmountCtx = document.getElementById('invoiceAmountChart');
            new Chart(invoiceAmountCtx, {
                type: 'line',
                data: {
                    labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
                    datasets: [{
                        label: 'Fatura Tutarları (₺)',
                        data: [15000, 18000, 22000, 17500, 25000, 21000],
                        borderColor: '#5A67D8',
                        backgroundColor: 'rgba(90, 103, 216, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Invoice Status Chart (Invoices)
            const invoiceStatusCtx = document.getElementById('invoiceStatusChart');
            new Chart(invoiceStatusCtx, {
                type: 'pie',
                data: {
                    labels: ['Ödenmiş', 'Ödenmemiş', 'Gecikmiş'],
                    datasets: [{
                        data: [68, 15, 2],
                        backgroundColor: ['#48BB78', '#F6AD55', '#E53E3E'],
                        borderColor: ['#fff', '#fff', '#fff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
            
            // Best Selling Chart (Stock)
            const bestSellingCtx = document.getElementById('bestSellingChart');
            new Chart(bestSellingCtx, {
                type: 'bar',
                data: {
                    labels: ['Laptop', 'Monitör', 'Gaming Mouse', 'Kablosuz Klavye', 'A4 Kağıt'],
                    datasets: [{
                        label: 'Satılan Miktar',
                        data: [120, 95, 80, 75, 200],
                        backgroundColor: '#5A67D8',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Satış Adedi'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Stock Category Chart (Stock)
            const stockCategoryCtx = document.getElementById('stockCategoryChart');
            new Chart(stockCategoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Elektronik', 'Ofis Malzemeleri', 'Yazılım'],
                    datasets: [{
                        data: [75, 40, 10], // Örnek Veri
                        backgroundColor: ['#5A67D8', '#F6AD55', '#48BB78'],
                        borderColor: ['#fff', '#fff', '#fff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
            
            // Revenue/Expense Chart (Reports)
            const revenueExpenseCtx = document.getElementById('revenueExpenseChart');
            new Chart(revenueExpenseCtx, {
                type: 'line',
                data: {
                    labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
                    datasets: [
                        {
                            label: 'Gelir',
                            data: [18000, 22000, 25000, 19000, 28000, 21300],
                            borderColor: '#48BB78',
                            tension: 0.4,
                            fill: false,
                            pointRadius: 5
                        },
                        {
                            label: 'Gider',
                            data: [12000, 15000, 14500, 10500, 17000, 11000],
                            borderColor: '#E53E3E',
                            tension: 0.4,
                            fill: false,
                            pointRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Tutar (₺)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Top Customers Chart (Reports)
            const topCustomersCtx = document.getElementById('topCustomersChart');
            new Chart(topCustomersCtx, {
                type: 'bar',
                data: {
                    labels: ['Alpha Yazılım', 'Gamma İnşaat', 'Beta Ltd.', 'Hızlı Kargo', 'Tekno Bilgisayar'],
                    datasets: [{
                        label: 'Fatura Tutarı (₺)',
                        data: [22500, 18000, 15000, 9500, 8000],
                        backgroundColor: '#5A67D8',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Toplam Tutar'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Theme Toggle
            const themeToggleBtn = document.getElementById('themeToggle');
            themeToggleBtn.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                const icon = themeToggleBtn.querySelector('i');
                if (document.body.classList.contains('dark-mode')) {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                } else {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            });
        });
    </script>
</body>
</html>
