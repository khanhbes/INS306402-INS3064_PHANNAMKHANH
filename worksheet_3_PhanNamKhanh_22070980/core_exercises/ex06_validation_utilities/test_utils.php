<?php
declare(strict_types=1);

// Bật hiển thị lỗi để debug [cite: 18-19, 33]
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Bắt buộc phải có dòng này để nhúng thư viện hàm vào
require_once 'utils.php';

echo "<h2>Testing Validation Utilities</h2>";

// --- TEST 1: sanitize ---
echo "<h3>1. Test sanitize()</h3>";
$dirtyInput = "<script>alert('XSS')</script>";
$cleanOutput = sanitize($dirtyInput);
echo "Input: " . htmlspecialchars($dirtyInput) . "<br>";
echo "Output: " . $cleanOutput . "<br>";
// Nếu output đã bị mã hóa thành ký tự HTML entities thì Pass
echo ($cleanOutput === "&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;") ? "<span style='color:green; font-weight:bold;'>[Pass]</span>" : "<span style='color:red; font-weight:bold;'>[Fail]</span>";


// --- TEST 2: validateEmail ---
echo "<h3>2. Test validateEmail()</h3>";
$testEmails = [
    'hello@example.com' => true,
    'invalid-email.com' => false
];
foreach ($testEmails as $email => $expectedResult) {
    $actualResult = validateEmail($email);
    $status = ($actualResult === $expectedResult) ? "<span style='color:green; font-weight:bold;'>[Pass]</span>" : "<span style='color:red; font-weight:bold;'>[Fail]</span>";
    echo "Testing '$email': $status <br>";
}


// --- TEST 3: validateLength ---
echo "<h3>3. Test validateLength() (min: 3, max: 10)</h3>";
$testStrings = [
    'Hi' => false,         // Quá ngắn
    'Hello' => true,       // Hợp lệ
    'Hello World!' => false // Quá dài
];
foreach ($testStrings as $str => $expectedResult) {
    $actualResult = validateLength($str, 3, 10);
    $status = ($actualResult === $expectedResult) ? "<span style='color:green; font-weight:bold;'>[Pass]</span>" : "<span style='color:red; font-weight:bold;'>[Fail]</span>";
    echo "Testing '$str': $status <br>";
}


// --- TEST 4: validatePassword ---
echo "<h3>4. Test validatePassword()</h3>";
$testPasswords = [
    'weak' => false,           // Quá ngắn, không ký tự đặc biệt
    'NoSpecial123' => false,   // Đủ dài nhưng không có ký tự đặc biệt
    'Strong!Pass1' => true     // Hợp lệ
];
foreach ($testPasswords as $pass => $expectedResult) {
    $actualResult = validatePassword($pass);
    $status = ($actualResult === $expectedResult) ? "<span style='color:green; font-weight:bold;'>[Pass]</span>" : "<span style='color:red; font-weight:bold;'>[Fail]</span>";
    echo "Testing '$pass': $status <br>";
}
?>