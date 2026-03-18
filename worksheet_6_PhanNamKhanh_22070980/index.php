<?php
require_once 'Database.php';
$db = Database::getInstance()->getConnection();

// 1. Lấy dữ liệu cho thanh Dropdown (Thẻ Select)
$catStmt = $db->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll();

// 2. Nhận dữ liệu người dùng gửi lên từ form (qua phương thức GET)
$searchName = $_GET['search'] ?? '';
$filterCategory = $_GET['category'] ?? '';

// 3. Xây dựng câu SQL động dựa trên việc người dùng có tìm kiếm hay không
$query = "SELECT p.id, p.name, p.price, p.stock, c.category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE 1=1"; // WHERE 1=1 là một mẹo nhỏ để nối các chữ 'AND' phía sau dễ dàng hơn

$params = []; // Mảng chứa các giá trị thực tế

// Nếu người dùng nhập tên vào ô tìm kiếm
if (!empty($searchName)) {
    $query .= " AND p.name LIKE :searchName";
    $params[':searchName'] = '%' . $searchName . '%'; // Cặp ký tự % đại diện cho "chứa từ khóa này"
}

// Nếu người dùng chọn một danh mục cụ thể
if (!empty($filterCategory)) {
    $query .= " AND p.category_id = :categoryId";
    $params[':categoryId'] = $filterCategory;
}

// 4. Thực thi truy vấn với tham số an toàn
$stmt = $db->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .low-stock { background-color: #ffdddd; color: #d00; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <h2>Bảng Điều Khiển Sản Phẩm</h2>
    
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Nhập tên sản phẩm..." value="<?= htmlspecialchars($searchName) ?>">
        
        <select name="category">
            <option value="">-- Tất cả --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filterCategory == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Tìm kiếm / Lọc</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Tên</th><th>Giá</th><th>Danh mục</th><th>Tồn kho</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $prod): ?>
                <?php $isLowStock = $prod['stock'] < 10; ?>
                
                <tr class="<?= $isLowStock ? 'low-stock' : '' ?>">
                    <td><?= $prod['id'] ?></td>
                    <td><?= htmlspecialchars($prod['name']) ?></td>
                    <td>$<?= number_format($prod['price']) ?></td>
                    <td><?= htmlspecialchars($prod['category_name'] ?? 'Chưa phân loại') ?></td>
                    <td><?= $prod['stock'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>