<?php
// Gerekli oturum ve kullanıcı bilgileri burada alınabilir (gerekirse)
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yazdırma Şablonları</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        body { background: #e5e4e2; }
        .template-header-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 24px;
            margin-bottom: 18px;
        }
        .template-create-btn {
            background: #4a423a;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 1.08rem;
            transition: background 0.2s;
        }
        .template-create-btn:hover { background: #2c3e50; }
        .template-welcome-box {
            background: #e5e4e2;
            border-radius: 10px;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 0 auto;
            margin-bottom: 32px;
            width: 100%;
            padding: 60px 0;
        }
        .template-welcome-title {
            font-size: 1.6rem;
            font-weight: 400;
            color: #222;
            margin-bottom: 32px;
        }
        .template-welcome-icon {
            background: #ddd;
            border-radius: 50%;
            width: 140px;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
        }
        .template-welcome-icon i {
            font-size: 70px;
            color: #aaa;
        }
        .template-welcome-desc {
            color: #bdbdbd;
            font-size: 1.1rem;
            max-width: 420px;
            margin: 0 auto;
        }
        @media (max-width: 900px) {
            .template-welcome-box { min-height: 320px; padding: 30px 0; }
            .template-welcome-title { font-size: 1.2rem; }
            .template-welcome-icon { width: 90px; height: 90px; }
            .template-welcome-icon i { font-size: 40px; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Yazdırma Şablonları</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="template-header-row">
            <button class="template-create-btn">YENİ YAZDIRMA ŞABLONU OLUŞTUR</button>
        </div>
        <div class="template-welcome-box">
            <div class="template-welcome-title">Yazdırma şablonları sayfasına hoş geldiniz!</div>
            <div class="template-welcome-icon"><i class="fas fa-print"></i></div>
            <div class="template-welcome-desc">
                Mutabık'ta oluşturacağınız tüm yazdırma şablonlarına<br>bu sayfadan ulaşacaksınız. İlk şablonunuzu oluşturun.
            </div>
        </div>
    </div>
    <script src="../script2.js"></script>
</body>
</html> 