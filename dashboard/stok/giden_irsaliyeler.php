<?php
// Bu alana PHP backend kodları veya veritabanı bağlantıları gelebilir
// Örneğin, irsaliye verilerini çekme, filtreleme vb.
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giden İrsaliyeler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/stok/giden_irsaliyeler.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>

   <div class="main-content p-4">
        <!-- Üst Header -->
        <div class="top-header">
            <div class="header-left">
            <h1>Giden İrsaliyeler</h1>
        </div>
            <div class="header-right">
        <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
        </div>
    </div>
        <div class="header-actions">
            <div class="search-filter-group">
                <button class="search-filter-button">Filtrele <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                        <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>
                <div class="search-filter-input-group">
                    <input type="text" id="filter" placeholder="Ara...">
                    <button><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.088.12l3.493 3.493a1 1 0 0 0 1.414-1.414l-3.493-3.493a1 1 0 0 0-.12-.088zM6.5 11A4.5 4.5 0 1 1 6.5 2a4.5 4.5 0 0 1 0 9" />
                        </svg></button>
                </div>
            </div>
            <div class="action-buttons">
                <button class="btn-secondary">TOPLU SATIŞ FATURASI OLUŞTUR</button>
                <button class="btn-primary">YENİ GİDEN İRSALİYE</button>
            </div>
        </div>

        <table class="delivery-table">
            <thead>
                <tr>
                    <th>İRSALİYE İSMİ</th>
                    <th>İRSALİYE NO</th>
                    <th>İRSALİYE TİPİ</th>
                    <th>DURUMU</th>
                    <th>DÜZENLEME TARİHİ
                        <span class="date-header-icons">
                            <i class="fa-solid fa-calendar-alt date-icon-trigger" id="dateCalendarIcon"></i>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up sort-icon" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                        </span>
                        <input type="text" id="hiddenDatepicker" style="position: absolute; left: -9999px; top: -9999px; opacity: 0;">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <p>Henüz hiç irsaliye oluşturmadınız.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer-pagination">
            <div class="dropdown" id="recordsDropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Tüm Kayıtlar <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                        <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592c.859 0 1.319-1.012.753-1.659L8.753 4.86a1 1 0 0 0-1.506 0z" />
                    </svg>
                </button>
                <div class="dropdown-content">
                </div>
            </div>
            <span>- 0 Kayıt</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>

    <script>
        $(function() {
            // "DÜZENLEME TARİHİ" başlığındaki takvim ikonuna tıklandığında datepicker'ı aç
            $('#dateCalendarIcon').on('click', function() {
                $('#hiddenDatepicker').datepicker('show');
            });

            // Gizli datepicker'ı başlat ve konumlandırma ayarlarını yap
            $('#hiddenDatepicker').datepicker({
                dateFormat: "dd.mm.yy", // Tarih formatı
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true, // Diğer aylardan günleri göster
                selectOtherMonths: true, // Diğer aylardan günleri seçilebilir yap
                // Datepicker gösterilmeden hemen önce tetiklenir
                beforeShow: function(input, inst) {
                    var $th = $('#dateCalendarIcon').closest('th'); // Tarih ikonunun bulunduğu th elementini bul
                    var thOffset = $th.offset();
                    var thHeight = $th.outerHeight();
                    var dpWidth = $(inst.dpDiv).outerWidth();

                    // Datepicker'ı th elementinin altına, ikonun ortasına hizala
                    var iconOffset = $('#dateCalendarIcon').offset();
                    var iconWidth = $('#dateCalendarIcon').outerWidth();
                    var leftPosition = iconOffset.left + (iconWidth / 2) - (dpWidth / 2);

                    // Ekranın dışına taşmayı engelle
                    if (leftPosition < 0) {
                        leftPosition = 0;
                    } else if (leftPosition + dpWidth > $(window).width()) {
                        leftPosition = $(window).width() - dpWidth;
                    }

                    setTimeout(function() {
                        $(inst.dpDiv).css({
                            top: thOffset.top + thHeight + 5, // 5px boşluk bırak
                            left: leftPosition
                        });
                    }, 1); // Küçük bir gecikme, DOM renderını beklemek için
                },
                onSelect: function(dateText, inst) {
                    // Kullanıcı tarih seçtiğinde yapılacak işlemler
                    console.log("Seçilen Tarih: " + dateText);
                    // Burada AJAX çağrısı yaparak tablo verilerini güncelleyebilirsiniz
                    // updateTableByDate(dateText); // Eğer bir fonksiyonunuz varsa
                },
                onClose: function(dateText, inst) {
                    // Datepicker kapandığında yapılacak işlemler (isteğe bağlı)
                }
            });
        });

        // Dropdown menüyü açıp kapatma fonksiyonu (Tüm Kayıtlar)
        function toggleDropdown() {
            document.getElementById("recordsDropdown").classList.toggle("show");
        }

        // Dropdown dışına tıklanınca kapatma
        window.onclick = function(event) {
            // "Tüm Kayıtlar" dropdown'ı için
            if (!event.target.matches('.dropbtn') && !$(event.target).closest('.dropdown-content').length && !$(event.target).closest('.ui-datepicker').length) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
            // Bu senaryoda filtreleme artık bir dropdown değil, bu yüzden toggleFilterDropdown veya ilgili JS'ye ihtiyaç kalmadı.
            // Eğer "Filtrele" butonu tıklanınca farklı bir filtre menüsü açılacaksa, o kısım yeniden tasarlanabilir.
        }
    </script>
</body>

</html>