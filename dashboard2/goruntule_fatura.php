<?php
require_once 'includes/db_connect.php';
$fatura = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM faturalar WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); 
        if($stmt->execute()){
            $result = $stmt->get_result();
            if ($result->num_rows == 1) { 
                $fatura = $result->fetch_assoc(); 
            } else { 
                header("Location: faturalar.php?status=error"); 
                exit(); 
            }
        }
        $stmt->close();
    }
} else { 
    header("Location: faturalar.php"); 
    exit(); 
}
include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header">
        <h1>Fatura Detayları: <?php echo htmlspecialchars($fatura['fatura_no']); ?></h1>
        <div class="header-actions">
            <a href="faturalar.php" class="btn btn-secondary"><span class="material-symbols-outlined">arrow_back</span> Listeye Geri Dön</a>
            <a href="duzenle_fatura.php?id=<?php echo $fatura['id']; ?>" class="btn btn-primary"><span class="material-symbols-outlined">edit</span> Düzenle</a>
        </div>
    </header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="detail-view">
                    <div class="detail-item"><span class="detail-label">Fatura Numarası</span><span class="detail-value"><?php echo htmlspecialchars($fatura['fatura_no']); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Müşteri Adı</span><span class="detail-value"><?php echo htmlspecialchars($fatura['musteri_adi']); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Konu / Açıklama</span><span class="detail-value"><?php echo htmlspecialchars($fatura['konu'] ?? '-'); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Fatura Tarihi</span><span class="detail-value"><?php echo date("d F Y, l", strtotime($fatura['fatura_tarihi'])); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Vade Tarihi</span><span class="detail-value"><?php echo date("d F Y, l", strtotime($fatura['vade_tarihi'])); ?></span></div>
                    <div class="detail-item"><span class="detail-label">Toplam Tutar</span><span class="detail-value amount"><?php echo number_format($fatura['tutar'], 2, ',', '.') . ' ₺'; ?></span></div>
                    <div class="detail-item"><span class="detail-label">Durum</span><span class="detail-value">
                        <?php
                            $durum_class = '';
                            switch ($fatura['durum']) {
                                case 'Ödendi': $durum_class = 'status-odendi'; break;
                                case 'Ödenmedi': $durum_class = 'status-odenmedi'; break;
                                case 'Kısmi Ödendi': $durum_class = 'status-kismi-odendi'; break;
                                case 'İptal': $durum_class = 'status-iptal'; break;
                            }
                        ?>
                        <span class="status-badge <?php echo $durum_class; ?>"><?php echo htmlspecialchars($fatura['durum']); ?></span>
                    </span></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>