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
        <a href="/MutabikV2/dashboard/dashboard2.php" class="menu-item">
            <i class="fas fa-chart-line"></i>
            <span>Güncel Durum</span>
        </a>

        <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="cari-submenu" onclick="toggleSubmenu(event)">
                <i class="fas fa-user-friends"></i>
                <span>CARİ</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>

            <div id="cari-submenu" class="submenu" style="display: none;">
                <a href="/MutabikV2/dashboard/cari/musteriler.php" class="submenu-item">
                    <i class="fas fa-users"></i>
                    <span>Müşteriler</span>
                </a>
                <a href="/MutabikV2/dashboard/cari/tedarikciler.php" class="submenu-item">
                    <i class="fas fa-truck"></i>
                    <span>Tedarikçiler</span>
                </a>
                <a href="/MutabikV2/dashboard/cari/calisanlar.php" class="submenu-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Çalışanlar</span>
                </a>
                
            </div>

            <div class="menu-section">
                <div class="menu-item menu-toggle" data-target="stock-submenu">
                    <i class="fas fa-boxes"></i>
                    <span>STOK</span>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="submenu" id="stock-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-tags"></i>
                        <span>Hizmet ve Ürünler</span>
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Depolar</span>
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Depolar Arası Transfer</span>
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck"></i>
                        <span>Giden İrsaliyeler</span>
                    </a>
                   
                    <a href="#" class="submenu-item">
                        <i class="fas fa-list"></i>
                        <span>Fiyat Listeleri</span>
                    </a>
                </div>
            </div>
             <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="expenses-submenu">
                <i class="fas fa-arrow-up"></i>
                <span>ALIŞLAR</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="expenses-submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-truck fa-flip-horizontal"></i>
                        <span>Gelen İrsaliyeler</span>
                    </a>

                    <a href="#" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Alışlar Raporu</span>
                    </a>
                
                
            </div>
        </div>
            <div class="menu-item menu-toggle" data-target="sales-submenu">
                <i class="fas fa-arrow-down"></i>
                <span>SATIŞLAR</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="sales-submenu">
                <a href="/MutabikV2/dashboard/satislar/teklifler/teklifler.php" class="submenu-item">
                    <i class="fas fa-file-alt"></i>
                    <span>Teklifler</span>
                </a>
                <a href="/MutabikV2/dashboard/satislar/faturalar/faturalar.php" class="submenu-item">
                    <i class="fas fa-file-invoice"></i>
                    <span>Faturalar</span>
                </a>

                <a href="/MutabikV2/dashboard/satislar/satislar_raporu/satis_rapor.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Satışlar Raporu</span>
                </a>
                <a href="/MutabikV2/dashboard/satislar/tahsilatlar_raporu/tahsilat_rapor.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Tahsilatlar Raporu</span>
                </a>
                <a href="/MutabikV2/dashboard/satislar/gelir_gider_raporu/gelir-gider.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Gelir Gider Raporu</span>
                </a>
            </div>
        </div>

        <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="expenses-submenu">
                <i class="fas fa-arrow-up"></i>
                <span>GİDERLER</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="expenses-submenu">
                <a href="/MutabikV2/dashboard/giderler/giderler_raporu.php" class="submenu-item">
                    <i class="fas fa-file-alt"></i>
                    <span>Gider Listesi</span>
                </a>

                <a href="/MutabikV2/dashboard/giderler/giderler_raporu.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Giderler Raporu</span>
                </a>
                <a href="/MutabikV2/dashboard/giderler/odemeler_raporu.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Ödemeler Raporu</span>
                </a>
                <a href="/MutabikV2/dashboard/giderler/kdv-raporu.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>KDV Raporu</span>
                </a>
            </div>
        </div>

        <div class="menu-section">
            <div class="menu-item menu-toggle" data-target="cash-submenu">
                <i class="fas fa-money-bill-wave"></i>
                <span>FİNANS</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu" id="cash-submenu">
                <a href="/MutabikV2/dashboard/nakit/kasa_ve_bankalar.php" class="submenu-item">
                    <i class="fas fa-university"></i>
                    <span>Kasa ve Bankalar</span>
                </a>
                <a href="/MutabikV2/dashboard/nakit/cekler.php" class="submenu-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Çekler</span>
                </a>
                <a href="/MutabikV2/dashboard/nakit/kasa-banka-raporu.php" class="submenu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Kasa / Banka Raporu</span>
                </a>
                <a href="/MutabikV2/dashboard/nakit/nakit_akis_raporu.php" class="submenu-item">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Nakit Akışı Raporu</span>
                </a>
            </div>
        </div>



        <div class="menu-separator"></div>

        <a href="#" class="menu-item" id="sidebar-collapse-btn">
            <i class="fas fa-chevron-left"></i>
            <span>Menüyü Sakla</span>
        </a>

        <a href="#" class="menu-item">
            <i class="fas fa-cog"></i>
            <span>Ayarlar</span>
        </a>
    </div>
</div>
