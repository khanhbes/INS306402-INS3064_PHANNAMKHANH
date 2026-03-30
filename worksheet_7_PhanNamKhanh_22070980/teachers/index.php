<?php
require_once __DIR__ . '/../classes/Database.php';
$db = Database::getInstance();
$teachers = $db->fetchAll('SELECT * FROM teachers ORDER BY created_at DESC');
?>
<!DOCTYPE html>
<html lang="vi">
<head><title>Quản lý Giảng viên</title></head>
<body>
<h1>Danh sách Giảng viên</h1>
<p><a href="create.php">+ Thêm Giảng viên</a></p>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Hành động</th></tr>
    <?php foreach ($teachers as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td><?= htmlspecialchars($t['email']) ?></td>
            <td><?= htmlspecialchars($t['phone'] ?? '') ?></td>
            <td>
                <a href="edit.php?id=<?= $t['id'] ?>">Sửa</a> | 
                <a href="delete.php?id=<?= $t['id'] ?>" onclick="return confirm('Xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>