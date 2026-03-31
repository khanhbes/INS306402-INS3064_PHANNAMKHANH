<?php
// delete.php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = $_POST['id'] ?? 0;

if ($id) {
    try {
        // Kiểm tra sách có tồn tại không
        $check = $pdo->prepare("SELECT id FROM books WHERE id = ?");
        $check->execute([$id]);
        if ($check->fetch()) {
            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
            $stmt->execute([$id]);
        }
    } catch (PDOException $e) {
        die("Lỗi xóa dữ liệu: " . $e->getMessage());
    }
}

header("Location: index.php");
exit;
?>