<?php
require_once 'includes/db_connect.php';

// 1. Tarih Filtrelerini Ayarla
// Eğer kullanıcı tarih seçmemişse, varsayılan olarak bu ayın başlangıcını ve sonunu al
$baslangic_tarihi = $_GET['baslangic'] ?? date('Y-m-01');
$bitis_tarihi = $_GET['bitis'] ?? date('Y-m-t');

// 2. Özet Verileri Çek (KPI Kartları için)
$sql_ozet = "SELECT 
                COUNT(id) as fatura_sayisi, 
                SUM(tutar) as toplam_satis, 
                AVG(tutar) as ortalama_fatura 
             FROM faturalar 
             WHERE durum != 'İptal' AND fatura_tarihi BETWEEN ? AND ?";
$stmt_ozet = $conn->prepare($sql_ozet);
$stmt_ozet->bind_param("ss", $baslangic_tarihi, $bitis_tarihi);
$stmt_ozet->execute();
$ozet_sonuc = $stmt_ozet->get_result()->fetch_assoc();

// 3. Grafik Verilerini Çek
$sql_grafik = "SELECT 
                    DATE(fatura_tarihi) as gun, 
                    SUM(tutar) as gunluk_toplam 
               FROM faturalar 
               WHERE durum != 'İptal' AND fatura_tarihi BETWEEN ? AND ?
               GROUP BY DATE(fatura_tarihi) 
               ORDER BY gun ASC";
$stmt_grafik = $conn->prepare($sql_grafik);
$stmt_grafik->bind_param("ss", $baslangic_tarihi, $bitis_tarihi);
$stmt_grafik->execute();
$grafik_sonuc = $stmt_grafik->get_result();
$grafik_etiketler = [];
$grafik_veriler = [];
while($row = $grafik_sonuc->fetch_assoc()){
    $grafik_etiketler[] = date("d M", strtotime($row['gun']));
    $grafik_veriler[] = $row['gunluk_toplam'];
}

// 4. Detay Tablosu için Verileri Çek
$sql_tablo = "SELECT * FROM faturalar WHERE durum != 'İptal' AND fatura_tarihi BETWEEN ? AND ? ORDER BY fatura_tarihi DESC";
$stmt_tablo = $conn->prepare($sql_tablo);
$stmt_tablo->bind_param("ss", $baslangic_tarihi, $bitis_tarihi);
$stmt_tablo->execute();
$tablo_sonuc = $stmt_tablo->get_result();


include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <header class="page-header"><h1>Satışlar Raporu</h1></header>

    <div class="page-content">
        <div class="card">
            <form action="satislar_raporu.php" method="GET">
                <div class="card-header">
                    <div class="filter-group">
                        <label for="baslangic">Başlangıç Tarihi:</label>
                        <input type="date" id="baslangic" name="baslangic" class="form-control" value="<?php echo $baslangic_tarihi; ?>">
                    </div>
                     <div class="filter-group">
                        <label for="bitis">Bitiş Tarihi:</label>
                        <input type="date" id="bitis" name="bitis" class="form-control" value="<?php echo $bitis_tarihi; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Raporu Getir</button>
                </div>
            </form>
        </div>

        <div class="static-grid" style="margin-top: 20px;">
            <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Toplam Satış (KDV Hariç)</h3><div class="metric-item single-metric"><span class="metric-value"><?php echo number_format($ozet_sonuc['toplam_satis'] ?? 0, 2, ',', '.') . ' ₺'; ?></span></div></div></div>
            <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Fatura Sayısı</h3><div class="metric-item single-metric"><span class="metric-value"><?php echo $ozet_sonuc['fatura_sayisi'] ?? 0; ?></span></div></div></div>
            <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Ortalama Fatura Tutarı</h3><div class="metric-item single-metric"><span class="metric-value"><?php echo number_format($ozet_sonuc['ortalama_fatura'] ?? 0, 2, ',', '.') . ' ₺'; ?></span></div></div></div>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="satislarGrafigi"></canvas>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                           <tr><th>Fatura No</th><th>Müşteri</th><th>Fatura Tarihi</th><th>Tutar</th><th>Durum</th></tr>
                        </thead>
                        <tbody>
                            <?php if ($tablo_sonuc->num_rows > 0): while($fatura = $tablo_sonuc->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fatura['fatura_no']); ?></td>
                                    <td><?php echo htmlspecialchars($fatura['musteri_adi']); ?></td>
                                    <td><?php echo date("d.m.Y", strtotime($fatura['fatura_tarihi'])); ?></td>
                                    <td><?php echo number_format($fatura['tutar'], 2, ',', '.') . ' ₺'; ?></td>
                                    <td><?php echo htmlspecialchars($fatura['durum']); ?></td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="5" style="text-align:center;">Seçilen tarih aralığında fatura bulunamadı.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
// Sayfa sonunda veritabanı bağlantısını ve sorguları kapatıyoruz
$stmt_ozet->close();
$stmt_grafik->close();
$stmt_tablo->close();
include 'includes/footer.php';
$conn->close(); 
?>

<script>
// Grafik için JavaScript kodunu footer'a ekliyoruz
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('satislarGrafigi');
    if (ctx) {
        new Chart(ctx, {
            type: 'line', // Grafik tipi: çizgi
            data: {
                labels: <?php echo json_encode($grafik_etiketler); ?>,
                datasets: [{
                    label: 'Günlük Satış Tutarı (₺)',
                    data: <?php echo json_encode($grafik_veriler); ?>,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }
});
</script>