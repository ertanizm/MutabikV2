<?php
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Nakit Akışı Raporu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet"> 
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Nakit Akışı Raporu</h1>
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
                            <div class="card-title">Başlangıç Bakiyesi</div>
                            <div class="card-icon income">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺10.000,00</div>
                        <div class="card-status">01.06.2024</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Giriş</div>
                            <div class="card-icon success">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺7.500,00</div>
                        <div class="card-status">Haziran 2024</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Çıkış</div>
                            <div class="card-icon expense">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺4.200,00</div>
                        <div class="card-status">Haziran 2024</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Bitiş Bakiyesi</div>
                            <div class="card-icon income">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺13.300,00</div>
                        <div class="card-status">30.06.2024</div>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <canvas id="cashFlowChart" height="80"></canvas>
            </div>
        </div>

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                Nakit Akışı Detayı
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tarih</th>
                            <th>Açıklama</th>
                            <th>Kategori</th>
                            <th>Giriş (₺)</th>
                            <th>Çıkış (₺)</th>
                            <th>Bakiye (₺)</th>
                            <th>Not</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01.06.2024</td>
                            <td>Başlangıç Bakiyesi</td>
                            <td>Genel</td>
                            <td>10.000,00</td>
                            <td>-</td>
                            <td>10.000,00</td>
                            <td>Yeni ay açılışı</td>
                        </tr>
                        <tr>
                            <td>03.06.2024</td>
                            <td>Satış Geliri</td>
                            <td>Gelir</td>
                            <td>2.500,00</td>
                            <td>-</td>
                            <td>12.500,00</td>
                            <td>Fatura #2024-001</td>
                        </tr>
                        <tr>
                            <td>05.06.2024</td>
                            <td>Ofis Kirası</td>
                            <td>Gider</td>
                            <td>-</td>
                            <td>1.200,00</td>
                            <td>11.300,00</td>
                            <td>Haziran ayı kira</td>
                        </tr>
                        <tr>
                            <td>10.06.2024</td>
                            <td>Hizmet Geliri</td>
                            <td>Gelir</td>
                            <td>5.000,00</td>
                            <td>-</td>
                            <td>16.300,00</td>
                            <td>Proje teslimatı</td>
                        </tr>
                        <tr>
                            <td>15.06.2024</td>
                            <td>Personel Maaşı</td>
                            <td>Gider</td>
                            <td>-</td>
                            <td>3.000,00</td>
                            <td>13.300,00</td>
                            <td>Haziran maaş ödemesi</td>
                        </tr>
                        <tr>
                            <td>20.06.2024</td>
                            <td>Ekstra Satış</td>
                            <td>Gelir</td>
                            <td>700,00</td>
                            <td>-</td>
                            <td>14.000,00</td>
                            <td>Ek sipariş</td>
                        </tr>
                        <tr>
                            <td>25.06.2024</td>
                            <td>Ofis Malzemesi</td>
                            <td>Gider</td>
                            <td>-</td>
                            <td>500,00</td>
                            <td>13.500,00</td>
                            <td>Kırtasiye</td>
                        </tr>
                        <tr>
                            <td>30.06.2024</td>
                            <td>Elektrik Faturası</td>
                            <td>Gider</td>
                            <td>-</td>
                            <td>200,00</td>
                            <td>13.300,00</td>
                            <td>Haziran elektrik</td>
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
    // Grafik için örnek veri
    const ctx = document.getElementById('cashFlowChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['01.06', '03.06', '05.06', '10.06', '15.06', '20.06', '25.06', '30.06'],
                datasets: [{
                    label: 'Bakiye (₺)',
                    data: [10000, 12500, 11300, 16300, 13300, 14000, 14500, 13300],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52,152,219,0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#3498db',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Aylık Nakit Akışı Grafiği',
                        color: '#2c3e50',
                        font: { size: 18 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: { color: '#2c3e50' }
                    },
                    x: {
                        ticks: { color: '#2c3e50' }
                    }
                }
            }
        });
    }
    </script>
</body>
</html>
