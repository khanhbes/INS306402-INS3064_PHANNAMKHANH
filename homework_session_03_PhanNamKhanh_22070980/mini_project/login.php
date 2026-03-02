<?php
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    header('Location: profile.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = loginUser($username, $password);
    if ($result['success']) {
        header('Location: profile.php');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Mini Project</title>
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #0F172A;
            --card-bg: #1E293B;
            --text-color: #F8FAFC;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: var(--card-bg);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #94A3B8;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #334155;
            background: #0F172A;
            color: #F8FAFC;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            background-color: var(--primary);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 0.5rem;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        .error {
            color: #EF4444;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            text-align: center;
            background: rgba(239, 68, 68, 0.1);
            padding: 0.5rem;
            border-radius: 4px;
        }

        .redirect {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #94A3B8;
        }

        .redirect a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .redirect a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Đăng nhập</h2>
        <?php if ($error): ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Tên người dùng</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <div class="redirect">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
        </div>
    </div>
</body>

</html>