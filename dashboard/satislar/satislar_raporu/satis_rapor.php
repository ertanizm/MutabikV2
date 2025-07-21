<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satışlar Raporu</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../../dashboard.css" rel="stylesheet">

    <style>
   
        /* satis_rapor.php için özel CSS */

/* Genel içerik alanı (content-area) */
.content-area {
    max-width: 1100px;
    margin: 0 auto;
    padding-bottom: 40px;
}

/* Ana başlık */
.main-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 30px;
}

/* Kart genel stili */
.card {
    background-color: var(--card-bg);
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    padding: 25px;
}

/* Kart başlığı alanı */
.card-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: var(--text-primary);
}

/* Filtre bar */
.filter-bar {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
}

/* Tarih seçici kapsayıcı */
.date-filter-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #f0f2f5;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 14px;
    cursor: pointer;
    min-width: 220px;
}

.date-filter-wrapper i {
    color: var(--secondary-color);
    font-size: 18px;
}

/* Datepicker input */
#datePicker {
    border: none;
    background: transparent;
    outline: none;
    font-size: 14px;
    color: var(--text-primary);
    width: 140px;
    cursor: pointer;
}

/* Dropdown buton */
.dropdown-wrapper {
    position: relative;
}

.dropdown-button {
    background-color: #f0f2f5;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 16px;
    color: var(--text-secondary);
    font-size: 14px;
    cursor: pointer;
    user-select: none;
    min-width: 160px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Dropdown menü */
.dropdown-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: none;
    width: 100%;
    z-index: 100;
}

