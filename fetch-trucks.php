<?php
header('Content-Type: application/json');

// Database connection
$host = 'your_database_host';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query('SELECT id, name, auction_time, starting_price, image_url FROM trucks WHERE auction_live = 1');
    $trucks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($trucks);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
