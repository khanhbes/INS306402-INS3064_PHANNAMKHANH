<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

// Nếu đã đăng nhập, đẩy thẳng vào profile
if (isset($_SESSION['user'])) {
    header("Location: profile.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate cơ bản
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        // Kiểm tra khớp mật khẩu [cite: 185, 195]
        $errors[] = "Passwords do not match!";
    } elseif (getUserByUsername($username)) {
        $errors[] = "Username already exists.";
    }

    if (empty($errors)) {
        $users = getUsers();
        // Mã hóa mật khẩu an toàn
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $users[] = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'bio' => '',
            'avatar' => ''
        ];

        saveUsers($users); // Lưu vào JSON 
        $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
</head>

<body style="font-family: sans-serif; padding: 20px;">
    <h2>Register Account</h2>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $err)
                echo "<li>$err</li>"; ?>
        </ul>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green; font-weight: bold;"><?php echo $success; ?></div>
    <?php else: ?>
        <form action="register.php" method="POST">
            <p>Username: <input type="text" name="username" value="<?php echo sanitize($_POST['username'] ?? ''); ?>"
                    required></p>
            <p>Email: <input type="email" name="email" value="<?php echo sanitize($_POST['email'] ?? ''); ?>" required></p>
            <p>Password: <input type="password" name="password" required></p>
            <p>Confirm Password: <input type="password" name="confirm_password" required></p>
            <button type="submit">Register</button>
        </form>
    <?php endif; ?>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>

</html>