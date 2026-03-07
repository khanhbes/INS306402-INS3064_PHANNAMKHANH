<?php
declare(strict_types=1);

// Khởi tạo session an toàn
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_FILE', __DIR__ . '/../data/users.json');

// Hàm làm sạch dữ liệu chống XSS
function sanitize(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Đọc danh sách user từ file JSON
function getUsers(): array {
    if (!file_exists(DB_FILE)) {
        file_put_contents(DB_FILE, json_encode([]));
    }
    $json = file_get_contents(DB_FILE);
    return json_decode($json, true) ?: [];
}

// Lưu danh sách user vào file JSON
function saveUsers(array $users): void {
    file_put_contents(DB_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// Tìm user theo username
function getUserByUsername(string $username): ?array {
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    return null;
}
?>