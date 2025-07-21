<?php
require_once 'includes/db_connect.php';
$musteri = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM musteriler WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); $stmt->execute(); $result = $stmt->get_result();
        if ($result->num_rows == 1) { $musteri = $result->fetch_assoc(); } 
        else { header("Location: musteriler.php?status=error"); exit(); }
        $stmt->close();
    }
} else { header("Location: musteriler.php"); exit(); }
include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header">
        <h1>Müşteri Detayları</h1>
        <div class="header-actions">
            <a href="musteriler.php" class="btn btn-secondary"><span class="material-symbols-outlined">arrow_back</span> Listeye Geri Dön</a>
            <a href="duzenle_musteri.php?id=<?php echo $musteri['id']; ?>" class="btn btn-primary"><span class="material-symbols-outlined">edit</span> Düzenle</a>
        </div>
    </header>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h3><?php echo htmlspecialchars($musteri['unvan']); ?></h3>
            </div>
            <div class="card-body">
                <div class="detail-view">
                    <div class="detail-item"><span class="detail-label">Yetkili Kişi</span><span class="detail-value"><?php echo htmlspecialchars($musteri['yetkili_kisi'] ?? '-'); ?></span></div>
                    <div class="detail-item"><span class="detail-label">E-posta</span><span class="detail-value"><?php echo htmlspecialchars($musteri['eposta'] ?? '-'); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Telefon</span><span class="detail-value"><?php echo htmlspecialchars($musteri['telefon'] ?? '-'); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Vergi Dairesi</span><span class="detail-value"><?php echo htmlspecialchars($musteri['vergi_dairesi'] ?? '-'); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Vergi / TC No</span><span class="detail-value"><?php echo htmlspecialchars($musteri['vergi_no'] ?? '-'); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Kayıt Tarihi</span><span class="detail-value"><?php echo date("d.m.Y", strtotime($musteri['kayit_tarihi'])); ?></span></div>
                    <div class="detail-item full-width"><span class="detail-label">Adres</span><span class="detail-value"><?php echo nl2br(htmlspecialchars($musteri['adres'] ?? '-')); ?></span></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>