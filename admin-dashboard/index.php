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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Add styles for the button */
        .btn-signup {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-signup:hover {
            background-color: #218838;
        }

        /* Ensure container and main content have proper layout */
        .container {
            position: relative; /* For button positioning */
            display: flex;
        }

        .sidebar {
            width: 250px;
            padding: 20px;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }

    </style>
</head>

<body>

    <div class="container">
        <!-- Add Admin Button (upper-right corner) -->
        <a href="../adminlogin/admin_signup.php" class="btn-signup">
            <i class="fa-solid fa-user-plus"></i> Add Admin Account
        </a>

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
            <h1>Active Bids</h1>
            <div class="stats">
                <div class="stat-box">
                    <i class="fas fa-gavel stat-icon"></i> <!-- Icon for Active Bids -->
                    <div>80 <br>Total Listings</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-trophy stat-icon"></i> <!-- Icon for Items Won -->
                    <div>15 <br>Successful Auctions</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-star stat-icon"></i> <!-- Icon for Favorites -->
                    <div>124 <br>Revenue Generated</div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Item List</th>
                        <th>Last Bid</th>
                        <th>Opening Bid</th>
                        <th>Left Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2015 Premium Watch</td>
                        <td>$589.21</td>
                        <td>$159.87</td>
                        <td class="expired">EXPIRED</td>
                    </tr>
                    <tr>
                        <td>2015 Premium Watch</td>
                        <td>$589.21</td>
                        <td>$159.87</td>
                        <td class="ongoing" data-time="398">06:38:18</td>
                    </tr>
                    <tr>
                        <td>2015 Premium Watch</td>
                        <td>$589.21</td>
                        <td>$159.87</td>
                        <td class="ongoing" data-time="30">00:00:30</td>
                    </tr>
                    <tr>
                        <td>2015 Premium Watch</td>
                        <td>$589.21</td>
                        <td>$159.87</td>
                        <td class="ongoing" data-time="10">00:00:10</td>
                    </tr>
                </tbody>
            </table>
        </main>

    </div>
    <script src="script.js"></script>
</body>
</html>
