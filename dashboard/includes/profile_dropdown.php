<!-- PROFIL DROPDOWN & WIDGET BAŞLANGIÇ -->
<div class="user-info" id="userInfo" style="cursor:pointer; position:relative;">
    <div class="user-avatar">
        MD
    </div>
    <div class="user-details">
        <h6><?php echo htmlspecialchars($userName ?? 'Miraç Deprem'); ?></h6>
        <small><?php echo htmlspecialchars($companyName ?? 'Atia Yazılım'); ?></small>
    </div>
    <i class="fas fa-chevron-down" style="color: var(--text-secondary);"></i>
    <div id="profileDropdown" class="profile-dropdown" style="display:none; right:0; left:auto;">
        <ul>
            <li><button type="button" id="openProfileWidget">Profilim</button></li>
            <li><a href="/MutabikV2/authentication/login.php" id="logoutBtn">Çıkış Yap</a></li>
        </ul>
    </div>
    <div id="profileWidget" class="profile-widget" style="display:none; right:0; left:auto;">
        <button class="close-btn" onclick="document.getElementById('profileWidget').style.display='none'">&times;</button>
        <div class="profile-title">Profilim</div>
        <div class="profile-row"><i class="fas fa-user"></i><span class="profile-label">Adı Soyadı:</span><span class="profile-value">Miraç Deprem</span></div>
        <div class="profile-row"><i class="fas fa-envelope"></i><span class="profile-label">E-posta Adresi:</span><span class="profile-value">miracdeprem0@gmail.com</span></div>
        <div class="profile-row"><i class="fas fa-phone"></i><span class="profile-label">Telefon:</span><span class="profile-value">-</span></div>
        <div class="profile-row"><i class="fas fa-briefcase"></i><span class="profile-label">Unvan:</span><span class="profile-value">Atia Yazılım</span></div>
    </div>
</div>
<!-- PROFIL DROPDOWN & WIDGET BİTİŞ --> 