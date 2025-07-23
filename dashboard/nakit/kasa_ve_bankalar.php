<?php
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Kasa ve Bankalar</title>
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
                <h1>Kasa ve Bankalar</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="financial-section">
            <div class="row mb-4">
                <div class="col-md-4 col-12 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Kasa Bakiyesi</div>
                            <div class="card-icon income">
                                <i class="fas fa-cash-register"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺5.000,00</div>
                        <div class="card-status">Güncel</div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Banka Bakiyesi</div>
                            <div class="card-icon success">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺18.500,00</div>
                        <div class="card-status">Güncel</div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <div class="financial-card">
                        <div class="card-header">
                            <div class="card-title">Toplam Varlık</div>
                            <div class="card-icon pending">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="card-amount">₺23.500,00</div>
                        <div class="card-status">Kasa + Banka</div>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <canvas id="bankCashChart" height="80"></canvas>
            </div>
        </div>

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                Kasa ve Banka Hesapları Detayı
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hesap Adı</th>
                            <th>Tür</th>
                            <th>Bakiye (₺)</th>
                            <th>Banka Adı</th>
                            <th>IBAN</th>
                            <th>Son İşlem</th>
                            <th>Açıklama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Merkez Kasa</td>
                            <td>Kasa</td>
                            <td>₺3.000,00</td>
                            <td>-</td>
                            <td>-</td>
                            <td>28.06.2024</td>
                            <td>Günlük tahsilat</td>
                        </tr>
                        <tr>
                            <td>Şube Kasa</td>
                            <td>Kasa</td>
                            <td>₺2.000,00</td>
                            <td>-</td>
                            <td>-</td>
                            <td>27.06.2024</td>
                            <td>Şube kapanış</td>
                        </tr>
                        <tr>
                            <td>Ziraat Bankası</td>
                            <td>Banka</td>
                            <td>₺10.000,00</td>
                            <td>Ziraat</td>
                            <td>TR12 0001 0000 1234 5678 0000 01</td>
                            <td>26.06.2024</td>
                            <td>Havale girişi</td>
                        </tr>
                        <tr>
                            <td>İş Bankası</td>
                            <td>Banka</td>
                            <td>₺8.500,00</td>
                            <td>İş Bankası</td>
                            <td>TR33 0006 1005 1978 6457 8412 34</td>
                            <td>25.06.2024</td>
                            <td>Fatura ödemesi</td>
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
    const ctx = document.getElementById('bankCashChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Merkez Kasa', 'Şube Kasa', 'Ziraat Bankası', 'İş Bankası'],
                datasets: [
                    {
                        label: 'Bakiye (₺)',
                        data: [3000, 2000, 10000, 8500],
                        backgroundColor: [
                            '#27ae60',
                            '#2ecc71',
                            '#2980b9',
                            '#3498db'
                        ],
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Kasa ve Banka Hesapları Dağılımı',
                        color: '#2c3e50',
                        font: { size: 18 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
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
