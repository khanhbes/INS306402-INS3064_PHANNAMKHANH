<?php
declare(strict_types=1);

// Lấy tham số 'page' từ URL, mặc định là 'home' nếu không có
$page = $_GET['page'] ?? 'home';

// Danh sách Whitelist các trang được phép truy cập
$allowedPages = ['home', 'contact'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Simple Front Controller Router</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            background: #333;
            padding: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=contact">Contact</a>
        <a href="index.php?page=invalid_page">Test 404 Error</a>
    </nav>

    <div class="content">
        <?php
        // Logic Routing (Điều hướng)
        if (in_array($page, $allowedPages)) {
            // Nếu hợp lệ, nhúng file tương ứng từ thư mục pages/
            include __DIR__ . "/pages/{$page}.php";
        } else {
            // Nếu không hợp lệ, trả về mã lỗi HTTP 404 và hiển thị trang 404
            http_response_code(404);
            include __DIR__ . "/pages/404.php";
        }
        ?>
    </div>

</body>

</html>