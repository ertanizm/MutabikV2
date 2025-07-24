<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Depolar Arası Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
            margin-left: 280px;
            position: relative; /* Add this to make absolute positioning work relative to the container */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            gap: 10px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            padding: 6px;
            font-size: 14px;
            width: 900px;
        }

        .button {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 8px 14px;
            cursor: pointer;
            border-radius: 4px;
        }

        .table {
            margin-top: 30px;
            background: white;
            border-radius: 6px;
            padding: 20px;
        }

        .table-header {
            display: flex;
            font-weight: bold;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .table-header div,
        .empty-row div {
            flex: 1;
        }

        .empty-row {
            text-align: center;
            padding: 50px 0;
            color: #888;
        }

        /* New CSS for positioning the profile dropdown */
        .profile-dropdown-container {
            position: absolute;
            top: 30px; /* Adjust as needed for vertical alignment */
            right: 30px; /* Adjust as needed for horizontal alignment */
            z-index: 1000; /* Ensure it's above other elements if necessary */
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <div class="container">
        <h2 class="page-title">Depolar Arası Transfer</h2>
        <div class="header">
            <div class="header-left">
                <button class="button">Filtrele</button>
                <input type="text" placeholder="Ara...">
            </div>

            <div class="header-right">
                <button class="button">Yeni Transfer Fişi Oluştur</button>
            </div>
        </div>

        <div class="profile-dropdown-container">
            <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
        </div>


        <div class="table">
            <div class="table-header">
                <div>Hareket İsmi</div>
                <div>Çıkış Deposu</div>
                <div>Giriş Deposu</div>
                <div>Düzenleme Tarihi</div>
            </div>
            <div class="empty-row">
                <div style="flex: 1;">Depolar Arası Transfer sayfasına hoş geldiniz! <br> MUTABIK 'a kaydedeceğiniz depo
                    transfer fişlerinize bu sayfadan ulaşacaksınız.</div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
</body>

</html>
