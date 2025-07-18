<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Örnek kullanıcı adı
$userName = 'Atia Kullanıcı';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MutabikV2 Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../public/assets/dashboard.css">
</head>
<body>
  <!-- Sabit Navbar Başlangıç -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" style="height:60px;z-index:1001;">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <span class="fw-bold ms-2" style="color:#764ba2;font-size:1.2rem;">MutabikV2</span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="fw-bold" style="color:#764ba2; font-size:1rem; margin-right:10px;"><?php echo $userName; ?></span>
        <div class="dropdown">
          <button class="btn btn-link dropdown-toggle d-flex align-items-center p-1" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar" class="rounded-circle" style="height:32px;width:32px;object-fit:cover;">
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#" id="profileBtn">Profil</a></li>
            <li><a class="dropdown-item" href="#" id="settingsBtn">Ayarlar</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/public/authentication/login.php" id="logoutBtn">Çıkış Yap</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div style="height:60px;"></div>
  <!-- Sabit Navbar Bitiş -->
  <div class="dashboard-wrapper">
    <div class="sidebar">
      <div class="user-info">👤 <?php echo $userName; ?></div>
      <button class="menu-button active" data-tab="overview">📊 Genel Bakış</button>
      <button class="menu-button" data-tab="cari">💰 Bakiye</button>
      <button class="menu-button" data-tab="alacaklar">🧾 Alacaklar</button>
      <button class="menu-button" data-tab="borclar">💳 Borçlar</button>
      <button class="menu-button" data-tab="tahsilat">📅 Tahsilat</button>
      <button class="menu-button" data-tab="stok">📦 Stok</button>
    </div>
    <div class="main-content">
      <div class="container py-5">
        <div class="text-center mb-5">
          <h1 class="fw-bold" style="color:#764ba2">MutabikV2 Dashboard</h1>
          <p class="lead" style="color:#667eea">Hızlı Finansal Yönetim Paneli</p>
        </div>
        <!-- GENEL BAKIŞ SEKME İÇERİĞİ -->
        <div id="overview" class="tab-section active">
          <div class="overview-row">
            <div class="overview-col">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Bakiye (Cari)</h5>
                <div class="summary-box summary-cari mb-3">
                  <i class="fas fa-users"></i>
                  <div>
                    <div class="fs-5">Toplam Cari</div>
                    <div class="fs-3 fw-bold">42</div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-hover align-middle mb-0">
                    <thead><tr><th>#</th><th>Unvan</th><th>Durum</th></tr></thead>
                    <tbody>
                      <tr><td>1</td><td>Atia Yazılım</td><td><span class="badge bg-success">Aktif</span></td></tr>
                      <tr><td>2</td><td>Beta Ltd.</td><td><span class="badge bg-success">Aktif</span></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="overview-col">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Alacaklar</h5>
                <div class="summary-box summary-cari mb-3">
                  <i class="fas fa-arrow-down"></i>
                  <div>
                    <div class="fs-5">Toplam Alacak</div>
                    <div class="fs-3 fw-bold">₺ 18.500</div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-hover align-middle mb-0">
                    <thead><tr><th>#</th><th>Cari</th><th>Tutar</th></tr></thead>
                    <tbody>
                      <tr><td>1</td><td>Atia Yazılım</td><td>₺ 5.000</td></tr>
                      <tr><td>2</td><td>Beta Ltd.</td><td>₺ 8.500</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="overview-col">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Borçlar</h5>
                <div class="summary-box summary-stok mb-3">
                  <i class="fas fa-arrow-up"></i>
                  <div>
                    <div class="fs-5">Toplam Borç</div>
                    <div class="fs-3 fw-bold">₺ 12.200</div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-hover align-middle mb-0">
                    <thead><tr><th>#</th><th>Tedarikçi</th><th>Tutar</th></tr></thead>
                    <tbody>
                      <tr><td>1</td><td>Ofis Market</td><td>₺ 2.200</td></tr>
                      <tr><td>2</td><td>Tekno Bilgisayar</td><td>₺ 10.000</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="overview-row mt-4">
            <div class="overview-col">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Tahsilat (Faturalar)</h5>
                <div class="summary-box summary-fatura mb-3">
                  <i class="fas fa-file-invoice"></i>
                  <div>
                    <div class="fs-5">Toplam Fatura</div>
                    <div class="fs-3 fw-bold">68</div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-hover align-middle mb-0">
                    <thead><tr><th>#</th><th>Fatura No</th><th>Tutar</th></tr></thead>
                    <tbody>
                      <tr><td>1</td><td>FTR-2023-001</td><td>₺ 12.000</td></tr>
                      <tr><td>2</td><td>FTR-2023-002</td><td>₺ 8.500</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="overview-col">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Stok</h5>
                <div class="summary-box summary-stok mb-3">
                  <i class="fas fa-boxes"></i>
                  <div>
                    <div class="fs-5">Toplam Stok</div>
                    <div class="fs-3 fw-bold">120</div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-hover align-middle mb-0">
                    <thead><tr><th>#</th><th>Stok Adı</th><th>Miktar</th></tr></thead>
                    <tbody>
                      <tr><td>1</td><td>Ofis Sandalyesi</td><td>15</td></tr>
                      <tr><td>2</td><td>Laptop</td><td>8</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- CARİ SEKME İÇERİĞİ -->
        <div id="cari" class="tab-section">
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="summary-box summary-cari d-flex flex-column align-items-center">
                <i class="fas fa-users"></i>
                <div>
                  <div class="fs-5">Toplam Cari</div>
                  <div class="fs-3 fw-bold">42</div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="summary-box summary-stok d-flex flex-column align-items-center">
                <i class="fas fa-user-plus"></i>
                <div>
                  <div class="fs-5">Yeni Cari (Bu Ay)</div>
                  <div class="fs-3 fw-bold">5</div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="summary-box summary-fatura d-flex flex-column align-items-center">
                <i class="fas fa-user-check"></i>
                <div>
                  <div class="fs-5">Aktif Cari</div>
                  <div class="fs-3 fw-bold">38</div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="summary-box summary-fatura d-flex flex-column align-items-center">
                <i class="fas fa-user-times"></i>
                <div>
                  <div class="fs-5">Pasif Cari</div>
                  <div class="fs-3 fw-bold">4</div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-7 mb-4">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Cari Listesi</h5>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Unvan</th>
                        <th>Telefon</th>
                        <th>E-posta</th>
                        <th>Durum</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td>1</td><td>Atia Yazılım</td><td>0555 123 45 67</td><td>info@atia.com</td><td><span class="badge bg-success">Aktif</span></td></tr>
                      <tr><td>2</td><td>Beta Ltd.</td><td>0532 987 65 43</td><td>beta@ltd.com</td><td><span class="badge bg-success">Aktif</span></td></tr>
                      <tr><td>3</td><td>Gamma A.Ş.</td><td>0544 111 22 33</td><td>gamma@as.com</td><td><span class="badge bg-danger">Pasif</span></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-5 mb-4">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Cari Dağılımı</h5>
                <div class="chart-container">
                  <canvas id="cariChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ALACAKLAR SEKME İÇERİĞİ -->
        <div id="alacaklar" class="tab-section">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="summary-box summary-cari">
                <i class="fas fa-arrow-down"></i>
                <div>
                  <div class="fs-5">Toplam Alacak</div>
                  <div class="fs-3 fw-bold">₺ 18.500</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="summary-box summary-fatura">
                <i class="fas fa-clock"></i>
                <div>
                  <div class="fs-5">Vadesi Geçen</div>
                  <div class="fs-3 fw-bold">₺ 2.300</div>
                </div>
              </div>
            </div>
          </div>
          <div class="dashboard-card p-4">
            <h5 class="mb-3">Alacaklar Listesi</h5>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Cari</th>
                    <th>Tutar</th>
                    <th>Vade</th>
                    <th>Durum</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>1</td><td>Atia Yazılım</td><td>₺ 5.000</td><td>01.07.2023</td><td><span class="badge bg-warning">Bekliyor</span></td></tr>
                  <tr><td>2</td><td>Beta Ltd.</td><td>₺ 8.500</td><td>10.07.2023</td><td><span class="badge bg-success">Tahsil Edildi</span></td></tr>
                  <tr><td>3</td><td>Gamma A.Ş.</td><td>₺ 5.000</td><td>15.07.2023</td><td><span class="badge bg-danger">Gecikmiş</span></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- BORÇLAR SEKME İÇERİĞİ -->
        <div id="borclar" class="tab-section">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="summary-box summary-stok">
                <i class="fas fa-arrow-up"></i>
                <div>
                  <div class="fs-5">Toplam Borç</div>
                  <div class="fs-3 fw-bold">₺ 12.200</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="summary-box summary-fatura">
                <i class="fas fa-clock"></i>
                <div>
                  <div class="fs-5">Vadesi Geçen</div>
                  <div class="fs-3 fw-bold">₺ 1.500</div>
                </div>
              </div>
            </div>
          </div>
          <div class="dashboard-card p-4">
            <h5 class="mb-3">Borçlar Listesi</h5>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tedarikçi</th>
                    <th>Tutar</th>
                    <th>Vade</th>
                    <th>Durum</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>1</td><td>Ofis Market</td><td>₺ 2.200</td><td>05.07.2023</td><td><span class="badge bg-warning">Bekliyor</span></td></tr>
                  <tr><td>2</td><td>Tekno Bilgisayar</td><td>₺ 10.000</td><td>20.07.2023</td><td><span class="badge bg-danger">Gecikmiş</span></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- TAHSİLAT SEKME İÇERİĞİ -->
        <div id="tahsilat" class="tab-section">
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="summary-box summary-fatura">
                <i class="fas fa-file-invoice"></i>
                <div>
                  <div class="fs-5">Toplam Fatura</div>
                  <div class="fs-3 fw-bold">68</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="summary-box summary-stok">
                <i class="fas fa-lira-sign"></i>
                <div>
                  <div class="fs-5">Toplam Tutar</div>
                  <div class="fs-3 fw-bold">₺ 245.000</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="summary-box summary-cari">
                <i class="fas fa-calendar-alt"></i>
                <div>
                  <div class="fs-5">Bu Ayki Faturalar</div>
                  <div class="fs-3 fw-bold">12</div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-7 mb-4">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Fatura Listesi</h5>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Fatura No</th>
                        <th>Cari</th>
                        <th>Tutar</th>
                        <th>Tarih</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td>1</td><td>FTR-2023-001</td><td>Atia Yazılım</td><td>₺ 12.000</td><td>01.06.2023</td></tr>
                      <tr><td>2</td><td>FTR-2023-002</td><td>Beta Ltd.</td><td>₺ 8.500</td><td>05.06.2023</td></tr>
                      <tr><td>3</td><td>FTR-2023-003</td><td>Gamma A.Ş.</td><td>₺ 4.200</td><td>10.06.2023</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-5 mb-4">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Fatura Dağılımı</h5>
                <div class="chart-container">
                  <canvas id="faturaChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- STOK SEKME İÇERİĞİ -->
        <div id="stok" class="tab-section">
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="summary-box summary-stok">
                <i class="fas fa-boxes"></i>
                <div>
                  <div class="fs-5">Toplam Stok</div>
                  <div class="fs-3 fw-bold">120</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="summary-box summary-cari">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                  <div class="fs-5">Kritik Stok</div>
                  <div class="fs-3 fw-bold">7</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="summary-box summary-fatura">
                <i class="fas fa-cubes"></i>
                <div>
                  <div class="fs-5">Toplam Miktar</div>
                  <div class="fs-3 fw-bold">2.350</div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-7 mb-4">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Stok Listesi</h5>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Stok Kodu</th>
                        <th>Stok Adı</th>
                        <th>Birim</th>
                        <th>Miktar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td>1</td><td>STK-001</td><td>Ofis Sandalyesi</td><td>Adet</td><td>15</td></tr>
                      <tr><td>2</td><td>STK-002</td><td>Laptop</td><td>Adet</td><td>8</td></tr>
                      <tr><td>3</td><td>STK-003</td><td>Yazıcı Kartuşu</td><td>Paket</td><td>3</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-5 mb-4">
              <div class="dashboard-card p-4">
                <h5 class="mb-3">Stok Durumu</h5>
                <div class="chart-container">
                  <canvas id="stokChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Profil Modalı (düzenlenebilir) -->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="profileForm">
          <div class="modal-header">
            <h5 class="modal-title" id="profileModalLabel">Profil Bilgileri</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-3">
              <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mb-2" style="width:80px;height:80px;object-fit:cover;">
            </div>
            <div class="row g-2">
              <div class="col-md-6"><input type="text" class="form-control" id="profileName" placeholder="Ad" value="Atia"></div>
              <div class="col-md-6"><input type="text" class="form-control" id="profileSurname" placeholder="Soyad" value="Kullanıcı"></div>
            </div>
            <div class="row g-2 mt-2">
              <div class="col-md-6"><input type="email" class="form-control" id="profileEmail" placeholder="E-posta" value="atia@example.com"></div>
              <div class="col-md-6"><input type="text" class="form-control" id="profilePhone" placeholder="Telefon" value="0555 123 45 67"></div>
            </div>
            <div class="mt-2"><input type="text" class="form-control" id="profileUsername" placeholder="Kullanıcı Adı" value="atiauser"></div>
            <div class="mt-2"><input type="text" class="form-control" id="profileAddress" placeholder="Adres" value="İstanbul, Türkiye"></div>
            <div class="mt-2"><input type="date" class="form-control" id="profileBirth" value="1990-01-01"></div>
            <div class="row g-2 mt-2">
              <div class="col-md-6"><input type="text" class="form-control" id="profileLinkedin" placeholder="LinkedIn" value="linkedin.com/in/atia"></div>
              <div class="col-md-6"><input type="text" class="form-control" id="profileInstagram" placeholder="Instagram" value="instagram.com/atia"></div>
            </div>
            <div class="mt-2"><input type="password" class="form-control" id="profilePassword" placeholder="Yeni Şifre"></div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary w-100">Kaydet</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Ayarlar Modalı (gelişmiş) -->
  <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="settingsForm">
          <div class="modal-header">
            <h5 class="modal-title" id="settingsModalLabel">Ayarlar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Dil</label>
              <select class="form-select" id="langSelect">
                <option value="tr">Türkçe</option>
                <option value="en">English</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Para Birimi</label>
              <select class="form-select" id="currencySelect">
                <option value="TRY">₺ Türk Lirası</option>
                <option value="USD">$ Amerikan Doları</option>
                <option value="EUR">€ Euro</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tema</label>
              <select class="form-select" id="themeSelect">
                <option value="light">Açık</option>
                <option value="dark">Koyu</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Bildirimler</label>
              <select class="form-select" id="notifSelect">
                <option value="all">Tümü</option>
                <option value="important">Sadece Önemli</option>
                <option value="none">Kapalı</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tablo Satır Sayısı</label>
              <input type="number" class="form-control" id="rowCount" min="5" max="100" value="10">
            </div>
            <div class="mb-3">
              <label class="form-label">Varsayılan Sekme</label>
              <select class="form-select" id="defaultTab">
                <option value="overview">Genel Bakış</option>
                <option value="cari">Bakiye</option>
                <option value="alacaklar">Alacaklar</option>
                <option value="borclar">Borçlar</option>
                <option value="tahsilat">Tahsilat</option>
                <option value="stok">Stok</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Güvenlik</label>
              <select class="form-select" id="securityLevel">
                <option value="low">Düşük</option>
                <option value="medium">Orta</option>
                <option value="high">Yüksek</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Şifre Değiştir</label>
              <input type="password" class="form-control" id="settingsPassword" placeholder="Yeni Şifre">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary w-100">Kaydet</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="./dashboard.js"></script>
</body>
</html> 