<?php
declare(strict_types=1);

// Khởi tạo session để có thể phá hủy nó
session_start();

// Xóa toàn bộ biến session
$_SESSION = array();

// Nếu session dùng cookie, xóa cookie đó đi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Phá hủy session 
session_destroy();

// Redirect về trang đăng nhập
header("Location: login.php");
exit;
?>