<?php
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
if (!$user) {
    header('Location: logout.php');
    exit;
}

$message = '';
$uploadDir = __DIR__ . '/assets/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['avatar']['tmp_name'];
        $fileSize = $_FILES['avatar']['size'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png'];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $message = "<div class='error'>Lỗi: Chỉ hỗ trợ định dạng JPG và PNG.</div>";
        } elseif ($fileSize > 2097152) {
            $message = "<div class='error'>Lỗi: File ảnh không được vượt quá 2MB.</div>";
        } else {
            $extension = ($mimeType === 'image/jpeg') ? 'jpg' : 'png';
            $newFileName = uniqid('avt_' . $user['id'] . '_', true) . '.' . $extension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $destination)) {
                if (!empty($user['avatar']) && file_exists(__DIR__ . '/' . $user['avatar'])) {
                    @unlink(__DIR__ . '/' . $user['avatar']);
                }
                updateAvatar($user['id'], 'assets/uploads/' . $newFileName);
                $message = "<div class='success'>Cập nhật ảnh đại diện thành công!</div>";
                $user = getCurrentUser(); // Refresh data
            } else {
                $message = "<div class='error'>Lỗi khi lưu file.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ của tôi</title>
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #0F172A;
            --card-bg: #1E293B;
            --text-color: #F8FAFC;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: var(--card-bg);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
            text-align: center;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            margin: 0 auto 1.5rem;
            display: block;
            background-color: #334155;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        p.subtitle {
            color: #94A3B8;
            margin-bottom: 2rem;
        }

        .upload-form {
            background: rgba(15, 23, 42, 0.5);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px dashed #334155;
        }

        .upload-form label {
            display: block;
            margin-bottom: 1rem;
            color: #E2E8F0;
            font-size: 0.9rem;
        }

        input[type="file"] {
            margin-bottom: 1rem;
            width: 100%;
            color: #94A3B8;
        }

        input[type="file"]::file-selector-button {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            background: #334155;
            color: white;
            cursor: pointer;
            margin-right: 1rem;
            transition: background 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background: #475569;
        }

        button {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        .logout-btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background-color: transparent;
            color: #EF4444;
            border: 1px solid #EF4444;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background-color: #EF4444;
            color: white;
        }

        .success {
            color: #10B981;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 6px;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .error {
            color: #EF4444;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 6px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (!empty($user['avatar'])): ?>
            <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="avatar">
        <?php else: ?>
            <div class="avatar"
                style="display: flex; align-items: center; justify-content: center; color: #94A3B8; font-size: 0.9rem;">Chưa
                có ảnh</div>
        <?php endif; ?>

        <h2>Xin chào,
            <?php echo htmlspecialchars($user['username']); ?>!
        </h2>
        <p class="subtitle">Chào mừng bạn trở lại với bảng điều khiển.</p>

        <?php echo $message; ?>

        <form action="profile.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <label for="avatar">Cập nhật ảnh đại diện (JPG/PNG, Tối đa 2MB)</label>
            <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg" required>
            <button type="submit">Tải lên</button>
        </form>

        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    </div>
</body>

</html>