<?php
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - Ön Muhasebe Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="dashboard.css" rel="stylesheet"> </head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
        <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Güncel Durum</h1>
            </div>
            
            <div class="header-right">
                <?php include __DIR__ . '/includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-hand-holding-usd"></i>
                Tahsilatlar
            </h2>
            
            <div class="cards-row">
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

        <div class="financial-section">
            <h2 class="section-title">
                <i class="fas fa-credit-card"></i>
                Ödemeler
            </h2>
            
            <div class="cards-row">
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
    <script src="script2.js"></script>
</body>
</html>
