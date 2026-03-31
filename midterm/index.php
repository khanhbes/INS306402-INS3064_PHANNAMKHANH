<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// index.php
require 'db.php';

// Lấy danh sách publisher để lọc
$pubStmt = $pdo->query("SELECT DISTINCT publisher FROM books WHERE publisher IS NOT NULL AND publisher != '' ORDER BY publisher");
$publishers = $pubStmt->fetchAll(PDO::FETCH_COLUMN);

// Lấy danh sách năm để lọc
$yearStmt = $pdo->query("SELECT DISTINCT publication_year FROM books WHERE publication_year IS NOT NULL ORDER BY publication_year DESC");
$years = $yearStmt->fetchAll(PDO::FETCH_COLUMN);

// Đọc tham số từ GET
$search = trim($_GET['search'] ?? '');
$filterPublisher = trim($_GET['publisher'] ?? '');
$filterYear = trim($_GET['year'] ?? '');
$filterStatus = trim($_GET['status'] ?? '');
$sortBy = $_GET['sort'] ?? 'id';
$sortDir = $_GET['dir'] ?? 'desc';

// Whitelist sort columns
$allowedSort = ['id', 'isbn', 'title', 'author', 'publisher', 'publication_year', 'available_copies', 'borrow_status'];
if (!in_array($sortBy, $allowedSort)) $sortBy = 'id';
$sortDir = strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC';

// Xây dựng query - JOIN với borrow_transactions để lấy status
$where = [];
$params = [];
$having = [];

if ($search !== '') {
    $where[] = "(b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
    $like = "%$search%";
    $params = array_merge($params, [$like, $like, $like]);
}
if ($filterPublisher !== '') {
    $where[] = "b.publisher = ?";
    $params[] = $filterPublisher;
}
if ($filterYear !== '') {
    $where[] = "b.publication_year = ?";
    $params[] = (int)$filterYear;
}

// Sort mapping cho borrow_status
$orderColumn = $sortBy;
if ($sortBy === 'borrow_status') {
    $orderColumn = "active_borrows";
} else {
    $orderColumn = "b.$sortBy";
}

$sql = "SELECT b.*, 
        COUNT(CASE WHEN bt.status = 'borrowed' THEN 1 END) AS active_borrows,
        COUNT(CASE WHEN bt.status = 'overdue' THEN 1 END) AS overdue_count,
        COUNT(CASE WHEN bt.status = 'returned' THEN 1 END) AS returned_count,
        GROUP_CONCAT(
            CASE WHEN bt.status IN ('borrowed', 'overdue') 
            THEN CONCAT(bt.borrower_name, '|', bt.status, '|', bt.due_date)
            END
            ORDER BY bt.due_date ASC SEPARATOR ';;'
        ) AS active_borrowers
        FROM books b
        LEFT JOIN borrow_transactions bt ON b.id = bt.book_id";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " GROUP BY b.id";

// Filter by status (after GROUP BY)
if ($filterStatus === 'available') {
    $sql .= " HAVING active_borrows = 0 AND overdue_count = 0";
} elseif ($filterStatus === 'borrowed') {
    $sql .= " HAVING active_borrows > 0";
} elseif ($filterStatus === 'overdue') {
    $sql .= " HAVING overdue_count > 0";
}

$sql .= " ORDER BY $orderColumn $sortDir";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

