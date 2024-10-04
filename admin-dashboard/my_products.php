<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "jma");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and user_id is set in the session
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch the user's actual name and email from the database
    $stmt = $conn->prepare("SELECT username, email FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($username, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    // Redirect to login page if user is not logged in
    header("Location: ../adminlogin/admin_login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<?php include('../includes/header.php'); ?>

<body>
    <div class="container">
        <nav class="sidebar">
            <!-- Display user's actual name and email -->
            <h2><?php echo $username; ?></h2>
            <p><?php echo $email; ?></p>
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-home"></i> Dashboard</a></li>
                <li><a href="personal_profile.php"><i class="fa-solid fa-user"></i> Personal Profile</a></li>
                <li><a href="my_products.php"><i class="fa-solid fa-gavel"></i> My Products</a></li>
                <li><a href="add_products.php"><i class="fa-solid fa-trophy"></i> Add Products</a></li>
                <li><a href="user_account.php"><i class="fa-solid fa-users"></i> User Account</a></li>
            </ul>
        </nav>
        <main class="content">
            <h1>My Products</h1>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2015 Premium Watch</td>
                        <td>$589.21</td>
                        <td>Available</td>
                    </tr>
                    <tr>
                        <td>2020 Sports Watch</td>
                        <td>$299.99</td>
                        <td>Sold</td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
    <script src="script.js"></script>
</body>
<?php include('../includes/footer.php'); ?>

</html>
