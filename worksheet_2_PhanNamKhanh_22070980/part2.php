<?php
// Part 2 - Trình bày giống phong cách Part 1
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Worksheet 2 - Part 2 (Phan Nam Khanh)</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; margin: 20px; color:#222; max-width:980px }
    header { margin-bottom: 18px }
    h1 { color:#2c3e50 }
    .grid-container { display:block; gap:18px }
    .exercise { border:1px solid #e1e1e1; padding:12px; border-radius:6px; background:#fbfbfb; margin-bottom:12px }
    .prompt { background:#fff; border-left:4px solid #3498db; padding:8px; margin-bottom:8px }
    .code { background:#f5f5f5; padding:8px; border-radius:4px; overflow:auto; font-family:monospace }
    .result { margin-top:8px; padding:8px; background:#fff; border-radius:4px; border:1px dashed #ccc; font-family:monospace }
    .label { font-weight:700; color:#444 }
    .badge { background:#f1c40f; color:#333; padding:2px 8px; border-radius:4px; font-size:0.8em }
    /* Single-column layout — exercises stacked vertically */
    a.button { display:inline-block; margin-top:8px; padding:8px 12px; background:#3498db; color:#fff; text-decoration:none; border-radius:4px }
  </style>
</head>
<body>
  <header>
    <h1>Worksheet 2 — Part 2</h1>
    <p><strong>Sinh viên:</strong> Phan Nam Khanh | <strong>MSSV:</strong> 22070980</p>
    <p><a href="/INS306402-INS3064_PHANNAMKHANH/index.php" class="button">Quay về trang chủ</a></p>
  </header>

  <div class="grid-container">

    <section class="exercise">
      <h2>01. Grade Bot <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Dùng if/else để xếp loại điểm số (0–100): >=90 A, >=80 B, >=70 C, khác F.</div>
      <div class="code"><pre>$score = 85;
if ($score >= 90) { echo 'A'; } elseif ($score >= 80) { echo 'B'; } elseif ($score >= 70){ echo 'C'; } else { echo 'F'; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $score = 85; if ($score >= 90) { echo 'Grade: A'; } elseif ($score >= 80) { echo 'Grade: B'; } elseif ($score >= 70){ echo 'Grade: C'; } else { echo 'Grade: F'; } ?>
      </div>
    </section>

    <section class="exercise">
      <h2>02. Day Planner <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Dùng switch case: 1–7 chuyển thành Monday–Sunday, default in 'Invalid'.</div>
      <div class="code"><pre>$day = 3;
switch ($day) { case 1: echo 'Monday'; break; /* ... */ }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $day = 3; switch ($day) { case 1: echo 'Monday'; break; case 2: echo 'Tuesday'; break; case 3: echo 'Wednesday'; break; case 4: echo 'Thursday'; break; case 5: echo 'Friday'; break; case 6: echo 'Saturday'; break; case 7: echo 'Sunday'; break; default: echo 'Invalid'; } ?>
      </div>
    </section>

    <section class="exercise">
      <h2>03. Multi-Table <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Dùng vòng lặp lồng nhau tạo bảng cửu chương 1–5 (5x5).</div>
      <div class="code"><pre>for ($i=1;$i<=5;$i++) { for ($j=1;$j<=5;$j++) { echo $i*$j.' '; } echo "\n"; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php for ($i = 1; $i <= 5; $i++) { for ($j = 1; $j <= 5; $j++) { echo ($i * $j) . " "; } echo "<br>"; } ?>
      </div>
    </section>

    <section class="exercise">
      <h2>04. Cart Total <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Dùng foreach tính tổng các phần tử trong mảng giá.</div>
      <div class="code"><pre>$prices=[10,20,5]; $total=0; foreach($prices as $p){ $total+=$p; } echo $total;</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $prices = [10,20,5]; $total = 0; foreach ($prices as $price) { $total += $price; } echo 'Total: ' . $total; ?>
      </div>
    </section>

    <section class="exercise">
      <h2>05. Countdown <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Dùng while loop đếm ngược từ 10 về 1 rồi in 'Liftoff!'.</div>
      <div class="code"><pre>$c=10; while($c>=1){ echo $c.','; $c--; } echo 'Liftoff!';</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $count = 10; while ($count >= 1) { echo $count . ', '; $count--; } echo 'Liftoff!'; ?>
      </div>
    </section>

    <section class="exercise">
      <h2>06. Even Filter <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Dùng for (1–20) chỉ in số chẵn.</div>
      <div class="code"><pre>for($i=1;$i<=20;$i++){ if($i%2==0) echo $i.' '; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php for ($i = 1; $i <= 20; $i++) { if ($i % 2 == 0) echo $i . ' '; } ?>
      </div>
    </section>

    <section class="exercise">
      <h2>07. Array Reverse <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Đảo mảng thủ công sang mảng mới (không dùng array_reverse).</div>
      <div class="code"><pre>$orig=[1,2,3]; $rev=[]; for($i=count($orig)-1;$i>=0;$i--){ $rev[]=$orig[$i]; } echo '['.implode(', ',$rev).']';</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $original = [1,2,3]; $reversed = []; for ($i = count($original)-1; $i >= 0; $i--) { $reversed[] = $original[$i]; } echo '[' . implode(', ', $reversed) . ']'; ?>
      </div>
    </section>

    <section class="exercise">
      <h2>08. FizzBuzz <span class="badge">Medium</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Loop 1–50. Chia hết cho 3 => 'Fizz', 5 => 'Buzz', cả hai => 'FizzBuzz'.</div>
      <div class="code"><pre>for($i=1;$i<=50;$i++){ /* logic */ }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php for ($i = 1; $i <= 50; $i++) { if ($i % 3 == 0 && $i % 5 == 0) { echo "FizzBuzz, "; } elseif ($i % 3 == 0) { echo "Fizz, "; } elseif ($i % 5 == 0) { echo "Buzz, "; } else { echo $i . ", "; } } echo '...'; ?>
      </div>
    </section>

  </div>

  <footer style="margin-top:18px;color:#666;font-size:0.9em"> — mở file <code>part2.php</code> để xem mã nguồn.</footer>
</body>
</html>