<?php
declare(strict_types=1);

// Bắt buộc phải khởi tạo session để lưu trữ token trên server
session_start();

// 1. Khởi tạo CSRF Token nếu chưa có [cite: 142, 143]
if (empty($_SESSION['csrf_token'])) {
    // Tạo chuỗi 64 ký tự hex ngẫu nhiên an toàn mã hóa
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Lấy token từ form gửi lên
    $postToken = $_POST['csrf_token'] ?? '';

    // 3. So sánh token (Sử dụng hash_equals để chống tấn công Timing Attack) [cite: 145]
    if (!hash_equals($_SESSION['csrf_token'], $postToken)) {
        // Nếu token không khớp hoặc không tồn tại, chặn ngay lập tức [cite: 145]
        http_response_code(403);
        die("<h1>403 Forbidden: Invalid CSRF Token! Possible attack detected.</h1>");
    }

    // Nếu token hợp lệ, tiếp tục xử lý logic form
    $action = $_POST['action'] ?? 'Unknown';
    $message = "Success! Action '{$action}' performed securely.";

    // Khuyến nghị: Sinh lại token mới sau mỗi request thành công để tăng cường bảo mật
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CSRF Protected Form</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .secure-zone {
            padding: 20px;
            border: 2px dashed #28a745;
            max-width: 400px;
        }

        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }

        button.danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Sensitive Action Form (CSRF Protected)</h2>

    <div class="secure-zone">
        <?php if ($message !== ''): ?>
            <div class="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <p>Are you sure you want to delete your account?</p>
            <input type="hidden" name="action" value="Delete Account">
            <button type="submit" class="danger">Confirm Delete</button>
        </form>
    </div>

    <hr style="margin-top: 30px;">

    <h3>Hacker Simulation Form (Will Fail with 403)</h3>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="hidden" name="csrf_token" value="fake_or_missing_token">
        <input type="hidden" name="action" value="Hack Account">
        <button type="submit">Submit as Hacker</button>
    </form>

</body>

</html>