.dropdown-menu > div {
    padding: 10px 16px;
    font-size: 14px;
    color: var(--text-primary);
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.dropdown-menu > div:hover {
    background-color: var(--secondary-color);
    color: white;
    border-radius: 8px;
}

/* Kart içeriği */
.card-content {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Boş durum gösterimi */
.empty-state {
    text-align: center;
    color: var(--text-secondary);
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 15px;
    color: var(--warning-color);
}

/* Responsive - küçük ekranlar için */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .filter-bar {
        width: 100%;
        justify-content: flex-start;
    }

    .date-filter-wrapper, .dropdown-button {
        min-width: 100%;
        max-width: 300px;
    }
}

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
</head>

<body>
    
    <div class="sidebar" id="sidebar">
        
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-calculator"></i>
                Mutabık
            </div>
        </div>
        <div class="sidebar-menu">
            <a href="/MutabikV2/dashboard/dashboard2.php" class="menu-item">
                <i class="fas fa-chart-line"></i>
                Güncel Durum
            </a>

            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="sales-submenu">
                    <i class="fas fa-arrow-down"></i>
                    SATIŞLAR
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="sales-submenu">
                    <a href="../teklifler/teklifler.php" class="submenu-item">
                        <i class="fas fa-file-alt"></i>
                        Teklifler
                    </a>
                    <a href="../faturalar/faturalar.php" class="submenu-item">
                        <i class="fas fa-file-invoice"></i>
                        Faturalar
                    </a>
                    <a href="../musteriler/musteriler.php" class="submenu-item">
                        <i class="fas fa-users"></i>
                        Müşteriler
                    </a>
                    <a href="satislar_raporu/satis_rapor.php" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Satışlar Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Tahsilatlar Raporu
                    </a>
                    <a href="../gelir_gider_raporu/gelir-gider.php" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Gelir Gider Raporu
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="expenses-submenu">
                    <i class="fas fa-arrow-up"></i>
                    GİDERLER
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="expenses-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-alt"></i>
                        Gider Listesi
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        Tedarikçiler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-tie"></i>
                        Çalışanlar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Giderler Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Ödemeler Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        KDV Raporu
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="cash-submenu">
                    <i class="fas fa-money-bill-wave"></i>
                    NAKİT
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="cash-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-university"></i>
                        Kasa ve Bankalar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Çekler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Kasa / Banka Raporu
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar"></i>
                        Nakit Akışı Raporu
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="stock-submenu">
                    <i class="fas fa-boxes"></i>
                    STOK
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="stock-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-tags"></i>
                        Hizmet ve Ürünler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-map-marker-alt"></i>
                        Depolar
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-exchange-alt"></i>
                        Depolar Arası Transfer
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        Giden İrsaliyeler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck fa-flip-horizontal"></i>
                        Gelen İrsaliyeler
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-list"></i>
                        Fiyat Listeleri
                    </a>
                </div>
            </div>

            <div class="menu-separator"></div>

            <a href="../../dashboard2.php" class="menu-item">
                <i class="fas fa-star"></i>
                Uygulamalar
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                Pazaryeri
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-chevron-left"></i>
                Menüyü Sakla
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Ayarlar
            </a>
        </div>
    </div>
    <div class="profile-top-right" style="position: absolute; top: 24px; right: 40px; z-index: 1100;">
        <div class="header-right">
            <div class="user-info">
                <div class="user-avatar">MD</div>
                <div class="user-details">
                    <h6>Miraç Deprem</h6>
                    <small>Deprem Yazılım</small>
                </div>
                <i class="fas fa-chevron-down" style="color: var(--text-secondary);"></i>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="content-area">
            <h1 class="main-title">Satışlar Raporu</h1>

            <div class="card">
                <div class="card-header">
                    <h2 class="page-title">Satış Faturaları</h2>

                    <div class="filter-bar">
                        <div class="date-filter-wrapper">
                            <i class="fa-solid fa-calendar-days"></i>
                            <input type="text" id="datePicker" placeholder="Tarih Seçin">
                        </div>

                        <div class="dropdown-wrapper">
                            <button class="dropdown-button" onclick="toggleDropdown()">Vergiler Dahil ⏷</button>
                            <div class="dropdown-menu" id="dropdown-menu">
                                <div>Vergiler Hariç</div>
                                <div>Vergiler Dahil</div>
                                <div>Tümünü Göster</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <div class="empty-state">
                        <i class="fa-solid fa-calendar-xmark empty-icon"></i>
                        <p>Belirtilen zaman aralığında faturanız bulunmuyor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/rangePlugin.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const menuToggles = document.querySelectorAll('.menu-toggle');
            const salesSubmenu = document.getElementById('sales-submenu');

            // Sidebar'ı varsayılan olarak açık tut
            if (sidebar) {
                sidebar.classList.add('active'); // Sidebar'a 'active' sınıfı ekle
            }

            // "Satışlar" alt menüsünü varsayılan olarak açık tut ve ikonunu güncelle
            if (salesSubmenu) {
                salesSubmenu.classList.add('active');
                const salesToggle = document.querySelector('.menu-item.menu-toggle[data-target="sales-submenu"]');
                if (salesToggle) {
                    salesToggle.querySelector('.toggle-icon').classList.replace('fa-chevron-down', 'fa-chevron-up');
                }
            }

            // Alt menü açma/kapama mantığı
            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const submenu = document.getElementById(targetId);
                    if (submenu) {
                        submenu.classList.toggle('active');
                        const icon = this.querySelector('.toggle-icon');
                        if (submenu.classList.contains('active')) {
                            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                        } else {
                            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                        }
                    }
                });
            });

            // Takvim açılır (Mevcut kodunuz)
            flatpickr("#datePicker", {
                mode: "range",
                dateFormat: "d F Y",
                locale: {
                    firstDayOfWeek: 1 // Pazartesi haftanın ilk günü
                }
            });

            // Dropdown aç/kapa (Mevcut kodunuz)
            window.toggleDropdown = function () { // Fonksiyonu global scope'a taşıdım
                const menu = document.getElementById('dropdown-menu');
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }
        });
    </script>
    <!-- PROFIL DROPDOWN & WIDGET BAŞLANGIÇ -->
<div id="profileDropdown" class="profile-dropdown">
    <ul>
        <li><button type="button" id="openProfileWidget">Profilim</button></li>
        <li><a href="/MutabikV2/public/authentication/login.php" id="logoutBtn">Çıkış Yap</a></li>
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
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../script2.js"></script>
</body>

</html>