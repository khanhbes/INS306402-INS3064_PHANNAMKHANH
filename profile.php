<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

// 1. Access Control: Chặn người dùng chưa đăng nhập 
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['user'];
$users = getUsers();
$userIndex = null;
$userData = null;

// Tìm vị trí của user hiện tại trong mảng JSON
foreach ($users as $index => $u) {
    if ($u['username'] === $currentUser) {
        $userIndex = $index;
        $userData = $u;
        break;
    }
}

// Nếu lỗi không tìm thấy user, cho đăng xuất
if ($userData === null) {
    header("Location: logout.php");
    exit;
}

$message = '';
$uploadDir = __DIR__ . '/assets/uploads/';

// Đảm bảo thư mục upload tồn tại
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 2. Xử lý Form Cập nhật Hồ sơ [cite: 190]
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cập nhật Bio (Chống XSS khi lưu hoặc khi hiển thị) [cite: 198]
    $newBio = trim($_POST['bio'] ?? '');
    $users[$userIndex]['bio'] = $newBio; // Lưu thô, sẽ sanitize khi xuất ra HTML

    // Xử lý Upload Avatar (chặn .exe, .pdf) [cite: 199]
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['avatar']['tmp_name'];
        $fileSize = $_FILES['avatar']['size'];

        // Validate MIME Type thực sự của file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png'];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $message = "<span style='color:red;'>Error: Only JPG and PNG avatars are allowed! Executables/PDFs are blocked.</span>";
        } elseif ($fileSize > 2097152) { // Kích thước tối đa 2MB
            $message = "<span style='color:red;'>Error: Avatar must be less than 2MB.</span>";
        } else {
            // Đổi tên file để tránh trùng lặp
            $extension = ($mimeType === 'image/jpeg') ? 'jpg' : 'png';
            $newFileName = uniqid('avt_' . $currentUser . '_', true) . '.' . $extension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $destination)) {
                // Xóa avatar cũ nếu có
                if (!empty($users[$userIndex]['avatar']) && file_exists(__DIR__ . '/' . $users[$userIndex]['avatar'])) {
                    unlink(__DIR__ . '/' . $users[$userIndex]['avatar']);
                }
                // Lưu đường dẫn mới vào mảng
                $users[$userIndex]['avatar'] = 'assets/uploads/' . $newFileName;
                $message = "<span style='color:green;'>Profile updated successfully!</span>";
            } else {
                $message = "<span style='color:red;'>Error uploading file.</span>";
            }
        }
    } else {
        $message = "<span style='color:green;'>Bio updated successfully!</span>";
    }

    // Lưu lại dữ liệu vào file JSON [cite: 191, 202]
    saveUsers($users);
    $userData = $users[$userIndex]; // Cập nhật lại data hiển thị
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }

        .profile-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .avatar-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            display: block;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="profile-card">
        <h2>Welcome, <?php echo htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8'); ?>!</h2>

        <?php if ($message): ?>
            <p><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>

        <?php if (!empty($userData['avatar'])): ?>
            <img src="<?php echo htmlspecialchars($userData['avatar'], ENT_QUOTES, 'UTF-8'); ?>" alt="Avatar"
                class="avatar-img">
        <?php else: ?>
            <div
                style="width: 150px; height: 150px; border-radius: 50%; background: #ccc; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                No Avatar
            </div>
        <?php endif; ?>

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bio"><strong>Your Biography:</strong></label><br>
                <textarea name="bio" id="bio"
                    rows="5"><?php echo htmlspecialchars($userData['bio'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="form-group">
                <label for="avatar"><strong>Update Avatar (JPG/PNG only, max 2MB):</strong></label><br>
                <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg">
            </div>

            <button type="submit">Save Profile</button>
        </form>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

</body>

</html>