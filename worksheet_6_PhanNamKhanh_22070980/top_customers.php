<?php
// 1. Kéo file Database.php vào để dùng
require_once 'Database.php';

// 2. Lấy kết nối
$db = Database::getInstance()->getConnection();

// 3. Viết và thực thi SQL
$sql = "SELECT u.name, u.email, SUM(o.total_amount) AS total_spent
        FROM users u
        JOIN orders o ON u.id = o.user_id
        GROUP BY u.id
        ORDER BY total_spent DESC
        LIMIT 3";
$stmt = $db->query($sql);

// 4. Lấy toàn bộ kết quả trả về dạng mảng
$top_customers = $stmt->fetchAll();
?>

<h2>Top 3 Khách Hàng VIP</h2>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Total Spent</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($top_customers as $customer): ?>
            <tr>
                <td><?= htmlspecialchars($customer['name']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td>$<?= htmlspecialchars(number_format($customer['total_spent'], 2)) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>