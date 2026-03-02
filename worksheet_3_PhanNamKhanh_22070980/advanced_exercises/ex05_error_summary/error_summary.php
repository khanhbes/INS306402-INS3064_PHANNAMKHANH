<?php
declare(strict_types=1);

$errors = []; // Mảng chứa thông báo lỗi chung [cite: 163]
$fieldErrors = []; // Mảng đánh dấu trường nào bị lỗi để thêm CSS class
$name = '';
$age = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = trim($_POST['age'] ?? '');

    // Validate Name
    if (empty($name)) {
        $errors[] = "Name cannot be left empty.";
        $fieldErrors['name'] = true;
    } elseif (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long.";
        $fieldErrors['name'] = true;
    }

    // Validate Age
    if (empty($age)) {
        $errors[] = "Age is required.";
        $fieldErrors['age'] = true;
    } elseif (!is_numeric($age) || (int) $age < 18) {
        $errors[] = "You must be 18 or older to register.";
        $fieldErrors['age'] = true;
    }

    if (empty($errors)) {
        $success = "All fields validated successfully!";
        $name = $age = ''; // Xóa form
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Error Summary Block</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .form-container {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
        }

        /* Class highlight lỗi [cite: 165] */
        input.error {
            border: 2px solid #dc3545;
            background-color: #fff8f8;
        }

        /* Khối hiển thị tổng hợp lỗi [cite: 164] */
        .error-summary {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .error-summary ul {
            margin: 0;
            padding-left: 20px;
        }

        .success {
            color: #155724;
            background: #d4edda;
            padding: 15px;
            border: 1px solid #c3e6cb;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Registration (Error Summary Pattern)</h2>

    <div class="form-container">

        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-summary">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name"
                    value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                    class="<?php echo isset($fieldErrors['name']) ? 'error' : ''; ?>">
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="text" id="age" name="age"
                    value="<?php echo htmlspecialchars($age, ENT_QUOTES, 'UTF-8'); ?>"
                    class="<?php echo isset($fieldErrors['age']) ? 'error' : ''; ?>">
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>

</html>