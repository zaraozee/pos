<?php
$db = 'latihan2';
$host = '127.0.0.1';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected to DB: " . $db . "\n";
    $cur = $pdo->query("SELECT DATABASE() as db")->fetch(PDO::FETCH_ASSOC);
    echo "PDO selected database: " . var_export($cur, true) . "\n";

    $stmt = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=DATABASE()");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "TABLES (raw): " . var_export($tables, true) . "\n\n";

    $stmt = $pdo->query("SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='orders'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "ORDERS COLUMNS (raw): " . var_export($rows, true) . "\n";
} catch (Exception $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
