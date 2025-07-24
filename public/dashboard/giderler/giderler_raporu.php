<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giderler Raporu - Mutabık</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">

    <style>
        /* CSS Başlangıcı */

        /* Genel Sıfırlamalar ve Temel Ayarlar */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            /* Genel arka plan rengi */
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            /* Yatay kaydırmayı engelle */
            min-height: 100vh;
            /* Sayfanın en az viewport yüksekliği kadar olmasını sağla */
            display: flex;
            /* Flexbox ile main-content'i yönetmek için */
            flex-direction: column;
            /* İçeriğin dikeyde akmasını sağlar */
        }

        /* Ana İçerik Alanı */
        .main-content {
            flex-grow: 1;
            /* Kalan tüm genişliği kaplar */
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            /* Genel arka planı */
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
            min-height: 60px;
        }

        .header-section h1 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .header-button {
            background-color: #28a745;
            /* Yeşil tonu */
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin-right: 15px;
            transition: background-color 0.3s ease;
        }

        .header-button:hover {
            background-color: #218838;
        }

        .user-info {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #555;
        }

        .user-info img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            margin-left: 8px;
            object-fit: cover;
        }




        /* Filter Section */
        .filter-section {
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .filter-left {
            display: flex;
            align-items: center;
        }

        .filter-button {
            background-color: #f0f2f5;
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 15px;
            font-size: 14px;
            color: #555;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
        }

        .filter-button i {
            margin-right: 5px;
        }

        .filter-button i.fa-caret-down {
            margin-left: 5px;
            margin-right: 0;
        }

        .filter-button:hover {
            background-color: #e2e6ea;
        }

        .date-range {
            font-weight: normal;
            color: #333;
            margin-right: 20px;
            font-size: 14px;
        }

        .tax-toggle {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }

        /* Toggle Switch CSS */
        .switch {
            position: relative;
            display: inline-block;
            width: 38px;
            height: 22px;
            margin-left: 10px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }

        /* Tabs Section */
        .tabs-container {
            display: flex;
            background-color: #fff;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
        }

        .tab-button {
            padding: 12px 20px;
            border: none;
            background-color: transparent;
            cursor: pointer;
            font-size: 15px;
            color: #555;
            position: relative;
            flex-grow: 1;
            text-align: center;
            transition: color 0.3s ease;
        }

        .tab-button:hover {
            color: #007bff;
        }

        .tab-button.active {
            color: #007bff;
            font-weight: bold;
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #007bff;
        }

        /* Report Sections */
        .report-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .section-header h2 {
            font-size: 18px;
            color: #333;
            margin: 0;
        }

        /* KATEGORİLERİN DAĞILIMI ve KATEGORİLERİN DEĞİŞİMİ yazıları için */
        .section-header-buttons .inactive-header-text {
            font-size: 14px;
            /* Tablo başlıklarıyla aynı boyutta */
            margin-left: 15px;
            /* İki yazı arasında biraz boşluk bırakın */
            color: #666;
            /* Tablo başlıklarıyla aynı renk */
            font-weight: normal;
            /* Kalınlık normal olsun */
            text-transform: uppercase;
            /* Büyük harfli olsun */
            letter-spacing: 0.5px;
            /* Harf aralığı olsun */
            white-space: nowrap;
            /* Tek satırda kalmasını sağlar */
        }

        /* .section-header-buttons button için önceki stilleri geri getiriyoruz */
        .section-header-buttons button {
            background-color: #f0f2f5;
            border: 1px solid #ccc;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            /* Cursor pointer olsun, tıklanabilir oldukları için */
            font-size: 13px;
            margin-left: 10px;
            color: #555;
            transition: background-color 0.3s ease;
        }

        .section-header-buttons button:hover {
            background-color: #e2e6ea;
        }

        .section-header-buttons button.primary {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .section-header-buttons button.primary:hover {
            background-color: #0056b3;
        }


        /* Tablo başlıklarının kendi stillerinin doğru olduğundan emin olalım */
        .report-table th {
            background-color: #f8f9fa;
            /* Arka plan rengi */
            color: #666;
            /* Metin rengi */
            font-weight: normal;
            /* Normal font kalınlığı */
            font-size: 14px;
            /* Font boyutu */
            text-transform: uppercase;
            /* Büyük harf */
            letter-spacing: 0.5px;
            /* Hafif harf aralığı */
            padding: 12px;
            text-align: left;
            white-space: nowrap;
        }

        /* Responsive Düzenlemeler */
        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .header-right {
                margin-top: 10px;
                width: 100%;
                justify-content: space-between;
            }

            .filter-section {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .filter-left {
                margin-bottom: 15px;
                width: 100%;
                justify-content: space-between;
            }

            .tax-toggle {
                width: 100%;
                justify-content: space-between;
            }

            .tabs-container {
                flex-wrap: wrap;
            }

            .tab-button {
                flex-basis: 50%;
                padding: 10px;
                font-size: 14px;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            /* section-header-buttons responsive ayarlarına .inactive-header-text'i ekle */
            .section-header-buttons {
                margin-top: 15px;
                width: 100%;
                display: flex;
                flex-wrap: wrap;
            }

            /* Bu kısım güncellendi: span ve button için ayrı ayarlar */
            .section-header-buttons .inactive-header-text {
                /* Sadece metin olan kısım */
                margin-left: 0;
                margin-right: 10px;
                /* Aralarındaki boşluk */
                margin-bottom: 10px;
                font-size: 13px;
                padding: 0;
                /* Artık buton gibi bir padding'e ihtiyaç yok */
            }

            .section-header-buttons button {
                /* Butonlar için */
                margin-left: 0;
                margin-right: 10px;
                margin-bottom: 10px;
                font-size: 13px;
            }

            .category-grid {
                grid-template-columns: 1fr;
            }

            .filter-left {
                flex-direction: column;
                /* Küçük ekranlarda dikey sıralama */
                align-items: flex-start;
                /* Sola hizalama */
                margin-bottom: 15px;
                /* Alt boşluk */
                width: 100%;
                /* Tam genişlik */
            }

            .filter-left .filter-button {
                margin-bottom: 10px;
                /* Buton ile tarih seçicinin arasına boşluk */
                margin-right: 0;
                /* Sağdaki margin'i sıfırla */
                width: 100%;
                /* Butonun tam genişlikte olması için */
                justify-content: center;
                /* Buton içeriğini ortala */
            }

            .date-range-inputs {
                flex-direction: column;
                /* Tarih input'larını küçük ekranlarda alt alta getir */
                gap: 5px;
                /* Inputlar arasındaki boşluğu azalt */
                width: 100%;
                /* Tam genişlik */
            }

            .date-range-inputs input[type="date"] {
                width: calc(100% - 22px);
                /* Padding ve border dahil tam genişlik */
                box-sizing: border-box;
                /* Padding ve border'ı width'e dahil et */
                text-align: center;
                /* İçeriği ortala */
            }
        }

        /* CSS Bitişi */
        .report-table-container {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .report-table th,
        .report-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
            color: #444;
            white-space: nowrap;
        }

        .report-table tbody tr:hover {
            background-color: #f0f2f5;
        }

        .empty-message-table {
            text-align: center;
            padding: 20px !important;
            color: #777;
            font-size: 14px;
        }

        .export-button-container {
            text-align: right;
            margin-top: 20px;
        }

        .export-button {
            background-color: #6c757d;
            /* Gri */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .export-button:hover {
            background-color: #5a6268;
        }

        /* Tarih inputları için yeni stil */
        .date-range-inputs {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Inputlar arasında boşluk */
        }

        .date-range-inputs input[type="date"] {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            -webkit-appearance: none;
            /* iOS Safari'de varsayılan stilini kaldırır */
            -moz-appearance: none;
            /* Firefox'ta varsayılan stilini kaldırır */
            appearance: none;
            /* Genel olarak varsayılan stilini kaldırır */
            background-color: #f9f9f9;
            /* Hafif bir arka plan rengi */
            cursor: pointer;
        }

        .date-range-inputs input[type="date"]::-webkit-calendar-picker-indicator {
            /* Chrome ve Safari'de takvim ikonunu özelleştirme */
            filter: invert(0.5) sepia(1) saturate(5) hue-rotate(170deg);
            /* Mavi tonu bir ikon için */
            cursor: pointer;
        }

        .profile-dropdown {
            position: absolute;
            top: 60px;
            right: 25px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            min-width: 180px;
            z-index: 2000;
            display: none;
        }

        .profile-dropdown.active {
            display: block;
        }

        .profile-dropdown ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .profile-dropdown li {
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-dropdown li:last-child {
            border-bottom: none;
        }

        .profile-dropdown a,
        .profile-dropdown button {
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

        .profile-dropdown a:hover,
        .profile-dropdown button:hover {
            background: #f5f5f5;
        }

        .profile-widget {
            position: fixed;
            top: 80px;
            right: 40px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            min-width: 320px;
            z-index: 3000;
            display: none;
            padding: 28px 32px 24px 32px;
        }

        .profile-widget.active {
            display: block;
        }

        .profile-widget .profile-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 18px;
            color: #2c3e50;
        }

        .profile-widget .profile-row {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .profile-widget .profile-row i {
            width: 22px;
            color: #888;
            margin-right: 10px;
        }

        .profile-widget .profile-label {
            font-weight: 500;
            color: #888;
            width: 120px;
        }

        .profile-widget .profile-value {
            color: #2c3e50;
        }

        .profile-widget .close-btn {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            font-size: 20px;
            color: #888;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>
    <main class="main-content">
        <header class="header-section">
            <h1>Giderler Raporu</h1>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </header>

        <div class="content-area">
            <div class="filter-section">
                <div class="filter-left">
                    <button class="filter-button"><i class="fas fa-filter"></i> FİLTRELE <i class="fas fa-caret-down"></i></button>
                    <div class="date-range-inputs">
                        <input type="date" id="startDate" value="2025-07-01">
                        <span>-</span>
                        <input type="date" id="endDate" value="2025-07-22">
                    </div>
                </div>
                <div class="tax-toggle">
                    <label for="tax-include">VERGİLER DAHİL</label>
                    <label class="switch">
                        <input type="checkbox" id="tax-include">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="tabs-container">
                <button class="tab-button active" data-tab="giderler">Giderler</button>
                <button class="tab-button" data-tab="tedarikciler">Tedarikçiler</button>
                <button class="tab-button" data-tab="hizmet-urunler">Hizmet ve Ürünler</button>
                <button class="tab-button" data-tab="harcananlar">Harcananlar</button>
            </div>

            <div class="report-section">
                <div class="section-header">
                    <h2>GİDER KATEGORİLERİ</h2>
                    <div class="section-header-buttons">
                        <span class="inactive-header-text">KATEGORİLERİN DAĞILIMI</span>
                        <span class="inactive-header-text">KATEGORİLERİN DEĞİŞİMİ</span>
                    </div>
                </div>
                <?php if (empty($giderKategorileri)): ?>
                    <div class="empty-message">
                        Belirtilen tarih aralığında gideriniz yok.
                    </div>
                <?php else: ?>
                    <div class="category-grid">
                        <?php foreach ($giderKategorileri as $category): ?>
                            <div class="category-item">
                                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p><?php echo htmlspecialchars($category['value']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="report-section">
                <div class="section-header">
                    <h2>TÜM GİDER KAYITLARI</h2>
                    <div class="section-header-buttons">
                        <button class="primary">GİDER KAYITLARI</button>
                        <button>TEDARİKÇİLER</button>
                        <button>HİZMET / ÜRÜNLER</button>
                    </div>
                </div>

                <div class="report-table-container">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>KAYIT İSMİ</th>
                                <th>DÜZENLEME TARİHİ</th>
                                <th>BAKİYE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tumGiderKayitlari)): ?>
                                <tr>
                                    <td colspan="3" class="empty-message-table">Veri bulunamadı.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tumGiderKayitlari as $kayit): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($kayit['kayit_ismi']); ?></td>
                                        <td><?php htmlspecialchars($kayit['duzenleme_tarihi']); ?></td>
                                        <td><?php htmlspecialchars($kayit['bakiye']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="export-button-container">
                    <button class="export-button">DIŞARI AKTAR</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // JavaScript Başlangıcı

        // Tab butonları için JavaScript (Fazla tekrar eden kodları birleştirdim)
        document.querySelectorAll('.tabs-container .tab-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tabs-container .tab-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // Burada ilgili sekmenin içeriğini dinamik olarak yükleyebilirsiniz veya gösterebilirsiniz.
            });
        });

        // Toggle switch için basit JavaScript (Fazla tekrar eden kodu birleştirdim)
        document.getElementById('tax-include').addEventListener('change', function() {
            console.log('Vergiler Dahil:', this.checked);
        });

        // Tarih seçiciler için JavaScript
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        // Başlangıç ve bitiş tarihi varsayılan değerlerini bugünün tarihi olarak ayarla (veya istediğiniz gibi)
        // Şu anki tarih 22 Temmuz 2025 olduğu için başlangıcı 1 Temmuz, bitişi 22 Temmuz olarak tutalım.
        // startDateInput.value = "2025-07-01"; // İlk yüklemede varsayılan değer
        // endDateInput.value = "2025-07-22";   // İlk yüklemede varsayılan değer

        function updateDateRange() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                console.log('Seçilen Tarih Aralığı:', startDate, 'ile', endDate);
                // Burada seçilen tarih aralığına göre verileri filtreleme veya PHP'ye gönderme
                // işlemini yapabilirsiniz. Örneğin, AJAX ile sunucuya istek gönderme:
                /*
                fetch(`gider_verileri.php?startDate=${startDate}&endDate=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        // Verileri tabloya veya diğer alanlara yerleştir
                        console.log('Yeni veriler:', data);
                    })
                    .catch(error => console.error('Veri çekme hatası:', error));
                */
            }
        }

        // Tarih değiştiğinde updateDateRange fonksiyonunu çağır
        startDateInput.addEventListener('change', updateDateRange);
        endDateInput.addEventListener('change', updateDateRange);

        // Sayfa yüklendiğinde bir kez tarihleri güncelle (opsiyonel)
        // updateDateRange(); // Eğer varsayılan değerlerle hemen bir işlem yapmak isterseniz
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>

</body>

</html>