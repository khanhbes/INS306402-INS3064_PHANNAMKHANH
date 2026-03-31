<?php
// create.php
require 'db.php';

$error = '';
$isbn = '';
$title = '';
$author = '';
$publisher = '';
$year = '';
$copies = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = trim($_POST['isbn']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $year = !empty($_POST['publication_year']) ? (int)$_POST['publication_year'] : null;
    $copies = (int)$_POST['available_copies'];

    // Validate Required Fields
    if (empty($isbn) || empty($title) || empty($author) || $copies < 0) {
        $error = "Vui lòng nhập đầy đủ các trường bắt buộc (ISBN, Title, Author, Copies) và Copies phải >= 0.";
    } else {
        try {
            $sql = "INSERT INTO books (isbn, title, author, publisher, publication_year, available_copies) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$isbn, $title, $author, $publisher, $year, $copies]);
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
    <title>Add Book</title>
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
        .btn-save { background: #2e7d32; }
        .btn-cancel { background: #757575; }
        .form-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #eee; }
        .required-note { font-size: 12px; color: #999; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1>Library Books Management</h1>
            <div class="subtitle">Add a new book to the collection</div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <h2>Add New Book</h2>
            <p class="required-note">Fields marked with (*) are required.</p>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>ISBN *</label>
                    <input type="text" name="isbn" value="<?= htmlspecialchars($isbn) ?>" required placeholder="e.g. 978-0134685991">
                </div>
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required placeholder="Book title">
                </div>
                <div class="form-group">
                    <label>Author *</label>
                    <input type="text" name="author" value="<?= htmlspecialchars($author) ?>" required placeholder="Author name">
                </div>
                <div class="form-group">
                    <label>Publisher</label>
                    <input type="text" name="publisher" value="<?= htmlspecialchars($publisher) ?>" placeholder="Publisher name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Publication Year</label>
                        <input type="number" name="publication_year" value="<?= htmlspecialchars($year) ?>" placeholder="e.g. 2024" min="1900" max="2099">
                    </div>
                    <div class="form-group">
                        <label>Available Copies *</label>
                        <input type="number" name="available_copies" value="<?= htmlspecialchars($copies) ?>" required min="0" placeholder="0">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-save">Save Book</button>
                    <a href="index.php" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>