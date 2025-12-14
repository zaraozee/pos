<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=latihan2;charset=utf8mb4','root','', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $rows = $pdo->query("SELECT id, name, email FROM users LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
    echo "USERS:\n";
    foreach ($rows as $r) echo json_encode($r) . "\n";
    if (empty($rows)) echo "(no users)\n";
} catch (Exception $e) {
    echo 'ERR: '.$e->getMessage()."\n";
}
