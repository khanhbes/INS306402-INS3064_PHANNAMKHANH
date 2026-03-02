<?php
declare(strict_types=1);

$errors = [];
$username = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Validate Username: Chỉ chứa chữ cái và số (Alphanumeric) [cite: 150]
    if (empty($username)) {
        $errors['username'][] = "Username cannot be empty.";
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors['username'][] = "Username can only contain letters and numbers.";
    }

    // 2. Validate Password với các thông báo lỗi cụ thể [cite: 149, 151]
    if (empty($password)) {
        $errors['password'][] = "Password cannot be empty.";
    } else {
        if (!preg_match('/[A-Z]/', $password)) {
            $errors['password'][] = "Password is missing an uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors['password'][] = "Password is missing a lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors['password'][] = "Password is missing a number.";
        }
        // Kiểm tra ký tự đặc biệt (bất kỳ ký tự nào không phải chữ cái và số)
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors['password'][] = "Password is missing a special symbol.";
        }
    }

    if (empty($errors)) {
        $successMsg = "Registration successful! Strong password verified.";
        $username = ''; // Clear form
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Regex Validation Form</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .form-container {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            background: #fafafa;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .error-text {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }

        .success {
            color: #155724;
            background: #d4edda;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Secure Registration (Regex Validation)</h2>

    <div class="form-container">
        <?php if ($successMsg !== ''): ?>
            <div class="success"><?php echo $successMsg; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="username">Username (Alphanumeric only):</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if (isset($errors['username'])): ?>
                    <?php foreach ($errors['username'] as $err): ?>
                        <span class="error-text">&bull; <?php echo $err; ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password (Complex):</label>
                <input type="password" id="password" name="password">
                <?php if (isset($errors['password'])): ?>
                    <?php foreach ($errors['password'] as $err): ?>
                        <span class="error-text">&bull; <?php echo $err; ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>