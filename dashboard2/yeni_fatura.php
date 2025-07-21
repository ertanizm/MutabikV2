<?php
require_once 'includes/db_connect.php';
$mesaj = '';
$yeni_fatura_no = '';
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $sql_last_id = "SELECT id FROM faturalar ORDER BY id DESC LIMIT 1";
    $result_last_id = $conn->query($sql_last_id);
    $new_id = 1;
    if ($result_last_id && $result_last_id->num_rows > 0) {
        $last_row = $result_last_id->fetch_assoc();
        $new_id = $last_row['id'] + 1;
    }
    $yeni_fatura_no = sprintf("FAT-%03d", $new_id);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fatura_no = trim($_POST['fatura_no']);
    $musteri_adi = trim($_POST['musteri_adi']);
    $konu = trim($_POST['konu']);
    $fatura_tarihi = trim($_POST['fatura_tarihi']);
    $vade_tarihi = trim($_POST['vade_tarihi']);
    $tutar = trim($_POST['tutar']);
    $durum = trim($_POST['durum']);
    if (!empty($fatura_no) && !empty($musteri_adi) && !empty($fatura_tarihi) && !empty($vade_tarihi) && !empty($tutar)) {
        $sql = "INSERT INTO faturalar (fatura_no, musteri_adi, konu, fatura_tarihi, vade_tarihi, tutar, durum) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            // DÜZELTME: Parametre tipleri doğru sıraya konuldu (6 string, 1 double)
            $stmt->bind_param("sssssds", $fatura_no, $musteri_adi, $konu, $fatura_tarihi, $vade_tarihi, $tutar, $durum);
            if ($stmt->execute()) {
                header("Location: faturalar.php?status=success_add");
                exit();
            } else { $mesaj = '<div class="alert alert-danger">Hata: Fatura eklenemedi.</div>'; }
            $stmt->close();
        }
    } else { $mesaj = '<div class="alert alert-warning">Lütfen tüm zorunlu alanları doldurun.</div>'; }
    $yeni_fatura_no = $fatura_no;
}
include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header"><h1>Yeni Fatura Oluştur</h1></header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <?php echo $mesaj; ?>
                <form action="yeni_fatura.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group"><label for="fatura_no">Fatura Numarası</label><input type="text" id="fatura_no" name="fatura_no" class="form-control" value="<?php echo htmlspecialchars($yeni_fatura_no); ?>" required></div>
                        <div class="form-group"><label for="musteri_adi">Müşteri Adı</label><input type="text" id="musteri_adi" name="musteri_adi" class="form-control" required></div>
                        <div class="form-group full-width"><label for="konu">Konu / Açıklama</label><input type="text" id="konu" name="konu" class="form-control"></div>
                        <div class="form-group"><label for="fatura_tarihi">Fatura Tarihi</label><input type="date" id="fatura_tarihi" name="fatura_tarihi" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
                        <div class="form-group"><label for="vade_tarihi">Vade Tarihi</label><input type="date" id="vade_tarihi" name="vade_tarihi" class="form-control" required></div>
                        <div class="form-group"><label for="tutar">Tutar (₺)</label><input type="number" id="tutar" name="tutar" class="form-control" step="0.01" required></div>
                         <div class="form-group"><label for="durum">Durum</label>
                            <select id="durum" name="durum" class="form-control" required>
                                <option value="Ödenmedi" selected>Ödenmedi</option>
                                <option value="Kısmi Ödendi">Kısmi Ödendi</option>
                                <option value="Ödendi">Ödendi</option>
                                <option value="İptal">İptal</option>
                            </select>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn btn-primary">Faturayı Kaydet</button><a href="faturalar.php" class="btn btn-secondary">İptal</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>