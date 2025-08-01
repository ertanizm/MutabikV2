<?php

require '../../config/config.php';
try {
   $pdo = getTenantPDO();
} catch (Exception $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Form gönderilmişse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaydet'])) {
    $kaynak_depo_id = $_POST['kaynak_depo_id'];
    $hedef_depo_id = $_POST['hedef_depo_id'];
    $aciklama = $_POST['aciklama'];
    $tarih = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO depo_transferleri (tarih, kaynak_depo_id, hedef_depo_id, aciklama) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tarih, $kaynak_depo_id, $hedef_depo_id, $aciklama]);

  

    header("Location: " . $_SERVER['PHP_SELF'] . "?ok=1");
    exit;
}

// Depoları çek (form ve liste için)
$depolar = $pdo->query("SELECT id, ad FROM depolar")->fetchAll();

// Transferleri çek (liste için)
$transferler = $pdo->query("
    SELECT t.id, t.tarih, d1.ad AS cikis_depo, d2.ad AS giris_depo, t.aciklama
    FROM depo_transferleri t
    JOIN depolar d1 ON t.kaynak_depo_id = d1.id
    JOIN depolar d2 ON t.hedef_depo_id = d2.id
    ORDER BY t.tarih DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Depolar Arası Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/stok/depolar_arasi_transfer.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>
  
        <div class="main-content p-4">
        <!-- Üst Header -->
        <div class="top-header">
            <div class="header-left">
            <h1>Depolar Arası Transferler</h1>
        </div>
            <div class="header-right">
        <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
        </div>
    </div>

       
        <div class="header">
            <div class="header-left">
                <button class="button">Filtrele</button>
                <input type="text" placeholder="Ara...">
            </div>
            <div class="header-right">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transferFormModal">
                    Yeni Transfer Fişi Oluştur
                </button>
            </div>
        </div>

        <div class="table">
            <div class="table-header">
                <div>ID</div>
                <div>Çıkış Deposu</div>
                <div>Giriş Deposu</div>
                <div>Düzenleme Tarihi</div>
                <div>Açıklama</div>
            </div>

            <?php if (count($transferler) > 0): ?>
                <?php foreach ($transferler as $transfer): ?>
                    <div class="table-row d-flex border-bottom py-2">
                        <div class="flex-fill"><?= htmlspecialchars($transfer['id']) ?></div>
                        <div class="flex-fill"><?= htmlspecialchars($transfer['cikis_depo']) ?></div>
                        <div class="flex-fill"><?= htmlspecialchars($transfer['giris_depo']) ?></div>
                        <div class="flex-fill"><?= htmlspecialchars($transfer['tarih']) ?></div>
                        <div class="flex-fill"><?= htmlspecialchars($transfer['aciklama']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-row">
                    <div>Henüz transfer kaydı bulunmamaktadır.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Transfer Ekle Modal -->
<div class="modal fade" id="transferFormModal" tabindex="-1" aria-labelledby="transferFormModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transferFormModalLabel">Yeni Transfer Fişi Oluştur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="kaynakDepo" class="form-label">Çıkış Deposu</label>
            <select name="kaynak_depo_id" id="kaynakDepo" class="form-select" required>
              <option value="">Seçiniz</option>
              <?php foreach ($depolar as $depo): ?>
                <option value="<?= $depo['id'] ?>"><?= htmlspecialchars($depo['ad']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="hedefDepo" class="form-label">Giriş Deposu</label>
            <select name="hedef_depo_id" id="hedefDepo" class="form-select" required>
              <option value="">Seçiniz</option>
              <?php foreach ($depolar as $depo): ?>
                <option value="<?= $depo['id'] ?>"><?= htmlspecialchars($depo['ad']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12">
            <label for="aciklama" class="form-label">Açıklama</label>
            <textarea name="aciklama" id="aciklama" rows="3" class="form-control" placeholder="İsteğe bağlı bir açıklama girin..."></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="submit" name="kaydet" class="btn btn-primary">Kaydet</button>
      </div>
    </form>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
</body>

</html>

