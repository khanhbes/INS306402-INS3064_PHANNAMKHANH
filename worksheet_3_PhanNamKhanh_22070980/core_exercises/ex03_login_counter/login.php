<?php
// Bắt buộc kiểm tra kiểu dữ liệu nghiêm ngặt [cite: 16]
declare(strict_types=1);

// Hardcode tài khoản [cite: 99]
$valid_user = 'admin';
$valid_pass = '123456';

$message = '';
$attempts = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Ép kiểu (int) cực kỳ quan trọng để tránh lỗi TypeError với strict_types
    $attempts = isset($_POST['attempts']) ? (int) $_POST['attempts'] : 0;

    if ($username === $valid_user && $password === $valid_pass) {
        $message = "<div class='success'>Login Successful</div>";
        $attempts = 0; // Reset bộ đếm nếu đăng nhập đúng
    } else {
        $attempts++; // Tăng bộ đếm nếu sai [cite: 100]
        $message = "<div class='error'>Invalid Credentials</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login with Counter</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .login-box {
            padding: 20px;
            border: 1px solid #ccc;
            max-width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .attempt-text {
            color: #dc3545;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>System Login</h2>
    <div class="login-box">
        <?php echo $message; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="attempts" value="<?php echo $attempts; ?>">

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <?php if ($attempts > 0): ?>
            <div class="attempt-text">Failed Attempts: <?php echo $attempts; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>