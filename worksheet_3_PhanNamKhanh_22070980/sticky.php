<?php
declare(strict_types=1);

$errors = [];
$name = '';
$email = '';
$bio = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu và loại bỏ khoảng trắng thừa
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $bio = trim($_POST['bio'] ?? '');

    // Validate Name
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    // Validate Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid Email is required.";
    }

    // Cố tình ép một lỗi theo yêu cầu: Password phải từ 8 ký tự trở lên [cite: 123]
    if (mb_strlen($password, 'UTF-8') < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        // Thành công (trong thực tế sẽ lưu database ở đây)
        echo "<h3 style='color:green;'>Registration Successful!</h3>";
        // Reset biến để dọn dẹp form
        $name = $email = $bio = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sticky Form</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-container { max-width: 400px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .error-box { background: #ffe6e6; color: #cc0000; padding: 10px; border: 1px solid #cc0000; margin-bottom: 15px; }
        button { padding: 10px 20px; background: #0056b3; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Create Account</h2>
    <div class="form-container">
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password (min 8 chars):</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="bio">Short Bio:</label>
                <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>