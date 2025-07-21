<?php
// Sayfanın adını alıyoruz, örneğin "anasayfa.php"
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);

// Hangi menünün alt sayfalarının aktif olduğunu belirlemek için listeler oluşturalım
$satislarPages = ['teklifler.php', 'faturalar.php', 'musteriler.php', 'satislar_raporu.php'];
// Gelecek modüller için şimdiden hazırlık
$giderlerPages = ['gider_listesi.php', 'tedarikciler.php']; 
$nakitPages = [];
$stokPages = [];
$ayarlarPages = [];

?>
<aside class="sidebar">
    <div class="logo">PROJE ADI</div>
    <nav>
        <div class="menu-category">Ana Panel</div>
        <ul>
            <li class="<?php echo ($currentPage == 'anasayfa.php') ? 'active' : ''; ?>">
                <a href="anasayfa.php"><span class="icon material-symbols-outlined">dashboard</span> GÜNCEL DURUM</a>
            </li>
        </ul>
        <div class="menu-category">Finansal İşlemler</div>
        <ul>
            <li class="has-submenu <?php echo in_array($currentPage, $satislarPages) ? 'open' : ''; ?>">
                <a href="#"><span class="icon material-symbols-outlined">receipt_long</span> SATIŞLAR <span class="dropdown-icon material-symbols-outlined">chevron_right</span></a>
                <ul class="submenu">
                    <li class="<?php echo ($currentPage == 'teklifler.php') ? 'active' : ''; ?>"><a href="teklifler.php">Teklifler</a></li>
                    <li class="<?php echo ($currentPage == 'faturalar.php') ? 'active' : ''; ?>"><a href="faturalar.php">Faturalar</a></li>
                    <li class="<?php echo ($currentPage == 'musteriler.php') ? 'active' : ''; ?>"><a href="musteriler.php">Müşteriler</a></li>
                    <li class="<?php echo ($currentPage == 'satislar_raporu.php') ? 'active' : ''; ?>"><a href="satislar_raporu.php">Satışlar Raporu</a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage, $giderlerPages) ? 'open' : ''; ?>">
                <a href="#"><span class="icon material-symbols-outlined">shopping_cart</span> GİDERLER <span class="dropdown-icon material-symbols-outlined">chevron_right</span></a>
                <ul class="submenu">
                    <li><a href="#">Gider Listesi</a></li>
                    <li><a href="#">Tedarikçiler</a></li>
                    <li><a href="#">Çalışanlar</a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage, $nakitPages) ? 'open' : ''; ?>">
                <a href="#"><span class="icon material-symbols-outlined">account_balance</span> NAKİT <span class="dropdown-icon material-symbols-outlined">chevron_right</span></a>
                <ul class="submenu">
                    <li><a href="#">Kasa ve Bankalar</a></li>
                    <li><a href="#">Çekler</a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage, $stokPages) ? 'open' : ''; ?>">
                <a href="#"><span class="icon material-symbols-outlined">inventory_2</span> STOK <span class="dropdown-icon material-symbols-outlined">chevron_right</span></a>
                <ul class="submenu">
                    <li><a href="#">Hizmet ve Ürünler</a></li>
                    <li><a href="#">Depolar</a></li>
                </ul>
            </li>
        </ul>
        <div class="menu-category">Yönetim</div>
        <ul>
            <li class="has-submenu <?php echo in_array($currentPage, $ayarlarPages) ? 'open' : ''; ?>">
                <a href="#"><span class="icon material-symbols-outlined">settings</span> AYARLAR <span class="dropdown-icon material-symbols-outlined">chevron_right</span></a>
                <ul class="submenu">
                    <li><a href="#">Firma Bilgileri</a></li>
                    <li><a href="#">Kategori ve Etiketler</a></li>
                    <li><a href="#">Kullanıcılar</a></li>
                    <li><a href="#">Yazdırma Şablonları</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>
