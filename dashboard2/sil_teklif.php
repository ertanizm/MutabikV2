<?php
// Veritabanı bağlantısını dahil et
require_once 'includes/db_connect.php';

// Sadece POST metodu ile gelen istekleri kabul et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Silinecek kaydın ID'sini al
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        // Güvenli silme işlemi için prepared statement
        $sql = "DELETE FROM teklifler WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Parametreyi bağla (i = integer)
            $stmt->bind_param("i", $id);
            
            // Sorguyu çalıştır
            if ($stmt->execute()) {
                // Başarılı olursa, başarı mesajıyla listeye geri yönlendir
                header("Location: teklifler.php?status=deleted");
                exit();
            }
        }
    }
}

// Bir sorun olursa veya direk erişim denenirse, hata mesajıyla geri yönlendir
header("Location: teklifler.php?status=error");
exit();
?>