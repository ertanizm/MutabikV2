<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $code = $_POST['code'];

    // DB'den bu mail için kodu çek
    /*
    $stmt = $pdo->prepare("SELECT verification_code FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    */

    // Örnek: DB'den gelen kod = $row['verification_code']
    $db_code = $row['verification_code'];

    if ($code == $db_code) {
        // Kod doğru → is_verified = 1 yap
        /*
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $stmt->execute([$email]);
        */
        // Login sayfasına yönlendir
        header("Location: login.php");
        exit;
    } else {
        echo "Kod yanlış!";
    }
}
?>

<link rel="stylesheet" href="../assets/verify.css">
<div class="verify-container">
  <h2>Doğrulama Kodu Gir</h2>
  <form method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <input type="text" name="code" placeholder="Doğrulama Kodu" required>
    <button type="submit">Doğrula</button>
  </form>
  <p>Mail adresine gönderilen kodu giriniz.</p>
</div>
