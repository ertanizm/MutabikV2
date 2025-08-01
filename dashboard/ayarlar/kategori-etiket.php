<?php
require '../../config/config.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori ve Etiketler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/ayarlar/kategori-etiket.css" rel="stylesheet"> 
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Kategori ve Etiketler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="container-fluid">
            <div class="category-grid" id="categoryGrid">
                <!-- Kategori kartları JS ile doldurulacak -->
            </div>
            <div class="category-hint mt-4">
                <i class="fas fa-object-group"></i>
                Kategorileri sürükleyip bırakarak hiyerarşik olarak düzenleyebilirsiniz. Etiketler hiyerarşik olarak düzenlenemez.
            </div>
        </div>
    </div>
    <script src="../script2.js"></script>
    <script>
    // Kategori ve etiket verileri (örnek statik, ileride dinamik yapılabilir)
    const categories = [
        { id: 1, name: 'Satış Kategorileri', icon: 'fa-folder', labels: [], type: 'kategori' },
        { id: 2, name: 'Gider Kategorileri', icon: 'fa-folder', labels: [], type: 'kategori' },
        { id: 3, name: 'Gelir ve Gider Etiketleri', icon: 'fa-tag', labels: [], type: 'etiket' },
        { id: 4, name: 'Çalışan Kategorileri', icon: 'fa-folder', labels: [], type: 'kategori' },
        { id: 5, name: 'Müşteri ve Tedarikçi Kategorileri', icon: 'fa-folder', labels: [], type: 'kategori' },
        { id: 6, name: 'Hizmet ve Ürün Kategorileri', icon: 'fa-folder', labels: [], type: 'kategori' },
    ];
    // Renk paletleri
    const bgColors = [
        '#5cb85c','#e67e22','#3498db','#f1c40f','#4e944f','#d35400','#e67e9c','#8e44ad',
        '#e57373','#64b5f6','#ffd54f','#aed581','#ffb74d','#ba68c8','#4fc3f7','#fff176',
        '#b2dfdb','#f8bbd0','#dce775','#f5e1a4','#b39ddb','#cfd8dc','#ffe082','#f8bbd0'
    ];
    const textColors = ['#222','#444','#666','#888','#aaa','#ccc','#eee','#fff'];

    // Kategori kartlarını oluştur
    function renderCategories() {
        const grid = document.getElementById('categoryGrid');
        grid.innerHTML = '';
        categories.forEach(cat => {
            const card = document.createElement('div');
            card.className = 'category-card';
            card.innerHTML = `
                <div class="category-card-header">
                    <i class="fas ${cat.icon}"></i> ${cat.name}
                </div>
                <div class="category-card-body">
                    <div class="category-label-list">
                        ${cat.labels.map(l => `<span class="category-label" style="background:${l.bg};color:${l.text}">${l.name}</span>`).join('')}
                    </div>
                    <button class="category-add-btn" data-cat="${cat.id}">YENİ EKLE</button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    // Widgetı göster
    function showAddWidget(catId) {
        // Önce varsa eski widgetı kaldır
        document.querySelectorAll('.add-widget').forEach(w => w.remove());
        const card = document.querySelectorAll('.category-card')[catId-1];
        const widget = document.createElement('div');
        widget.className = 'add-widget';
        widget.innerHTML = `
            <div class="add-widget-label">KATEGORİ ADI</div>
            <input type="text" class="add-widget-input" id="widgetName">
            <div class="add-widget-label">ZEMİN RENGİ</div>
            <div class="color-palette" id="bgPalette">
                ${bgColors.map(c => `<span class="color-swatch" data-color="${c}" style="background:${c}"></span>`).join('')}
            </div>
            <div class="add-widget-label">YAZI RENGİ</div>
            <div class="color-palette" id="textPalette">
                ${textColors.map(c => `<span class="color-swatch" data-color="${c}" style="background:${c}"></span>`).join('')}
            </div>
            <div class="add-widget-footer">
                <button class="add-widget-cancel">Vazgeç</button>
                <button class="add-widget-save">Kaydet</button>
            </div>
        `;
        card.appendChild(widget);

        // Renk seçimi
        let selectedBg = bgColors[0];
        let selectedText = textColors[0];
        widget.querySelectorAll('#bgPalette .color-swatch').forEach((el,i) => {
            if(i===0) el.classList.add('selected');
            el.onclick = () => {
                widget.querySelectorAll('#bgPalette .color-swatch').forEach(e=>e.classList.remove('selected'));
                el.classList.add('selected');
                selectedBg = el.dataset.color;
            };
        });
        widget.querySelectorAll('#textPalette .color-swatch').forEach((el,i) => {
            if(i===0) el.classList.add('selected');
            el.onclick = () => {
                widget.querySelectorAll('#textPalette .color-swatch').forEach(e=>e.classList.remove('selected'));
                el.classList.add('selected');
                selectedText = el.dataset.color;
            };
        });
        // Vazgeç
        widget.querySelector('.add-widget-cancel').onclick = () => widget.remove();
        // Kaydet
        widget.querySelector('.add-widget-save').onclick = () => {
            const name = widget.querySelector('#widgetName').value.trim();
            if(!name) { widget.querySelector('#widgetName').focus(); return; }
            categories[catId-1].labels.push({name, bg:selectedBg, text:selectedText});
            renderCategories();
        };
    }

    // Başlangıçta render et
    renderCategories();
    // Yeni Ekle butonları için event delegation
    document.getElementById('categoryGrid').onclick = function(e) {
        if(e.target.classList.contains('category-add-btn')) {
            const catId = +e.target.dataset.cat;
            showAddWidget(catId);
        }
    };
    </script>
</body>
</html> 