<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabık - cari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../dashboard.css" rel="stylesheet">
    <style>
        /* Müşteriler sayfası özel stil */
        .customer-actions-bar {
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .customer-actions-bar .search-box {
            flex-grow: 1;
            max-width: 300px;
        }
        .customer-list-section .table {
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden; /* Köşeleri yuvarlatmak için */
        }
        .customer-list-section .table thead {
            background-color: var(--sidebar-bg);
            color: white;
        }
        .customer-list-section .table th,
        .customer-list-section .table td {
            vertical-align: middle;
        }
        .customer-list-section .table tbody tr:nth-child(even) {
            background-color: #f0f2f5;
        }
        .customer-list-section .table-bordered {
            border: 1px solid var(--border-color);
        }
        .customer-list-section .action-buttons .btn {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        /* Modal Stilleri */
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }
        .modal-header .btn-close {
            filter: invert(1); /* Beyaz çarpı işareti */
        }
        .modal-footer {
            border-top: none;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="main-content">
        <div class="top-header">
            <div class="header-left">
                <h1>Çalışanlar</h1>
            </div>
            
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
       
        <div class="customer-actions-bar">
            <input type="text" class="form-control search-box" placeholder="Müşteri Ara...">
            <div>
                <button class="btn btn-info text-white me-2">
                    <i class="fas fa-file-export"></i> Dışa Aktar
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus"></i> Yeni Çalışan Ekle
                </button>
            </div>
        </div>

        <div class="financial-section customer-list-section">
            <h2 class="section-title">
                <i class="fas fa-users"></i>
                Çalışan Listesi
            </h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Çalışan Adı</th>
                            <th>Vergi Numarası / TCKN</th>
                            <th>E-posta</th>
                            <th>Telefon</th>
                            <th>Adres</th>
                            <th>Toplam Borç</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ABC İnşaat Ltd. Şti.</td>
                            <td>1234567890</td>
                            <td>info@abcinsaat.com</td>
                            <td>(532) 123 45 67</td>
                            <td>Merkez Mah. No:1, İstanbul</td>
                            <td class="text-danger">₺2.500,00</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-warning me-1" title="Düzenle"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Defne Pazarlama A.Ş.</td>
                            <td>9876543210</td>
                            <td>iletisim@defnepazarlama.com</td>
                            <td>(541) 987 65 43</td>
                            <td>Gül Sok. No:5, Ankara</td>
                            <td class="text-success">₺0,00</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-warning me-1" title="Düzenle"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mehmet Yılmaz (Şahıs)</td>
                            <td>11223344556</td>
                            <td>mehmet.yilmaz@example.com</td>
                            <td>(505) 111 22 33</td>
                            <td>Çınar Cad. No:10, İzmir</td>
                            <td class="text-danger">₺750,00</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-warning me-1" title="Düzenle"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Sil"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
            <nav aria-label="Müşteri Sayfalama" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Önceki</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sonraki</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Yeni Çalışan Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Çalışan Adı / Unvanı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerTaxId" class="form-label">Vergi Numarası / T.C. Kimlik Numarası</label>
                            <input type="text" class="form-control" id="customerTaxId">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerEmail" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="customerEmail">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customerPhone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="customerPhone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Adres</label>
                            <textarea class="form-control" id="customerAddress" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customerCity" class="form-label">İl</label>
                                <input type="text" class="form-control" id="customerCity">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customerDistrict" class="form-label">İlçe</label>
                                <input type="text" class="form-control" id="customerDistrict">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerNote" class="form-label">Notlar</label>
                            <textarea class="form-control" id="customerNote" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary">Çalışanı Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
    
</body>
</html>


