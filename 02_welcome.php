<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INS3064 Welcome Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 600px;
            text-align: center;
        }
        h1 { color: #667eea; margin-bottom: 20px; }
        .info { background: #f0f0f0; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .info p { margin: 10px 0; text-align: left; }
        .label { font-weight: bold; color: #667eea; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to INS3064</h1>
        
        <?php
            // KHUVUC 1: Khai báo biến thông tin sinh viên
            // TODO: BẠN HÃY SỬA THÔNG TIN TRONG DẤU NHÁY DƯỚI ĐÂY THÀNH CỦA BẠN
            $name = "Phan Nam Khanh";          // Điền tên bạn
            $studentId = "22070980";         // Điền MSSV của bạn
            $class = "ICE2022A";         // Điền lớp của bạn
            $email = "22070980@vnu.edu.vn"; // Điền email trường
            
            // Lấy thời gian hiện tại (Múi giờ có thể cần chỉnh trong php.ini, nhưng tạm thời dùng mặc định)
            date_default_timezone_set('Asia/Ho_Chi_Minh'); // Thiết lập múi giờ Việt Nam
        ?>

        <div class="info">
            <p><span class="label">Name:</span> <?php echo $name; ?></p>
            <p><span class="label">Student ID:</span> <?php echo $studentId; ?></p>
            <p><span class="label">Class:</span> <?php echo $class; ?></p>
            <p><span class="label">Email:</span> <?php echo $email; ?></p>
            
            <p><span class="label">Date:</span> <?php echo date("l, F j, Y"); ?></p>
            
            <p><span class="label">Time:</span> <?php echo date("H:i:s"); ?></p>
        </div>

    </div>
</body>
</html>