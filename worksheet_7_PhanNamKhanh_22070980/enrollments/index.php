<?php
// enrollments/index.php

require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

// 1. Xử lý Lọc (Filter)
$filter_course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0;
$whereClauses = [];
$params = [];

if ($filter_course_id > 0) {
    $whereClauses[] = "e.course_id = ?";
    $params[] = $filter_course_id;
}

$whereSQL = "";
if (!empty($whereClauses)) {
    $whereSQL = "WHERE " . implode(" AND ", $whereClauses);
}

// 2. Xử lý Phân trang (Pagination)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Đếm tổng số bản ghi để tính số trang
$countSql = "SELECT COUNT(*) as total FROM enrollments e $whereSQL";
$totalRecords = $db->fetch($countSql, $params)['total'];
$totalPages = ceil($totalRecords / $limit);

// 3. Query lấy dữ liệu (JOIN)
$sql = "SELECT e.id, s.name AS student_name, s.email, c.title AS course_title, e.enrolled_at
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        JOIN courses  c ON e.course_id  = c.id
        $whereSQL
        ORDER BY e.enrolled_at DESC
        LIMIT $limit OFFSET $offset";

$enrollments = $db->fetchAll($sql, $params);

// Lấy danh sách khóa học để làm dropdown lọc
$courses = $db->fetchAll("SELECT id, title FROM courses ORDER BY title");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đăng ký học</title>
</head>
<body>
<h1>Danh sách đăng ký học</h1>

<form method="get" style="margin-bottom: 20px;">
    <label>Lọc theo khóa học:</label>
    <select name="course_id">
        <option value="0">-- Tất cả khóa học --</option>
        <?php foreach ($courses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($filter_course_id === $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['title']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Lọc</button>
    <a href="index.php">Xóa lọc</a>
</form>

<p><a href="create.php">+ Thêm đăng ký</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Sinh viên</th>
        <th>Khóa học</th>
        <th>Thời gian</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($enrollments as $enroll): ?>
        <tr>
            <td><?= $enroll['id'] ?></td>
            <td><?= htmlspecialchars($enroll['student_name']) ?><br><small><?= htmlspecialchars($enroll['email']) ?></small></td>
            <td><?= htmlspecialchars($enroll['course_title']) ?></td>
            <td><?= $enroll['enrolled_at'] ?></td>
            <td><a href="delete.php?id=<?= $enroll['id'] ?>" onclick="return confirm('Hủy đăng ký này?');">Xóa</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($totalPages > 1): ?>
    <div style="margin-top: 20px;">
        Trang: 
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&course_id=<?= $filter_course_id ?>" style="padding: 5px; <?= ($page === $i) ? 'font-weight:bold; background:#eee;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

</body>
</html>