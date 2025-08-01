<?php
require '../../../config/config.php';

// Örnek fatura verileri (local olarak)
$faturalar = [
    [
        'id' => 1,
        'isim' => 'Web Sitesi Geliştirme Faturası',
        'faturaNo' => 'FAT-2024-001',
        'musteri' => 'ABC Teknoloji A.Ş.',
        'duzenlemeTarihi' => '15.12.2024',
        'vadeTarihi' => '15.01.2025',
        'toplamTutar' => 15000.00,
        'kalanMeblag' => 15000.00,
        'durum' => 'Ödenmedi'
    ],
    [
        'id' => 2,
        'isim' => 'Mobil Uygulama Projesi',
        'faturaNo' => 'FAT-2024-002',
        'musteri' => 'XYZ Yazılım Ltd.',
        'duzenlemeTarihi' => '10.12.2024',
        'vadeTarihi' => '10.01.2025',
        'toplamTutar' => 25000.00,
        'kalanMeblag' => 12500.00,
        'durum' => 'Kısmi Ödendi'
    ],
    [
        'id' => 3,
        'isim' => 'SEO Hizmeti Faturası',
        'faturaNo' => 'FAT-2024-003',
        'musteri' => 'DEF Mağazaları',
        'duzenlemeTarihi' => '05.12.2024',
        'vadeTarihi' => '05.01.2025',
        'toplamTutar' => 3000.00,
        'kalanMeblag' => 0.00,
        'durum' => 'Ödendi'
    ],
    [
        'id' => 4,
        'isim' => 'E-ticaret Sistemi Kurulumu',
        'faturaNo' => 'FAT-2024-004',
        'musteri' => 'GHI Holding',
        'duzenlemeTarihi' => '01.12.2024',
        'vadeTarihi' => '01.01.2025',
        'toplamTutar' => 12000.00,
        'kalanMeblag' => 12000.00,
        'durum' => 'Ödenmedi'
    ],
    [
        'id' => 5,
        'isim' => 'Sosyal Medya Yönetimi',
        'faturaNo' => 'FAT-2024-005',
        'musteri' => 'JKL E-ticaret',
        'duzenlemeTarihi' => '28.11.2024',
        'vadeTarihi' => '28.12.2024',
        'toplamTutar' => 6000.00,
        'kalanMeblag' => 0.00,
        'durum' => 'Ödendi'
    ],
    [
        'id' => 6,
        'isim' => 'Logo ve Kurumsal Kimlik',
        'faturaNo' => 'FAT-2024-006',
        'musteri' => 'MNO Reklam Ajansı',
        'duzenlemeTarihi' => '25.11.2024',
        'vadeTarihi' => '25.12.2024',
        'toplamTutar' => 3500.00,
        'kalanMeblag' => 3500.00,
        'durum' => 'Ödenmedi'
    ]
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satış Faturaları - Mutabık</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../dashboard.css" rel="stylesheet">
    <link href="../../../assets/satislar/faturalar/faturalar.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Faturalar</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../../includes/profile_dropdown.php'; ?>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Action Bar -->
            <div class="action-bar">
                <div class="filter-container">
                    <button class="filter-btn" onclick="toggleFilterModal()">
                        <i class="fas fa-filter"></i>
                        ▼ FİLTRELE
                    </button>
                    
                    <!-- Filter Modal -->
                    <div class="filter-modal" id="filterModal">
                        <div class="filter-section">
                            <h4>Tarih Aralığı</h4>
                            <div class="filter-date-range">
                                <input type="date" id="startDate" placeholder="gg.aa.yyyy">
                                <span>-</span>
                                <input type="date" id="endDate" placeholder="gg.aa.yyyy">
                            </div>
                        </div>
                        
                        <div class="filter-section">
                            <h4>Tutar Aralığı</h4>
                            <div class="filter-date-range">
                                <input type="number" id="minAmount" placeholder="Min Tutar" value="0">
                                <span>-</span>
                                <input type="number" id="maxAmount" placeholder="Max Tutar" value="100000">
                            </div>
                        </div>
                        
                        <div class="filter-section">
                            <h4>Ödeme Durumu</h4>
                            <div class="filter-option">
                                <input type="checkbox" id="odendi" value="Ödendi">
                                <label for="odendi">Ödendi</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="odenmedi" value="Ödenmedi">
                                <label for="odenmedi">Ödenmedi</label>
                            </div>
                            <div class="filter-option">
                                <input type="checkbox" id="kısmiOdendi" value="Kısmi Ödendi">
                                <label for="kısmiOdendi">Kısmi Ödendi</label>
                            </div>
                        </div>
                        
                        <div class="filter-actions">
                            <button class="btn-secondary" onclick="clearFilters()">TEMİZLE</button>
                            <button class="btn-primary" onclick="applyFilters()">UYGULA</button>
                        </div>
                    </div>
                </div>
                
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Ara..." oninput="searchFaturalar()">
                    <i class="fas fa-search"></i>
                </div>
                
                <a href="/MutabikV2/dashboard/satislar/faturalar/yeni-fatura.php" class="create-btn">YENİ FATURA OLUŞTUR</a>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="isim" onclick="sortTable('isim')">
                                FATURA İSMİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="faturaNo" onclick="sortTable('faturaNo')">
                                FATURA NO
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="musteri" onclick="sortTable('musteri')">
                                MÜŞTERİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="duzenlemeTarihi" onclick="sortTable('duzenlemeTarihi')">
                                DÜZENLEME TARİHİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="vadeTarihi" onclick="sortTable('vadeTarihi')">
                                VADE TARİHİ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="toplamTutar" onclick="sortTable('toplamTutar')">
                                TOPLAM TUTAR
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="kalanMeblag" onclick="sortTable('kalanMeblag')">
                                KALAN MEBLAĞ
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="durum" onclick="sortTable('durum')">
                                DURUM
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="faturaTableBody">
                        <?php foreach ($faturalar as $fatura): ?>
                        <tr class="fatura-row">
                            <td class="fatura-info">
                                <div class="fatura-name"><?php echo htmlspecialchars($fatura['isim']); ?></div>
                            </td>
                            <td class="fatura-number"><?php echo htmlspecialchars($fatura['faturaNo']); ?></td>
                            <td class="fatura-customer"><?php echo htmlspecialchars($fatura['musteri']); ?></td>
                            <td class="fatura-date"><?php echo htmlspecialchars($fatura['duzenlemeTarihi']); ?></td>
                            <td class="fatura-due"><?php echo htmlspecialchars($fatura['vadeTarihi']); ?></td>
                            <td class="fatura-amount"><?php echo number_format($fatura['toplamTutar'], 2, ',', '.'); ?> ₺</td>
                            <td class="fatura-remaining"><?php echo number_format($fatura['kalanMeblag'], 2, ',', '.'); ?> ₺</td>
                            <td class="fatura-status">
                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $fatura['durum'])); ?>">
                                    <?php echo htmlspecialchars($fatura['durum']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div style="display: flex; gap: 15px;">
                <div class="records-dropdown">
                    TÜM KAYITLAR ▼
                </div>
                <div class="export-dropdown">
                    <button class="export-btn" onclick="toggleExportDropdown()">
                        İÇE/DIŞA AKTAR ▼
                    </button>
                    <div class="export-menu" id="exportMenu" style="display: none;">
                        <button onclick="importData()">
                            <i class="fas fa-upload"></i>
                            İçe Aktar
                        </button>
                        <button onclick="exportData()">
                            <i class="fas fa-download"></i>
                            Dışa Aktar (Excel)
                        </button>
                    </div>
                    <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" style="display: none;" onchange="handleFileUpload(event)">
                </div>
            </div>
            
            <div class="summary-stats">
                <span id="recordCount"><?php echo count($faturalar); ?> Kayıt</span>
                <span id="totalAmount"><?php echo number_format(array_sum(array_column($faturalar, 'toplamTutar')), 2, ',', '.'); ?>₺</span>
                <span id="remainingAmount">Tahsil Edilecek <?php echo number_format(array_sum(array_column($faturalar, 'kalanMeblag')), 2, ',', '.'); ?>₺</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="../../script2.js"></script>
    
    <script>
        // Global değişkenler
        let originalData = <?php echo json_encode($faturalar); ?>;
        let filteredData = [];
        let currentSort = { column: null, direction: 'asc' };

        // Filtreleme modalını aç/kapat
        function toggleFilterModal() {
            const modal = document.getElementById('filterModal');
            modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
        }

        // Filtreleri uygula
        function applyFilters() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const minAmount = parseFloat(document.getElementById('minAmount').value) || 0;
            const maxAmount = parseFloat(document.getElementById('maxAmount').value) || Infinity;
            
            const selectedStatuses = [];
            if (document.getElementById('odendi').checked) selectedStatuses.push('Ödendi');
            if (document.getElementById('odenmedi').checked) selectedStatuses.push('Ödenmedi');
            if (document.getElementById('kısmiOdendi').checked) selectedStatuses.push('Kısmi Ödendi');

            filteredData = originalData.filter(fatura => {
                // Tarih filtresi
                if (startDate || endDate) {
                    const faturaDate = new Date(fatura.duzenlemeTarihi.split('.').reverse().join('-'));
                    if (startDate && faturaDate < new Date(startDate)) return false;
                    if (endDate && faturaDate > new Date(endDate)) return false;
                }

                // Tutar filtresi
                if (fatura.toplamTutar < minAmount || fatura.toplamTutar > maxAmount) return false;

                // Durum filtresi
                if (selectedStatuses.length > 0 && !selectedStatuses.includes(fatura.durum)) return false;

                return true;
            });

            renderTable();
            updateStats();
            toggleFilterModal();
        }

        // Filtreleri temizle
        function clearFilters() {
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('minAmount').value = '0';
            document.getElementById('maxAmount').value = '100000';
            document.getElementById('odendi').checked = false;
            document.getElementById('odenmedi').checked = false;
            document.getElementById('kısmiOdendi').checked = false;
            
            filteredData = [...originalData];
            renderTable();
            updateStats();
        }

        // Fatura arama
        function searchFaturalar() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.fatura-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // Tablo sıralama
        function sortTable(column) {
            const direction = currentSort.column === column && currentSort.direction === 'asc' ? 'desc' : 'asc';
            currentSort = { column, direction };

            // Sıralama ikonlarını güncelle
            document.querySelectorAll('.sort-icon').forEach(icon => {
                icon.className = 'fas fa-sort sort-icon';
            });
            
            const header = document.querySelector(`[data-sort="${column}"]`);
            const icon = header.querySelector('.sort-icon');
            icon.className = `fas fa-sort-${direction === 'asc' ? 'up' : 'down'} sort-icon`;

            // Veriyi sırala
            const dataToSort = filteredData.length > 0 ? filteredData : originalData;
            dataToSort.sort((a, b) => {
                let aVal = a[column];
                let bVal = b[column];

                // Tarih sıralaması için
                if (column.includes('Tarihi')) {
                    aVal = new Date(aVal.split('.').reverse().join('-'));
                    bVal = new Date(bVal.split('.').reverse().join('-'));
                }
                // Sayısal sıralama için
                else if (column.includes('Tutar') || column.includes('Meblag')) {
                    aVal = parseFloat(aVal);
                    bVal = parseFloat(bVal);
                }
                // Metin sıralaması için
                else {
                    aVal = aVal.toString().toLowerCase();
                    bVal = bVal.toString().toLowerCase();
                }

                if (direction === 'asc') {
                    return aVal > bVal ? 1 : -1;
                } else {
                    return aVal < bVal ? 1 : -1;
                }
            });

            renderTable();
        }

        // Tabloyu render et
        function renderTable() {
            const tbody = document.getElementById('faturaTableBody');
            const dataToRender = originalData;
            
            tbody.innerHTML = dataToRender.map(fatura => `
                <tr class="fatura-row">
                    <td class="fatura-info">
                        <div class="fatura-name">${fatura.isim}</div>
                    </td>
                    <td class="fatura-number">${fatura.faturaNo}</td>
                    <td class="fatura-customer">${fatura.musteri}</td>
                    <td class="fatura-date">${fatura.duzenlemeTarihi}</td>
                    <td class="fatura-due">${fatura.vadeTarihi}</td>
                    <td class="fatura-amount">${parseFloat(fatura.toplamTutar).toLocaleString('tr-TR', {minimumFractionDigits: 2})} ₺</td>
                    <td class="fatura-remaining">${parseFloat(fatura.kalanMeblag).toLocaleString('tr-TR', {minimumFractionDigits: 2})} ₺</td>
                    <td class="fatura-status">
                        <span class="status-badge status-${fatura.durum.toLowerCase().replace(' ', '-')}">
                            ${fatura.durum}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        // İstatistikleri güncelle
        function updateStats() {
            const dataToCalculate = originalData;
            const recordCount = dataToCalculate.length;
            const totalAmount = dataToCalculate.reduce((sum, fatura) => sum + parseFloat(fatura.toplamTutar), 0);
            const remainingAmount = dataToCalculate.reduce((sum, fatura) => sum + parseFloat(fatura.kalanMeblag), 0);

            document.getElementById('recordCount').textContent = `${recordCount} Kayıt`;
            document.getElementById('totalAmount').textContent = `${totalAmount.toLocaleString('tr-TR', {minimumFractionDigits: 2})}₺`;
            document.getElementById('remainingAmount').textContent = `Tahsil Edilecek ${remainingAmount.toLocaleString('tr-TR', {minimumFractionDigits: 2})}₺`;
        }

        // LocalStorage'dan faturaları yükle
        function loadFaturalarFromStorage() {
            const storedFaturalar = JSON.parse(localStorage.getItem('faturalar') || '[]');
            if (storedFaturalar.length > 0) {
                // Stored faturaları PHP faturaları ile birleştir
                originalData = [...originalData, ...storedFaturalar];
                filteredData = [...originalData];
                renderTable();
            }
        }

        // Sayfa yüklendiğinde
        document.addEventListener('DOMContentLoaded', function() {
            loadFaturalarFromStorage();
            filteredData = [...originalData];
            renderTable();
            updateStats();
        });

        // Modal dışına tıklandığında kapat
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('filterModal');
            const filterBtn = document.querySelector('.filter-btn');
            
            if (!modal.contains(e.target) && !filterBtn.contains(e.target)) {
                modal.style.display = 'none';
            }
        });

        // Export dropdown işlevleri
        function toggleExportDropdown() {
            const menu = document.getElementById('exportMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        // Dışa aktar dropdown dışına tıklandığında kapat
        document.addEventListener('click', function(e) {
            const exportBtn = document.querySelector('.export-btn');
            const exportMenu = document.getElementById('exportMenu');
            
            if (!exportBtn.contains(e.target) && !exportMenu.contains(e.target)) {
                exportMenu.style.display = 'none';
            }
        });

        // İçe aktar
        function importData() {
            document.getElementById('fileInput').click();
        }

        // Dosya yükleme işlemi
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    // CSV dosyası için basit parse
                    if (file.name.endsWith('.csv')) {
                        const csv = e.target.result;
                        const lines = csv.split('\n');
                        const headers = lines[0].split(',');
                        
                        const newFaturalar = [];
                        for (let i = 1; i < lines.length; i++) {
                            if (lines[i].trim()) {
                                const values = lines[i].split(',');
                                if (values.length >= 8) {
                                    newFaturalar.push({
                                        id: originalData.length + i,
                                        isim: values[0] || '',
                                        faturaNo: values[1] || '',
                                        musteri: values[2] || '',
                                        duzenlemeTarihi: values[3] || '',
                                        vadeTarihi: values[4] || '',
                                        toplamTutar: parseFloat(values[5]) || 0,
                                        kalanMeblag: parseFloat(values[6]) || 0,
                                        durum: values[7] || 'Ödenmedi'
                                    });
                                }
                            }
                        }
                        
                        if (newFaturalar.length > 0) {
                            originalData = [...originalData, ...newFaturalar];
                            filteredData = [...originalData];
                            renderTable();
                            updateStats();
                            alert(`${newFaturalar.length} fatura başarıyla içe aktarıldı!`);
                        }
                    } else {
                        alert('Şu anda sadece CSV dosyaları desteklenmektedir.');
                    }
                } catch (error) {
                    alert('Dosya okuma hatası: ' + error.message);
                }
            };
            
            reader.readAsText(file);
            event.target.value = ''; // Input'u temizle
        }

        // Dışa aktar (Excel)
        function exportData() {
            const dataToExport = filteredData.length > 0 ? filteredData : originalData;
            
            // Excel için veri hazırlama
            const excelData = [
                ['Fatura İsmi', 'Fatura No', 'Müşteri', 'Düzenleme Tarihi', 'Vade Tarihi', 'Toplam Tutar (₺)', 'Kalan Meblağ (₺)', 'Durum'],
                ...dataToExport.map(fatura => [
                    fatura.isim,
                    fatura.faturaNo,
                    fatura.musteri,
                    fatura.duzenlemeTarihi,
                    fatura.vadeTarihi,
                    fatura.toplamTutar,
                    fatura.kalanMeblag,
                    fatura.durum
                ])
            ];

            // Workbook oluştur
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(excelData);

            // Sütun genişliklerini ayarla
            const colWidths = [
                { wch: 30 }, // Fatura İsmi
                { wch: 15 }, // Fatura No
                { wch: 25 }, // Müşteri
                { wch: 15 }, // Düzenleme Tarihi
                { wch: 15 }, // Vade Tarihi
                { wch: 15 }, // Toplam Tutar
                { wch: 15 }, // Kalan Meblağ
                { wch: 12 }  // Durum
            ];
            ws['!cols'] = colWidths;

            // Başlık satırını kalın yap
            const headerRange = XLSX.utils.decode_range(ws['!ref']);
            for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
                const cellAddress = XLSX.utils.encode_cell({ r: 0, c: col });
                if (!ws[cellAddress]) ws[cellAddress] = {};
                ws[cellAddress].s = { font: { bold: true } };
            }

            // Tutar sütunları için para formatı
            dataToExport.forEach((fatura, index) => {
                const rowIndex = index + 1; // Başlık satırından sonra
                
                // Toplam Tutar sütunu (F)
                const toplamTutarCell = XLSX.utils.encode_cell({ r: rowIndex, c: 5 });
                if (ws[toplamTutarCell]) {
                    ws[toplamTutarCell].z = '#,##0.00₺';
                }
                
                // Kalan Meblağ sütunu (G)
                const kalanMeblagCell = XLSX.utils.encode_cell({ r: rowIndex, c: 6 });
                if (ws[kalanMeblagCell]) {
                    ws[kalanMeblagCell].z = '#,##0.00₺';
                }
            });

            // Worksheet'i workbook'a ekle
            XLSX.utils.book_append_sheet(wb, ws, 'Faturalar');

            // Dosyayı indir
            const fileName = `faturalar_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, fileName);
        }
    </script>
</body>
</html> 