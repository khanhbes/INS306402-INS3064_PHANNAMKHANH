<?php
// Homepage: list all worksheet folders and files as direct links for teacher review
$root = __DIR__;
$wsDirs = glob($root . DIRECTORY_SEPARATOR . 'worksheet_*', GLOB_ONLYDIR);
function webpath($path) {
    global $root;
    $rel = str_replace($root, '', $path);
    $rel = ltrim(str_replace('\\', '/', $rel), '/');
    return $rel;
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>INS3064 - Worksheets (Phan Nam Khanh)</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;margin:24px;color:#222}
    h1{color:#2c3e50}
    ul{list-style:none;padding:0}
    li{margin:6px 0}
    a{color:#0066cc;text-decoration:none}
    .ws{border:1px solid #e6e6e6;padding:12px;border-radius:6px;margin-bottom:12px;background:#fbfbfb}
    .meta{color:#666;font-size:0.95em}
  </style>
</head>
<body>
  <h1>INS3064 — Worksheets</h1>
  <p class="meta">Danh sách các thư mục worksheet trong project. Nhấn vào tên file để mở bài kiểm tra tương ứng.</p>

  <?php if (empty($wsDirs)): ?>
    <p>Không tìm thấy thư mục <code>worksheet_*</code>. Hãy chắc chắn bạn đang đặt các worksheet trong thư mục gốc của project.</p>
  <?php else: ?>
    <?php foreach ($wsDirs as $dir): ?>
      <div class="ws">
        <h2><?php echo htmlspecialchars(basename($dir)); ?></h2>
        <?php
          $files = glob($dir . DIRECTORY_SEPARATOR . '*');
          $phpFiles = array_filter($files, function($f){ return is_file($f) && preg_match('/\.(php|html|htm|txt)$/i', $f); });
        ?>
        <?php if (empty($phpFiles)): ?>
          <div class="meta">(Không có file hiển thị) — hãy thêm file .php hoặc .txt vào thư mục này.</div>
        <?php else: ?>
          <ul>
          <?php foreach ($phpFiles as $f): ?>
            <li><a href="<?php echo htmlspecialchars(webpath($f)); ?>" target="_blank"><?php echo htmlspecialchars(basename($f)); ?></a></li>
          <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <footer style="margin-top:18px;color:#666;font-size:0.9em">Khanhbes</footer>
</body>
</html>