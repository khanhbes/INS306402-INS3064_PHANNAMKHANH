<?php
// config/setup.php

$environment = 'development'; // Đổi thành 'production' khi đưa lên server thật

if ($environment === 'development') {
    // Hiện mọi lỗi ra màn hình
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('log_errors', '0');
} else {
    // Ẩn lỗi với user, chỉ ghi vào file log
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../logs/php_errors.log'); 
}