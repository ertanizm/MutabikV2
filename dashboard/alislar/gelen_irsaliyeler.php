//          <a href="/MutabikV2/dashboard/alislar/gelen_irsaliyeler.php" class="submenu-item">
//              <i class="fas fa-truck fa-flip-horizontal"></i>
//              <span>Gelen İrsaliyeler</span>
//          </a>

<?php
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Gelen İrsaliyeler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { display: block !important; }
        .main-content { margin-left: 280px !important; }
    </style>
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Gelen İrsaliyeler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="financial-section">
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam İrsaliye</div>
                            <div class="card-icon income">
                                <i class="fas fa-truck"></i>
                            </div>
                        </div>
                        <div class="card-amount">18</div>
                        <div class="card-status">Haziran 2024</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Bekleyen İrsaliye</div>
                            <div class="card-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="card-amount">3</div>
                        <div class="card-status">Teslim Edilmedi</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">En Büyük Tedarikçi</div>
                            <div class="card-icon success">
                                <i class="fas fa-industry"></i>
                            </div>
                        </div>
                        <div class="card-amount">ABC Lojistik</div>
                        <div class="card-status">7 İrsaliye</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Tutar</div>
                            <div class="card-icon expense">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺42.500,00</div>
                        <div class="card-status">Haziran 2024</div>
                    </div>
                </div>
            </div>
            <div class="row mb-5 g-4">
                <div class="col-lg-6 col-12">
                    <div class="bg-white p-3 rounded shadow-sm h-100">
                        <h6 class="mb-3"><i class="fas fa-chart-line"></i> Aylık İrsaliye Trend</h6>
                        <canvas id="irsaliyeTrendChart" height="120"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="bg-white p-3 rounded shadow-sm h-100">
                        <h6 class="mb-3"><i class="fas fa-chart-pie"></i> Tedarikçi Bazlı Dağılım</h6>
                        <canvas id="supplierPieChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                Gelen İrsaliyeler Detayı
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tarih</th>
                            <th>İrsaliye No</th>
                            <th>Tedarikçi</th>
                            <th>Tutar (₺)</th>
                            <th>Durum</th>
                            <th>Ürün Adedi</th>
                            <th>Teslim Alan</th>
                            <th>Not</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>02.06.2024</td>
                            <td>IR-2024-001</td>
                            <td>ABC Lojistik</td>
                            <td>₺7.500,00</td>
                            <td>Teslim Edildi</td>
                            <td>12</td>
                            <td>Ahmet Yılmaz</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>05.06.2024</td>
                            <td>IR-2024-002</td>
                            <td>XYZ Tedarik</td>
                            <td>₺3.200,00</td>
                            <td>Teslim Edildi</td>
                            <td>8</td>
                            <td>Mehmet Kaya</td>
                            <td>Hızlı teslimat</td>
                        </tr>
                        <tr>
                            <td>10.06.2024</td>
                            <td>IR-2024-003</td>
                            <td>ABC Lojistik</td>
                            <td>₺6.000,00</td>
                            <td>Bekliyor</td>
                            <td>10</td>
                            <td>-</td>
                            <td>Eksik ürün</td>
                        </tr>
                        <tr>
                            <td>15.06.2024</td>
                            <td>IR-2024-004</td>
                            <td>DEF Malzeme</td>
                            <td>₺2.800,00</td>
                            <td>Teslim Edildi</td>
                            <td>6</td>
                            <td>Ayşe Demir</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>18.06.2024</td>
                            <td>IR-2024-005</td>
                            <td>ABC Lojistik</td>
                            <td>₺8.000,00</td>
                            <td>Teslim Edildi</td>
                            <td>15</td>
                            <td>Ahmet Yılmaz</td>
                            <td>Depoya teslim</td>
                        </tr>
                        <tr>
                            <td>22.06.2024</td>
                            <td>IR-2024-006</td>
                            <td>XYZ Tedarik</td>
                            <td>₺5.000,00</td>
                            <td>Bekliyor</td>
                            <td>9</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>28.06.2024</td>
                            <td>IR-2024-007</td>
                            <td>ABC Lojistik</td>
                            <td>₺10.000,00</td>
                            <td>Teslim Edildi</td>
                            <td>20</td>
                            <td>Mehmet Kaya</td>
                            <td>En yüksek tutar</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../script2.js"></script>
    <script>
    // Aylık irsaliye trendi (örnek veri)
    const trendCtx = document.getElementById('irsaliyeTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
                datasets: [{
                    label: 'İrsaliye Sayısı',
                    data: [8, 12, 10, 15, 14, 18],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52,152,219,0.08)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#3498db',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#2c3e50', font: { size: 13 } }
                    },
                    x: {
                        ticks: { color: '#2c3e50', font: { size: 13 } }
                    }
                }
            }
        });
    }
    // Tedarikçi bazlı dağılım (örnek veri)
    const pieCtx = document.getElementById('supplierPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['ABC Lojistik', 'XYZ Tedarik', 'DEF Malzeme'],
                datasets: [
                    {
                        label: 'İrsaliye Sayısı',
                        data: [7, 3, 1],
                        backgroundColor: [
                            '#2980b9',
                            '#e67e22',
                            '#16a085'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                }
            }
        });
    }
    </script>
</body>
</html>
