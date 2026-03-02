<?php
declare(strict_types=1);

// 1. Hàm làm sạch dữ liệu chống XSS
function sanitize(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// 2. Hàm kiểm tra định dạng Email
function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 3. Hàm kiểm tra độ dài chuỗi
function validateLength(string $str, int $min, int $max): bool
{
    $length = mb_strlen($str, 'UTF-8');
    return ($length >= $min && $length <= $max);
}

// 4. Hàm kiểm tra Password (Yêu cầu có độ dài và ký tự đặc biệt) [cite: 117]
function validatePassword(string $pass): bool
{
    // Độ dài tối thiểu giả định là 8
    if (mb_strlen($pass, 'UTF-8') < 8) {
        return false;
    }
    // Dùng regex để tìm xem có ký tự đặc biệt (không phải chữ cái và số) hay không
    if (preg_match('/[^a-zA-Z0-9]/', $pass) !== 1) {
        return false;
    }
    return true;
}
?>