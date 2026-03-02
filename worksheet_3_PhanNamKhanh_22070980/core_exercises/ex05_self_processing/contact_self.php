<?php
declare(strict_types=1);

$isSubmitted = false;
$errors = [];

// Phát hiện submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (empty($fullName) || empty($email) || empty($message)) {
        $errors[] = "Missing Data: Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email format.";
    } else {
        // Validation thành công, đổi trạng thái
        $isSubmitted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Self-Processing Form</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .error-msg {
            color: red;
            margin-bottom: 15px;
        }

        .success-msg {
            color: green;
            font-size: 20px;
            font-weight: bold;
            padding: 20px;
            border: 1px solid green;
            display: inline-block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        textarea {
            width: 100%;
            max-width: 400px;
            padding: 8px;
        }
    </style>
</head>

<body>

    <?php if ($isSubmitted): ?>
        <div class="success-msg">
            Thank You, <?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>! We have received your message.
        </div>
    <?php else: ?>

        <h2>Contact Us (Self-Processing)</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-msg">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name">
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" rows="4"></textarea>
            </div>
            <button type="submit">Submit</button>
        </form>

    <?php endif; ?>

</body>

</html>