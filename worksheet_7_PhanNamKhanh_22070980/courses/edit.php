<?php
// courses/edit.php
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/ValidationException.php';

$db = Database::getInstance();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $course = $db->fetch('SELECT * FROM courses WHERE id = ?', [$id]);
    if (!$course) {
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    die('Lỗi truy xuất dữ liệu.');
}

$errors = [];
$title = $course['title'];
$description = $course['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    try {
        $validationErrors = [];
        if ($title === '') {
            $validationErrors['title'] = 'Vui lòng nhập tên khóa học.';
        } elseif (mb_strlen($title) < 3) {
            $validationErrors['title'] = 'Tên khóa học phải có ít nhất 3 ký tự.';
        }

        if (!empty($validationErrors)) {
            throw new ValidationException($validationErrors);
        }

        $db->update('courses', [
            'title' => $title,
            'description' => $description
        ], 'id = ?', [$id]);

        header('Location: index.php?updated=1');
        exit;

    } catch (ValidationException $e) {
        $errors = $e->getErrors();
    } catch (Exception $e) {
        $errors['general'] = 'Có lỗi khi cập nhật, vui lòng thử lại.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa khóa học</title>
</head>
<body>
<h1>Sửa khóa học</h1>

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
    <button type="submit">Cập nhật</button>
    <a href="index.php">Hủy</a>
</form>
</body>
</html>