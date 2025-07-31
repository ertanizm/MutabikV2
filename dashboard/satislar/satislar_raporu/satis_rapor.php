<?php
require_once __DIR__ . '/../../../config/db_connect.php';
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
    <link href="../../../assets/satislar/satislar_raporu/satis_rapor.css" rel="stylesheet">
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