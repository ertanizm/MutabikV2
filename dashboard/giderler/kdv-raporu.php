<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDV Raporu - Mutabık</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../dashboard.css">
</head>
<body>

    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>KDV Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- KDV Kartları -->
            <div class="kdv-cards">
                <div class="kdv-card hesaplanan">
                    <span class="label">Hesaplanan KDV</span>
                    <span class="value">₺12.500</span>
                </div>
                <div class="kdv-card indirilecek">
                    <span class="label">İndirilecek KDV</span>
                    <span class="value">₺7.800</span>
                </div>
                <div class="kdv-card net">
                    <span class="label">Net KDV</span>
                    <span class="value">₺4.700</span>
                </div>
            </div>

            <!-- Yıl Filtresi -->
            <div class="filter-bar">
                <label for="year">Yıl:</label>
                <select id="year">
                    <option>2025</option>
                    <option>2024</option>
                    <option>2023</option>
                </select>
            </div>

            <!-- Tablo -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ay</th>
                            <th>Hesaplanan KDV</th>
                            <th>İndirilecek KDV</th>
                            <th>Net KDV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ocak</td>
                            <td>₺1.200</td>
                            <td>₺800</td>
                            <td>₺400</td>
                        </tr>
                        <tr>
                            <td>Şubat</td>
                            <td>₺1.500</td>
                            <td>₺1.200</td>
                            <td>₺300</td>
                        </tr>
                        <tr>
                            <td>Mart</td>
                            <td>₺2.000</td>
                            <td>₺1.600</td>
                            <td>₺400</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KDV Dökümü -->
<div class="kdv-dokumu-section mt-5">
    <h3>Aya Göre KDV Dökümü</h3>

    <!-- Filtre Butonları -->
    <div class="btn-group my-3" role="group" aria-label="KDV Döküm Filtresi">
        <button type="button" class="btn btn-outline-primary active" onclick="filterKdv('all')">Tümü</button>
        <button type="button" class="btn btn-outline-success" onclick="filterKdv('sales')">Sadece Satışlar</button>
        <button type="button" class="btn btn-outline-danger" onclick="filterKdv('expenses')">Sadece Giderler</button>
    </div>

    <!-- Döküm Tablosu -->
    <div class="table-container">
        <table class="table" id="kdvDokumTable">
            <thead>
                <tr>
                    <th>Ay</th>
                    <th class="dokum-sales">Satış KDV</th>
                    <th class="dokum-expenses">Gider KDV</th>
                    <th>Toplam</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ocak</td>
                    <td class="dokum-sales">₺1.000</td>
                    <td class="dokum-expenses">₺600</td>
                    <td>₺1.600</td>
                </tr>
                <tr>
                    <td>Şubat</td>
                    <td class="dokum-sales">₺1.200</td>
                    <td class="dokum-expenses">₺500</td>
                    <td>₺1.700</td>
                </tr>
                <tr>
                    <td>Mart</td>
                    <td class="dokum-sales">₺1.500</td>
                    <td class="dokum-expenses">₺700</td>
                    <td>₺2.200</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



    </div>

    <!-- SAYFA ÖZEL STİL -->
    <style>
        .kdv-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .kdv-card {
            flex: 1;
            min-width: 200px;
            background: #f9f9f9;
            border-left: 6px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .kdv-card.hesaplanan { border-color: #007bff; }
        .kdv-card.indirilecek { border-color: #28a745; }
        .kdv-card.net { border-color: #ffc107; }

        .kdv-card .label {
            font-size: 14px;
            color: #666;
        }

        .kdv-card .value {
            display: block;
            font-size: 22px;
            font-weight: bold;
            margin-top: 8px;
            color: #333;
        }

        .filter-bar {
            margin-bottom: 20px;
        }

        .filter-bar label {
            font-weight: bold;
            margin-right: 10px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background-color: #f1f1f1;
            font-weight: 600;
            color: #333;
        }

        @media (max-width: 768px) {
            .kdv-cards {
                flex-direction: column;
            }
        }
        .kdv-dokumu-section h3 {
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 600;
}
    </style>

    <!-- SAYFA ÖZEL SCRIPT -->
    <script>
        document.getElementById('year').addEventListener('change', function () {
            const selectedYear = this.value;
            alert('Seçilen yıl: ' + selectedYear);
            // Buraya AJAX eklenebilir
        });
        function filterKdv(type) {
        const salesCols = document.querySelectorAll('.dokum-sales');
        const expenseCols = document.querySelectorAll('.dokum-expenses');
        const buttons = document.querySelectorAll('.btn-group .btn');

        buttons.forEach(btn => btn.classList.remove('active'));

        if (type === 'sales') {
            salesCols.forEach(el => el.style.display = '');
            expenseCols.forEach(el => el.style.display = 'none');
            buttons[1].classList.add('active');
        } else if (type === 'expenses') {
            salesCols.forEach(el => el.style.display = 'none');
            expenseCols.forEach(el => el.style.display = '');
            buttons[2].classList.add('active');
        } else {
            salesCols.forEach(el => el.style.display = '');
            expenseCols.forEach(el => el.style.display = '');
            buttons[0].classList.add('active');
        }
    }
    </script>

    <!-- Ortak Script -->
    <script src="../script2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
