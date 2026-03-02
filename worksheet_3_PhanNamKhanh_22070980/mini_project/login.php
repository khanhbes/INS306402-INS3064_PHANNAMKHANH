<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

if (isset($_SESSION['user'])) {
    header("Location: profile.php");
    exit;
}

$error = '';
// Khởi tạo biến đếm số lần sai trong session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nếu sai quá 3 lần, khóa form [cite: 196]
    if ($_SESSION['login_attempts'] >= 3) {
        $error = "Account temporarily locked due to 3 failed attempts. Please try again later.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = getUserByUsername($username);

        // Verify mật khẩu (so sánh chuỗi nhập vào với hash trong JSON) [cite: 187]
        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công, reset số lần sai
            $_SESSION['login_attempts'] = 0;
            // Lưu session user [cite: 188]
            $_SESSION['user'] = $user['username'];
            header("Location: profile.php");
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $remaining = 3 - $_SESSION['login_attempts'];
            if ($remaining > 0) {
                $error = "Invalid credentials. $remaining attempts left.";
            } else {
                $error = "Invalid credentials. You have been locked out.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
</head>

<body style="font-family: sans-serif; padding: 20px;">
    <h2>Login System</h2>

    <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($_SESSION['login_attempts'] < 3): ?>
        <form action="login.php" method="POST">
            <p>Username: <input type="text" name="username" required></p>
            <p>Password: <input type="password" name="password" required></p>
            <button type="submit">Login</button>
        </form>
    <?php endif; ?>

    <p>No account? <a href="register.php">Register here</a></p>
</body>

</html>