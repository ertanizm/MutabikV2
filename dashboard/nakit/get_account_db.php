<?php
session_start();

// Session kontrolü
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    exit('Unauthorized');
}

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'deneme_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("SELECT * FROM kasa_bankalar WHERE id = ?");
        $stmt->execute([$id]);
        $hesap = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($hesap) {
            header('Content-Type: application/json');
            echo json_encode($hesap);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Hesap bulunamadı']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID parametresi gerekli']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?> 
