<?php
// Gerekli oturum ve kullanıcı bilgileri burada alınabilir (gerekirse)
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcılar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        body { background: #e5e4e2; }
        .user-table-container { margin-top: 32px; }
        .user-table { width: 100%; border-collapse: separate; border-spacing: 0 0.5rem; }
        .user-table th { color: #bdbdbd; font-size: 1.08rem; font-weight: 600; padding: 12px 18px; background: none; border: none; }
        .user-table td { background: #fff; font-size: 1.08rem; padding: 16px 18px; border: none; vertical-align: middle; }
        .user-table tr { border-radius: 10px; }
        .user-table tr td:first-child { border-radius: 10px 0 0 10px; }
        .user-table tr td:last-child { border-radius: 0 10px 10px 0; }
        .user-avatar { width: 38px; height: 38px; border-radius: 8px; background: #e0e0e0; color: #888; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; margin-right: 12px; }
        .user-table .user-row { cursor: pointer; transition: box-shadow 0.2s; }
        .user-table .user-row:hover { box-shadow: 0 2px 12px rgba(44,62,80,0.10); }
        .user-status-active { color: #4a423a; font-weight: 600; }
        .user-status-invite { color: #bdbdbd; font-weight: 500; }
        .user-employee-yes { color: #4a423a; font-weight: 600; }
        .user-employee-no { color: #bdbdbd; font-weight: 500; }
        .user-perms { display: flex; gap: 6px; }
        .user-perm-dot { width: 12px; height: 12px; border-radius: 50%; background: #bdbdbd; display: inline-block; }
        .user-table-actions { text-align: right; }
        .invite-btn-group { display: flex; justify-content: flex-end; align-items: center; gap: 0; margin-bottom: 18px; }
        .invite-btn { background: #4a423a; color: #fff; font-weight: 700; border: none; border-radius: 8px 0 0 8px; padding: 10px 28px; font-size: 1.08rem; transition: background 0.2s; }
        .invite-btn:hover { background: #2c3e50; }
        .invite-btn-dropdown { background: #4a423a; color: #fff; font-weight: 700; border: none; border-radius: 0 8px 8px 0; padding: 10px 12px; font-size: 1.08rem; border-left: 1px solid #fff2; transition: background 0.2s; }
        .invite-btn-dropdown:hover { background: #2c3e50; }
        /* Widget overlay */
        .user-widget-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(44,62,80,0.18); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .user-widget { background: #fff; border-radius: 12px; box-shadow: 0 4px 32px rgba(44,62,80,0.13); max-width: 900px; width: 98vw; margin: 32px auto; padding: 0; position: relative; animation: modalIn 0.18s cubic-bezier(.4,2,.6,1) 1; }
        .user-widget-header { display: flex; align-items: center; justify-content: space-between; padding: 28px 32px 18px 32px; border-bottom: 1px solid #eee; }
        .user-widget-title { font-size: 2rem; font-weight: 500; color: #444; display: flex; align-items: center; gap: 18px; }
        .user-widget-avatar { width: 54px; height: 54px; border-radius: 12px; background: #e0e0e0; color: #888; font-weight: 700; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; }
        .user-widget-close { background: none; border: none; font-size: 2rem; color: #bbb; cursor: pointer; transition: color 0.2s; }
        .user-widget-close:hover { color: #e74c3c; }
        .user-widget-body { padding: 32px; }
        .user-widget-row { display: flex; align-items: flex-start; margin-bottom: 18px; }
        .user-widget-label { min-width: 180px; color: #bdbdbd; font-weight: 600; font-size: 1.08rem; display: flex; align-items: center; margin-right: 18px; }
        .user-widget-label i { margin-right: 10px; font-size: 20px; }
        .user-widget-value { color: #444; font-weight: 400; font-size: 1.08rem; }
        .user-widget-hint { color: #bdbdbd; font-size: 0.98rem; margin-top: 4px; }
        .user-widget-perms { margin-top: 24px; border-top: 1px solid #eee; padding-top: 18px; }
        .user-widget-perm-row { display: flex; align-items: center; margin-bottom: 12px; }
        .user-widget-perm-label { min-width: 180px; color: #bdbdbd; font-weight: 600; font-size: 1.08rem; display: flex; align-items: center; margin-right: 18px; }
        .user-widget-perm-dot { width: 14px; height: 14px; border-radius: 50%; background: #bdbdbd; display: inline-block; margin-right: 10px; }
        .user-widget-perm-value { color: #444; font-weight: 400; font-size: 1.08rem; }
        /* Davet widget */
        .invite-widget {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 32px rgba(44,62,80,0.13);
            max-width: 1100px;
            width: 98vw;
            margin: 32px auto;
            padding: 0;
            position: relative;
            animation: modalIn 0.18s cubic-bezier(.4,2,.6,1) 1;
            max-height: 90vh;
            overflow-y: auto;
        }
        .invite-widget-header { display: flex; align-items: center; justify-content: space-between; padding: 28px 32px 18px 32px; border-bottom: 1px solid #eee; }
        .invite-widget-title { font-size: 1.5rem; font-weight: 600; color: #444; }
        .invite-widget-close { background: none; border: none; font-size: 2rem; color: #bbb; cursor: pointer; transition: color 0.2s; }
        .invite-widget-close:hover { color: #e74c3c; }
        .invite-widget-body { padding: 32px; }
        .invite-form-row { display: flex; align-items: flex-start; margin-bottom: 24px; }
        .invite-form-label { min-width: 180px; color: #bdbdbd; font-weight: 600; font-size: 1.08rem; display: flex; align-items: center; margin-right: 18px; }
        .invite-form-label i { margin-right: 10px; font-size: 20px; }
        .invite-form-input, .invite-form-select { flex: 1; font-size: 1.08rem; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa; color: #444; }
        .invite-form-hint { color: #bdbdbd; font-size: 0.98rem; margin-top: 4px; }
        .invite-widget-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 18px 32px 28px 32px; border-top: 1px solid #eee; }
        .invite-widget-cancel { border: 1.5px solid #bdbdbd; background: #fafafa; color: #888; font-weight: 600; border-radius: 8px; padding: 7px 22px; font-size: 1rem; transition: all 0.2s; }
        .invite-widget-cancel:hover { background: #f0f0f0; color: #333; border-color: #888; }
        .invite-widget-save { background: #4a423a; color: #fff; font-weight: 700; border: none; border-radius: 8px; padding: 7px 28px; font-size: 1.08rem; transition: background 0.2s; }
        .invite-widget-save:hover { background: #2c3e50; }
        .custom-select {
            position: relative;
            width: 100%;
            min-width: 180px;
            font-size: 1.01rem;
        }
        .custom-select-selected {
            background: #fff;
            border: 1.5px solid #bdbdbd;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 34px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.04);
            transition: border 0.2s, box-shadow 0.2s;
            font-size: 1.01rem;
        }
        .custom-select-selected.open {
            border-color: #4a423a;
            box-shadow: 0 4px 16px rgba(44,62,80,0.13);
        }
        .custom-select-arrow {
            margin-left: 8px;
            color: #888;
            font-size: 1em;
        }
        .custom-select-options {
            position: absolute;
            left: 0; right: 0;
            top: 110%;
            background: #fff;
            border: 1.5px solid #bdbdbd;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
            z-index: 100;
            display: none;
            max-height: 220px;
            overflow-y: auto;
            font-size: 1.01rem;
        }
        .custom-select-options.open { display: block; }
        .custom-select-option {
            padding: 8px 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.01rem;
            background: #fff;
            transition: background 0.15s;
        }
        .custom-select-option.selected, .custom-select-option:hover {
            background: #f5f5f5;
        }
        .custom-select-icon {
            font-size: 1.1em;
            width: 22px;
            text-align: center;
        }
        /* Widget içi spacing düzeltmeleri */
        .invite-form-row { align-items: center; }
        .invite-form-label { min-width: 180px; }
        .invite-form-hint {
            display: block;
            margin-left: 0;
            margin-top: 4px;
            color: #bdbdbd;
            font-size: 0.97rem;
            font-style: italic;
        }
        @media (max-width: 900px) {
            .user-widget { max-width: 99vw; width: 99vw; }
            .user-widget-header, .user-widget-body, .user-widget-perms, .invite-widget-header, .invite-widget-body, .invite-widget-footer { padding-left: 12px; padding-right: 12px; }
            .user-widget-title { font-size: 1.2rem; }
            .user-widget-avatar { width: 40px; height: 40px; font-size: 1.1rem; }
            .user-widget-label, .user-widget-perm-label, .invite-form-label { min-width: 0; font-size: 0.98rem; margin-right: 8px; }
            .user-widget-row, .invite-form-row { flex-direction: column; align-items: stretch; margin-bottom: 12px; gap: 6px; }
            .user-widget-value, .user-widget-perm-value, .invite-form-input, .invite-form-select { font-size: 0.98rem; }
            .custom-select, .custom-select-selected { font-size: 0.95rem; }
            .custom-select-option { font-size: 0.95rem; }
        }
        @media (max-width: 600px) {
            .user-widget, .invite-widget { max-width: 100vw; width: 100vw; border-radius: 0; margin: 0; }
            .user-widget-header, .user-widget-body, .user-widget-perms, .invite-widget-header, .invite-widget-body, .invite-widget-footer { padding-left: 2vw; padding-right: 2vw; }
            .user-widget-title { font-size: 1rem; }
            .user-widget-avatar { width: 32px; height: 32px; font-size: 0.9rem; }
            .custom-select, .custom-select-selected { font-size: 0.92rem; }
            .custom-select-option { font-size: 0.92rem; }
        }
        @media (max-width: 900px) {
            .custom-select, .custom-select-selected { font-size: 0.95rem; }
            .custom-select-option { font-size: 0.95rem; }
        }
        @media (max-width: 600px) {
            .custom-select, .custom-select-selected { font-size: 0.92rem; }
            .custom-select-option { font-size: 0.92rem; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Kullanıcılar</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="invite-btn-group" style="margin-bottom:18px;">
            <button class="invite-btn" id="openInviteWidget">KULLANICI DAVET ET</button>
            <button class="invite-btn-dropdown"><i class="fas fa-chevron-down"></i></button>
        </div>
        <div class="container-fluid user-table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ADI SOYADI</th>
                        <th>E-POSTA ADRESİ</th>
                        <th>DURUM</th>
                        <th>ÇALIŞAN KAYDI</th>
                        <th>YETKİ</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <!-- JS ile doldurulacak -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="user-widget-overlay" id="userWidgetOverlay"></div>
    <script src="../script2.js"></script>
    <script>
    // Statik kullanıcı verisi
    const users = [
        {
            id: 1,
            name: 'miraç deprem',
            email: 'miracdeprem0@gmail.com',
            status: 'Aktif (Hesap Sahibi)',
            statusClass: 'user-status-active',
            employee: 'Var',
            employeeClass: 'user-employee-yes',
            perms: [
                { label: 'Ayarlar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Kasa ve Bankalar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Satışlar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Giderler', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Çalışanlar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
            ],
            initials: 'MD',
            avatarType: 'initials',
            employeeHint: 'Bu kullanıcı için çalışan eşleştirmesi yapılmış.'
        },
        {
            id: 2,
            name: 'kemal deniz',
            email: 'kemaldeniz@gmail.com',
            status: 'Davetiye Gönderildi (23 Temmuz 2025)',
            statusClass: 'user-status-invite',
            employee: 'Yok',
            employeeClass: 'user-employee-no',
            perms: [
                { label: 'Ayarlar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Kasa ve Bankalar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Satışlar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Giderler', value: 'Tüm İşlemleri Gerçekleştirebilir' },
                { label: 'Çalışanlar', value: 'Tüm İşlemleri Gerçekleştirebilir' },
            ],
            initials: '<i class="fas fa-envelope"></i>',
            avatarType: 'icon',
            employeeHint: ''
        }
    ];
    // Tabloyu doldur
    function renderUserTable() {
        const tbody = document.getElementById('userTableBody');
        tbody.innerHTML = '';
        users.forEach(user => {
            const tr = document.createElement('tr');
            tr.className = 'user-row';
            tr.innerHTML = `
                <td><span class="user-avatar">${user.avatarType==='icon'?user.initials:user.initials}</span></td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td class="${user.statusClass}">${user.status}</td>
                <td class="${user.employeeClass}">${user.employee}</td>
                <td><div class="user-perms">${'<span class="user-perm-dot"></span>'.repeat(5)}</div></td>
            `;
            tr.onclick = () => showUserWidget(user);
            tbody.appendChild(tr);
        });
    }
    // Kullanıcı detay widget
    function showUserWidget(user) {
        const overlay = document.getElementById('userWidgetOverlay');
        overlay.innerHTML = `
        <div class="user-widget">
            <div class="user-widget-header">
                <span class="user-widget-title">
                    <span class="user-widget-avatar">${user.avatarType==='icon'?user.initials:user.initials}</span>
                    ${user.name}
                </span>
                <button class="user-widget-close" id="closeUserWidget">&times;</button>
            </div>
            <div class="user-widget-body">
                <div class="user-widget-row">
                    <span class="user-widget-label"><i class="fas fa-user"></i>ADI SOYADI</span>
                    <span class="user-widget-value">${user.name}</span>
                </div>
                <div class="user-widget-row">
                    <span class="user-widget-label"><i class="fas fa-envelope"></i>E-POSTA ADRESİ</span>
                    <span class="user-widget-value">${user.email}</span>
                </div>
                <div class="user-widget-row">
                    <span class="user-widget-label"><i class="fas fa-upload"></i>GİDERLER İÇİN ÇALIŞAN KAYDI</span>
                    <span class="user-widget-value">${user.employee}</span>
                </div>
                ${user.employeeHint ? `<div class="user-widget-row"><span></span><span class="user-widget-hint"><i class='fas fa-info-circle'></i> ${user.employeeHint}</span></div>` : ''}
                <div class="user-widget-perms">
                    <div class="user-widget-perm-row"><span class="user-widget-perm-label"><i class="fas fa-cog"></i>AYARLAR</span><span class="user-widget-perm-dot"></span><span class="user-widget-perm-value">Tüm İşlemleri Gerçekleştirebilir</span></div>
                    <div class="user-widget-perm-row"><span class="user-widget-perm-label"><i class="fas fa-university"></i>KASA ve BANKALAR</span><span class="user-widget-perm-dot"></span><span class="user-widget-perm-value">Tüm İşlemleri Gerçekleştirebilir</span></div>
                    <div class="user-widget-perm-row"><span class="user-widget-perm-label"><i class="fas fa-download"></i>SATIŞLAR</span><span class="user-widget-perm-dot"></span><span class="user-widget-perm-value">Tüm İşlemleri Gerçekleştirebilir</span></div>
                    <div class="user-widget-perm-row"><span class="user-widget-perm-label"><i class="fas fa-upload"></i>GİDERLER</span><span class="user-widget-perm-dot"></span><span class="user-widget-perm-value">Tüm İşlemleri Gerçekleştirebilir</span></div>
                    <div class="user-widget-perm-row"><span class="user-widget-perm-label"><i class="fas fa-user"></i>ÇALIŞANLAR</span><span class="user-widget-perm-dot"></span><span class="user-widget-perm-value">Tüm İşlemleri Gerçekleştirebilir</span></div>
                </div>
            </div>
        </div>
        `;
        overlay.style.display = 'flex';
        document.getElementById('closeUserWidget').onclick = () => overlay.style.display = 'none';
        overlay.onclick = (e) => { if(e.target === overlay) overlay.style.display = 'none'; };
    }
    // Kullanıcı davet widget
    function showInviteWidget() {
        const overlay = document.getElementById('userWidgetOverlay');
        overlay.innerHTML = `
        <div class="invite-widget">
            <div class="invite-widget-header">
                <span class="invite-widget-title">Kullanıcı Davet Et</span>
                <button class="invite-widget-close" id="closeInviteWidget">&times;</button>
            </div>
            <form class="invite-widget-body" id="inviteForm">
                <div class="invite-form-row">
                    <label class="invite-form-label"><i class="fas fa-user"></i>ADI SOYADI</label>
                    <input type="text" class="invite-form-input" id="inviteName">
                </div>
                <div class="invite-form-row">
                    <label class="invite-form-label"><i class="fas fa-envelope"></i>E-POSTA ADRESİ *</label>
                    <input type="email" class="invite-form-input" id="inviteEmail" required>
                </div>
                <div class="invite-form-row">
                    <label class="invite-form-label"><i class="fas fa-upload"></i>ÇALIŞAN KAYDI</label>
                    <select class="invite-form-select" id="inviteEmployee"><option>Çalışan Kaydı Yok</option><option>Var</option></select>
                    <span class="invite-form-hint">Bu kullanıcı aynı zamanda firma çalışanıysa, iki kaydı buradan ilişkilendirebilirsiniz.</span>
                </div>
                ${renderCustomSelectRow('Ayarlar', 'settings', 2, 'Firma Ayarları, Kategoriler ve Kullanıcılar')}
                ${renderCustomSelectRow('Kasa ve Bankalar', 'cash', 2, 'Kasa, Banka ve Çekler')}
                ${renderCustomSelectRow('Satışlar', 'sales', 0, 'Satış Faturaları, Hizmetler / Ürünler, Müşteriler, Tahsilatlar ve e-Ticaret')}
                ${renderCustomSelectRow('Giderler', 'expenses', 2, 'Tüm Gider Belgeleri, Hizmetler / Ürünler, Tedarikçiler ve Ödemeler')}
                ${renderCustomSelectRow('Çalışanlar', 'employees', 2, 'Çalışanlar, Maaşlar ve Avanslar')}
                <div class="invite-widget-footer">
                    <button type="button" class="invite-widget-cancel" id="cancelInviteWidget">Vazgeç</button>
                    <button type="submit" class="invite-widget-save">Davet Et</button>
                </div>
            </form>
        </div>
        `;
        overlay.style.display = 'flex';
        document.getElementById('closeInviteWidget').onclick = () => overlay.style.display = 'none';
        document.getElementById('cancelInviteWidget').onclick = () => overlay.style.display = 'none';
        overlay.onclick = (e) => { if(e.target === overlay) overlay.style.display = 'none'; };
        // Custom select JS
        setupCustomSelects();
        document.getElementById('inviteForm').onsubmit = function(e) {
            e.preventDefault();
            // Seçili yetkileri topla
            const perms = {};
            document.querySelectorAll('.custom-select').forEach(sel => {
                perms[sel.dataset.key] = sel.dataset.value;
            });
            // Form verilerini al
            const name = document.getElementById('inviteName').value.trim();
            const email = document.getElementById('inviteEmail').value.trim();
            const employee = document.getElementById('inviteEmployee').value;
            if (!email) {
                document.getElementById('inviteEmail').focus();
                return;
            }
            users.push({
                id: users.length + 1,
                name: name || email.split('@')[0],
                email: email,
                status: 'Davetiye Gönderildi (Bugün)',
                statusClass: 'user-status-invite',
                employee: employee,
                employeeClass: employee === 'Var' ? 'user-employee-yes' : 'user-employee-no',
                perms: [
                    { label: 'Ayarlar', value: perms.settings == 0 ? 'Tüm İşlemleri Gerçekleştirebilir' : perms.settings == 1 ? 'Sadece Görebilir' : 'Yetki Yok' },
                    { label: 'Kasa ve Bankalar', value: perms.cash == 0 ? 'Tüm İşlemleri Gerçekleştirebilir' : perms.cash == 1 ? 'Sadece Görebilir' : 'Yetki Yok' },
                    { label: 'Satışlar', value: perms.sales == 0 ? 'Tüm İşlemleri Gerçekleştirebilir' : perms.sales == 1 ? 'Sadece Görebilir' : 'Yetki Yok' },
                    { label: 'Giderler', value: perms.expenses == 0 ? 'Tüm İşlemleri Gerçekleştirebilir' : perms.expenses == 1 ? 'Sadece Görebilir' : 'Yetki Yok' },
                    { label: 'Çalışanlar', value: perms.employees == 0 ? 'Tüm İşlemleri Gerçekleştirebilir' : perms.employees == 1 ? 'Sadece Görebilir' : 'Yetki Yok' },
                ],
                initials: '<i class="fas fa-envelope"></i>',
                avatarType: 'icon',
                employeeHint: ''
            });
            renderUserTable();
            overlay.style.display = 'none';
        };
    }
    // Custom select render fonksiyonu
    function renderCustomSelectRow(label, key, defaultValue, hint) {
        // 0: Tüm İşlemleri Gerçekleştirebilir, 1: Sadece Görebilir, 2: Yetki Yok
        const options = [
            {icon:'fa-circle', text:'Tüm İşlemleri Gerçekleştirebilir'},
            {icon:'fa-dot-circle', text:'Sadece Görebilir'},
            {icon:'fa-ban', text:'Yetki Yok'}
        ];
        return `
        <div class="invite-form-row">
            <label class="invite-form-label"><i class="fas ${iconForKey(key)}"></i>${label.toUpperCase()}</label>
            <div style="width:100%">
                <div class="custom-select" data-key="${key}" data-value="${defaultValue}">
                    <div class="custom-select-selected">${customSelectOptionHTML(options[defaultValue])}<span class="custom-select-arrow"><i class="fas fa-chevron-down"></i></span></div>
                    <div class="custom-select-options">
                        ${options.map((opt,i)=>`<div class="custom-select-option${i===defaultValue?' selected':''}" data-value="${i}">${customSelectOptionHTML(opt)}</div>`).join('')}
                    </div>
                </div>
                <span class="invite-form-hint"><i class='fas fa-info-circle'></i> ${hint}</span>
            </div>
        </div>`;
    }
    function customSelectOptionHTML(opt) {
        return `<span class='custom-select-icon fas ${opt.icon}'></span> ${opt.text}`;
    }
    function iconForKey(key) {
        switch(key) {
            case 'settings': return 'fa-cog';
            case 'cash': return 'fa-university';
            case 'sales': return 'fa-download';
            case 'expenses': return 'fa-upload';
            case 'employees': return 'fa-user';
            default: return 'fa-cog';
        }
    }
    function setupCustomSelects() {
        document.querySelectorAll('.custom-select').forEach(sel => {
            const selected = sel.querySelector('.custom-select-selected');
            const options = sel.querySelector('.custom-select-options');
            selected.onclick = function(e) {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-options').forEach(o=>o!==options&&o.classList.remove('open'));
                options.classList.toggle('open');
                selected.classList.toggle('open');
            };
            options.querySelectorAll('.custom-select-option').forEach(opt => {
                opt.onclick = function(e) {
                    options.querySelectorAll('.custom-select-option').forEach(o=>o.classList.remove('selected'));
                    this.classList.add('selected');
                    sel.dataset.value = this.dataset.value;
                    selected.innerHTML = this.innerHTML + '<span class="custom-select-arrow"><i class="fas fa-chevron-down"></i></span>';
                    options.classList.remove('open');
                    selected.classList.remove('open');
                };
            });
        });
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.custom-select-options').forEach(o=>o.classList.remove('open'));
            document.querySelectorAll('.custom-select-selected').forEach(s=>s.classList.remove('open'));
        });
    }
    // Başlangıçta tabloyu doldur
    renderUserTable();
    // Kullanıcı davet et butonu
    document.getElementById('openInviteWidget').onclick = showInviteWidget;
    </script>
</body>
</html> 