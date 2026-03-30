<?php
// courses/create.php
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/ValidationException.php';

$errors = [];
$title  = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    try {
        // 1. Validate
        $validationErrors = [];
        if ($title === '') {
            $validationErrors['title'] = 'Vui lòng nhập tên khóa học.';
        } elseif (mb_strlen($title) < 3) {
            $validationErrors['title'] = 'Tên khóa học phải có ít nhất 3 ký tự.';
        }

        if (!empty($validationErrors)) {
            throw new ValidationException($validationErrors);
        }

        // 2. Insert DB
        $db = Database::getInstance();
        $db->insert('courses', [
            'title'       => $title,
            'description' => $description,
        ]);

        header('Location: index.php?success=1');
        exit;

    } catch (ValidationException $e) {
        $errors = $e->getErrors(); // Lấy mảng lỗi validate ra để hiển thị
    } catch (Exception $e) {
        $errors['general'] = 'Có lỗi xảy ra khi lưu vào cơ sở dữ liệu.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm khóa học</title>
</head>
<body>
<h1>Thêm khóa học mới</h1>

<?php if (!empty($errors['general'])): ?>
    <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post">
    <div>
        <label>Tên khóa học:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">
        <?php if (!empty($errors['title'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['title']) ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label>Mô tả:</label><br>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea>
    </div>

    <button type="submit">Lưu</button>
    <a href="index.php">Hủy</a>
</form>
</body>
</html>