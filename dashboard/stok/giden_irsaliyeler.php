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

    <style>
        /* GENEL VE BODY STİLLERİ */
        body {
            display: flex;
            /* Sidebar ve içerik için flexbox */
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            /* Açık gri arka plan */
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Ana İçerik Konteyneri */
        .container {
            flex-grow: 1;
            /* Sidebar dışında kalan alanı kapla */
            padding: 20px 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-left: 250px;
            /* Sidebar genişliği kadar boşluk bırak, sidebar'ınızın genişliğine göre ayarlayın */
            transition: margin-left 0.3s ease;
            /* Sidebar kapanıp açıldığında geçiş efekti */
        }

        /* Üst Başlık ve Profil Alanı */
        .top-header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin: 0;
            /* Flexbox'tan dolayı varsayılan margin'i sıfırla */
        }

        /* Arama/Filtre ve Buton Bölümü */
        .header-actions {
            display: flex;
            justify-content: space-between;
            /* Filtre ve butonları yan yana */
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            /* Küçük ekranlarda alt alta geçiş */
            gap: 15px;
            /* Elemanlar arası boşluk */
        }

        /* Filtre ve Arama Alanı (Yan Yana) */
        .search-filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-grow: 1;
            /* Mevcut alanı kapla */
            min-width: 300px;
            /* Daralmayı engelle */
            flex-wrap: wrap;
            /* Küçük ekranlarda sarmalama */
        }

        .search-filter-button {
            background-color: #e9ecef;
            color: #495057;
            padding: 8px 15px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s ease, border-color 0.2s ease;
            white-space: nowrap;
            /* Metnin tek satırda kalması */
        }

        .search-filter-button:hover,
        .search-filter-button:focus {
            background-color: #dee2e6;
            border-color: #adb5bd;
        }

        .search-filter-input-group {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-grow: 1;
            /* Input'un kalan alanı doldurması */
        }

        .search-filter-input-group input[type="text"] {
            flex-grow: 1;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-filter-input-group button {
            background-color: #007bff;
            /* Mavi arama butonu */
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-filter-input-group button:hover {
            background-color: #0056b3;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            /* Butonlar arası boşluk */
            flex-wrap: wrap;
            justify-content: flex-end;
            /* Sağ tarafa hizala */
        }

        .action-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
        }

        .action-buttons .btn-primary {
            background-color: #28a745;
            /* Yeşil buton */
            color: white;
        }

        .action-buttons .btn-primary:hover {
            background-color: #218838;
        }

        .action-buttons .btn-secondary {
            background-color: #6c757d;
            /* Gri buton */
            color: white;
        }

        .action-buttons .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Tablo Stilleri */
        .delivery-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .delivery-table th,
        .delivery-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .delivery-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }

        .delivery-table td {
            color: #666;
        }

        /* Tarih Tetikleyici İkon ve Sıralama İkonu */
        .date-header-icons {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 5px;
            /* Başlık metnine biraz boşluk */
        }

        .date-header-icons .date-icon-trigger,
        .date-header-icons .sort-icon {
            cursor: pointer;
            font-size: 1.1em;
            color: #555;
            transition: color 0.2s ease;
        }

        .date-header-icons .date-icon-trigger:hover,
        .date-header-icons .sort-icon:hover {
            color: #007bff;
            /* Hover'da ikon rengi */
        }

        /* Boş Durum (Empty State) */
        .empty-state {
            text-align: center;
            padding: 50px 0;
            color: #888;
            font-size: 16px;
            background-color: #fdfdfd;
            border-radius: 8px;
            margin-top: 20px;
        }

        .empty-state p {
            margin-bottom: 25px;
            font-size: 18px;
            font-weight: 500;
            color: #555;
        }

        .info-text-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        .info-text {
            font-size: 16px;
            color: #666;
            padding: 10px 20px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: fit-content;
            text-align: center;
        }

        /* Footer ve Sayfalama */
        .footer-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Dropdown menü (Tüm Kayıtlar) */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #e9ecef;
            color: #495057;
            padding: 8px 15px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .dropbtn:hover,
        .dropbtn:focus {
            background-color: #dee2e6;
            border-color: #adb5bd;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            /* Diğer öğelerin üzerinde görünmesi için */
            border-radius: 5px;
            overflow: hidden;
            bottom: 100%;
            /* Dropup menü için */
            left: 0;
            margin-bottom: 5px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s ease;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown.show .dropdown-content {
            display: block;
        }

        /* jQuery UI Datepicker Özelleştirmeleri */
        .ui-datepicker {
            font-family: Arial, sans-serif;
            font-size: 14px;
            /* Hafifçe büyütüldü */
            border: none;
            /* Kenarlık kaldırıldı */
            border-radius: 8px;
            /* Köşeler daha yuvarlak */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            /* Daha belirgin gölge */
            z-index: 1001 !important;
            background-color: #fff;
            /* Arka plan beyaz */
            padding: 15px;
            /* İç boşluk eklendi */
        }

        .ui-datepicker .ui-datepicker-header {
            background-color: #fff;
            /* Başlık arka planı beyaz */
            color: #333;
            /* Metin rengi siyah */
            padding: 0 0 15px 0;
            /* Üst boşluk kaldırıldı, alt boşluk eklendi */
            border-radius: 0;
            /* Köşeler düz */
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            /* Ay ve yıl daha belirgin */
        }

        .ui-datepicker .ui-datepicker-title {
            margin: 0;
            line-height: 1.8em;
            /* Metin dikey hizalaması için */
            text-align: center;
            flex-grow: 1;
            /* Ortadaki ay/yıl alanını genişlet */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            /* Ay ve yıl arasına boşluk */
        }

        .ui-datepicker .ui-datepicker-month,
        .ui-datepicker .ui-datepicker-year {
            font-size: 16px;
            /* Ay ve yıl font büyüklüğü */
            color: #333;
            font-weight: bold;
        }

        .ui-datepicker .ui-datepicker-prev,
        .ui-datepicker .ui-datepicker-next {
            top: 0;
            /* Kutuya göre üstte hizala */
            cursor: pointer;
            width: 2em;
            /* Okların tıklama alanını büyüt */
            height: 2em;
            /* Okların tıklama alanını büyüt */
            line-height: 2em;
            /* Dikey hizalama */
            text-align: center;
            color: #666;
            /* Ok rengi gri */
            background-color: transparent;
            /* Arka planı şeffaf */
            border: none;
            /* Kenarlık yok */
            border-radius: 50%;
            /* Yuvarlak ok butonları */
            transition: background-color 0.2s ease;
        }

        .ui-datepicker .ui-datepicker-prev span,
        .ui-datepicker .ui-datepicker-next span {
            display: none;
            /* Varsayılan ok metinlerini gizle */
        }

        .ui-datepicker .ui-datepicker-prev:after {
            content: '<';
            /* Font Awesome ikonu yerine direkt karakter */
            font-size: 1.2em;
        }

        .ui-datepicker .ui-datepicker-next:after {
            content: '>';
            /* Font Awesome ikonu yerine direkt karakter */
            font-size: 1.2em;
        }

        .ui-datepicker .ui-datepicker-prev:hover,
        .ui-datepicker .ui-datepicker-next:hover {
            background-color: #f0f0f0;
            /* Hover rengi */
        }

        /* Hafta günleri başlıkları */
        .ui-datepicker .ui-datepicker-calendar th {
            padding: 8px 5px;
            text-align: center;
            font-size: 12px;
            color: #888;
            /* Hafta günleri rengi */
            border: none;
            /* Kenarlık kaldırıldı */
            background-color: transparent;
            /* Arka plan kaldırıldı */
            font-weight: normal;
            /* Normal font kalınlığı */
        }

        /* Günler */
        .ui-datepicker td {
            padding: 0;
            /* Varsayılan padding'i kaldır */
            border: none;
            /* Kenarlık kaldırıldı */
        }

        .ui-datepicker td span,
        .ui-datepicker td a {
            padding: 8px;
            /* Günlerin etrafındaki boşluk */
            border-radius: 50%;
            /* Günler yuvarlak */
            text-align: center;
            display: flex;
            /* İçeriği ortalamak için flexbox */
            justify-content: center;
            align-items: center;
            color: #333;
            /* Gün metin rengi */
            transition: background-color 0.2s ease, color 0.2s ease;
            width: 32px;
            /* Gün kutusu genişliği */
            height: 32px;
            /* Gün kutusu yüksekliği */
            box-sizing: border-box;
            /* Padding ve border genişliğe dahil */
        }

        .ui-datepicker td.ui-datepicker-other-month span {
            color: #bbb;
            /* Diğer ayların günleri daha soluk */
        }

        .ui-datepicker td a.ui-state-default {
            background-color: transparent;
            border: none;
        }

        .ui-datepicker td a.ui-state-hover {
            background-color: #e0e0e0;
            /* Hover'da hafif gri arka plan */
            color: #333;
        }

        .ui-datepicker td a.ui-state-active,
        .ui-datepicker td a.ui-state-highlight {
            background-color: #007bff;
            /* Seçili gün mavi */
            color: white;
            /* Seçili gün metni beyaz */
            font-weight: bold;
        }

        /* Tarih seçicinin ay/yıl dropdown'ları için stiller */
        .ui-datepicker select.ui-datepicker-month,
        .ui-datepicker select.ui-datepicker-year {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px;
            font-size: 14px;
            margin: 0 5px;
            cursor: pointer;
        }

        .ui-datepicker select.ui-datepicker-month:focus,
        .ui-datepicker select.ui-datepicker-year:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>

    <div class="container">
        <div class="top-header-section">
            <h1>Giden İrsaliyeler</h1>
            <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
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