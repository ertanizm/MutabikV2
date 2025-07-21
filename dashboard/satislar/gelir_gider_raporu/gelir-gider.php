<?php
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
        /* PROFIL DROPDOWN & WIDGET BAŞLANGIÇ */
        .profile-dropdown {
            position: absolute;
            top: 60px;
            right: 25px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            min-width: 180px;
            z-index: 2000;
            display: none;
        }
        .profile-dropdown.active { display: block; }
        .profile-dropdown ul { list-style: none; margin: 0; padding: 0; }
        .profile-dropdown li { border-bottom: 1px solid #f0f0f0; }
        .profile-dropdown li:last-child { border-bottom: none; }
        .profile-dropdown a, .profile-dropdown button {
            display: block;
            width: 100%;
            padding: 12px 18px;
            background: none;
            border: none;
            text-align: left;
            color: #2c3e50;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .profile-dropdown a:hover, .profile-dropdown button:hover { background: #f5f5f5; }
        .profile-widget {
            position: fixed;
            top: 80px;
            right: 40px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            min-width: 320px;
            z-index: 3000;
            display: none;
            padding: 28px 32px 24px 32px;
        }
        .profile-widget.active { display: block; }
        .profile-widget .profile-title { font-size: 20px; font-weight: bold; margin-bottom: 18px; color: #2c3e50; }
        .profile-widget .profile-row { display: flex; align-items: center; margin-bottom: 12px; }
        .profile-widget .profile-row i { width: 22px; color: #888; margin-right: 10px; }
        .profile-widget .profile-label { font-weight: 500; color: #888; width: 120px; }
        .profile-widget .profile-value { color: #2c3e50; }
        .profile-widget .close-btn { position: absolute; top: 12px; right: 16px; background: none; border: none; font-size: 20px; color: #888; cursor: pointer; }
        /* PROFIL DROPDOWN & WIDGET BİTİŞ */
    </style>
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

    <!-- PROFIL DROPDOWN & WIDGET BAŞLANGIÇ -->
    <style>
    .profile-dropdown {
        position: absolute;
        top: 60px;
        right: 25px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        min-width: 180px;
        z-index: 2000;
        display: none;
    }
    .profile-dropdown.active { display: block; }
    .profile-dropdown ul { list-style: none; margin: 0; padding: 0; }
    .profile-dropdown li { border-bottom: 1px solid #f0f0f0; }
    .profile-dropdown li:last-child { border-bottom: none; }
    .profile-dropdown a, .profile-dropdown button {
        display: block;
        width: 100%;
        padding: 12px 18px;
        background: none;
        border: none;
        text-align: left;
        color: #2c3e50;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .profile-dropdown a:hover, .profile-dropdown button:hover { background: #f5f5f5; }
    .profile-widget {
        position: fixed;
        top: 80px;
        right: 40px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        min-width: 320px;
        z-index: 3000;
        display: none;
        padding: 28px 32px 24px 32px;
    }
    .profile-widget.active { display: block; }
    .profile-widget .profile-title { font-size: 20px; font-weight: bold; margin-bottom: 18px; color: #2c3e50; }
    .profile-widget .profile-row { display: flex; align-items: center; margin-bottom: 12px; }
    .profile-widget .profile-row i { width: 22px; color: #888; margin-right: 10px; }
    .profile-widget .profile-label { font-weight: 500; color: #888; width: 120px; }
    .profile-widget .profile-value { color: #2c3e50; }
    .profile-widget .close-btn { position: absolute; top: 12px; right: 16px; background: none; border: none; font-size: 20px; color: #888; cursor: pointer; }
    </style>
    <div id="profileDropdown" class="profile-dropdown">
        <ul>
            <li><button type="button" id="openProfileWidget">Profilim</button></li>
            <li><a href="#" id="logoutBtn">Çıkış Yap</a></li>
        </ul>
    </div>
    <div id="profileWidget" class="profile-widget">
        <button class="close-btn" onclick="document.getElementById('profileWidget').classList.remove('active')">&times;</button>
        <div class="profile-title">Profilim</div>
        <div class="profile-row"><i class="fas fa-user"></i><span class="profile-label">Adı Soyadı:</span><span class="profile-value"><?php echo htmlspecialchars($userName); ?></span></div>
        <div class="profile-row"><i class="fas fa-envelope"></i><span class="profile-label">E-posta Adresi:</span><span class="profile-value"><?php echo htmlspecialchars($userEmail); ?></span></div>
        <div class="profile-row"><i class="fas fa-phone"></i><span class="profile-label">Telefon:</span><span class="profile-value"><?php echo isset($userPhone) ? htmlspecialchars($userPhone) : '-'; ?></span></div>
        <div class="profile-row"><i class="fas fa-briefcase"></i><span class="profile-label">Unvan:</span><span class="profile-value"><?php echo htmlspecialchars($companyName); ?></span></div>
    </div>
    <script>
    // Profil dropdown aç/kapa
    const userInfo = document.querySelector('.user-info');
    const profileDropdown = document.getElementById('profileDropdown');
    const profileWidget = document.getElementById('profileWidget');
    const openProfileWidgetBtn = document.getElementById('openProfileWidget');

    document.addEventListener('click', function(e) {
        if (userInfo && userInfo.contains(e.target)) {
            profileDropdown.classList.toggle('active');
        } else if (profileDropdown && !profileDropdown.contains(e.target) && !userInfo.contains(e.target)) {
            profileDropdown.classList.remove('active');
        }
        if (profileWidget && !profileWidget.contains(e.target) && e.target !== openProfileWidgetBtn) {
            profileWidget.classList.remove('active');
        }
    });
    if (openProfileWidgetBtn) {
        openProfileWidgetBtn.onclick = function() {
            profileDropdown.classList.remove('active');
            profileWidget.classList.add('active');
        };
    }
    </script>
    <!-- PROFIL DROPDOWN & WIDGET BİTİŞ -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
   
</body>
</html>