<?php
require_once __DIR__ . '/../classes/Database.php';
$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);

$teacher = $db->fetch('SELECT * FROM teachers WHERE id = ?', [$id]);
if (!$teacher) { header('Location: index.php'); exit; }

$errors = [];
$name = $teacher['name']; $email = $teacher['email']; $phone = $teacher['phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '') $errors['name'] = 'Nhập họ tên.';
    
    if (empty($errors)) {
        try {
            $exists = $db->fetch('SELECT id FROM teachers WHERE email = ? AND id <> ?', [$email, $id]);
            if ($exists) {
                $errors['email'] = 'Email trùng lặp.';
            } else {
                $db->update('teachers', ['name' => $name, 'email' => $email, 'phone' => $phone], 'id = ?', [$id]);
                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'Lỗi DB.';
        }
    }
}
?>
<form method="post">
    <p>Họ tên: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"></p>
    <p>Email: <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"></p>
    <p>SĐT: <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>"></p>
    <p style="color:red"><?= implode('<br>', $errors) ?></p>
    <button type="submit">Cập nhật</button> <a href="index.php">Hủy</a>
</form>