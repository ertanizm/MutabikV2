<?php
require_once 'includes/db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM musteriler WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: musteriler.php?status=success_delete");
            exit();
        }
    }
}
header("Location: musteriler.php?status=error");
exit();
?>