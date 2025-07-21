<?php
require_once 'includes/db_connect.php';
$mesaj = '';
$form_data = $_POST;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unvan = trim($_POST['unvan']);
    $yetkili_kisi = trim($_POST['yetkili_kisi']);
    $eposta = trim($_POST['eposta']);
    $telefon = trim($_POST['telefon']);
    $adres = trim($_POST['adres']);
    $vergi_dairesi = trim($_POST['vergi_dairesi']);
    $vergi_no = trim($_POST['vergi_no']);

    $errors = [];

    // --- DÜZELTİLMİŞ KONTROLLER ---
    if (empty($unvan)) {
        $errors[] = "Müşteri unvanı zorunlu bir alandır.";
    }
    if (!empty($eposta) && !filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Lütfen geçerli bir e-posta adresi girin.";
    }
    // Telefon numarasındaki harf olmayan her şeyi temizle ve uzunluğunu kontrol et
    if (!empty($telefon)) {
        $telefon_temiz = preg_replace('/[^0-9]/', '', $telefon);
        if (strlen($telefon_temiz) < 10) {
            $errors[] = "Telefon numarası en az 10 rakamdan oluşmalıdır.";
        }
    }
    // Vergi/TC numarasındaki harf olmayan her şeyi temizle ve uzunluğunu kontrol et
    if (!empty($vergi_no)) {
        $vergi_no_temiz = preg_replace('/[^0-9]/', '', $vergi_no);
        if (strlen($vergi_no_temiz) != 10 && strlen($vergi_no_temiz) != 11) {
            $errors[] = "Vergi/TC Kimlik No 10 veya 11 rakamdan oluşmalıdır.";
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO musteriler (unvan, yetkili_kisi, eposta, telefon, adres, vergi_dairesi, vergi_no) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $unvan, $yetkili_kisi, $eposta, $telefon, $adres, $vergi_dairesi, $vergi_no);
            if ($stmt->execute()) {
                header("Location: musteriler.php?status=success_add");
                exit();
            } else { $mesaj = '<div class="alert alert-danger">Hata: Müşteri eklenemedi.</div>';}
            $stmt->close();
        }
    } else {
        $mesaj = '<div class="alert alert-danger"><ul>';
        foreach ($errors as $error) { $mesaj .= '<li>' . $error . '</li>'; }
        $mesaj .= '</ul></div>';
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>
<main class="main-content">
    <header class="page-header"><h1>Yeni Müşteri Ekle</h1></header>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <?php echo $mesaj; ?>
                <form action="yeni_musteri.php" method="POST" id="musteriForm" novalidate>
                    <div class="form-grid">
                        <div class="form-group"><label for="unvan">Müşteri Unvanı / Adı Soyadı</label><input type="text" id="unvan" name="unvan" class="form-control" value="<?php echo htmlspecialchars($form_data['unvan'] ?? ''); ?>" required></div>
                        <div class="form-group"><label for="yetkili_kisi">Yetkili Kişi</label><input type="text" id="yetkili_kisi" name="yetkili_kisi" class="form-control" value="<?php echo htmlspecialchars($form_data['yetkili_kisi'] ?? ''); ?>"></div>
                        <div class="form-group"><label for="eposta">E-posta Adresi</label><input type="email" id="eposta" name="eposta" class="form-control" value="<?php echo htmlspecialchars($form_data['eposta'] ?? ''); ?>"><div class="invalid-feedback">Lütfen geçerli bir e-posta adresi girin.</div></div>
                        <div class="form-group"><label for="telefon">Telefon</label><input type="text" id="telefon" name="telefon" class="form-control" value="<?php echo htmlspecialchars($form_data['telefon'] ?? ''); ?>"><div class="invalid-feedback">Telefon numarası en az 10 rakamdan oluşmalıdır.</div></div>
                        <div class="form-group full-width"><label for="adres">Adres</label><textarea id="adres" name="adres" class="form-control" rows="3"><?php echo htmlspecialchars($form_data['adres'] ?? ''); ?></textarea></div>
                        <div class="form-group"><label for="vergi_dairesi">Vergi Dairesi</label><input type="text" id="vergi_dairesi" name="vergi_dairesi" class="form-control" value="<?php echo htmlspecialchars($form_data['vergi_dairesi'] ?? ''); ?>"></div>
                        <div class="form-group"><label for="vergi_no">Vergi / T.C. Kimlik No</label><input type="text" id="vergi_no" name="vergi_no" class="form-control" value="<?php echo htmlspecialchars($form_data['vergi_no'] ?? ''); ?>"><div class="invalid-feedback">Vergi/TC Kimlik No 10 veya 11 rakamdan oluşmalıdır.</div></div>
                        <div class="form-actions"><button type="submit" class="btn btn-primary">Müşteriyi Kaydet</button><a href="musteriler.php" class="btn btn-secondary">İptal</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; $conn->close(); ?>