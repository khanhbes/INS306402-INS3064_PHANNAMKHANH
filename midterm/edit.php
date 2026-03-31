<?php
// edit.php
require 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    die("Không tìm thấy sách.");
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book['isbn'] = trim($_POST['isbn']);
    $book['title'] = trim($_POST['title']);
    $book['author'] = trim($_POST['author']);
    $book['publisher'] = trim($_POST['publisher']);
    $book['publication_year'] = !empty($_POST['publication_year']) ? (int)$_POST['publication_year'] : null;
    $book['available_copies'] = (int)$_POST['available_copies'];

    if (empty($book['isbn']) || empty($book['title']) || empty($book['author']) || $book['available_copies'] < 0) {
        $error = "Vui lòng nhập đầy đủ trường bắt buộc.";
    } else {
        try {
            $sql = "UPDATE books SET isbn=?, title=?, author=?, publisher=?, publication_year=?, available_copies=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$book['isbn'], $book['title'], $book['author'], $book['publisher'], $book['publication_year'], $book['available_copies'], $id]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Mã ISBN này đã tồn tại trong hệ thống.";
            } else {
                $error = "Lỗi hệ thống: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; color: #333; }
        .navbar { background: linear-gradient(135deg, #1a237e, #283593); color: white; padding: 16px 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .navbar h1 { font-size: 22px; font-weight: 600; }
        .navbar .subtitle { font-size: 13px; opacity: 0.8; }
        .container { max-width: 700px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 30px; }
        .card h2 { font-size: 20px; margin-bottom: 20px; color: #1a237e; border-bottom: 2px solid #e8eaf6; padding-bottom: 12px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fafafa; transition: border-color 0.2s; }
        .form-group input:focus { outline: none; border-color: #1a237e; background: white; }
        .form-row { display: flex; gap: 16px; }
        .form-row .form-group { flex: 1; }
        .error { background: #ffebee; color: #c62828; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #c62828; }
        .btn { padding: 10px 24px; text-decoration: none; color: white; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-save { background: #1565c0; }
        .btn-cancel { background: #757575; }
        .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #eee; }
        .required-note { font-size: 12px; color: #999; margin-bottom: 16px; }
        .book-id { font-size: 13px; color: #999; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1>Library Books Management</h1>
            <div class="subtitle">Edit book information</div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <h2>Edit Book</h2>
            <p class="book-id">Book ID: #<?= $book['id'] ?></p>
            <p class="required-note">Fields marked with (*) are required.</p>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>ISBN *</label>
                    <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>" required placeholder="e.g. 978-0134685991">
                </div>
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required placeholder="Book title">
                </div>
                <div class="form-group">
                    <label>Author *</label>
                    <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required placeholder="Author name">
                </div>
                <div class="form-group">
                    <label>Publisher</label>
                    <input type="text" name="publisher" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>" placeholder="Publisher name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Publication Year</label>
                        <input type="number" name="publication_year" value="<?= htmlspecialchars($book['publication_year'] ?? '') ?>" placeholder="e.g. 2024" min="1900" max="2099">
                    </div>
                    <div class="form-group">
                        <label>Available Copies *</label>
                        <input type="number" name="available_copies" value="<?= htmlspecialchars($book['available_copies']) ?>" required min="0" placeholder="0">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-save">Update Book</button>
                    <a href="index.php" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>