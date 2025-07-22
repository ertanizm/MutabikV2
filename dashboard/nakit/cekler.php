<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Çekler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        body {
            background-color: #f6f6f6;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            padding: 1rem;
            background-color: white;
            border-bottom: 1px solid #ddd;
        }

        .content {
            padding: 2rem;
            background-color: white;
            margin: 1rem;
            border-radius: 8px;
            min-height: calc(100vh - 150px);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding: 0.5rem 0;
            font-weight: bold;
            color: #666;
        }

        .empty-message {
            text-align: center;
            margin-top: 4rem;
            color: #999;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-top: 2rem;
            color: #666;
        }

        .add-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #333;
            color: white;
            border-radius: 50%;
            font-size: 24px;
            width: 50px;
            height: 50px;
            border: none;
            z-index: 1000;
        }

        .tahsilat-popup {
            position: fixed;
            bottom: 100px;
            right: 40px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 999;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">

        <div class="header">
            <h4>Çekler</h4>
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
