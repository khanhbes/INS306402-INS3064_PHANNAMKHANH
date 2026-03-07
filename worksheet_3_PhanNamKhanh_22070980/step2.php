<?php
declare(strict_types=1);

// Lấy dữ liệu từ Step 1 (nếu có)
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Nếu Step 2 được submit, in ra toàn bộ dữ liệu 
if (isset($_POST['submit_final'])) {
    $bio = $_POST['bio'] ?? '';
    $location = $_POST['location'] ?? '';
    
    echo "<h2>Registration Complete!</h2>";
    echo "<h3>Summary of Collected Data:</h3>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> " . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "</li>";
    echo "<li><strong>Password:</strong> [HIDDEN FOR SECURITY]</li>";
    echo "<li><strong>Location:</strong> " . htmlspecialchars($location, ENT_QUOTES, 'UTF-8') . "</li>";
    echo "<li><strong>Bio:</strong> " . htmlspecialchars($bio, ENT_QUOTES, 'UTF-8') . "</li>";
    echo "</ul>";
    echo "<a href='step1.php'>Start Over</a>";
    exit; // Dừng việc render form bên dưới
}

// Nếu truy cập thẳng step2.php mà không qua step1.php, chuyển hướng về step 1
if (empty($username)) {
    header("Location: step1.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Step 2: Profile Info</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">
    <h2>Step 2: Profile Information</h2>
    <form action="step2.php" method="POST">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="password" value="<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?>">
        
        <p>
            <label>Location:</label><br>
            <input type="text" name="location" required>
        </p>
        <p>
            <label>Biography:</label><br>
            <textarea name="bio" rows="4" required></textarea>
        </p>
        <button type="submit" name="submit_final">Finish Registration</button>
    </form>
</body>
</html>