<?php
require_once 'includes/db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM faturalar WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: faturalar.php?status=success_delete");
            exit();
        }
    }
}
header("Location: faturalar.php?status=error");
exit();
?>