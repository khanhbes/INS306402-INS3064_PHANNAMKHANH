<?php
declare(strict_types=1);

// Bật hiển thị lỗi để debug
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "<h2>Form Submission Result</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu và làm sạch cơ bản (dùng trim và htmlspecialchars chống XSS)
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Xử lý trường hợp dữ liệu bị thiếu
    if (empty($fullName) || empty($email) || empty($message)) {
        echo "<p style='color: red;'><strong>Error: Missing Data!</strong> Name, Email, and Message are required.</p>";
        echo "<a href='contact.html'>Go back</a>";
    } else {
        // Output dữ liệu ra HTML list có cấu trúc
        echo "<ul>";
        echo "<li><strong>Full Name:</strong> " . htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') . "</li>";
        echo "<li><strong>Email:</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</li>";
        echo "<li><strong>Phone:</strong> " . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . "</li>";
        echo "<li><strong>Message:</strong> " . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</li>";
        echo "</ul>";
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?>