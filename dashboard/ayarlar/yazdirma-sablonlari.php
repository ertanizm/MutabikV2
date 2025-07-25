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
    <link href="../../assets/ayarlar/yazdirma-sablonlari.css" rel="stylesheet">
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