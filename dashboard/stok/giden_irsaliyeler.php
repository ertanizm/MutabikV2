<?php
// Bu alana PHP backend kodları veya veritabanı bağlantıları gelebilir
// Örneğin, irsaliye verilerini çekme, filtreleme vb.
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giden İrsaliyeler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="../dashboard.css" rel="stylesheet">
    <link href="../../assets/stok/giden_irsaliyeler.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../sidebar.php' ?>

    <div class="main-content p-4">
        <!-- Üst Header -->
        <div class="top-header">
            <div class="header-left">
                <h1>Giden İrsaliyeler</h1>
            </div>
            <div class="header-right">
                <?php include __DIR__ . '/../includes/profile_dropdown.php'; ?>
            </div>
        </div>
        <div class="header-actions">
            <div class="search-filter-group">
                <button class="search-filter-button">Filtrele <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                        <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>
                <div class="search-filter-input-group">
                    <input type="text" id="filter" placeholder="Ara...">
                    <button><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.088.12l3.493 3.493a1 1 0 0 0 1.414-1.414l-3.493-3.493a1 1 0 0 0-.12-.088zM6.5 11A4.5 4.5 0 1 1 6.5 2a4.5 4.5 0 0 1 0 9" />
                        </svg></button>
                </div>
            </div>
            <div class="action-buttons">
                <button class="btn-secondary">TOPLU SATIŞ FATURASI OLUŞTUR</button>
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#fullModal">YENİ GİDEN İRSALİYE</button>
            </div>
        </div>

        <table class="delivery-table">
            <thead>
                <tr>
                    <th>İRSALİYE İSMİ</th>
                    <th>İRSALİYE NO</th>
                    <th>İRSALİYE TİPİ</th>
                    <th>DURUMU</th>
                    <th>DÜZENLEME TARİHİ
                        <span class="date-header-icons">
                            <i class="fa-solid fa-calendar-alt date-icon-trigger" id="dateCalendarIcon"></i>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up sort-icon" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                        </span>
                        <input type="text" id="hiddenDatepicker" style="position: absolute; left: -9999px; top: -9999px; opacity: 0;">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <p>Henüz hiç irsaliye oluşturmadınız.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer-pagination">
            <div class="dropdown" id="recordsDropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Tüm Kayıtlar <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                        <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592c.859 0 1.319-1.012.753-1.659L8.753 4.86a1 1 0 0 0-1.506 0z" />
                    </svg>
                </button>
                <div class="dropdown-content">
                </div>
            </div>
            <span>- 0 Kayıt</span>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="fullModal" tabindex="-1" aria-labelledby="fullModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg w-50 modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="fullModalLabel"><i class="fa fa-truck me-2"></i>İrsaliye Formu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Uyarı -->
                        <div class="alert alert-warning d-flex align-items-start d-none" role="alert">
                            <i class="fa fa-exclamation-circle me-2 mt-1"></i>
                            <div>
                                <strong>Dikkat!</strong><br>
                                <small>İşlem gerçekleştirilemedi. Lütfen aşağıdaki alanları kontrol ediniz.</small>
                            </div>
                        </div>

                        <form>
                            <!-- İrsaliye İsmi -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fa fa-truck me-1"></i>İrsaliye İsmi</label>
                                <input type="text" class="form-control" placeholder="İrsaliye başlığı giriniz">
                            </div>

                            <!-- Müşteri -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fa fa-building me-1"></i>Müşteri</label>
                                <input type="text" class="form-control" placeholder="Müşteri adı giriniz">
                                <div class="form-text">
                                    <i class="fa fa-info-circle"></i> Kayıtlı müşteri seçebilir veya yeni müşteri girebilirsiniz.
                                </div>
                            </div>

                            <!-- Müşteri Bilgileri -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fa fa-user-circle me-1"></i>Müşteri Bilgileri</label>
                                <input type="text" class="form-control" value="–" readonly>
                            </div>

                            <!-- Sevkiyat Adresi -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fa fa-map-marker me-1"></i>Sevkiyat Adresi</label>
                                <textarea class="form-control mb-2" rows="2" placeholder="Adres bilgisi"></textarea>

                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Posta Kodu">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="İlçe">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="İl">
                                    </div>
                                </div>

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="sameAddressCheck">
                                    <label class="form-check-label" for="sameAddressCheck">
                                        Sevkiyat adresi alıcı adresi ile aynı
                                    </label>
                                </div>
                            </div>

                            <!-- Çıkış Adresi -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fa fa-map-marker-alt me-1"></i>Çıkış Adresi</label>
                                <textarea class="form-control mb-2" rows="2"></textarea>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Posta Kodu">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="İlçe">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="İl">
                                    </div>
                                </div>
                            </div>

                            <!-- Sevkiyat Yöntemi -->
                            <div class="mb-3">
                                <label class="form-label d-block">Sevkiyat Yöntemi</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sevkiyatYontemi" id="tasiyici" value="tasiyici">
                                    <label class="form-check-label" for="tasiyici">Taşıyıcı</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sevkiyatYontemi" id="kargo" value="kargo">
                                    <label class="form-check-label" for="kargo">Kargo / Lojistik</label>
                                </div>
                            </div>

                            <div id="tasiyiciBilgisi" class="mb-3 d-none">
                                <label class="form-label">Araç Plakası</label>
                                <input type="text" class="form-control mb-2" placeholder="Araç plakası">
                                <label class="form-label">Şoför Bilgisi</label>
                                <input type="text" class="form-control" placeholder="Şoför adı">
                            </div>

                            <div id="kargoBilgisi" class="mb-3 d-none">
                                <label class="form-label">Kargo Firması</label>
                                <input type="text" class="form-control mb-2" placeholder="Firma adı">
                                <label class="form-label">VKN</label>
                                <input type="text" class="form-control" placeholder="Vergi Kimlik Numarası">
                            </div>


                            <!-- Tarihler -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fa fa-calendar me-1"></i>Düzenleme Tarihi</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fa fa-calendar-check me-1"></i>Fiili Sevk Tarihi</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>

                            <!-- Ürün Tablosu -->
                            <div class="mt-4">
                                <label class="form-label"><i class="fa fa-box me-1"></i>Ürünler</label>
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ürün</th>
                                            <th>Miktar</th>
                                            <th>Birim</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="urunTablo">

                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="yeniSatirEkle()"><i class="fa fa-plus"></i> Yeni Ürün Ekle</button>
                            </div>

                            <!-- İrsaliye Tipi -->
                            <div class="mt-4">
                                <label class="form-label"><i class="fa fa-file-alt me-1"></i>İrsaliye Tipi</label>
                                <select class="form-select">
                                    <option>Satış İrsaliyesi</option>
                                    <option>Alış İrsaliyesi</option>
                                    <option>İade İrsaliyesi</option>
                                    <option>Transfer İrsaliyesi</option>
                                    <option>Numune İrsaliyesi</option>
                                    <option>Diğer</option>
                                </select>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                                <button type="submit" class="btn btn-primary">Kaydet</button>
                            </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script2.js"></script>
    <script>
        function yeniSatirEkle() {
            const tbody = document.getElementById('urunTablo');
            const satir = `
            <tr>
                <td><input type="text" class="form-control" placeholder="Ürün adı"></td>
                <td><input type="number" class="form-control" placeholder="Miktar"></td>
                <td>
                <select class="form-select">
                    <option>Adet</option>
                    <option>Kutu</option>
                    <option>Kg</option>
                </select>
                </td>
                <td><button type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button></td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', satir);

            const sonButon = tbody.querySelector('tr:last-child button.btn-danger');
            sonButon.addEventListener('click', function() {
                this.closest('tr').remove();
            });

        }

        document.querySelectorAll('input[name="sevkiyatYontemi"]').forEach(function(el) {
            el.addEventListener('change', function() {
                document.getElementById('tasiyiciBilgisi').classList.add('d-none');
                document.getElementById('kargoBilgisi').classList.add('d-none');
                if (this.value === 'tasiyici') {
                    document.getElementById('tasiyiciBilgisi').classList.remove('d-none');
                } else if (this.value === 'kargo') {
                    document.getElementById('kargoBilgisi').classList.remove('d-none');
                }
            });
        });
    </script>
    <script>
        $(function() {
            // "DÜZENLEME TARİHİ" başlığındaki takvim ikonuna tıklandığında datepicker'ı aç
            $('#dateCalendarIcon').on('click', function() {
                $('#hiddenDatepicker').datepicker('show');
            });

            // Gizli datepicker'ı başlat ve konumlandırma ayarlarını yap
            $('#hiddenDatepicker').datepicker({
                dateFormat: "dd.mm.yy", // Tarih formatı
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true, // Diğer aylardan günleri göster
                selectOtherMonths: true, // Diğer aylardan günleri seçilebilir yap
                // Datepicker gösterilmeden hemen önce tetiklenir
                beforeShow: function(input, inst) {
                    var $th = $('#dateCalendarIcon').closest('th'); // Tarih ikonunun bulunduğu th elementini bul
                    var thOffset = $th.offset();
                    var thHeight = $th.outerHeight();
                    var dpWidth = $(inst.dpDiv).outerWidth();

                    // Datepicker'ı th elementinin altına, ikonun ortasına hizala
                    var iconOffset = $('#dateCalendarIcon').offset();
                    var iconWidth = $('#dateCalendarIcon').outerWidth();
                    var leftPosition = iconOffset.left + (iconWidth / 2) - (dpWidth / 2);

                    // Ekranın dışına taşmayı engelle
                    if (leftPosition < 0) {
                        leftPosition = 0;
                    } else if (leftPosition + dpWidth > $(window).width()) {
                        leftPosition = $(window).width() - dpWidth;
                    }

                    setTimeout(function() {
                        $(inst.dpDiv).css({
                            top: thOffset.top + thHeight + 5, // 5px boşluk bırak
                            left: leftPosition
                        });
                    }, 1); // Küçük bir gecikme, DOM renderını beklemek için
                },
                onSelect: function(dateText, inst) {
                    // Kullanıcı tarih seçtiğinde yapılacak işlemler
                    console.log("Seçilen Tarih: " + dateText);
                    // Burada AJAX çağrısı yaparak tablo verilerini güncelleyebilirsiniz
                    // updateTableByDate(dateText); // Eğer bir fonksiyonunuz varsa
                },
                onClose: function(dateText, inst) {
                    // Datepicker kapandığında yapılacak işlemler (isteğe bağlı)
                }
            });
        });

        // Dropdown menüyü açıp kapatma fonksiyonu (Tüm Kayıtlar)
        function toggleDropdown() {
            document.getElementById("recordsDropdown").classList.toggle("show");
        }

        // Dropdown dışına tıklanınca kapatma
        window.onclick = function(event) {
            // "Tüm Kayıtlar" dropdown'ı için
            if (!event.target.matches('.dropbtn') && !$(event.target).closest('.dropdown-content').length && !$(event.target).closest('.ui-datepicker').length) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
            // Bu senaryoda filtreleme artık bir dropdown değil, bu yüzden toggleFilterDropdown veya ilgili JS'ye ihtiyaç kalmadı.
            // Eğer "Filtrele" butonu tıklanınca farklı bir filtre menüsü açılacaksa, o kısım yeniden tasarlanabilir.
        }
    </script>

</body>

</html>