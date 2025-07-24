<?php
session_start();

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'master_db';
$user = 'root';
$pass = '1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    $userEmail = 'default@email.com';
    $userName = 'Varsayılan Ad';
    $companyName = 'Varsayılan Şirket';
}

// Kullanıcı bilgilerini al
$userEmail = $_SESSION['email'] ?? 'default@email.com';
$userName = 'Miraç Deprem';
$companyName = 'Atia Yazılım';

// Veritabanı bağlantısı başarılıysa kullanıcı bilgilerini al
if (isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT u.email, u.username, c.name as company_name 
                               FROM users u 
                               JOIN companies c ON u.company_id = c.id 
                               WHERE u.email = ?");
        $stmt->execute([$userEmail]);
        $userData = $stmt->fetch();
        
        if ($userData) {
            $userEmail = $userData['email'];
            $userName = $userData['username'] ?? 'Varsayılan Ad';
            $companyName = $userData['company_name'] ?? 'Varsayılan Şirket';
        }
    } catch (PDOException $e) {
        error_log("Kullanıcı bilgileri alınamadı: " . $e->getMessage());
    }
}
?>
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
        /* Ana içerik ve kenar çubuğu düzeni */
        .main-content {
            /* Kenar çubuğu genişliğine göre soldan boşluk bırakır. */
            /* dashboard.css dosyanızdaki değere göre bunu ayarlayabilirsiniz. */
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        /* Üst Başlık Çubuğu */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top-header .header-left h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* satis_rapor.php için özel CSS */

        /* Genel içerik alanı (content-area) */
        /* .main-content altına alındığı için bu kurala şimdilik gerek yok */
        /*
        .content-area {
            max-width: 1100px;
            margin: 0 auto;
            padding-bottom: 40px;
        }
        */

        /* Ana başlık .top-header içine taşındı */
        /*
        .main-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 30px;
        }
        */

        /* Kart genel stili */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
            width: 100%;
            z-index: 100;
        }

        .dropdown-menu>div {
            padding: 10px 16px;
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .dropdown-menu>div:hover {
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
            .main-content {
                margin-left: 0; /* Kenar çubuğu kapalıyken veya mobil görünümde */
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .filter-bar {
                width: 100%;
                justify-content: flex-start;
            }

            .date-filter-wrapper,
            .dropdown-button {
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
    <?php include __DIR__ . '/../../sidebar.php'; ?>

    <div class="main-content">

        <div class="top-header">
            <div class="header-left">
                <h1>Satışlar Raporu</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>

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

    </div> <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/rangePlugin.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                toggle.addEventListener('click', function() {
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

            // Dropdown toggle fonksiyonu (Eğer ayrı bir dosyada değilse)
            window.toggleDropdown = function() {
                document.getElementById("dropdown-menu").style.display = 
                    document.getElementById("dropdown-menu").style.display === "block" ? "none" : "block";
            }

            // Dışarı tıklandığında dropdown'ı kapat
            window.onclick = function(event) {
                if (!event.target.matches('.dropdown-button')) {
                    var dropdowns = document.getElementsByClassName("dropdown-menu");
                    for (var i = 0; i < dropdowns.length; i++) {
                        var openDropdown = dropdowns[i];
                        if (openDropdown.style.display === "block") {
                            openDropdown.style.display = "none";
                        }
                    }
                }
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script2.js"></script>
</body>

</html>