<?php
require_once 'includes/db_connect.php';

$teklif = null;

// Sayfaya bir ID parametresi ile gelip gelinmediğini kontrol et
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Güvenli sorgu için prepared statement
    $sql = "SELECT * FROM teklifler WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $teklif = $result->fetch_assoc();
        } else {
            // Eğer o ID ile bir kayıt bulunamazsa, hata mesajı ile listeye yönlendir
            header("Location: teklifler.php?status=error");
            exit();
        }
        $stmt->close();
    }
} else {
    // Eğer ID parametresi yoksa, listeye yönlendir
    header("Location: teklifler.php");
    exit();
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <header class="page-header">
        <h1>Teklif Detayları: <?php echo htmlspecialchars($teklif['teklif_no']); ?></h1>
        <div class="header-actions">
            <a href="teklifler.php" class="btn btn-secondary">
                <span class="material-symbols-outlined">arrow_back</span>
                Listeye Geri Dön
            </a>
            <a href="duzenle_teklif.php?id=<?php echo $teklif['id']; ?>" class="btn btn-primary">
                <span class="material-symbols-outlined">edit</span>
                Düzenle
            </a>
        </div>
    </header>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="detail-view">
                    <div class="detail-item">
                        <span class="detail-label">Teklif Numarası</span>
                        <span class="detail-value"><?php echo htmlspecialchars($teklif['teklif_no']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Müşteri Adı</span>
                        <span class="detail-value"><?php echo htmlspecialchars($teklif['musteri_adi']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Konu</span>
                        <span class="detail-value"><?php echo htmlspecialchars($teklif['konu']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Teklif Tarihi</span>
                        <span class="detail-value"><?php echo date("d F Y, l", strtotime($teklif['tarih'])); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Toplam Tutar</span>
                        <span class="detail-value amount"><?php echo number_format($teklif['tutar'], 2, ',', '.') . ' ₺'; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Durum</span>
                        <span class="detail-value">
                            <?php
                                $durum_class = '';
                                if ($teklif['durum'] == 'Onaylandı') $durum_class = 'status-onaylandi';
                                elseif ($teklif['durum'] == 'Beklemede') $durum_class = 'status-beklemede';
                                elseif ($teklif['durum'] == 'Reddedildi') $durum_class = 'status-reddedildi';
                            ?>
                            <span class="status-badge <?php echo $durum_class; ?>"><?php echo htmlspecialchars($teklif['durum']); ?></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include 'includes/footer.php';
$conn->close();
?>