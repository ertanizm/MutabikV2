<?php
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Fiyat Listeleri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .summary-cards { display: flex; gap: 24px; margin-bottom: 32px; flex-wrap: wrap; }
        .summary-card {
            flex: 1 1 220px;
            background: #3498db;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(44,62,80,0.07);
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 28px 22px;
            min-width: 220px;
            transition: box-shadow 0.2s, background 0.2s;
        }
        .summary-card:hover {
            box-shadow: 0 4px 24px rgba(52,152,219,0.13);
            background: #217dbb;
        }
        .summary-icon {
            font-size: 38px;
            color: #fff;
            background: rgba(255,255,255,0.18);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .summary-label {
            font-size: 15px;
            color: #eaf6fb;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
        }
        .table-modern {
            background: #fff;
            color: #2c3e50;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(44,62,80,0.07);
            margin-bottom: 32px;
        }
        .table-modern th, .table-modern td {
            border-color: #ecf0f1;
        }
        .table-modern thead th {
            color: #3498db;
            background: #f8f9fa;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding-top: 18px;
            padding-bottom: 18px;
        }
        .table-modern tbody tr {
            transition: background 0.18s;
        }
        .table-modern tbody tr:nth-child(even) {
            background: #fcfcfc;
        }
        .table-modern tbody tr:hover {
            background: #fdf6e3;
        }
        .table-modern td {
            font-size: 15px;
            padding-top: 16px;
            padding-bottom: 16px;
        }
        .table-modern td, .table-modern th {
            vertical-align: middle;
        }
        .table-modern th:first-child, .table-modern td:first-child {
            border-top-left-radius: 18px;
        }
        .table-modern th:last-child, .table-modern td:last-child {
            border-top-right-radius: 18px;
        }
        .doughnut-container {
            max-width: 320px;
            margin: 0 auto;
        }
        .badge-status { font-size: 13px; padding: 6px 12px; border-radius: 12px; font-weight: 500; }
        .badge-active { background: #eafaf1; color: #27ae60; }
        .badge-passive { background: #fdeaea; color: #e74c3c; }
        @media (max-width: 900px) { .summary-cards { flex-direction: column; gap: 16px; } }
        .product-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            border: 2px solid #e67e22;
            background: #fdf6e3;
        }
        .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .product-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }
        .product-cat {
            font-size: 13px;
            color: #e67e22;
            font-weight: 500;
        }
        .badge-status i {
            margin-right: 5px;
        }
        .badge-date {
            background: #f8f9fa;
            color: #7f8c8d;
            font-size: 13px;
            border-radius: 10px;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .table-modern tbody tr {
            box-shadow: 0 1px 8px rgba(44,62,80,0.04);
        }
        .table-modern tbody tr:hover {
            background: #fdf6e3;
            box-shadow: 0 4px 18px rgba(230, 126, 34, 0.10);
        }
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
                <h1>Fiyat Listeleri</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-list"></i></div>
                    <div>
                        <div class="summary-label">Toplam Fiyat Listesi</div>
                        <div class="summary-value">6</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-tags"></i></div>
                    <div>
                        <div class="summary-label">Aktif Liste</div>
                        <div class="summary-value">4</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-box"></i></div>
                    <div>
                        <div class="summary-label">Ürün Çeşidi</div>
                        <div class="summary-value">28</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div>
                        <div class="summary-label">Son Güncelleme</div>
                        <div class="summary-value">21.07.2024</div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-lg d-flex align-items-center gap-2 mt-3 mt-lg-0" data-bs-toggle="modal" data-bs-target="#urunEkleModal" style="height:56px; font-size:18px; font-weight:600;">
                <i class="fas fa-plus"></i> Ürün Ekle
            </button>
        </div>

        <!-- Ürün Ekle Modal -->
        <div class="modal fade" id="urunEkleModal" tabindex="-1" aria-labelledby="urunEkleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="urunEkleModalLabel">Yeni Ürün Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
              </div>
              <div class="modal-body">
                <form>
                  <div class="mb-3">
                    <label for="urunAdi" class="form-label">Ürün Adı</label>
                    <input type="text" class="form-control" id="urunAdi" placeholder="Ürün adı girin">
                  </div>
                  <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori">
                      <option>Elektronik</option>
                      <option>Genel</option>
                      <option>Ofis</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="fiyat" class="form-label">Fiyat (₺)</label>
                    <input type="number" class="form-control" id="fiyat" placeholder="Fiyat girin">
                  </div>
                  <div class="mb-3">
                    <label for="adet" class="form-label">Adet</label>
                    <input type="number" class="form-control" id="adet" placeholder="Adet girin">
                  </div>
                  <div class="mb-3">
                    <label for="birimFiyat" class="form-label">Birim Fiyat (₺)</label>
                    <input type="number" class="form-control" id="birimFiyat" placeholder="Birim fiyat girin">
                  </div>
                  <div class="mb-3">
                    <label for="durum" class="form-label">Durum</label>
                    <select class="form-select" id="durum">
                      <option selected>Aktif</option>
                      <option>Pasif</option>
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

        <div class="row mb-5 g-4">
            <div class="col-lg-6 col-12">
                <div class="bg-white p-3 rounded shadow-sm h-100">
                    <h6 class="mb-3" style="color:#e67e22;"><i class="fas fa-chart-bar"></i> Kategori Bazlı Fiyat Listesi</h6>
                    <canvas id="categoryPriceChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="bg-white p-3 rounded shadow-sm h-100">
                    <h6 class="mb-3" style="color:#e67e22;"><i class="fas fa-percentage"></i> Ortalama Fiyatlar</h6>
                    <div class="doughnut-container">
                        <canvas id="averagePriceChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="financial-section" style="background:transparent; box-shadow:none;">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <h2 class="section-title mb-0" style="color:#e67e22; font-size:20px;">
                    <i class="fas fa-list"></i>
                    Fiyat Listesi Detayı
                </h2>
                <input type="text" class="form-control search-bar" placeholder="Liste Adı, Ürün veya Kategori ara..." onkeyup="filterTable(this.value)">
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover align-middle" id="fiyatListesiTable">
                    <thead>
                        <tr>
                            <th>Liste Adı</th>
                            <th>Kategori</th>
                            <th>Ürün Sayısı</th>
                            <th>Adet</th>
                            <th>Birim Fiyat (₺)</th>
                            <th>Ortalama Fiyat (₺)</th>
                            <th>Durum</th>
                            <th>Güncelleme</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="https://ui-avatars.com/api/?name=Kampanya&background=e67e22&color=fff" class="product-avatar" alt="Ürün">
                                    <div>
                                        <div class="product-title">2024 Yaz Kampanyası</div>
                                        <div class="product-cat">Elektronik</div>
                                    </div>
                                </div>
                            </td>
                            <td>8</td>
                            <td>120</td>
                            <td>₺2.000,00</td>
                            <td>₺2.150,00</td>
                            <td><span class="badge-status badge-active"><i class="fas fa-check-circle"></i>Aktif</span></td>
                            <td><span class="badge-date"><i class="fas fa-calendar-alt"></i>21.07.2024</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="https://ui-avatars.com/api/?name=Standart&background=3498db&color=fff" class="product-avatar" alt="Ürün">
                                    <div>
                                        <div class="product-title">Standart Liste</div>
                                        <div class="product-cat">Genel</div>
                                    </div>
                                </div>
                            </td>
                            <td>12</td>
                            <td>200</td>
                            <td>₺1.200,00</td>
                            <td>₺1.350,00</td>
                            <td><span class="badge-status badge-active"><i class="fas fa-check-circle"></i>Aktif</span></td>
                            <td><span class="badge-date"><i class="fas fa-calendar-alt"></i>15.07.2024</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="https://ui-avatars.com/api/?name=Kurumsal&background=16a085&color=fff" class="product-avatar" alt="Ürün">
                                    <div>
                                        <div class="product-title">Kurumsal Müşteri</div>
                                        <div class="product-cat">Ofis</div>
                                    </div>
                                </div>
                            </td>
                            <td>4</td>
                            <td>40</td>
                            <td>₺2.500,00</td>
                            <td>₺2.800,00</td>
                            <td><span class="badge-status badge-passive"><i class="fas fa-times-circle"></i>Pasif</span></td>
                            <td><span class="badge-date"><i class="fas fa-calendar-alt"></i>10.07.2024</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="https://ui-avatars.com/api/?name=Yaz+Sonu&background=f39c12&color=fff" class="product-avatar" alt="Ürün">
                                    <div>
                                        <div class="product-title">Yaz Sonu İndirimi</div>
                                        <div class="product-cat">Elektronik</div>
                                    </div>
                                </div>
                            </td>
                            <td>2</td>
                            <td>30</td>
                            <td>₺1.800,00</td>
                            <td>₺1.900,00</td>
                            <td><span class="badge-status badge-active"><i class="fas fa-check-circle"></i>Aktif</span></td>
                            <td><span class="badge-date"><i class="fas fa-calendar-alt"></i>05.07.2024</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="https://ui-avatars.com/api/?name=Ofis+Malz&background=9b59b6&color=fff" class="product-avatar" alt="Ürün">
                                    <div>
                                        <div class="product-title">Ofis Malzemeleri</div>
                                        <div class="product-cat">Ofis</div>
                                    </div>
                                </div>
                            </td>
                            <td>2</td>
                            <td>25</td>
                            <td>₺1.100,00</td>
                            <td>₺1.200,00</td>
                            <td><span class="badge-status badge-passive"><i class="fas fa-times-circle"></i>Pasif</span></td>
                            <td><span class="badge-date"><i class="fas fa-calendar-alt"></i>01.07.2024</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="https://ui-avatars.com/api/?name=Kış+Fırsat&background=34495e&color=fff" class="product-avatar" alt="Ürün">
                                    <div>
                                        <div class="product-title">Kış Fırsatları</div>
                                        <div class="product-cat">Genel</div>
                                    </div>
                                </div>
                            </td>
                            <td>5</td>
                            <td>60</td>
                            <td>₺1.500,00</td>
                            <td>₺1.600,00</td>
                            <td><span class="badge-status badge-active"><i class="fas fa-check-circle"></i>Aktif</span></td>
                            <td><span class="badge-date"><i class="fas fa-calendar-alt"></i>15.06.2024</span></td>
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
    // Kategori bazlı fiyat listesi (örnek veri)
    const catCtx = document.getElementById('categoryPriceChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: ['Elektronik', 'Genel', 'Ofis'],
                datasets: [{
                    label: 'Liste Sayısı',
                    data: [2, 2, 2],
                    backgroundColor: ['#e67e22', '#3498db', '#16a085'],
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#2c3e50', font: { size: 13 } } },
                    x: { ticks: { color: '#2c3e50', font: { size: 13 } } }
                }
            }
        });
    }
    // Ortalama fiyatlar (örnek veri)
    const avgCtx = document.getElementById('averagePriceChart');
    if (avgCtx) {
        new Chart(avgCtx, {
            type: 'doughnut',
            data: {
                labels: ['Elektronik', 'Genel', 'Ofis'],
                datasets: [{
                    label: 'Ortalama Fiyat',
                    data: [2025, 1475, 2000],
                    backgroundColor: ['#e67e22', '#3498db', '#16a085'],
                    borderWidth: 1
                }]
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
        const rows = document.querySelectorAll('#fiyatListesiTable tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    }
    </script>
</body>
</html>
