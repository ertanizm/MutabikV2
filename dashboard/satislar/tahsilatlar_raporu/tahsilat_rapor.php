<?php
// Gerekirse session başlat
// session_start();

// Kullanıcı bilgileri örnek (gerçek uygulamada session veya veritabanından alınır)
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahsilat Raporu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../dashboard.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
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
        .report-table-section .table {
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
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
        @media (min-width: 769px) {
            .main-content {
                margin-left: 280px;
            }
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">
    <?php include '../../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Tahsilat Raporu</h1>
            </div>
            <div class="header-right">
                <?php include '/../../includes/profile_dropdown.php'; ?>
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
                <i class="fas fa-file-invoice-dollar"></i> Tahsilat Özeti
            </h2>
            <div class="cards-row">
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Toplam Tahsilat</div>
                        <div class="card-icon income">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺12.500,00</div>
                    <div class="card-status">Bu Dönem</div>
                </div>
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Nakit Tahsilat</div>
                        <div class="card-icon success">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺7.000,00</div>
                    <div class="card-status">Bu Dönem</div>
                </div>
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-title">Banka Tahsilat</div>
                        <div class="card-icon expense">
                            <i class="fas fa-university"></i>
                        </div>
                    </div>
                    <div class="card-amount">₺5.500,00</div>
                    <div class="card-status">Bu Dönem</div>
                </div>
            </div>
        </div>
        <div class="financial-section report-table-section">
            <h2 class="section-title">
                <i class="fas fa-list"></i> Tahsilat Detayları
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Müşteri</th>
                            <th>Tutar</th>
                            <th>Yöntem</th>
                            <th>Açıklama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-07-22</td>
                            <td>Ahmet Yılmaz</td>
                            <td class="text-success">₺3.000,00</td>
                            <td>Nakit</td>
                            <td>Peşin ödeme</td>
                        </tr>
                        <tr>
                            <td>2025-07-20</td>
                            <td>Mehmet Kaya</td>
                            <td class="text-success">₺2.500,00</td>
                            <td>Banka</td>
                            <td>Havale</td>
                        </tr>
                        <tr>
                            <td>2025-07-18</td>
                            <td>Ayşe Demir</td>
                            <td class="text-success">₺4.000,00</td>
                            <td>Nakit</td>
                            <td>Kasadan tahsilat</td>
                        </tr>
                        <tr>
                            <td>2025-07-15</td>
                            <td>Ali Vural</td>
                            <td class="text-success">₺3.000,00</td>
                            <td>Banka</td>
                            <td>EFT</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
</body>
</html>
