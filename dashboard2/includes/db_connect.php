<?php
// Veritabanı bağlantı bilgileri
$servername = "localhost";
$username = "root"; // XAMPP için varsayılan kullanıcı adı
$password = "";     // XAMPP için varsayılan şifre (boş)
$dbname = "muhasebe_projesi"; // Oluşturduğumuz veritabanının adı

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Türkçe karakter sorunu yaşamamak için karakter setini ayarla
$conn->set_charset("utf8mb4");

?>
