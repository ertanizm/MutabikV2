<button class="sidebar-toggle">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-calculator"></i>
            Mutabık
        </div>
    </div>

    <div class="sidebar-menu">
        <a href="/MutabikV2/public/dashboard/dashboard2.php" class="menu-item guncel-durum" title="Güncel Durum">
            <i class="fas fa-chart-line"></i>
            <span>Güncel Durum</span>
        </a>

        <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="cari-submenu" title="CARİ">
                <i class="fas fa-user-friends"></i>
                <span>CARİ</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>

            <div id="cari-submenu" class="submenu" style="display: none;">
                <a href="/MutabikV2/public/dashboard/cari/musteriler.php" class="submenu-item" title="Müşteriler">
                    <i class="fas fa-users"></i>
                    <span>Müşteriler</span>
                </a>
                <a href="/MutabikV2/public/dashboard/cari/tedarikciler.php" class="submenu-item" title="Tedarikçiler">
                    <i class="fas fa-truck"></i>
                    <span>Tedarikçiler</span>
                </a>
                <a href="/MutabikV2/public/dashboard/cari/calisanlar.php" class="submenu-item" title="Çalışanlar">
                    <i class="fas fa-user-tie"></i>
                    <span>Çalışanlar</span>
                </a>

            </div>

            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="stock-submenu" title="STOK">
                    <i class="fas fa-boxes"></i>
                    <span>STOK</span>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="stock-submenu">
                    <a href="/MutabikV2/public/dashboard/stok/hizmet_urunler.php" class="submenu-item" title="Hizmet ve Ürünler">
                        <i class="fas fa-tags"></i>
                        <span>Hizmet ve Ürünler</span>
                    </a>
                    <a href="/MutabikV2/public/dashboard/stok/depolar.php" class="submenu-item" title="Depolar">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Depolar</span>
                    </a>
                    <a href="/MutabikV2/public/dashboard/stok/depolar_arasi_transfer.php" class="submenu-item" title="Depolar Arası Transfer">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Depolar Arası Transfer</span>
                    </a>
                    <a href="/MutabikV2/public/dashboard/stok/giden_irsaliyeler.php" class="submenu-item" title="Giden İrsaliyeler">
                        <i class="fas fa-truck"></i>
                        <span>Giden İrsaliyeler</span>
                    </a>
                   
                    <a href="/MutabikV2/public/dashboard/stok/fiyat_listeleri.php" class="submenu-item" title="Fiyat Listeleri">
                        <i class="fas fa-list"></i>
                        <span>Fiyat Listeleri</span>
                    </a>
                </div>
            </div>
             <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="expenses-submenu" title="ALIŞLAR">
                <i class="fas fa-arrow-up"></i>
                <span>ALIŞLAR</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="expenses-submenu">
                    <a href="/MutabikV2/public/dashboard/alislar/gelen_irsaliyeler.php" class="submenu-item" title="Gelen İrsaliyeler">
                        <i class="fas fa-truck fa-flip-horizontal"></i>
                        <span>Gelen İrsaliyeler</span>
                    </a>

                    <a href="/MutabikV2/public/dashboard/alislar/alislar_raporu.php" class="submenu-item" title="Alışlar Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Alışlar Raporu</span>
                    </a>


                </div>
            </div>
        </div>
            <div class="menu-item menu-toggle" data-target="sales-submenu" title="SATIŞLAR">
                <i class="fas fa-arrow-down"></i>
                <span>SATIŞLAR</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="sales-submenu">
                <a href="/MutabikV2/public/dashboard/satislar/teklifler/teklifler.php" class="submenu-item" title="Teklifler">
                    <i class="fas fa-file-alt"></i>
                    <span>Teklifler</span>
                </a>
                <a href="/MutabikV2/public/dashboard/satislar/faturalar/faturalar.php" class="submenu-item" title="Faturalar">
                    <i class="fas fa-file-invoice"></i>
                    <span>Faturalar</span>
                </a>

                <a href="/MutabikV2/public/dashboard/satislar/satislar_raporu/satis_rapor.php" class="submenu-item" title="Satışlar Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Satışlar Raporu</span>
                </a>
                <a href="/MutabikV2/public/dashboard/satislar/tahsilatlar_raporu/tahsilat_rapor.php" class="submenu-item" title="Tahsilatlar Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Tahsilatlar Raporu</span>
                </a>
                <a href="/MutabikV2/public/dashboard/satislar/gelir_gider_raporu/gelir-gider.php" class="submenu-item" title="Gelir Gider Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Gelir Gider Raporu</span>
                </a>
            </div>
            
            <div class="menu-item menu-toggle" data-target="expenses2-submenu" title="GİDERLER">
                <i class="fas fa-arrow-up"></i>
                <span>GİDERLER</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="expenses2-submenu">
                <a href="/MutabikV2/public/dashboard/giderler/gider_listesi.php" class="submenu-item" title="Gider Listesi">
                    <i class="fas fa-file-alt"></i>
                    <span>Gider Listesi</span>
                </a>

                <a href="/MutabikV2/public/dashboard/giderler/giderler_raporu.php" class="submenu-item" title="Giderler Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Giderler Raporu</span>
                </a>
                <a href="/MutabikV2/public/dashboard/giderler/odemeler_raporu.php" class="submenu-item" title="Ödemeler Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Ödemeler Raporu</span>
                </a>
                <a href="/MutabikV2/public/dashboard/giderler/kdv-raporu.php" class="submenu-item" title="KDV Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>KDV Raporu</span>
                </a>
            </div>
       

        
            <div class="menu-item menu-toggle" data-target="cash-submenu" title="FİNANS">
                <i class="fas fa-money-bill-wave"></i>
                <span>FİNANS</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="cash-submenu">
                <a href="/MutabikV2/public/dashboard/nakit/kasa_ve_bankalar.php" class="submenu-item" title="Kasa ve Bankalar">
                    <i class="fas fa-university"></i>
                    <span>Kasa ve Bankalar</span>
                </a>
                <a href="/MutabikV2/public/dashboard/nakit/cekler.php" class="submenu-item" title="Çekler">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Çekler</span>
                </a>
                <a href="/MutabikV2/public/dashboard/nakit/kasa-banka-raporu.php" class="submenu-item" title="Kasa / Banka Raporu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Kasa / Banka Raporu</span>
                </a>
                <a href="/MutabikV2/public/dashboard/nakit/nakit_akis_raporu.php" class="submenu-item" title="Nakit Akışı Raporu">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Nakit Akışı Raporu</span>
                </a>
            </div>
        </div>

        
        

        <div class="menu-separator"></div>
        <a href="#" class="menu-item" id="sidebar-collapse-btn" title="Menüyü Sakla">
            <i class="fas fa-chevron-left"></i>
            <span>Menüyü Sakla</span>
        </a>
        <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="settings-submenu" title="AYARLAR">
                <i class="fas fa-cog"></i>
                <span>AYARLAR</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="settings-submenu">
                <a href="/MutabikV2/public/dashboard/ayarlar/firma-bilgileri.php" class="submenu-item" title="Firma Bilgileri">Firma Bilgileri</a>
                <a href="/MutabikV2/public/dashboard/ayarlar/kategori-etiket.php" class="submenu-item" title="Kategori ve Etiketler">Kategori ve Etiketler</a>
                <a href="/MutabikV2/public/dashboard/ayarlar/kullanicilar.php" class="submenu-item" title="Kullanıcılar">Kullanıcılar</a>
                <a href="/MutabikV2/public/dashboard/ayarlar/yazdirma-sablonlari.php" class="submenu-item" title="Yazdırma Şablonları">Yazdırma Şablonları</a>
            </div>
        </div>
    </div>
</div>
