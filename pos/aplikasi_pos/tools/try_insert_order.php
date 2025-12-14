<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=latihan2;charset=utf8mb4','root','', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->prepare('INSERT INTO orders (invoice, total, user_id, member_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
    $stmt->execute(['INV_TEST_'.time(), 1000, null, 1]);
    echo "Inserted order id: " . $pdo->lastInsertId() . "\n";
} catch (Exception $e) {
    echo 'ERR: '.$e->getMessage() . "\n";
}
