<?php
$db = 'latihan2';
$host = '127.0.0.1';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='orders' AND COLUMN_NAME='member_id'");
    $stmt->execute();
    $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    echo "member_id exists: " . ($cnt > 0 ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
