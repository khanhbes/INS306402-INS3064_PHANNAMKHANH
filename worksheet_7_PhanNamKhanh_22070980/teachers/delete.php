<?php
require_once __DIR__ . '/../classes/Database.php';
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    Database::getInstance()->delete('teachers', 'id = ?', [$id]);
}
header('Location: index.php');
exit;