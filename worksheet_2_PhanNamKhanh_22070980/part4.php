<?php
// Part 4 - Trình bày giống phong cách Part 1/2/3 (Hard level)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Worksheet 2 - Part 4 (Phan Nam Khanh)</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; margin: 20px; color:#222; max-width:980px }
    header { margin-bottom: 18px }
    h1 { color:#c0392b }
    .grid-container { display:block; gap:18px }
    .exercise { border:1px solid #e1e1e1; padding:12px; border-radius:6px; background:#fbfbfb; margin-bottom:12px }
    .prompt { background:#fff; border-left:4px solid #c0392b; padding:8px; margin-bottom:8px }
    .code { background:#f5f5f5; padding:8px; border-radius:4px; overflow:auto; font-family:monospace }
    .result { margin-top:8px; padding:8px; background:#fff; border-radius:4px; border:1px dashed #ccc; font-family:monospace }
    .label { font-weight:700; color:#444 }
    .badge { background:#c0392b; color:#fff; padding:2px 8px; border-radius:4px; font-size:0.8em }
    table { width: 100%; border-collapse: collapse; margin-top: 10px }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left }
    th { background: #f4f4f4 }
    a.button { display:inline-block; margin-top:8px; padding:8px 12px; background:#3498db; color:#fff; text-decoration:none; border-radius:4px }
    @media (max-width:700px) { .grid-container { grid-template-columns:1fr } }
  </style>
</head>
<body>
  <header>
    <h1>Worksheet 2 — Part 4 (Hard)</h1>
    <p><strong>Sinh viên:</strong> Phan Nam Khanh | <strong>MSSV:</strong> 22070980</p>
    <p><a href="/INS306402-INS3064_PHANNAMKHANH/index.php" class="button">Quay về trang chủ</a></p>
  </header>

  <div class="grid-container">

    <section class="exercise">
      <h2>01. BMI Calculator <span class="badge">Hard</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Tính BMI = kg / (m * m). Phân loại: &lt;18.5 Under, 18.5-24.9 Normal, 25+ Over.</div>
      <div class="code"><pre>function calculateBMI($kg, $m) {
  $bmi = $kg / ($m * $m);
  if ($bmi < 18.5) return 'Underweight';
  if ($bmi <= 24.9) return 'Normal';
  return 'Overweight';
}
// Test: calculateBMI(70,1.75)</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function calculateBMI($kg, $m) { $bmi = $kg / ($m * $m); if ($bmi < 18.5) { $cat='Underweight'; } elseif ($bmi <= 24.9) { $cat='Normal'; } else { $cat='Overweight'; } return 'BMI: ' . number_format($bmi,1) . " ($cat)"; } echo calculateBMI(70,1.75); ?>
      </div>
    </section>

    <section class="exercise">
      <h2>02. Student List <span class="badge">Hard</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Hiển thị mảng đa chiều danh sách sinh viên dưới dạng bảng HTML.</div>
      <div class="code"><pre>$students = [ ['name'=>'Alice','grade'=>85], ['name'=>'Bob','grade'=>92] ];
// In thành table</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $students = [['name'=>'Alice','grade'=>85], ['name'=>'Bob','grade'=>92], ['name'=>'Charlie','grade'=>78]]; ?>
        <table>
          <tr><th>Name</th><th>Grade</th></tr>
          <?php foreach($students as $s): ?>
            <tr><td><?php echo htmlspecialchars($s['name']); ?></td><td><?php echo htmlspecialchars($s['grade']); ?></td></tr>
          <?php endforeach; ?>
        </table>
      </div>
    </section>

    <section class="exercise">
      <h2>03. Prime Seeker <span class="badge">Hard</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Tìm tất cả số nguyên tố từ 1 đến 100. Yêu cầu hàm <code>isPrime(int $n): bool</code>.</div>
      <div class="code"><pre>function isPrime(int $n): bool { if ($n < 2) return false; for($i=2;$i<=sqrt($n);$i++){ if($n%$i==0) return false; } return true; }</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php function isPrime(int $n): bool { if ($n < 2) return false; for ($i = 2; $i <= sqrt($n); $i++) { if ($n % $i == 0) return false; } return true; } $primes = []; for ($i = 1; $i <= 100; $i++) { if (isPrime($i)) $primes[] = $i; } echo implode(', ', $primes); ?>
      </div>
    </section>

    <section class="exercise">
      <h2>04. Scoreboard <span class="badge">Hard</span></h2>
      <div class="prompt"><span class="label">Đề bài:</span> Xử lý mảng điểm số: tính Max, Min, Average, và lọc các điểm > Average.</div>
      <div class="code"><pre>$scores=[45,80,90,60,75,100,55];
$max=max($scores); $min=min($scores); $avg=array_sum($scores)/count($scores);</pre></div>
      <div class="result"><span class="label">Lời giải — Kết quả:</span>
        <?php $scores=[45,80,90,60,75,100,55]; $max=max($scores); $min=min($scores); $avg=array_sum($scores)/count($scores); $top=array_filter($scores,function($s)use($avg){return $s>$avg;}); echo "Max: $max | Min: $min<br>"; echo "Average: " . number_format($avg,1) . "<br>"; echo "Top Performers: [".implode(', ',$top)."]"; ?>
      </div>
    </section>

  </div>

  <footer style="margin-top:18px;color:#666;font-size:0.9em">mở file <code>part4.php</code> để xem mã nguồn.</footer>
</body>
</html>