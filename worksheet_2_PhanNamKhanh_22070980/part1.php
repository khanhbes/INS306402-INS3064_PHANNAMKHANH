<?php
// Part 1 - Trình bày đề bài và lời giải cho giảng viên dễ kiểm tra
// Bật lỗi để dễ debug khi thầy xem
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Worksheet 2 - Part 1 (Phan Nam Khanh)</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; margin: 20px; color:#222 }
    header { margin-bottom: 18px }
    h1 { color:#2c3e50 }
    .exercise { border:1px solid #e1e1e1; padding:12px; border-radius:6px; margin-bottom:12px; background:#fbfbfb }
    .prompt { background:#fff; border-left:4px solid #3498db; padding:8px; margin-bottom:8px }
    .code { background:#f5f5f5; padding:8px; border-radius:4px; overflow:auto }
    .result { margin-top:8px; padding:8px; background:#fff; border-radius:4px; border:1px dashed #ccc }
    .label { font-weight:700; color:#444 }
    a.button { display:inline-block; margin-top:8px; padding:8px 12px; background:#3498db; color:#fff; text-decoration:none; border-radius:4px }
  </style>
</head>
<body>
  <header>
    <h1>Worksheet 2 — Part 1</h1>
    <p><strong>Sinh viên:</strong> Phan Nam Khanh | <strong>MSSV:</strong> 22070980</p>
    <p><a href="/INS306402-INS3064_PHANNAMKHANH/index.php" class="button">Quay về trang tổng hợp</a></p>
  </header>

  <section class="exercise">
    <h2>01 Hello Strings</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Tạo biến <code>$name</code>, <code>$city</code>. Nối chuỗi để in ra câu.</div>
    <div class="code"><pre>$name = "Alice";
$city = "Paris";
echo $name . " lives in " . $city . ".";</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $name = "Alice"; $city = "Paris"; echo htmlspecialchars("$name lives in $city."); ?>
    </div>
  </section>

  <section class="exercise">
    <h2>02 Math Ops</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Cho <code>$x = 10</code>, <code>$y = 5</code>. In kết quả cộng, trừ, nhân, chia.</div>
    <div class="code"><pre>$x = 10; $y = 5;
echo ($x + $y) . ", " . ($x - $y) . ", " . ($x * $y) . ", " . ($x / $y);</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $x = 10; $y = 5; echo ($x + $y) . ", " . ($x - $y) . ", " . ($x * $y) . ", " . ($x / $y); ?>
    </div>
  </section>

  <section class="exercise">
    <h2>03 Casting</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Chuyển chuỗi <code>'25.50'</code> -> float -> int. In ra kiểu dữ liệu dùng <code>gettype()</code>.</div>
    <div class="code"><pre>$numberString = '25.50';
$floatVal = (float)$numberString;
$intVal = (int)$floatVal;
echo gettype($floatVal) . "($floatVal), " . gettype($intVal) . "($intVal)";</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $numberString = '25.50'; $floatVal = (float)$numberString; $intVal = (int)$floatVal; echo gettype($floatVal) . "($floatVal), " . gettype($intVal) . "($intVal)"; ?>
    </div>
  </section>

  <section class="exercise">
    <h2>04 Truthiness</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Cho <code>$isOnline = true</code>. Dùng toán tử 3 ngôi để in trạng thái.</div>
    <div class="code"><pre>$isOnline = true;
echo $isOnline ? "User is Online" : "User is Offline";</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $isOnline = true; echo $isOnline ? "User is Online" : "User is Offline"; ?>
    </div>
  </section>

  <section class="exercise">
    <h2>05 Array Init</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Tạo mảng chỉ mục gồm 3 loại quả. In phần tử thứ 2.</div>
    <div class="code"><pre>$fruits = ["Apple", "Banana", "Pear"];
echo $fruits[1];</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $fruits = ["Apple", "Banana", "Pear"]; echo $fruits[1]; ?>
    </div>
  </section>

  <section class="exercise">
    <h2>06 Sentence Builder</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Dùng toán tử gán (.=) để nối 3 từ vào biến <code>$sentence</code>.</div>
    <div class="code"><pre>$sentence = "PHP";
$sentence .= " is";
$sentence .= " fun";
echo $sentence;</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $sentence = "PHP"; $sentence .= " is"; $sentence .= " fun"; echo $sentence; ?>
    </div>
  </section>

  <section class="exercise">
    <h2>07 Strict Check</h2>
    <div class="prompt"><span class="label">Đề bài:</span> So sánh <code>5</code> và <code>'5'</code> dùng <code>==</code> và <code>===</code>. Giải thích kết quả.</div>
    <div class="code"><pre>$num = 5; $str = '5';
$loose = ($num == $str) ? "Equal (True)" : "Equal (False)";
$strict = ($num === $str) ? "Identical (True)" : "Identical (False)";
echo "$loose, $strict";</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $num = 5; $str = '5'; $loose = ($num == $str) ? "Equal (True)" : "Equal (False)"; $strict = ($num === $str) ? "Identical (True)" : "Identical (False)"; echo "$loose, $strict"; ?>
      <div style="margin-top:8px; font-size:0.9em; color:#555">Giải thích: <em>==</em> so sánh lỏng (chỉ so sánh giá trị, không so sánh kiểu), còn <em>===</em> so sánh chặt (so sánh cả giá trị và kiểu).</div>
    </div>
  </section>

  <section class="exercise">
    <h2>08 Logic Gate</h2>
    <div class="prompt"><span class="label">Đề bài:</span> Kiểm tra nếu <code>$age &gt; 18</code> và <code>$hasTicket</code> đều đúng thì in "Enter" else in "Deny".</div>
    <div class="code"><pre>$age = 20; $hasTicket = true;
if ($age > 18 && $hasTicket) { echo "Enter"; } else { echo "Deny"; }</pre></div>
    <div class="result"><span class="label">Lời giải — Kết quả:</span>
      <?php $age = 20; $hasTicket = true; echo ($age > 18 && $hasTicket) ? 'Enter' : 'Deny'; ?>
    </div>
  </section>

  <footer style="margin-top:20px; color:#666; font-size:0.9em"> <code>part1.php</code>.</footer>
</body>
</html>