<?php
require_once 'includes/db_connect.php';
$arama_terimi = $_GET['arama'] ?? '';
$secilen_durum = $_GET['durum'] ?? '';
$sql = "SELECT * FROM faturalar";
$kosullar = [];
$parametreler = [];
$parametre_tipleri = '';
if (!empty($arama_terimi)) {
    $kosullar[] = "(fatura_no LIKE ? OR musteri_adi LIKE ?)";
    $arama_wildcard = "%" . $arama_terimi . "%";
    $parametreler[] = &$arama_wildcard; $parametreler[] = &$arama_wildcard;
    $parametre_tipleri .= 'ss';
}
if (!empty($secilen_durum)) {
    $kosullar[] = "durum = ?";
    $parametreler[] = &$secilen_durum;
    $parametre_tipleri .= 's';
}
if (!empty($kosullar)) {
    $sql .= " WHERE " . implode(" AND ", $kosullar);
}
$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);
if (!empty($parametreler)) {
    $stmt->bind_param($parametre_tipleri, ...$parametreler);
}
$stmt->execute();
$result = $stmt->get_result();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <?php 
    if(isset($_GET['status'])):
        if($_GET['status'] == 'success_add'): echo '<div class="alert alert-success">Fatura başarıyla eklendi!</div>';
        elseif($_GET['status'] == 'success_update'): echo '<div class="alert alert-success">Fatura başarıyla güncellendi!</div>';
        elseif($_GET['status'] == 'success_delete'): echo '<div class="alert alert-success">Fatura başarıyla silindi!</div>';
        elseif($_GET['status'] == 'error'): echo '<div class="alert alert-danger">Bir hata oluştu.</div>';
        endif;
    endif; 
    ?>
    <header class="page-header">
        <h1>Faturalar</h1>
        <a href="yeni_fatura.php" class="btn btn-primary"><span class="material-symbols-outlined">add</span> Yeni Fatura Oluştur</a>
    </header>
    <div class="page-content">
        <div class="card">
            <form action="faturalar.php" method="GET">
                <div class="card-header">
                    <input type="text" name="arama" class="form-control" placeholder="Müşteri veya fatura no ara..." value="<?php echo htmlspecialchars($arama_terimi); ?>">
                    <div class="filter-group">
                        <select name="durum" class="form-control">
                            <option value="">Tüm Durumlar</option>
                            <option value="Ödendi" <?php echo ($secilen_durum == 'Ödendi') ? 'selected' : ''; ?>>Ödendi</option>
                            <option value="Ödenmedi" <?php echo ($secilen_durum == 'Ödenmedi') ? 'selected' : ''; ?>>Ödenmedi</option>
                            <option value="Kısmi Ödendi" <?php echo ($secilen_durum == 'Kısmi Ödendi') ? 'selected' : ''; ?>>Kısmi Ödendi</option>
                            <option value="İptal" <?php echo ($secilen_durum == 'İptal') ? 'selected' : ''; ?>>İptal</option>
                        </select>
                        <button type="submit" class="btn btn-secondary">Filtrele</button>
                    </div>
                </div>
            </form>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                           <tr><th>Fatura No</th><th>Müşteri</th><th>Fatura Tarihi</th><th>Vade Tarihi</th><th>Tutar</th><th>Durum</th><th>İşlemler</th></tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): while($fatura = $result->fetch_assoc()): ?>
                                <?php
                                    $durum_class = '';
                                    switch ($fatura['durum']) {
                                        case 'Ödendi': $durum_class = 'status-odendi'; break;
                                        case 'Ödenmedi': $durum_class = 'status-odenmedi'; break;
                                        case 'Kısmi Ödendi': $durum_class = 'status-kismi-odendi'; break;
                                        case 'İptal': $durum_class = 'status-iptal'; break;
                                    }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fatura['fatura_no']); ?></td>
                                    <td><?php echo htmlspecialchars($fatura['musteri_adi']); ?></td>
                                    <td><?php echo date("d.m.Y", strtotime($fatura['fatura_tarihi'])); ?></td>
                                    <td><?php echo date("d.m.Y", strtotime($fatura['vade_tarihi'])); ?></td>
                                    <td><?php echo number_format($fatura['tutar'], 2, ',', '.') . ' ₺'; ?></td>
                                    <td><span class="status-badge <?php echo $durum_class; ?>"><?php echo htmlspecialchars($fatura['durum']); ?></span></td>
                                    <td class="actions">
                                        <a href="goruntule_fatura.php?id=<?php echo $fatura['id']; ?>" class="action-btn" title="Görüntüle"><span class="material-symbols-outlined">visibility</span></a>
                                        <a href="duzenle_fatura.php?id=<?php echo $fatura['id']; ?>" class="action-btn" title="Düzenle"><span class="material-symbols-outlined">edit</span></a>
                                        <form action="sil_fatura.php" method="POST" onsubmit="return confirm('Bu faturayı silmek istediğinizden emin misiniz?');" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $fatura['id']; ?>">
                                            <button type="submit" class="action-btn" title="Sil"><span class="material-symbols-outlined">delete</span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="7" style="text-align:center;">Aranan kriterlere uygun fatura bulunamadı.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $stmt->close(); include 'includes/footer.php'; $conn->close(); ?>