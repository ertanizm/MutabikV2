<?php
require_once 'includes/db_connect.php';

// Bekleyen Teklifleri Çek (Sadece ilk 3 kayıt)
$sql_bekleyen_teklifler = "SELECT id, teklif_no, musteri_adi, tutar 
                           FROM teklifler 
                           WHERE durum = 'Beklemede' 
                           ORDER BY tarih DESC 
                           LIMIT 3";
$bekleyen_teklifler = $conn->query($sql_bekleyen_teklifler);

// En Çok Borçlu Müşterileri Çek (Sadece ilk 3 müşteri)
$sql_borclu_musteriler = "SELECT musteri_adi, SUM(tutar) as toplam_borc
                          FROM faturalar
                          WHERE (durum = 'Ödenmedi' OR durum = 'Kısmi Ödendi')
                          AND vade_tarihi < CURDATE()
                          GROUP BY musteri_adi
                          ORDER BY toplam_borc DESC
                          LIMIT 3";
$borclu_musteriler = $conn->query($sql_borclu_musteriler);


include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <header class="page-header">
        <h1>Gösterge Paneli</h1>
        <div class="header-actions">
            <div class="widget-editor">
                <button id="editPanelBtn" class="btn btn-secondary"><span class="material-symbols-outlined">edit</span> Widget Ekle</button>
                <div id="widgetMenu" class="widget-menu">
                    <h4>Opsiyonel Widget'lar</h4>
                    <ul>
                        <li><label><input type="checkbox" data-widget="widget-grafik"> Nakit Akışı Grafiği</label></li>
                        <li><label><input type="checkbox" data-widget="widget-son-islemler"> Son İşlemler</label></li>
                        <li><label><input type="checkbox" data-widget="widget-teklifler"> Bekleyen Teklifler</label></li>
                        <li><label><input type="checkbox" data-widget="widget-borclular"> En Çok Borçlu Müşteriler</label></li>
                    </ul>
                </div>
            </div>
            <a href="yeni_fatura.php" class="btn btn-primary"><span class="material-symbols-outlined">add</span> Yeni Fatura Oluştur</a>
        </div>
    </header>
    
    <div class="static-grid">
        <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Toplam Tahsilat</h3><div class="metric-item single-metric"><span class="metric-value">0,00₺</span></div></div></div>
        <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Toplam Ödeme</h3><div class="metric-item single-metric"><span class="metric-value">0,00₺</span></div></div></div>
        <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Kasa & Banka</h3><div class="metric-item single-metric"><span class="metric-value">0,00₺</span></div></div></div>
        <div class="widget"><div class="widget-inner"><h3 class="widget-title static-title">Gecikmiş Alacak</h3><div class="metric-item single-metric"><span class="metric-value">0,00₺</span></div></div></div>
    </div>
    
    <hr class="separator">

    <div class="dashboard-grid">
        <div id="empty-placeholder" class="empty-dashboard-placeholder"><span class="icon material-symbols-outlined">add_circle</span><h4>Paneliniz Henüz Boş</h4><p>Panelinizi kişiselleştirmek için sağ üstteki "Widget Ekle" butonunu kullanabilirsiniz.</p></div>
        <div id="widget-grafik" class="widget large-widget widget-hidden"><h3 class="widget-title">Nakit Akışı Grafiği</h3><div class="widget-inner chart-container"><canvas id="cashFlowChart"></canvas></div></div>
        <div id="widget-son-islemler" class="widget widget-hidden"><h3 class="widget-title">Son İşlemler</h3><div class="widget-inner"><ul class="activity-list"><li><span class="activity-icon material-symbols-outlined receipt">receipt_long</span> <div><p>Fatura #FAT-001 oluşturuldu.</p><small>Az önce</small></div></li><li><span class="activity-icon material-symbols-outlined payment">payments</span> <div><p>ABC A.Ş. firmasından 1.500₺ tahsil edildi.</p><small>1 saat önce</small></div></li></ul></div></div>
        
        <div id="widget-teklifler" class="widget widget-hidden">
            <h3 class="widget-title">Bekleyen Teklifler</h3>
            <div class="widget-inner">
                <ul class="activity-list">
                    <?php if ($bekleyen_teklifler && $bekleyen_teklifler->num_rows > 0): while($teklif = $bekleyen_teklifler->fetch_assoc()): ?>
                        <li>
                            <span class="activity-icon material-symbols-outlined quote">request_quote</span>
                            <div>
                                <p><a href="goruntule_teklif.php?id=<?php echo $teklif['id']; ?>"><?php echo htmlspecialchars($teklif['musteri_adi']); ?></a></p>
                                <small><?php echo htmlspecialchars($teklif['teklif_no']); ?> - <?php echo number_format($teklif['tutar'], 2, ',', '.') . ' ₺'; ?></small>
                            </div>
                        </li>
                    <?php endwhile; else: ?>
                        <p style="color: var(--text-muted); text-align: center; padding: 20px 0;">Onay bekleyen teklif bulunmuyor.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <div id="widget-borclular" class="widget widget-hidden">
            <h3 class="widget-title">En Çok Borçlu Müşteriler</h3>
            <div class="widget-inner">
                <ul class="activity-list">
                    <?php if ($borclu_musteriler && $borclu_musteriler->num_rows > 0): while($musteri = $borclu_musteriler->fetch_assoc()): ?>
                        <li>
                            <span class="activity-icon material-symbols-outlined user">person</span>
                            <div>
                                <p><?php echo htmlspecialchars($musteri['musteri_adi']); ?></p>
                                <small>Vadesi Geçmiş Bakiye: <strong><?php echo number_format($musteri['toplam_borc'], 2, ',', '.') . ' ₺'; ?></strong></small>
                            </div>
                        </li>
                    <?php endwhile; else: ?>
                         <p style="color: var(--text-muted); text-align: center; padding: 20px 0;">Vadesi geçmiş borçlu müşteri bulunmuyor.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>