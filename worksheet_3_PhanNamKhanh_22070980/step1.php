<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Step 1: Account Info</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">
    <h2>Step 1: Account Information</h2>
    <form action="step2.php" method="POST">
        <p>
            <label>Username:</label><br>
            <input type="text" name="username" required>
        </p>
        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>
        <button type="submit">Next: Profile Info &rarr;</button>
    </form>
</body>
</html>