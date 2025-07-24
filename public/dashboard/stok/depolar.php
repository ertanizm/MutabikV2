<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Depolar - Mutabık</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <!-- Özel CSS -->
    <link rel="stylesheet" href="../dashboard.css" />
    <style>

        /* Top Header */
    .top-header {
    background-color: white;
    padding: 15px 25px;
    border-bottom: 1px solid var(--border-color, #ddd);
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

    .header-left h1 {
    font-size: 24px;
    color: var(--text-primary, #333);
    margin: 0;
    }

    .header-right {
    display: flex;
    align-items: center;
    gap: 20px;
    }

    </style>
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content p-4">
        <!-- Üst Header -->
        <div class="top-header">
            <div class="header-left">
            <h1>Depolar</h1>
        </div>
            <div class="header-right">
        <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
        </div>
    </div>

        <!-- Depo Ekle Butonu -->
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#depoEkleModal">
                <i class="fas fa-plus"></i> Depo Ekle
            </button>
        </div>

        <!-- Depo Listesi Tablosu -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Depo Adı</th>
                        <th>Adres</th>
                        <th>Telefon</th>
                        <th>Yetkili</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody id="depoTableBody">
                    <!-- Örnek veri -->
                    <tr>
                        <td>1</td>
                        <td>Merkez Depo</td>
                        <td>İstanbul, Türkiye</td>
                        <td>+90 212 123 45 67</td>
                        <td>Ahmet Yılmaz</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" title="Düzenle"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Şube Depo</td>
                        <td>Ankara, Türkiye</td>
                        <td>+90 312 765 43 21</td>
                        <td>Mehmet Demir</td>
                        <td><span class="badge bg-secondary">Pasif</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" title="Düzenle"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <!-- Buraya PHP ile veri çekilip eklenecek -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Depo Ekle Modal -->
    <div class="modal fade" id="depoEkleModal" tabindex="-1" aria-labelledby="depoEkleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="depoEkleForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="depoEkleModalLabel">Yeni Depo Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="depoAdi" class="form-label">Depo Adı</label>
                        <input type="text" class="form-control" id="depoAdi" required />
                    </div>
                    <div class="mb-3">
                        <label for="depoAdres" class="form-label">Adres</label>
                        <textarea class="form-control" id="depoAdres" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="depoTelefon" class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="depoTelefon" />
                    </div>
                    <div class="mb-3">
                        <label for="depoYetkili" class="form-label">Yetkili</label>
                        <input type="text" class="form-control" id="depoYetkili" />
                    </div>
                    <div class="mb-3">
                        <label for="depoDurum" class="form-label">Durum</label>
                        <select id="depoDurum" class="form-select" required>
                            <option value="aktif" selected>Aktif</option>
                            <option value="pasif">Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Modal form submit
        document.getElementById('depoEkleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Form alanlarını al
            const depoAdi = document.getElementById('depoAdi').value.trim();
            const depoAdres = document.getElementById('depoAdres').value.trim();
            const depoTelefon = document.getElementById('depoTelefon').value.trim();
            const depoYetkili = document.getElementById('depoYetkili').value.trim();
            const depoDurum = document.getElementById('depoDurum').value;

            if (!depoAdi || !depoAdres) {
                alert('Depo adı ve adresi zorunludur.');
                return;
            }

            // Yeni depo satırı oluştur
            const tbody = document.getElementById('depoTableBody');
            const newRow = document.createElement('tr');

            // Yeni ID örneği (statik, sen backend ile yapabilirsin)
            const newId = tbody.children.length + 1;

            newRow.innerHTML = `
                <td>${newId}</td>
                <td>${depoAdi}</td>
                <td>${depoAdres}</td>
                <td>${depoTelefon}</td>
                <td>${depoYetkili}</td>
                <td><span class="badge bg-${depoDurum === 'aktif' ? 'success' : 'secondary'}">${depoDurum.charAt(0).toUpperCase() + depoDurum.slice(1)}</span></td>
                <td>
                    <button class="btn btn-sm btn-warning" title="Düzenle"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;

            tbody.appendChild(newRow);

            // Modal kapat
            const modal = bootstrap.Modal.getInstance(document.getElementById('depoEkleModal'));
            modal.hide();

            // Formu resetle
            this.reset();
        });

        // TODO: Düzenle ve Sil butonlarına event ekleyebilirsin
    </script>
    <script src="../script2.js"></script>
</body>
</html>
