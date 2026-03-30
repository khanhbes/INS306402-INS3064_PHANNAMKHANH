<?php
// courses/index.php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$courses = $db->fetchAll('SELECT * FROM courses ORDER BY created_at DESC');

$successMessage = '';
if (isset($_GET['success'])) $successMessage = 'Thêm khóa học thành công!';
elseif (isset($_GET['updated'])) $successMessage = 'Cập nhật khóa học thành công!';
elseif (isset($_GET['deleted'])) $successMessage = 'Xóa khóa học thành công!';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khóa học</title>
</head>
<body>
<h1>Quản lý khóa học</h1>

<?php if ($successMessage): ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<p><a href="create.php">+ Thêm khóa học</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Tên khóa học</th>
        <th>Mô tả</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= htmlspecialchars($course['title']) ?></td>
            <td><?= htmlspecialchars($course['description'] ?? '') ?></td>
            <td><?= $course['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $course['id'] ?>">Sửa</a> | 
                <a href="delete.php?id=<?= $course['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>