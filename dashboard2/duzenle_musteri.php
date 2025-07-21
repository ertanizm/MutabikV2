<?php
require_once 'includes/db_connect.php';
$mesaj = '';
$musteri = null;

// FORM GÖNDERİLDİĞİNDE (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Form verilerini al
    $id = $_POST['id'];
    $unvan = trim($_POST['unvan']);
    $yetkili_kisi = trim($_POST['yetkili_kisi']);
    $eposta = trim($_POST['eposta']);
    $telefon = trim($_POST['telefon']);
    $adres = trim($_POST['adres']);
    $vergi_dairesi = trim($_POST['vergi_dairesi']);
    $vergi_no = trim($_POST['vergi_no']);
    
    // PHP tarafı doğrulama (validation)
    $errors = [];
    if (empty($unvan)) { $errors[] = "Müşteri unvanı zorunlu bir alandır."; }
    if (!empty($eposta) && !filter_var($eposta, FILTER_VALIDATE_EMAIL)) { $errors[] = "Lütfen geçerli bir e-posta adresi girin."; }
    if (!empty($telefon)) {
        $telefon_temiz = preg_replace('/[^0-9]/', '', $telefon);
        if (strlen($telefon_temiz) < 10) { $errors[] = "Telefon numarası en az 10 rakamdan oluşmalıdır."; }
    }
    if (!empty($vergi_no)) {
        $vergi_no_temiz = preg_replace('/[^0-9]/', '', $vergi_no);
        if (strlen($vergi_no_temiz) != 10 && strlen($vergi_no_temiz) != 11) { $errors[] = "Vergi/TC Kimlik No 10 veya 11 rakamdan oluşmalıdır."; }
    }

    // Eğer hata yoksa güncelle, varsa hata mesajını göster
    if (empty($errors)) {
        $sql = "UPDATE musteriler SET unvan = ?, yetkili_kisi = ?, eposta = ?, telefon = ?, adres = ?, vergi_dairesi = ?, vergi_no = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssi", $unvan, $yetkili_kisi, $eposta, $telefon, $adres, $vergi_dairesi, $vergi_no, $id);
            if ($stmt->execute()) {
                header("Location: musteriler.php?status=success_update");
                exit();
            } else { $mesaj = '<div class="alert alert-danger">Hata: Güncelleme yapılamadı.</div>'; }
            $stmt->close();
        }
    } else {
        $mesaj = '<div class="alert alert-danger"><ul>';
        foreach ($errors as $error) { $mesaj .= '<li>' . $error . '</li>'; }
        $mesaj .= '</ul></div>';
        // Hata olduğunda, formun tekrar doldurulması için müşteri verilerini yeniden yüklememiz gerekir.
        $musteri = $_POST;
    }
} 
// SAYFA İLK AÇILDIĞINDA (GET)
else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM musteriler WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $musteri = $result->fetch_assoc();
        } else {
            header("Location: musteriler.php?status=error");
            exit();
        }
        $stmt->close();
    }
} 
// ID olmadan direk erişim denendiğinde
else {
    header("Location: musteriler.php");
    exit();
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header"><h1>Müşteriyi Düzenle</h1></header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <?php echo $mesaj; ?>
                <?php if ($musteri): ?>
                <form action="duzenle_musteri.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $musteri['id']; ?>">
                    <div class="form-grid">
                        <div class="form-group"><label for="unvan">Müşteri Unvanı / Adı Soyadı</label><input type="text" id="unvan" name="unvan" class="form-control" value="<?php echo htmlspecialchars($_POST['unvan'] ?? $musteri['unvan']); ?>" required></div>
                        <div class="form-group"><label for="yetkili_kisi">Yetkili Kişi</label><input type="text" id="yetkili_kisi" name="yetkili_kisi" class="form-control" value="<?php echo htmlspecialchars($_POST['yetkili_kisi'] ?? $musteri['yetkili_kisi']); ?>"></div>
                        <div class="form-group"><label for="eposta">E-posta Adresi</label><input type="email" id="eposta" name="eposta" class="form-control" value="<?php echo htmlspecialchars($_POST['eposta'] ?? $musteri['eposta']); ?>"></div>
                        <div class="form-group"><label for="telefon">Telefon</label><input type="text" id="telefon" name="telefon" class="form-control" value="<?php echo htmlspecialchars($_POST['telefon'] ?? $musteri['telefon']); ?>"></div>
                        <div class="form-group full-width"><label for="adres">Adres</label><textarea id="adres" name="adres" class="form-control" rows="3"><?php echo htmlspecialchars($_POST['adres'] ?? $musteri['adres']); ?></textarea></div>
                        <div class="form-group"><label for="vergi_dairesi">Vergi Dairesi</label><input type="text" id="vergi_dairesi" name="vergi_dairesi" class="form-control" value="<?php echo htmlspecialchars($_POST['vergi_dairesi'] ?? $musteri['vergi_dairesi']); ?>"></div>
                        <div class="form-group"><label for="vergi_no">Vergi / T.C. Kimlik No</label><input type="text" id="vergi_no" name="vergi_no" class="form-control" value="<?php echo htmlspecialchars($_POST['vergi_no'] ?? $musteri['vergi_no']); ?>"></div>
                        <div class="form-actions"><button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button><a href="musteriler.php" class="btn btn-secondary">İptal</a></div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>