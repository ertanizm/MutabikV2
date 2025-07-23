<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giderler</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --sidebar-bg: #34495e;
            --card-bg: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-color: #ecf0f1;
            --button-bg-light: #f0f2f5;
            /* For filter/search buttons */
            --button-text-light: #555;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            /* Adjust based on sidebar width, assuming sidebar.css sets this */
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
            background-color: #f8f9fa;
            /* Ensure main content has a background */
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* YENİ Üst Başlık Stilleri */
        .top-header {
            display: flex;
            justify-content: space-between;
            /* Sol ve sağ elemanları ayırır */
            align-items: center;
            /* Dikeyde ortalar */
            background-color: var(--card-bg);
            /* Başlık çubuğu için beyaz arka plan */
            padding: 15px 25px;
            /* Başlık içindeki boşluk */
            border-radius: 8px;
            /* Hafif yuvarlak köşeler */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            /* Hafif gölge */
            margin-bottom: 25px;
            /* Başlığın altındaki boşluk */
        }

        .header-left h1 {
            font-size: 24px;
            color: var(--text-primary);
            margin: 0;
            /* h1'in varsayılan margin'ini kaldır */
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
            /* header-right içindeki elemanlar arasında boşluk */
        }

        /* Aksiyon Buton Çubuğu (Üst başlıktan ayrılmış) */
        .header-actions-bar {
            display: flex;
            justify-content: flex-end;
            /* Butonları sağa hizala */
            gap: 10px;
            /* Butonlar arası boşluk */
            background-color: var(--card-bg);
            /* Başlık arka planıyla eşleştir (isteğe bağlı) */
            padding: 15px 25px;
            /* Başlık padding'iyle eşleştir (isteğe bağlı) */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            /* Sonraki bölümden önceki boşluk */
            flex-wrap: wrap;
            /* Küçük ekranlarda butonların alt alta geçmesini sağlar */
        }


        /* General Button Styles */
        .button {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background-color 0.2s ease, color 0.2s ease;
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        /* Specific Button Styles for the action bar */
        .header-actions-bar .button {
            background-color: var(--secondary-color);
            color: white;
        }

        .header-actions-bar .button:hover {
            background-color: #2980b9;
        }

        /* Dropdown Container */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            background-color: var(--secondary-color);
            color: white;
            /* Inherits button styles */
        }

        .dropdown-toggle:hover {
            background-color: #2980b9;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--card-bg);
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 10;
            /* Ensure dropdown is above other content */
            border-radius: 5px;
            overflow: hidden;
            /* For rounded corners */
            right: 0;
            /* Align dropdown to the right of the button */
            margin-top: 5px;
            border: 1px solid var(--border-color);
        }

        .dropdown-content a {
            color: var(--text-primary);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        .dropdown-content a:hover {
            background-color: var(--button-bg-light);
        }

        .dropdown-header {
            padding: 12px 16px;
            font-weight: bold;
            color: var(--text-secondary);
            font-size: 13px;
            background-color: #f8f9fa;
            /* Slightly different background for headers */
            cursor: default;
            border-bottom: 1px solid var(--border-color);
            /* Separator for headers */
            margin-top: -1px;
            /* To prevent double border with first item */
        }

        .dropdown-content a:first-of-type {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .dropdown-content a:last-of-type {
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }


        /* Filter Section */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .filter-section .button {
            background-color: var(--button-bg-light);
            color: var(--button-text-light);
            border: 1px solid var(--border-color);
            padding: 9px 15px;
        }

        .filter-section .button:hover {
            background-color: #e2e6ea;
        }

        .filter-section input[type="text"] {
            flex-grow: 1;
            /* Take remaining space */
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 14px;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.2s ease;
        }

        .filter-section input[type="text"]:focus {
            border-color: var(--secondary-color);
        }

        /* Expense List Container */
        .expense-list-container {
            background-color: var(--card-bg);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .expense-list-container p {
            font-size: 15px;
            color: var(--text-secondary);
            margin-bottom: 20px;
            text-align: center;
            /* Center the welcome message */
        }

        .expense-table-headers {
            display: flex;
            justify-content: space-between;
            /* Adjust as needed for more columns */
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .expense-table-headers span {
            flex: 1;
            /* Distribute space evenly among headers */
            padding: 0 10px;
        }

        .expense-items {
            /* This will be populated by PHP, but ensure it has space */
            min-height: 150px;
            /* Placeholder height */
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-secondary);
            font-style: italic;
        }

        /* Footer Section */
        .dashboard-footer {
            display: flex;
            justify-content: flex-start;
            /* Align dropdowns to the left */
            gap: 15px;
            /* Space between dropdown buttons */
            margin-top: 30px;
            /* Space from the content above */
            padding: 15px 25px;
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .dashboard-footer .dropdown .dropdown-toggle {
            background-color: var(--button-bg-light);
            color: var(--button-text-light);
            border: 1px solid var(--border-color);
        }

        .dashboard-footer .dropdown .dropdown-toggle:hover {
            background-color: #e2e6ea;
        }

        /* Responsive adjustments for buttons */
        @media (max-width: 576px) {

            .top-header,
            /* Apply to the new top-header */
            .header-actions-bar,
            /* Also apply to action bar */
            .filter-section,
            .dashboard-footer {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
                padding: 15px;
            }

            /* Adjust top-header for mobile to stack H1 and profile */
            .top-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .header-right {
                margin-top: 10px;
                /* Space between H1 and profile on mobile */
            }

            .header-actions-bar {
                flex-direction: column;
                gap: 10px;
            }

            .header-actions-bar .button,
            .filter-section .button,
            .dashboard-footer .button {
                width: 100%;
                /* Make buttons full width */
            }

            .dropdown-content {
                left: 0;
                /* Make dropdowns full width or centered below button */
                right: auto;
                min-width: unset;
                width: 100%;
                /* Adjust as needed */
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>


    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Giderler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="header-actions-bar">
            <button class="button">HIZLI FATURA</button>
            <button class="button">DETAYLI FİŞ FATURA</button>
            <div class="dropdown">
                <button class="button dropdown-toggle">DİĞER</button>
                <div class="dropdown-content">
                </div>
            </div>
        </div>

        <section class="filter-section">
            <button class="button">FİLTRELE</button>
            <input type="text" placeholder="Ara...">
        </section>

        <section class="expense-list-container">
            <p>Giderler sayfasına hoş geldiniz! Oluşturduğunuz harcama ve alış faturalarınızı buradan takip edebileceksiniz.</p>
            <div class="expense-table-headers">
                <span>KAYIT İSMİ</span>
                <span>DÜZENLENME TARİHİ</span>
            </div>
            <div class="expense-items">
            </div>
        </section>

        <footer class="dashboard-footer">
            <div class="dropdown">
                <button class="button dropdown-toggle">Tüm Kayıtlar</button>
                <div class="dropdown-content">
                    <a href="#">Arşivlenmiş Kayıtlar</a>
                    <a href="#">Tekrarlama Şablonları</a>
                    <a href="#">İptal Edilmiş Kayıtlar</a>
                </div>
            </div>

            <div class="dropdown">
                <button class="button dropdown-toggle">İçe/Dışa Aktar</button>
                <div class="dropdown-content">
                    <span class="dropdown-header">Fiş Fatura</span>
                    <a href="#">İçeri Aktar</a>
                    <span class="dropdown-header">Giderler</span>
                    <a href="#">Dışarı Aktar</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Basic dropdown functionality (you might use a library like jQuery or a more robust pure JS solution)
        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const dropdownContent = this.nextElementSibling;
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            });
        });

        // Close dropdowns if clicked outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    if (content.style.display === 'block') {
                        content.style.display = 'none';
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>

</body>

</html>