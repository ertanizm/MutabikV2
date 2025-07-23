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
    <style>
        .company-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.04);
            padding: 0;
            margin: 30px 0 0 0;
            border: none;
        }
        .company-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 32px 32px 0 32px;
        }
        .company-card-header .icon {
            font-size: 38px;
            color: #bdbdbd;
            margin-right: 18px;
        }
        .company-title {
            font-size: 2rem;
            font-weight: 500;
            color: #444;
            margin-bottom: 0;
        }
        .edit-btn {
            border: 1.5px solid #bdbdbd;
            background: #fafafa;
            color: #888;
            font-weight: 600;
            border-radius: 8px;
            padding: 7px 22px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .edit-btn:hover {
            background: #f0f0f0;
            color: #333;
            border-color: #888;
        }
        .company-info-list {
            padding: 32px 32px 0 32px;
        }
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }
        .info-row .icon {
            color: #bdbdbd;
            font-size: 18px;
            width: 28px;
            text-align: center;
            margin-right: 10px;
        }
        .info-row .label {
            color: #bdbdbd;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1rem;
            min-width: 160px;
            text-transform: uppercase;
        }
        .info-row .value {
            color: #444;
            font-weight: 400;
            font-size: 1.08rem;
            margin-left: 32px;
        }
        .company-card-footer {
            background: #fafafa;
            border-radius: 0 0 10px 10px;
            padding: 18px 32px 12px 32px;
            border-top: 1px solid #eee;
        }
        .vergi-row {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }
        .vergi-row .icon {
            color: #bdbdbd;
            font-size: 18px;
            width: 28px;
            text-align: center;
            margin-right: 10px;
        }
        .vergi-row .label {
            color: #bdbdbd;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1rem;
            min-width: 160px;
            text-transform: uppercase;
        }
        .vergi-row .sub-label {
            color: #bdbdbd;
            font-size: 0.98rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-left: 32px;
        }
        @media (max-width: 900px) {
            .company-card-header, .company-info-list, .company-card-footer {
                padding-left: 12px;
                padding-right: 12px;
            }
            .info-row .value, .vergi-row .sub-label {
                margin-left: 16px;
            }
        }
        .edit-modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(44,62,80,0.18);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .edit-modal {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 32px rgba(44,62,80,0.13);
            max-width: 1200px;
            width: 100vw;
            min-width: 0;
            margin: 0 auto;
            padding: 0;
            position: relative;
            animation: modalIn 0.18s cubic-bezier(.4,2,.6,1) 1;
            max-height: 90vh;
            overflow-y: auto;
        }
        @keyframes modalIn {
            from { transform: translateY(40px) scale(0.98); opacity: 0; }
            to { transform: none; opacity: 1; }
        }
        .edit-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 28px 32px 18px 32px;
            border-bottom: 1px solid #eee;
        }
        .edit-modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #444;
        }
        .edit-modal-close {
            background: none;
            border: none;
            font-size: 2rem;
            color: #bbb;
            cursor: pointer;
            transition: color 0.2s;
        }
        .edit-modal-close:hover { color: #e74c3c; }
        .edit-modal-body {
            padding: 32px;
        }
        .edit-form-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 32px;
        }
        .edit-form-label {
            min-width: 180px;
            color: #bdbdbd;
            font-weight: 600;
            font-size: 1.08rem;
            display: flex;
            align-items: center;
            margin-right: 18px;
        }
        .edit-form-label i {
            margin-right: 10px;
            font-size: 20px;
        }
        .edit-form-input, .edit-form-select, .edit-form-textarea {
            flex: 1;
            font-size: 1.08rem;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fafafa;
            color: #444;
        }
        .edit-form-textarea { min-height: 60px; }
        .edit-form-hint {
            color: #bdbdbd;
            font-size: 0.98rem;
            margin-top: 4px;
        }
        .edit-form-upload-box {
            display: flex;
            align-items: center;
        }
        .edit-form-upload-preview {
            width: 70px; height: 70px;
            background: #ececec;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-right: 16px;
            font-size: 2.2rem;
            color: #bdbdbd;
        }
        .edit-form-upload-btn {
            border: 1.5px solid #bdbdbd;
            background: #fafafa;
            color: #888;
            font-weight: 600;
            border-radius: 8px;
            padding: 7px 22px;
            font-size: 1rem;
            transition: all 0.2s;
            margin-left: 8px;
        }
        .edit-form-upload-btn:hover { background: #f0f0f0; color: #333; border-color: #888; }
        .edit-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 18px 32px 28px 32px;
            border-top: 1px solid #eee;
        }
        .edit-modal-cancel {
            border: 1.5px solid #bdbdbd;
            background: #fafafa;
            color: #888;
            font-weight: 600;
            border-radius: 8px;
            padding: 7px 22px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .edit-modal-cancel:hover { background: #f0f0f0; color: #333; border-color: #888; }
        .edit-modal-save {
            background: #4a423a;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 7px 28px;
            font-size: 1.08rem;
            transition: background 0.2s;
        }
        .edit-modal-save:hover { background: #2c3e50; }
        @media (max-width: 900px) {
            .edit-modal-body, .edit-modal-header, .edit-modal-footer { padding-left: 12px; padding-right: 12px; }
            .edit-form-row { flex-direction: column; align-items: stretch; }
            .edit-form-label { margin-bottom: 6px; }
        }
        @media (max-width: 600px) {
            .edit-modal {
                max-width: 100vw;
                width: 100vw;
                min-width: 0;
                border-radius: 0;
                margin: 0;
            }
            .edit-modal-body, .edit-modal-header, .edit-modal-footer { padding-left: 2vw; padding-right: 2vw; }
            .edit-form-label { font-size: 0.93rem; }
            .edit-form-input, .edit-form-select, .edit-form-textarea { font-size: 0.93rem; }
        }
    </style>
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