<?php
// ... (sayfanın başındaki PHP kodları aynı kalıyor) ...
require_once 'includes/db_connect.php';
$mesaj = '';
$teklif = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id']; $teklif_no = trim($_POST['teklif_no']); $musteri_adi = trim($_POST['musteri_adi']); $konu = trim($_POST['konu']); $tarih = trim($_POST['tarih']); $tutar = trim($_POST['tutar']); $durum = trim($_POST['durum']);
    $sql = "UPDATE teklifler SET teklif_no = ?, musteri_adi = ?, konu = ?, tarih = ?, tutar = ?, durum = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssdsi", $teklif_no, $musteri_adi, $konu, $tarih, $tutar, $durum, $id);
        if ($stmt->execute()) { header("Location: teklifler.php?status=updated"); exit(); } else { $mesaj = '<div class="alert alert-danger">Hata: Güncelleme yapılamadı.</div>'; }
        $stmt->close();
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id']; $sql = "SELECT * FROM teklifler WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); $stmt->execute(); $result = $stmt->get_result();
        if ($result->num_rows == 1) { $teklif = $result->fetch_assoc(); } else { header("Location: teklifler.php?status=error"); exit(); }
        $stmt->close();
    }
} else if ($_SERVER["REQUEST_METHOD"] != "POST") { header("Location: teklifler.php"); exit(); }
include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header"><h1>Teklifi Düzenle</h1></header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <?php echo $mesaj; ?>
                <?php if ($teklif): ?>
                <form action="duzenle_teklif.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $teklif['id']; ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="teklif_no">Teklif Numarası</label>
                            <input type="text" id="teklif_no" name="teklif_no" class="form-control" value="<?php echo htmlspecialchars($teklif['teklif_no']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="musteri_adi">Müşteri Adı</label>
                            <input type="text" id="musteri_adi" name="musteri_adi" class="form-control" value="<?php echo htmlspecialchars($teklif['musteri_adi']); ?>" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="konu">Konu</label>
                            <input type="text" id="konu" name="konu" class="form-control" value="<?php echo htmlspecialchars($teklif['konu']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tarih">Tarih</label>
                            <input type="date" id="tarih" name="tarih" class="form-control" value="<?php echo htmlspecialchars($teklif['tarih']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tutar">Tutar (₺)</label>
                            <input type="number" id="tutar" name="tutar" class="form-control" step="0.01" value="<?php echo htmlspecialchars($teklif['tutar']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="durum">Durum</label>
                            <select id="durum" name="durum" class="form-control" required>
                                <option value="Beklemede" <?php echo ($teklif['durum'] == 'Beklemede') ? 'selected' : ''; ?>>Beklemede</option>
                                <option value="Onaylandı" <?php echo ($teklif['durum'] == 'Onaylandı') ? 'selected' : ''; ?>>Onaylandı</option>
                                <option value="Reddedildi" <?php echo ($teklif['durum'] == 'Reddedildi') ? 'selected' : ''; ?>>Reddedildi</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                            <a href="teklifler.php" class="btn btn-secondary">İptal</a>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>