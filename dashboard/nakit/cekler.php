<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Çekler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/finans/cekler.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Çekler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <div class="content">
            <div class="mb-3 d-flex justify-content-between align-items-center gap-3">
                <input type="text" class="form-control flex-grow-1" placeholder="Ara...">
                <button class="btn btn-outline-secondary btn-sm">Filtrele</button>
            </div>

            <div class="table-header">
                <span>DÜZENLEYEN</span>
                <span>ÇEK BİLGİLERİ</span>
                <span>VADE TARİHİ</span>
                <span>KALAN MEBLAĞ</span>
            </div>

            <div class="empty-message">
                <p>Kayıtlı bir çekiniz yok</p>
                <p><small>Tahsilat Ekle butonunu kullanarak oluşturacağınız çekleriniz burada listelenecek</small></p>
            </div>

            <div class="footer">
                <span>Dışarı Aktar</span>
                <span>0 Kayıt &nbsp;|&nbsp; Ödenecek 0,00₺ &nbsp;|&nbsp; Tahsil Edilecek 0,00₺</span>
            </div>
        </div>

        <!-- + Butonu -->
        <button class="add-btn" onclick="togglePopup()">+</button>

        <!-- Tahsilat Ekle Yazısı -->
        <div class="tahsilat-popup" id="popupBox">
            Tahsilat Ekle
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>

    <script>
        function togglePopup() {
            const popup = document.getElementById("popupBox");
            popup.style.display = popup.style.display === "block" ? "none" : "block";
        }
    </script>
</body>

</html>
