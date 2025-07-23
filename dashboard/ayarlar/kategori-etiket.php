<?php
// Gerekli oturum ve kullanıcı bilgileri burada alınabilir (gerekirse)
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
    <style>
        body { background: #e5e4e2; }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }
        .category-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.04);
            padding: 0;
            border: none;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
        }
        .category-card-header {
            display: flex;
            align-items: center;
            font-size: 1.18rem;
            font-weight: 600;
            color: #666;
            padding: 18px 22px 0 22px;
        }
        .category-card-header i {
            margin-right: 10px;
            font-size: 1.2em;
            color: #bdbdbd;
        }
        .category-card-body {
            flex: 1;
            padding: 0 22px 18px 22px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .category-add-btn {
            border: 1.5px solid #bdbdbd;
            background: #fafafa;
            color: #888;
            font-weight: 600;
            border-radius: 8px;
            padding: 7px 22px;
            font-size: 1rem;
            transition: all 0.2s;
            margin-top: 16px;
            margin-bottom: 0;
            width: 140px;
        }
        .category-add-btn:hover { background: #f0f0f0; color: #333; border-color: #888; }
        .category-label-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .category-label {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 2px;
        }
        /* Widget */
        .add-widget {
            position: absolute;
            left: 10px;
            top: 60px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(44,62,80,0.13);
            width: 98%;
            max-width: 420px;
            z-index: 10;
            padding: 18px 18px 12px 18px;
            border: 1px solid #eee;
            animation: widgetIn 0.18s cubic-bezier(.4,2,.6,1) 1;
        }
        @keyframes widgetIn {
            from { transform: translateY(30px) scale(0.98); opacity: 0; }
            to { transform: none; opacity: 1; }
        }
        .add-widget-label {
            color: #bdbdbd;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 6px;
        }
        .add-widget-input {
            width: 100%;
            font-size: 1.08rem;
            padding: 7px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fafafa;
            color: #444;
            margin-bottom: 12px;
        }
        .color-palette {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }
        .color-swatch {
            width: 36px; height: 24px;
            border-radius: 4px;
            border: 2px solid #eee;
            cursor: pointer;
            transition: border 0.2s;
            display: inline-block;
        }
        .color-swatch.selected { border: 2px solid #444; }
        .add-widget-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }
        .add-widget-cancel {
            border: 1.5px solid #bdbdbd;
            background: #fafafa;
            color: #888;
            font-weight: 600;
            border-radius: 8px;
            padding: 7px 22px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .add-widget-cancel:hover { background: #f0f0f0; color: #333; border-color: #888; }
        .add-widget-save {
            background: #4a423a;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 7px 28px;
            font-size: 1.08rem;
            transition: background 0.2s;
        }
        .add-widget-save:hover { background: #2c3e50; }
        .category-hint {
            color: #bdbdbd;
            font-size: 1rem;
            margin-top: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .category-hint i { font-size: 1.3rem; }
        @media (max-width: 900px) {
            .category-grid { grid-template-columns: 1fr; gap: 18px; }
            .category-card { min-width: 0; }
        }
    </style>
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