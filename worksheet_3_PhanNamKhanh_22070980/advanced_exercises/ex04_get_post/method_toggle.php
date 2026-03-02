<?php
declare(strict_types=1);

$methodUsed = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
$dataReceived = [];

// Phát hiện phương thức nào đã được sử dụng và lấy dữ liệu tương ứng [cite: 158-159]
if ($methodUsed === 'POST') {
    $dataReceived = $_POST;
} elseif ($methodUsed === 'GET' && !empty($_GET)) {
    $dataReceived = $_GET;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GET vs POST Toggle</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .debug-box {
            background: #222;
            color: #0f0;
            padding: 15px;
            margin-top: 20px;
            font-family: monospace;
            overflow-x: auto;
        }

        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Transmission Method Toggle</h2>

    <div class="container">
        <form id="toggleForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
            onsubmit="updateMethod()">
            <div class="form-group">
                <label><strong>Select Transmission Method:</strong></label><br>
                <input type="radio" id="method_post" name="method_choice" value="POST" checked>
                <label for="method_post">Send via POST (Hidden in HTTP Body)</label><br>

                <input type="radio" id="method_get" name="method_choice" value="GET">
                <label for="method_get">Send via GET (Visible in URL)</label>
            </div>

            <div class="form-group">
                <label for="secret_data">Enter some test data:</label><br>
                <input type="text" id="secret_data" name="secret_data" required style="width: 100%; padding: 8px;">
            </div>

            <button type="submit">Send Data</button>
        </form>
    </div>

    <?php if (!empty($dataReceived)): ?>
        <div class="debug-box">
            <h3>Server Received Request via: <?php echo htmlspecialchars($methodUsed); ?></h3>
            <pre><?php print_r($dataReceived); ?></pre>
        </div>
    <?php endif; ?>

    <script>
        [cite_start]// JS tự động thay đổi method của form dựa trên radio button được chọn [cite: 157]
        function updateMethod() {
            const form = document.getElementById('toggleForm');
            const selectedMethod = document.querySelector('input[name="method_choice"]:checked').value;
            form.method = selectedMethod;
        }
    </script>
</body>

</html>