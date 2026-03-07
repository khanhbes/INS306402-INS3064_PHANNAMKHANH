<?php
declare(strict_types=1);

// Lấy tham số 'q' từ URL, nếu không có thì gán chuỗi rỗng
$searchQuery = $_GET['q'] ?? '';

// Làm sạch dữ liệu để chống XSS trước khi in ra HTML
$safeQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Query Echo</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .search-box { padding: 20px; background: #f4f4f4; border-radius: 5px; max-width: 400px; }
        input[type="text"] { width: 70%; padding: 8px; }
        button { padding: 8px 15px; background: #17a2b8; color: white; border: none; cursor: pointer; }
        .result { margin-top: 20px; font-size: 1.1em; color: #333; }
    </style>
</head>
<body>
    <h2>Search System</h2>
    <div class="search-box">
        <form action="search.php" method="GET">
            <label for="q">Search Term:</label><br><br>
            <input type="text" id="q" name="q" value="<?php echo $safeQuery; ?>" placeholder="Type here...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php if ($searchQuery !== ''): ?>
        <div class="result">
            You searched for: <strong><?php echo $safeQuery; ?></strong>
        </div>
    <?php endif; ?>
</body>
</html>