<?php
session_start();
include '../includes/config.php'; // Include your PDO connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $_SESSION['username']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../myaccount/css/my_account.css">
    <title>My Account</title>
</head>
<body>
    <header class="header">
        <div class="container-header">
            <a href="#" class="logo">
                <img src="../assets/images/jma.png" alt="JMA HOME" id="logo-img">
            </a>
            <nav>
                <a href="../index.php">Home</a> 
                <a href="../login/login.php">Logout</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container-info">
            <h1>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
            <h2>Your Information:</h2>
            <ul>
                <li><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                <li><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></li>
                <li><strong>Middle Name:</strong> <?php echo htmlspecialchars($user['middle_name']); ?></li>
                <li><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></li>
                <li><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?></li>
                <li><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></li>
                <li><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></li>
                <li><strong>Account Created At:</strong> <?php echo htmlspecialchars($user['created_at']); ?></li>
            </ul>

            <div class="action-links">
                <a href="edit_account.php">Edit Account</a>
                <a href="delete_account.php" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a>
            </div>
        </div>
    </main>
</body>
</html>
