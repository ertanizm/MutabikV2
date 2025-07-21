<?php
require_once 'includes/db_connect.php';
$mesaj = '';
$fatura = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id']; $fatura_no = trim($_POST['fatura_no']); $musteri_adi = trim($_POST['musteri_adi']); $konu = trim($_POST['konu']); $fatura_tarihi = trim($_POST['fatura_tarihi']); $vade_tarihi = trim($_POST['vade_tarihi']); $tutar = trim($_POST['tutar']); $durum = trim($_POST['durum']);
    $sql = "UPDATE faturalar SET fatura_no = ?, musteri_adi = ?, konu = ?, fatura_tarihi = ?, vade_tarihi = ?, tutar = ?, durum = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // DÜZELTME: Parametre tipleri doğru sıraya konuldu (6 string, 1 double, 1 int)
        $stmt->bind_param("sssssdsi", $fatura_no, $musteri_adi, $konu, $fatura_tarihi, $vade_tarihi, $tutar, $durum, $id);
        if ($stmt->execute()) { header("Location: faturalar.php?status=success_update"); exit(); } else { $mesaj = '<div class="alert alert-danger">Hata: Güncelleme yapılamadı.</div>'; }
        $stmt->close();
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id']; $sql = "SELECT * FROM faturalar WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); $stmt->execute(); $result = $stmt->get_result();
        if ($result->num_rows == 1) { $fatura = $result->fetch_assoc(); } else { header("Location: faturalar.php?status=error"); exit(); }
        $stmt->close();
    }
} else if ($_SERVER["REQUEST_METHOD"] != "POST") { header("Location: faturalar.php"); exit(); }
include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header"><h1>Faturayı Düzenle</h1></header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <?php echo $mesaj; ?>
                <?php if ($fatura): ?>
                <form action="duzenle_fatura.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $fatura['id']; ?>">
                    <div class="form-grid">
                        <div class="form-group"><label for="fatura_no">Fatura Numarası</label><input type="text" id="fatura_no" name="fatura_no" class="form-control" value="<?php echo htmlspecialchars($fatura['fatura_no']); ?>" required></div>
                        <div class="form-group"><label for="musteri_adi">Müşteri Adı</label><input type="text" id="musteri_adi" name="musteri_adi" class="form-control" value="<?php echo htmlspecialchars($fatura['musteri_adi']); ?>" required></div>
                        <div class="form-group full-width"><label for="konu">Konu / Açıklama</label><input type="text" id="konu" name="konu" class="form-control" value="<?php echo htmlspecialchars($fatura['konu']); ?>"></div>
                        <div class="form-group"><label for="fatura_tarihi">Fatura Tarihi</label><input type="date" id="fatura_tarihi" name="fatura_tarihi" class="form-control" value="<?php echo htmlspecialchars($fatura['fatura_tarihi']); ?>" required></div>
                        <div class="form-group"><label for="vade_tarihi">Vade Tarihi</label><input type="date" id="vade_tarihi" name="vade_tarihi" class="form-control" value="<?php echo htmlspecialchars($fatura['vade_tarihi']); ?>" required></div>
                        <div class="form-group"><label for="tutar">Tutar (₺)</label><input type="number" id="tutar" name="tutar" class="form-control" step="0.01" value="<?php echo htmlspecialchars($fatura['tutar']); ?>" required></div>
                        <div class="form-group"><label for="durum">Durum</label>
                            <select id="durum" name="durum" class="form-control" required>
                                <option value="Ödenmedi" <?php echo ($fatura['durum'] == 'Ödenmedi') ? 'selected' : ''; ?>>Ödenmedi</option>
                                <option value="Kısmi Ödendi" <?php echo ($fatura['durum'] == 'Kısmi Ödendi') ? 'selected' : ''; ?>>Kısmi Ödendi</option>
                                <option value="Ödendi" <?php echo ($fatura['durum'] == 'Ödendi') ? 'selected' : ''; ?>>Ödendi</option>
                                <option value="İptal" <?php echo ($fatura['durum'] == 'İptal') ? 'selected' : ''; ?>>İptal</option>
                            </select>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button><a href="faturalar.php" class="btn btn-secondary">İptal</a></div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>