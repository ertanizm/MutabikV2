<?php
require '../../config/config.php';
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
   <link href="../../assets/ayarlar/kullanicilar.css" rel="stylesheet">
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