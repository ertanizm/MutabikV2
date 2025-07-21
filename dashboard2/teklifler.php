<?php
// Veritabanı bağlantısı ve filtreleme kodları aynı kalıyor...
require_once 'includes/db_connect.php';
$arama_terimi = $_GET['arama'] ?? '';
$secilen_durum = $_GET['durum'] ?? '';
$sql = "SELECT * FROM teklifler";
$kosullar = [];
$parametreler = [];
$parametre_tipleri = '';
if (!empty($arama_terimi)) {
    $kosullar[] = "(teklif_no LIKE ? OR musteri_adi LIKE ?)";
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
    // Yönlendirmelerden gelen durum mesajlarını göster
    if(isset($_GET['status'])):
        if($_GET['status'] == 'success'):
            echo '<div class="alert alert-success">Teklif başarıyla eklendi!</div>';
        elseif($_GET['status'] == 'updated'):
            echo '<div class="alert alert-success">Teklif başarıyla güncellendi!</div>';
        elseif($_GET['status'] == 'deleted'):
            echo '<div class="alert alert-success">Teklif başarıyla silindi!</div>';
        elseif($_GET['status'] == 'error'):
            echo '<div class="alert alert-danger">Bir hata oluştu. Lütfen tekrar deneyin.</div>';
        endif;
    endif; 
    ?>

    <header class="page-header">
        <h1>Teklifler</h1>
        <a href="yeni_teklif.php" class="btn btn-primary">
            <span class="material-symbols-outlined">add</span>
            Yeni Teklif Oluştur
        </a>
    </header>

    <div class="page-content">
        <div class="card">
            <form action="teklifler.php" method="GET">
                <div class="card-header">
                    <input type="text" name="arama" class="form-control" placeholder="Müşteri veya teklif no ara..." value="<?php echo htmlspecialchars($arama_terimi); ?>">
                    <div class="filter-group">
                        <select name="durum" class="form-control">
                            <option value="">Tüm Durumlar</option>
                            <option value="Onaylandı" <?php echo ($secilen_durum == 'Onaylandı') ? 'selected' : ''; ?>>Onaylandı</option>
                            <option value="Beklemede" <?php echo ($secilen_durum == 'Beklemede') ? 'selected' : ''; ?>>Beklemede</option>
                            <option value="Reddedildi" <?php echo ($secilen_durum == 'Reddedildi') ? 'selected' : ''; ?>>Reddedildi</option>
                        </select>
                        <button type="submit" class="btn btn-secondary">Filtrele</button>
                    </div>
                </div>
            </form>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                           <tr><th>Teklif No</th><th>Müşteri</th><th>Konu</th><th>Tarih</th><th>Tutar</th><th>Durum</th><th>İşlemler</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($teklif = $result->fetch_assoc()) {
                                    // ... durum class belirleme kodu aynı ...
                                    $durum_class = '';
                                    if ($teklif['durum'] == 'Onaylandı') $durum_class = 'status-onaylandi';
                                    elseif ($teklif['durum'] == 'Beklemede') $durum_class = 'status-beklemede';
                                    elseif ($teklif['durum'] == 'Reddedildi') $durum_class = 'status-reddedildi';
                            ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($teklif['teklif_no']); ?></td>
                                        <td><?php echo htmlspecialchars($teklif['musteri_adi']); ?></td>
                                        <td><?php echo htmlspecialchars($teklif['konu']); ?></td>
                                        <td><?php echo date("d.m.Y", strtotime($teklif['tarih'])); ?></td>
                                        <td><?php echo number_format($teklif['tutar'], 2, ',', '.') . ' ₺'; ?></td>
                                        <td><span class="status-badge <?php echo $durum_class; ?>"><?php echo htmlspecialchars($teklif['durum']); ?></span></td>
                                        <td class="actions">
                                            <a href="goruntule_teklif.php?id=<?php echo $teklif['id']; ?>" class="action-btn" title="Görüntüle"><span class="material-symbols-outlined">visibility</span></a>
                                            <a href="duzenle_teklif.php?id=<?php echo $teklif['id']; ?>" class="action-btn" title="Düzenle"><span class="material-symbols-outlined">edit</span></a>
                                            <form action="sil_teklif.php" method="POST" onsubmit="return confirm('Bu teklifi silmek istediğinizden emin misiniz?');" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $teklif['id']; ?>">
                                                <button type="submit" class="action-btn" title="Sil"><span class="material-symbols-outlined">delete</span></button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" style="text-align:center;">Aranan kriterlere uygun teklif bulunamadı.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$stmt->close();
include 'includes/footer.php';
$conn->close();
?>