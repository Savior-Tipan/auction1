<?php
// live-auctions.php

// Database connection details
$host = 'your_database_host';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a new PDO instance and set error mode
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the trucks that are currently live in auction
    $stmt = $pdo->query('SELECT id, name, auction_time, starting_price, image_url FROM trucks WHERE auction_live = 1');
    $trucks = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Auctions - JMA Trucks Solution</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

    <header class="header">
        <!-- Include your header content here -->
    </header>

    <main>
        <section class="live-auctions-section">
            <div class="container">
                <h1>Live Auction Trucks</h1>
                <div class="auction-cards">
                    <?php if (empty($trucks)): ?>
                        <p>No live auctions available at the moment.</p>
                    <?php else: ?>
                        <?php foreach ($trucks as $truck): ?>
                            <div class="auction-card">
                                <img src="<?= htmlspecialchars($truck['image_url']) ?>" alt="<?= htmlspecialchars($truck['name']) ?>" class="truck-image">
                                <div class="card-info">
                                    <h3 class="truck-name"><?= htmlspecialchars($truck['name']) ?></h3>
                                    <p class="auction-time">Auction Time: <?= htmlspecialchars($truck['auction_time']) ?></p>
                                    <p class="starting-price">Starting Price: $<?= htmlspecialchars($truck['starting_price']) ?></p>
                                    <a href="truck-details.php?id=<?= htmlspecialchars($truck['id']) ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <!-- Include your footer content here -->
    </footer>

</body>
</html>
