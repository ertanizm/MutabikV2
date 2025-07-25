<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giderler</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/giderler/gider_listesi.css" rel="stylesheet">
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