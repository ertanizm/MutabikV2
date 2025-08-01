<?php
require '../../config/config.php'
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Alışlar Raporu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/alislar/alislar_raporu.css" rel="stylesheet">
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Alışlar Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-shopping-cart"></i></div>
                <div>
                    <div class="summary-label">Toplam Alış</div>
                    <div class="summary-value">₺58.200,00</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <div class="summary-label">Fatura Sayısı</div>
                    <div class="summary-value">12</div>
                </div>
            </div>
        </div>

        <div class="row mb-5 g-4">
            <div class="col-lg-6 col-12">
                <div class="bg-white p-3 rounded shadow-sm h-100">
                    <h6 class="mb-3" style="color:#3498db;"><i class="fas fa-chart-line"></i> Aylık Alış Trend</h6>
                    <canvas id="alisTrendChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="bg-white p-3 rounded shadow-sm h-100">
                    <h6 class="mb-3" style="color:#3498db;"><i class="fas fa-chart-pie"></i> Tedarikçi Bazlı Alışlar</h6>
                    <canvas id="alisSupplierDoughnut" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="financial-section" style="background:transparent; box-shadow:none;">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <h2 class="section-title mb-0" style="color:#3498db; font-size:20px;">
                    <i class="fas fa-list"></i>
                    Alışlar Detayı
                </h2>
                <input type="text" class="form-control search-bar" placeholder="Fatura No, Tedarikçi veya Ürün ara..." onkeyup="filterTable(this.value)">
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover align-middle" id="alislarTable">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Fatura No</th>
                            <th>Tedarikçi</th>
                            <th>Tutar (₺)</th>
                            <th>Durum</th>
                            <th>Ürün Adedi</th>
                            <th>Ödeme Tipi</th>
                            <th>Not</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>02.06.2024</td>
                            <td>AL-2024-001</td>
                            <td>ABC Lojistik</td>
                            <td>₺7.500,00</td>
                            <td><span class="badge-status badge-paid">Ödendi</span></td>
                            <td>12</td>
                            <td>Banka</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>05.06.2024</td>
                            <td>AL-2024-002</td>
                            <td>XYZ Tedarik</td>
                            <td>₺3.200,00</td>
                            <td><span class="badge-status badge-paid">Ödendi</span></td>
                            <td>8</td>
                            <td>Kasa</td>
                            <td>Hızlı teslimat</td>
                        </tr>
                        <tr>
                            <td>10.06.2024</td>
                            <td>AL-2024-003</td>
                            <td>ABC Lojistik</td>
                            <td>₺6.000,00</td>
                            <td><span class="badge-status badge-pending">Bekliyor</span></td>
                            <td>10</td>
                            <td>Banka</td>
                            <td>Eksik ürün</td>
                        </tr>
                        <tr>
                            <td>15.06.2024</td>
                            <td>AL-2024-004</td>
                            <td>DEF Malzeme</td>
                            <td>₺2.800,00</td>
                            <td><span class="badge-status badge-paid">Ödendi</span></td>
                            <td>6</td>
                            <td>Kasa</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>18.06.2024</td>
                            <td>AL-2024-005</td>
                            <td>ABC Lojistik</td>
                            <td>₺8.000,00</td>
                            <td><span class="badge-status badge-paid">Ödendi</span></td>
                            <td>15</td>
                            <td>Banka</td>
                            <td>Depoya teslim</td>
                        </tr>
                        <tr>
                            <td>22.06.2024</td>
                            <td>AL-2024-006</td>
                            <td>XYZ Tedarik</td>
                            <td>₺5.000,00</td>
                            <td><span class="badge-status badge-pending">Bekliyor</span></td>
                            <td>9</td>
                            <td>Kasa</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>28.06.2024</td>
                            <td>AL-2024-007</td>
                            <td>ABC Lojistik</td>
                            <td>₺10.000,00</td>
                            <td><span class="badge-status badge-paid">Ödendi</span></td>
                            <td>20</td>
                            <td>Banka</td>
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
    // Aylık alış trendi (örnek veri)
    const trendCtx = document.getElementById('alisTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
                datasets: [{
                    label: 'Alış Tutarı (₺)',
                    data: [12000, 9000, 15000, 11000, 13000, 18200],
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
    // Tedarikçi bazlı alışlar (örnek veri, doughnut)
    const doughnutCtx = document.getElementById('alisSupplierDoughnut');
    if (doughnutCtx) {
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['ABC Lojistik', 'XYZ Tedarik', 'DEF Malzeme'],
                datasets: [
                    {
                        label: 'Toplam Alış (₺)',
                        data: [22000, 8300, 7900],
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
    // Tablo filtreleme
    function filterTable(value) {
        value = value.toLowerCase();
        const rows = document.querySelectorAll('#alislarTable tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    }
    </script>
</body>
</html> 
