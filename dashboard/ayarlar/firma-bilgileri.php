<?php
// Gerekli oturum ve kullanıcı bilgileri burada alınabilir (gerekirse)
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Bilgileri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/ayarlar/firma-bilgileri.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Firma Bilgileri</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="container-fluid">
            <div class="company-card mt-3">
                <div class="company-card-header">
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-building icon"></i>
                        <span class="company-title">atia yazılım</span>
                    </div>
                    <button class="edit-btn" id="openEditModal">DÜZENLE</button>
                </div>
                <hr style="margin: 18px 0 0 0;">
                <div class="company-info-list">
                    <div class="info-row">
                        <span class="icon"><i class="fas fa-building"></i></span>
                        <span class="label">Ticari Unvan</span>
                    </div>
                    <div class="info-row">
                        <span class="icon"><i class="fas fa-file-alt"></i></span>
                        <span class="label">Evrak Türü</span>
                        <span class="value">Fatura</span>
                    </div>
                    <div class="info-row">
                        <span class="icon"><i class="fas fa-industry"></i></span>
                        <span class="label">Sektör</span>
                        <span class="value">Diğer</span>
                    </div>
                    <div class="info-row">
                        <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span class="label">Açık Adresi</span>
                    </div>
                </div>
                <div class="company-card-footer">
                    <div class="vergi-row">
                        <span class="icon"><i class="fas fa-gavel"></i></span>
                        <span class="label">Vergi Bilgileri</span>
                        <span class="sub-label">V.D.</span>
                        <span class="sub-label">V.NO.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="edit-modal-overlay" id="editModalOverlay">
        <div class="edit-modal">
            <div class="edit-modal-header">
                <span class="edit-modal-title">Firma Bilgilerini Düzenle</span>
                <button class="edit-modal-close" id="closeEditModal">&times;</button>
            </div>
            <form class="edit-modal-body" id="editCompanyForm">
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-building"></i>FİRMA ADI *</label>
                    <input type="text" class="edit-form-input" value="atia yazılım">
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="far fa-image"></i>FİRMA LOGOSU</label>
                    <div class="edit-form-upload-box">
                        <span class="edit-form-upload-preview"><i class="fas fa-building"></i></span>
                        <button type="button" class="edit-form-upload-btn">LOGO YÜKLE</button>
                    </div>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-pen"></i>FİRMA İMZASI</label>
                    <div class="edit-form-upload-box">
                        <span class="edit-form-upload-preview"><i class="fas fa-pen"></i></span>
                        <button type="button" class="edit-form-upload-btn">İMZA YÜKLE</button>
                    </div>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-building"></i>TİCARİ UNVAN</label>
                    <input type="text" class="edit-form-input">
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-file-alt"></i>EVRAK TÜRÜ</label>
                    <input type="text" class="edit-form-input" value="Fatura" disabled>
                    <span class="edit-form-hint">Evrak türünüz <b>Fatura</b> olarak belirlenmiştir. Seçiminizde değişiklik yapmak için lütfen <b>destek</b> ekibimiz ile iletişime geçin.</span>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-industry"></i>SEKTÖR</label>
                    <select class="edit-form-select">
                        <option>Diğer</option>
                        <option>Yazılım</option>
                        <option>İnşaat</option>
                        <option>Sağlık</option>
                        <option>Gıda</option>
                    </select>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-map-marker-alt"></i>AÇIK ADRESİ</label>
                    <textarea class="edit-form-textarea"></textarea>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-map-marker-alt"></i>İLÇE, İL</label>
                    <input type="text" class="edit-form-input" placeholder="İlçe" style="max-width:120px;">
                    <button type="button" class="edit-form-upload-btn" style="padding:6px 12px;"><i class="fas fa-search"></i></button>
                    <input type="text" class="edit-form-input" placeholder="İl" style="max-width:120px; margin-left:12px;">
                    <button type="button" class="edit-form-upload-btn" style="padding:6px 12px;"><i class="fas fa-search"></i></button>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-phone"></i>TELEFON</label>
                    <input type="text" class="edit-form-input">
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-fax"></i>FAKS</label>
                    <input type="text" class="edit-form-input">
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-gavel"></i>VERGİ DAİRESİ</label>
                    <input type="text" class="edit-form-input">
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-hashtag"></i>VERGİ NUMARASI</label>
                    <input type="text" class="edit-form-input">
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-university"></i>MERSİS NUMARASI</label>
                    <input type="text" class="edit-form-input">
                    <span class="edit-form-hint">Mersis numaranızı <b>mersis.gtb.gov.tr</b> adresinden öğrenebilirsiniz.</span>
                </div>
                <div class="edit-form-row">
                    <label class="edit-form-label"><i class="fas fa-credit-card"></i>TİCARET SİCİL NUMARASI</label>
                    <input type="text" class="edit-form-input">
                    <span class="edit-form-hint">Şahıs şirketleri için Ticari Sicil Numarası bilgisi zorunlu değildir.</span>
                </div>
            </form>
            <div class="edit-modal-footer">
                <button type="button" class="edit-modal-cancel" id="cancelEditModal">Vazgeç</button>
                <button type="submit" class="edit-modal-save">Kaydet</button>
            </div>
        </div>
    </div>
    <script src="../script2.js"></script>
    <script>
        const openEditModalBtn = document.getElementById('openEditModal');
        const editModalOverlay = document.getElementById('editModalOverlay');
        const closeEditModalBtn = document.getElementById('closeEditModal');
        const cancelEditModalBtn = document.getElementById('cancelEditModal');
        const editCompanyForm = document.getElementById('editCompanyForm');
        openEditModalBtn.addEventListener('click', () => {
            editModalOverlay.style.display = 'flex';
        });
        closeEditModalBtn.addEventListener('click', () => {
            editModalOverlay.style.display = 'none';
        });
        cancelEditModalBtn.addEventListener('click', () => {
            editModalOverlay.style.display = 'none';
        });
        // Modal dışında bir yere tıklayınca kapansın
        editModalOverlay.addEventListener('click', (e) => {
            if (e.target === editModalOverlay) {
                editModalOverlay.style.display = 'none';
            }
        });
        // Kaydet butonuna basınca form submit olursa modalı kapat ve alert göster (örnek)
        editCompanyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            editModalOverlay.style.display = 'none';
            alert('Firma bilgileri kaydedildi!');
        });
    </script>
</body>
</html> 