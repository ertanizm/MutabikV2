<?php
require_once 'includes/db_connect.php';

$mesaj = '';
$yeni_teklif_no = ''; // Değişkeni başlangıçta boş olarak tanımlıyoruz.

// 1. EĞER SAYFA İLK KEZ AÇILIYORSA (POST DEĞİLSE), OTOMATİK NUMARA OLUŞTUR
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Veritabanındaki en son kaydın ID'sini alıyoruz
    $sql_last_id = "SELECT id FROM teklifler ORDER BY id DESC LIMIT 1";
    $result_last_id = $conn->query($sql_last_id);
    $new_id = 1; // Varsayılan, eğer hiç kayıt yoksa

    if ($result_last_id && $result_last_id->num_rows > 0) {
        $last_row = $result_last_id->fetch_assoc();
        $new_id = $last_row['id'] + 1;
    }
    // Yeni ID'ye göre TEK-000X formatında numarayı oluştur
    $yeni_teklif_no = sprintf("TEK-%03d", $new_id);
}

// 2. EĞER FORM GÖNDERİLDİYSE (POST İSE), KAYIT İŞLEMİNİ YAP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al ve temizle
    $teklif_no = trim($_POST['teklif_no']);
    $musteri_adi = trim($_POST['musteri_adi']);
    $konu = trim($_POST['konu']);
    $tarih = trim($_POST['tarih']);
    $tutar = trim($_POST['tutar']);
    $durum = trim($_POST['durum']);

    if (!empty($teklif_no) && !empty($musteri_adi) && !empty($konu) && !empty($tarih) && !empty($tutar) && !empty($durum)) {
        $sql = "INSERT INTO teklifler (teklif_no, musteri_adi, konu, tarih, tutar, durum) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssds", $teklif_no, $musteri_adi, $konu, $tarih, $tutar, $durum);
            if ($stmt->execute()) {
                header("Location: teklifler.php?status=success");
                exit();
            } else { $mesaj = '<div class="alert alert-danger">Hata: Kayıt eklenemedi.</div>'; }
            $stmt->close();
        }
    } else { $mesaj = '<div class="alert alert-warning">Lütfen tüm zorunlu alanları doldurun.</div>'; }
    
    // Eğer form gönderiminde hata olursa, kullanıcının girdiği değerleri koru
    $yeni_teklif_no = $teklif_no; 
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header"><h1>Yeni Teklif Oluştur</h1></header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <?php echo $mesaj; ?>
                <form action="yeni_teklif.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="teklif_no">Teklif Numarası</label>
                            <input type="text" id="teklif_no" name="teklif_no" class="form-control" value="<?php echo htmlspecialchars($yeni_teklif_no); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="musteri_adi">Müşteri Adı</label>
                            <input type="text" id="musteri_adi" name="musteri_adi" class="form-control" value="<?php echo htmlspecialchars($_POST['musteri_adi'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="konu">Konu</label>
                            <input type="text" id="konu" name="konu" class="form-control" value="<?php echo htmlspecialchars($_POST['konu'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tarih">Tarih</label>
                            <input type="date" id="tarih" name="tarih" class="form-control" value="<?php echo htmlspecialchars($_POST['tarih'] ?? date('Y-m-d')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tutar">Tutar (₺)</label>
                            <input type="number" id="tutar" name="tutar" class="form-control" step="0.01" value="<?php echo htmlspecialchars($_POST['tutar'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="durum">Durum</label>
                            <select id="durum" name="durum" class="form-control" required>
                                <option value="Beklemede" selected>Beklemede</option>
                                <option value="Onaylandı">Onaylandı</option>
                                <option value="Reddedildi">Reddedildi</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Teklifi Kaydet</button>
                            <a href="teklifler.php" class="btn btn-secondary">İptal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>