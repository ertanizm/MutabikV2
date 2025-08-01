<?php
require_once __DIR__ . '/../../config/config.php'; // config.php yolu projenin yapısına göre ayarla

$userName = 'Misafir';
$companyName = 'Firma Adı';
$userEmail = '';

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];

    $stmt = $masterPdo->prepare("
        SELECT u.email, u.role, c.name AS company_name
        FROM users u
        JOIN companies c ON u.company_id = c.id
        WHERE u.email = ?
    ");
    $stmt->execute([$userEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userName = strtok($user['email'], '@'); // Buraya gerçek kullanıcı adını çekmek için farklı bir kolon ekleyebilirsin
        $companyName = $user['company_name'];
    }
}
?>
<!-- BURADAN İTİBAREN HTML -->
<div class="user-info" id="userInfo" style="cursor:pointer; position:relative;">
    <div class="user-avatar">
        MD
    </div>
    <div class="user-details">
        <h6><?php echo htmlspecialchars($userName); ?></h6>
        <small><?php echo htmlspecialchars($companyName); ?></small>
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
        <div class="profile-row"><i class="fas fa-user"></i><span class="profile-label">Kullanıcı:</span><span class="profile-value"><?php echo htmlspecialchars($userName); ?></span></div>
        <div class="profile-row"><i class="fas fa-envelope"></i><span class="profile-label">E-posta:</span><span class="profile-value"><?php echo htmlspecialchars($userEmail ?? '-'); ?></span></div>
        <div class="profile-row"><i class="fas fa-phone"></i><span class="profile-label">Telefon:</span><span class="profile-value">-</span></div>
        <div class="profile-row"><i class="fas fa-briefcase"></i><span class="profile-label">Firma:</span><span class="profile-value"><?php echo htmlspecialchars($companyName); ?></span></div>
    </div>
</div>

<!-- PROFIL DROPDOWN & WIDGET BİTİŞ --> 