// Stats tổng (không filter)
$statsStmt = $pdo->query("SELECT 
    COUNT(DISTINCT b.id) AS total_books,
    SUM(b.available_copies) AS total_copies,
    COUNT(DISTINCT CASE WHEN bt_active.id IS NOT NULL THEN b.id END) AS books_borrowed,
    COUNT(DISTINCT CASE WHEN bt_overdue.id IS NOT NULL THEN b.id END) AS books_overdue
    FROM books b
    LEFT JOIN borrow_transactions bt_active ON b.id = bt_active.book_id AND bt_active.status = 'borrowed'
    LEFT JOIN borrow_transactions bt_overdue ON b.id = bt_overdue.book_id AND bt_overdue.status = 'overdue'
");
$stats = $statsStmt->fetch();

// Helper tạo URL sort
function sortUrl($column, $currentSort, $currentDir) {
    $params = $_GET;
    $params['sort'] = $column;
    $params['dir'] = ($currentSort === $column && $currentDir === 'ASC') ? 'desc' : 'asc';
    return '?' . http_build_query($params);
}
function sortIcon($column, $currentSort, $currentDir) {
    if ($currentSort !== $column) return ' <span style="color:#ccc;">&#8597;</span>';
    return $currentDir === 'ASC' ? ' &#9650;' : ' &#9660;';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; color: #333; }
        
        .navbar { background: linear-gradient(135deg, #1a237e, #283593); color: white; padding: 16px 30px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .navbar h1 { font-size: 22px; font-weight: 600; }
        .navbar .subtitle { font-size: 13px; opacity: 0.8; }
        
        .container { max-width: 1300px; margin: 0 auto; padding: 25px 20px; }
        
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 24px; margin-bottom: 20px; }
        
        /* Stats */
        .stats { display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
        .stat-box { flex: 1; min-width: 140px; background: white; border-radius: 10px; padding: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); text-align: center; }
        .stat-box .number { font-size: 28px; font-weight: 700; color: #1a237e; }
        .stat-box .label { font-size: 13px; color: #777; margin-top: 4px; }
        
        /* Filters */
        .filters { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; gap: 4px; }
        .filter-group label { font-size: 12px; font-weight: 600; color: #555; text-transform: uppercase; letter-spacing: 0.5px; }
        .filter-group input, .filter-group select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fafafa; transition: border-color 0.2s; }
        .filter-group input:focus, .filter-group select:focus { outline: none; border-color: #1a237e; background: white; }
        .filter-group input { width: 240px; }
        .filter-group select { min-width: 160px; }
        
        .btn { padding: 8px 18px; text-decoration: none; color: white; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s; }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-primary { background: #1a237e; }
        .btn-success { background: #2e7d32; }
        .btn-edit { background: #1565c0; padding: 5px 12px; font-size: 12px; }
        .btn-delete { background: #c62828; padding: 5px 12px; font-size: 12px; }
        .btn-clear { background: #757575; padding: 8px 14px; }
        .btn-filter { background: #1a237e; padding: 8px 18px; }
        
        .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        
        /* Table */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #f8f9fa; padding: 12px 14px; text-align: left; font-size: 13px; font-weight: 600; color: #555; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e0e0e0; white-space: nowrap; }
        thead th a { color: #555; text-decoration: none; }
        thead th a:hover { color: #1a237e; }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        tbody tr { transition: background 0.15s; }
        tbody tr:hover { background: #f5f7ff; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody tr:nth-child(even):hover { background: #f5f7ff; }
        
        .no-data { text-align: center; padding: 40px 20px; color: #999; font-size: 15px; }
        
        .actions-cell { white-space: nowrap; display: flex; gap: 6px; align-items: center; }
        .actions-cell form { display: inline; }
        
        .footer-info { display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #eee; color: #777; font-size: 13px; }
        
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-copies { background: #e8f5e9; color: #2e7d32; }
        .badge-low { background: #fff3e0; color: #e65100; }
        .badge-out { background: #ffebee; color: #c62828; }
        .badge-status { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-block; }
        .badge-available { background: #e8f5e9; color: #2e7d32; }
        .badge-borrowed { background: #e3f2fd; color: #1565c0; }
        .badge-overdue { background: #ffebee; color: #c62828; }
        
        .borrower-list { font-size: 12px; margin-top: 4px; }
        .borrower-item { padding: 3px 0; display: flex; align-items: center; gap: 6px; }
        .borrower-name { color: #333; }
        .borrower-due { color: #999; font-size: 11px; }
        .borrower-overdue { color: #c62828; font-weight: 600; }
        
        .tooltip-trigger { position: relative; cursor: pointer; }
        .tooltip-content { display: none; position: absolute; z-index: 10; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); min-width: 280px; left: 0; top: 100%; margin-top: 4px; }
        .tooltip-trigger:hover .tooltip-content { display: block; }
        .tooltip-header { font-weight: 600; font-size: 13px; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px solid #eee; color: #333; }

        .active-filters { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
        .active-filter-tag { background: #e8eaf6; color: #1a237e; padding: 4px 10px; border-radius: 20px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; }
        .active-filter-tag a { color: #c62828; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h1>Library Books Management</h1>
            <div class="subtitle">Manage your library collection</div>
        </div>
    </div>

    <div class="container">
        <!-- Stats -->
        <?php
            $totalBooks = $stats['total_books'];
            $totalCopies = $stats['total_copies'];
            $booksBorrowed = $stats['books_borrowed'];
            $booksOverdue = $stats['books_overdue'];
        ?>
        <div class="stats">
            <div class="stat-box">
                <div class="number"><?= $totalBooks ?></div>
                <div class="label">Total Titles</div>
            </div>
            <div class="stat-box">
                <div class="number"><?= $totalCopies ?></div>
                <div class="label">Total Copies</div>
            </div>
            <div class="stat-box">
                <div class="number" style="color:#1565c0;"><?= $booksBorrowed ?></div>
                <div class="label">Being Borrowed</div>
            </div>
            <div class="stat-box">
                <div class="number" style="color:#c62828;"><?= $booksOverdue ?></div>
                <div class="label">Overdue</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <form method="GET" class="filters">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Title, author, ISBN...">
                </div>
                <div class="filter-group">
                    <label>Publisher</label>
                    <select name="publisher">
                        <option value="">All Publishers</option>
                        <?php foreach ($publishers as $pub): ?>
                            <option value="<?= htmlspecialchars($pub) ?>" <?= $filterPublisher === $pub ? 'selected' : '' ?>><?= htmlspecialchars($pub) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Year</label>
                    <select name="year">
                        <option value="">All Years</option>
                        <?php foreach ($years as $y): ?>
                            <option value="<?= $y ?>" <?= $filterYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="available" <?= $filterStatus === 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="borrowed" <?= $filterStatus === 'borrowed' ? 'selected' : '' ?>>Borrowed</option>
                        <option value="overdue" <?= $filterStatus === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    </select>
                </div>
                <?php if (!empty($_GET['sort'])): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
                <?php endif; ?>
                <?php if (!empty($_GET['dir'])): ?>
                    <input type="hidden" name="dir" value="<?= htmlspecialchars($_GET['dir']) ?>">
                <?php endif; ?>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <div style="display:flex;gap:6px;">
                        <button type="submit" class="btn btn-filter">Filter</button>
                        <?php if ($search !== '' || $filterPublisher !== '' || $filterYear !== '' || $filterStatus !== ''): ?>
                            <a href="index.php" class="btn btn-clear">Clear</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
            <?php if ($search !== '' || $filterPublisher !== '' || $filterYear !== '' || $filterStatus !== ''): ?>
            <div class="active-filters">
                <?php if ($search !== ''): ?>
                    <span class="active-filter-tag">Search: "<?= htmlspecialchars($search) ?>"</span>
                <?php endif; ?>
                <?php if ($filterPublisher !== ''): ?>
                    <span class="active-filter-tag">Publisher: <?= htmlspecialchars($filterPublisher) ?></span>
                <?php endif; ?>
                <?php if ($filterYear !== ''): ?>
                    <span class="active-filter-tag">Year: <?= htmlspecialchars($filterYear) ?></span>
                <?php endif; ?>
                <?php if ($filterStatus !== ''): ?>
                    <span class="active-filter-tag">Status: <?= htmlspecialchars(ucfirst($filterStatus)) ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="top-actions" style="padding: 18px 24px 0;">
                <h3 style="font-size:16px; color:#333;">Book List</h3>
                <a href="create.php" class="btn btn-success">+ Add New Book</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><a href="<?= sortUrl('id', $sortBy, $sortDir) ?>">ID<?= sortIcon('id', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('isbn', $sortBy, $sortDir) ?>">ISBN<?= sortIcon('isbn', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('title', $sortBy, $sortDir) ?>">Title<?= sortIcon('title', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('author', $sortBy, $sortDir) ?>">Author<?= sortIcon('author', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('publisher', $sortBy, $sortDir) ?>">Publisher<?= sortIcon('publisher', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('publication_year', $sortBy, $sortDir) ?>">Year<?= sortIcon('publication_year', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('available_copies', $sortBy, $sortDir) ?>">Copies<?= sortIcon('available_copies', $sortBy, $sortDir) ?></a></th>
                            <th><a href="<?= sortUrl('borrow_status', $sortBy, $sortDir) ?>">Status<?= sortIcon('borrow_status', $sortBy, $sortDir) ?></a></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="9" class="no-data">No books found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= $book['id'] ?></td>
                            <td><code style="font-size:13px;"><?= htmlspecialchars($book['isbn']) ?></code></td>
                            <td><strong><?= htmlspecialchars($book['title']) ?></strong></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['publisher'] ?? '') ?></td>
                            <td><?= $book['publication_year'] ?? '-' ?></td>
                            <td>
                                <?php
                                    $copies = $book['available_copies'];
                                    $badgeClass = $copies == 0 ? 'badge-out' : ($copies <= 3 ? 'badge-low' : 'badge-copies');
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $copies ?></span>
                            </td>
                            <td>
                                <?php
                                    $activeBorrows = $book['active_borrows'];
                                    $overdueCount = $book['overdue_count'];
                                    $borrowers = $book['active_borrowers'];
                                    
                                    if ($overdueCount > 0) {
                                        $statusLabel = 'Overdue';
                                        $statusClass = 'badge-overdue';
                                    } elseif ($activeBorrows > 0) {
                                        $statusLabel = 'Borrowed';
                                        $statusClass = 'badge-borrowed';
                                    } else {
                                        $statusLabel = 'Available';
                                        $statusClass = 'badge-available';
                                    }
                                ?>
                                <?php if ($borrowers): ?>
                                <div class="tooltip-trigger">
                                    <span class="badge-status <?= $statusClass ?>"><?= $statusLabel ?> (<?= $activeBorrows + $overdueCount ?>)</span>
                                    <div class="tooltip-content">
                                        <div class="tooltip-header">Active Borrowers</div>
                                        <div class="borrower-list">
                                            <?php foreach (explode(';;', $borrowers) as $entry): 
                                                if (empty($entry)) continue;
                                                $parts = explode('|', $entry);
                                                $bName = $parts[0] ?? '';
                                                $bStatus = $parts[1] ?? '';
                                                $bDue = $parts[2] ?? '';
                                                $isOverdue = $bStatus === 'overdue';
                                            ?>
                                            <div class="borrower-item">
                                                <span class="borrower-name"><?= htmlspecialchars($bName) ?></span>
                                                <?php if ($isOverdue): ?>
                                                    <span class="badge-status badge-overdue" style="font-size:10px;padding:2px 6px;">OVERDUE</span>
                                                <?php endif; ?>
                                                <span class="<?= $isOverdue ? 'borrower-overdue' : 'borrower-due' ?>">Due: <?= htmlspecialchars($bDue) ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <span class="badge-status <?= $statusClass ?>"><?= $statusLabel ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="edit.php?id=<?= $book['id'] ?>" class="btn btn-edit">Edit</a>
                                    <form method="POST" action="delete.php" onsubmit="return confirm('Are you sure you want to delete &quot;<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>&quot;?');">
                                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="footer-info" style="padding: 12px 24px;">
                <span>Showing <?= count($books) ?> book(s)</span>
                <span>Borrowed: <?= $booksBorrowed ?> | Overdue: <?= $booksOverdue ?></span>
            </div>
        </div>
    </div>
</body>
</html>