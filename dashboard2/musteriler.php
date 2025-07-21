<?php
require_once 'includes/db_connect.php';
$arama_terimi = $_GET['arama'] ?? '';
$sql = "SELECT * FROM musteriler";
$parametreler = [];
$parametre_tipleri = '';
if (!empty($arama_terimi)) {
    $sql .= " WHERE (unvan LIKE ? OR yetkili_kisi LIKE ?)";
    $arama_wildcard = "%" . $arama_terimi . "%";
    $parametreler[] = &$arama_wildcard; 
    $parametreler[] = &$arama_wildcard;
    $parametre_tipleri .= 'ss';
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
        if($_GET['status'] == 'success_add'): echo '<div class="alert alert-success">Müşteri başarıyla eklendi!</div>';
        elseif($_GET['status'] == 'success_update'): echo '<div class="alert alert-success">Müşteri başarıyla güncellendi!</div>';
        elseif($_GET['status'] == 'success_delete'): echo '<div class="alert alert-success">Müşteri başarıyla silindi!</div>';
        elseif($_GET['status'] == 'error'): echo '<div class="alert alert-danger">Bir hata oluştu.</div>';
        endif;
    endif; 
    ?>
    <header class="page-header">
        <h1>Müşteriler</h1>
        <a href="yeni_musteri.php" class="btn btn-primary"><span class="material-symbols-outlined">add</span> Yeni Müşteri Ekle</a>
    </header>
    <div class="page-content">
        <div class="card">
            <form action="musteriler.php" method="GET">
                <div class="card-header">
                    <input type="text" name="arama" class="form-control" placeholder="Müşteri unvanı veya yetkili kişi ara..." value="<?php echo htmlspecialchars($arama_terimi); ?>">
                </div>
            </form>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                           <tr><th>Müşteri Unvanı</th><th>Yetkili Kişi</th><th>E-posta</th><th>Telefon</th><th>İşlemler</th></tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): while($musteri = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($musteri['unvan']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($musteri['yetkili_kisi']); ?></td>
                                    <td><?php echo htmlspecialchars($musteri['eposta']); ?></td>
                                    <td><?php echo htmlspecialchars($musteri['telefon']); ?></td>
                                    <td class="actions">
                                        <a href="goruntule_musteri.php?id=<?php echo $musteri['id']; ?>" class="action-btn" title="Görüntüle"><span class="material-symbols-outlined">visibility</span></a>
                                        <a href="duzenle_musteri.php?id=<?php echo $musteri['id']; ?>" class="action-btn" title="Düzenle"><span class="material-symbols-outlined">edit</span></a>
                                        <form action="sil_musteri.php" method="POST" onsubmit="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?');" style="display:inline;"><input type="hidden" name="id" value="<?php echo $musteri['id']; ?>"><button type="submit" class="action-btn" title="Sil"><span class="material-symbols-outlined">delete</span></button></form>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="5" style="text-align:center;">Müşteri bulunamadı.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $stmt->close(); include 'includes/footer.php'; $conn->close(); ?>