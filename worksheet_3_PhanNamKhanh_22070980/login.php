<?php
declare(strict_types=1);

// Hardcode tài khoản để test [cite: 99]
define('VALID_USER', 'admin');
define('VALID_PASS', '123456');

$loginSuccess = false;
$errorMessage = '';
// Khởi tạo bộ đếm từ 0
$attempts = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Ép kiểu số nguyên cho số lần thử từ trường hidden [cite: 100]
    $attempts = isset($_POST['attempts']) ? (int)$_POST['attempts'] : 0;

    if ($username === VALID_USER && $password === VALID_PASS) {
        $loginSuccess = true;
    } else {
        // Nếu sai, tăng bộ đếm lên 1 [cite: 100]
        $attempts++;
        $errorMessage = "Invalid Credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login System</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .login-box { padding: 20px; border: 1px solid #ccc; max-width: 300px; background: #fff; }
        .form-group { margin-bottom: 15px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; font-weight: bold; font-size: 1.2em; }
        .attempts { color: #dc3545; font-size: 0.9em; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>System Login</h2>
    
    <?php if ($loginSuccess): ?>
        <div class="success">Login Successful! [cite: 101]</div>
    <?php else: ?>
        <div class="login-box">
            <?php if ($errorMessage !== ''): ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

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
                <div class="attempts">Failed Attempts: <?php echo $attempts; [cite_start]?> [cite: 101]</div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>