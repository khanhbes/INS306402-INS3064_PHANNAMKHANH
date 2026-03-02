<?php
declare(strict_types=1);

$resultMessage = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operation = $_POST['operation'] ?? '';

    // Kiểm tra dữ liệu đầu vào phải là số [cite: 95]
    if (!is_numeric($num1) || !is_numeric($num2)) {
        $error = "Error: Cả hai giá trị đầu vào phải là số hợp lệ.";
    } else {
        // Ép kiểu về float để tính toán an toàn
        $n1 = (float) $num1;
        $n2 = (float) $num2;

        // Xử lý logic phép tính
        if ($operation === '/' && $n2 === 0.0) {
            $error = "Error: Không thể chia cho 0! [cite: 95]";
        } else {
            // Sử dụng biểu thức match của PHP 8.0+
            $result = match ($operation) {
                '+' => $n1 + $n2,
                '-' => $n1 - $n2,
                '*' => $n1 * $n2,
                '/' => $n1 / $n2,
                default => null,
            };

            if ($result !== null) {
                // Hiển thị phương trình và kết quả [cite: 95]
                $resultMessage = "{$n1} {$operation} {$n2} = <strong>{$result}</strong>";
            } else {
                $error = "Error: Phép toán không hợp lệ.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Arithmetic Calculator</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .calculator {
            padding: 20px;
            border: 1px solid #ccc;
            max-width: 350px;
            background: #f9f9f9;
        }

        .form-group {
            margin-bottom: 10px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .result {
            margin-top: 15px;
            padding: 10px;
            background: #e2e3e5;
            border: 1px solid #d6d8db;
        }

        .error {
            margin-top: 15px;
            padding: 10px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <h2>Arithmetic Calculator</h2>
    <div class="calculator">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="num1">Number 1:</label>
                <input type="text" id="num1" name="num1" required
                    value="<?php echo htmlspecialchars($_POST['num1'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="operation">Operation:</label>
                <select id="operation" name="operation">
                    <option value="+" <?php echo (isset($_POST['operation']) && $_POST['operation'] === '+') ? 'selected' : ''; ?>>+ (Add)</option>
                    <option value="-" <?php echo (isset($_POST['operation']) && $_POST['operation'] === '-') ? 'selected' : ''; ?>>- (Subtract)</option>
                    <option value="*" <?php echo (isset($_POST['operation']) && $_POST['operation'] === '*') ? 'selected' : ''; ?>>* (Multiply)</option>
                    <option value="/" <?php echo (isset($_POST['operation']) && $_POST['operation'] === '/') ? 'selected' : ''; ?>>/ (Divide)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="num2">Number 2:</label>
                <input type="text" id="num2" name="num2" required
                    value="<?php echo htmlspecialchars($_POST['num2'] ?? ''); ?>">
            </div>

            <button type="submit">Calculate</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($resultMessage !== ''): ?>
            <div class="result"><?php echo $resultMessage; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>