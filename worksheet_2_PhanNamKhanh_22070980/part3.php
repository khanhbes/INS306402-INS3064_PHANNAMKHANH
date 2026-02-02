<?php
// Part 3 - Trình bày giống phong cách Part 1/2
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Worksheet 2 - Part 3 (Phan Nam Khanh)</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; margin: 20px; color:#222; max-width:980px }
    header { margin-bottom: 18px }
    h1 { color:#2c3e50 }
    .grid-container { display:block; gap:18px }
    .exercise { border:1px solid #e1e1e1; padding:12px; border-radius:6px; background:#fbfbfb; margin-bottom:12px }
    .prompt { background:#fff; border-left:4px solid #27ae60; padding:8px; margin-bottom:8px }
    .code { background:#f5f5f5; padding:8px; border-radius:4px; overflow:auto; font-family:monospace }
    .result { margin-top:8px; padding:8px; background:#fff; border-radius:4px; border:1px dashed #ccc; font-family:monospace }
    .label { font-weight:700; color:#444 }
    .badge { background:#f1c40f; color:#333; padding:2px 8px; border-radius:4px; font-size:0.8em }
    a.button { display:inline-block; margin-top:8px; padding:8px 12px; background:#3498db; color:#fff; text-decoration:none; border-radius:4px }
    @media (max-width:700px) { .grid-container { grid-template-columns:1fr } }
  </style>
</head>
<body>
  <header>
    <h1>Worksheet 2 — Part 3</h1>
    <p><strong>Sinh viên:</strong> Phan Nam Khanh | <strong>MSSV:</strong> 22070980</p>
    <p><a href="/INS306402-INS3064_PHANNAMKHANH/index.php" class="button">Quay về trang chủ</a></p>
  </header>

  <div class="grid-container">

    <section class="exercise">
      <h2>01. Greeter <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Tạo hàm trả về lời chào. Signature: <code>greet(string $name): string</code></div>
      <div class="code"><pre>function greet(string $name): string { return "Hello, " . $name . "!"; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function greet(string $name): string { return "Hello, " . $name . "!"; } echo greet("Sam"); ?>
      </div>
    </section>

    <section class="exercise">
      <h2>02. Area Calc <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Tính diện tích hình chữ nhật. Signature: <code>area(float $w, float $h): float</code></div>
      <div class="code"><pre>function area(float $w, float $h): float { return $w * $h; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function area(float $w, float $h): float { return $w * $h; } echo area(5.5, 2); ?>
      </div>
    </section>

    <section class="exercise">
      <h2>03. Adult Check <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Kiểm tra tuổi. Cho phép null. Signature: <code>isAdult(?int $age): bool</code></div>
      <div class="code"><pre>function isAdult(?int $age): bool { if ($age === null) return false; return $age >= 18; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function isAdult(?int $age): bool { if ($age === null) return false; return $age >= 18; } echo isAdult(null) ? 'True' : 'False'; ?>
      </div>
    </section>

    <section class="exercise">
      <h2>04. Safe Divide <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Chia 2 số; trả về <code>null</code> nếu mẫu số là 0. Signature: <code>safeDiv(float $a, float $b): ?float</code></div>
      <div class="code"><pre>function safeDiv(float $a, float $b): ?float { if ($b == 0) return null; return $a / $b; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function safeDiv(float $a, float $b): ?float { if ($b == 0) return null; return $a / $b; } $res = safeDiv(10, 0); echo $res === null ? 'null' : $res; ?>
      </div>
    </section>

    <section class="exercise">
      <h2>05. Formatter <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Định dạng tiền tệ với tham số mặc định. Signature: <code>fmt(float $amt, string $c='$'): string</code></div>
      <div class="code"><pre>function fmt(float $amt, string $c = '$'): string { return $c . number_format($amt, 2); }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function fmt(float $amt, string $c = '$'): string { return $c . number_format($amt, 2); } echo fmt(50); ?>
      </div>
    </section>

    <section class="exercise">
      <h2>06. Pure Math <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Hàm thuần tuý (không echo bên trong). Signature: <code>add(int $a, int $b): int</code></div>
      <div class="code"><pre>function add(int $a, int $b): int { return $a + $b; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function add(int $a, int $b): int { return $a + $b; } echo 'Sum of inputs: ' . add(5, 10); ?>
      </div>
    </section>

  </div>

  <footer style="margin-top:18px;color:#666;font-size:0.9em">Trang được trình bày để thầy dễ kiểm tra — mở file <code>part3.php</code> để xem mã nguồn.</footer>
</body>
</html>