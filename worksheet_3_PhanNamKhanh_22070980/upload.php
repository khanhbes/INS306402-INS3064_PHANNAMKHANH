<?php
declare(strict_types=1);
ini_set('display_errors', '1'); error_reporting(E_ALL);

$uploadMessage = '';
$uploadClass = '';
// Thư mục lưu trữ (nhớ tạo thư mục này)
$uploadDir = __DIR__ . '/uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Kiểm tra xem có file được gửi lên và không có lỗi từ hệ thống không [cite: 137]
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['avatar']['tmp_name'];
        $fileSize = $_FILES['avatar']['size'];
        
        // 2. Validate kích thước (Max 2MB = 2 * 1024 * 1024 bytes) [cite: 138]
        if ($fileSize > 2097152) {
            $uploadMessage = "Error: File size exceeds the 2MB limit.";
            $uploadClass = "error";
        } else {
            // 3. Validate MIME type thực sự của file (Không tin tưởng đuôi file hay $_FILES['avatar']['type']) [cite: 138]
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpName);
            finfo_close($finfo);

            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            
            if (!in_array($mimeType, $allowedMimeTypes)) {
                $uploadMessage = "Error: Invalid file format. Only JPG and PNG are allowed.";
                $uploadClass = "error";
            } else {
                // 4. Tạo tên file mới ngẫu nhiên để tránh ghi đè và ẩn đi tên gốc [cite: 139]
                $extension = ($mimeType === 'image/jpeg') ? 'jpg' : 'png';
                $newFileName = uniqid('avatar_', true) . '.' . $extension;
                $destination = $uploadDir . $newFileName;

                // 5. Di chuyển file từ thư mục tạm vào thư mục đích [cite: 139]
                if (move_uploaded_file($tmpName, $destination)) {
                    $uploadMessage = "Success! Avatar uploaded securely as $newFileName.";
                    $uploadClass = "success";
                } else {
                    $uploadMessage = "Error: Failed to move uploaded file.";
                    $uploadClass = "error";
                }
            }
        }
    } else {
        $errorCode = $_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE;
        $uploadMessage = "Upload failed with error code: " . $errorCode;
        $uploadClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Avatar Upload</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .upload-box { max-width: 400px; padding: 20px; border: 1px solid #ddd; background: #fdfdfd; }
        .success { color: #155724; background-color: #d4edda; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 15px;}
        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15px;}
        button { margin-top: 15px; padding: 10px 15px; background: #007bff; color: white; border: none; cursor: pointer;}
    </style>
</head>
<body>
    <h2>Profile Avatar Upload</h2>
    <div class="upload-box">
        <?php if ($uploadMessage !== ''): ?>
            <div class="<?php echo $uploadClass; ?>">
                <?php echo htmlspecialchars($uploadMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <label for="avatar">Choose an image (JPG/PNG, Max 2MB):</label><br><br>
            <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg" required>
            <br>
            <button type="submit">Upload Securely</button>
        </form>
    </div>
</body>
</html>