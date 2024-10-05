<?php
// Start the session
session_start();
require '../includes/config.php'; // Include your database connection

// Check if user is logged in and user_id is set in the session
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch the user's actual name and email from the database using PDO
    $stmt = $pdo->prepare("SELECT username, email FROM admins WHERE admin_id = :admin_id");
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result as an associative array
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $username = $admin['username'];
        $email = $admin['email'];
    } else {
        // Redirect to login page if no admin found
        header("Location: ../adminlogin/admin_login.php");
        exit();
    }
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS for modal -->
    
</head>
<body>

    <div class="container">
        <!-- Add Admin Button (upper-right corner) -->
        <a href="../adminlogin/admin_signup.php" class="btn-signup">
            <i class="fa-solid fa-user-plus"></i> Add Admin Account
        </a>

        <nav class="sidebar">
            <!-- Display user's actual name and email -->
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><?php echo htmlspecialchars($email); ?></p>
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-home"></i> Dashboard</a></li>
                <li><a href="personal_profile.php"><i class="fa-solid fa-user"></i> Personal Profile</a></li>
                <li><a href="my_products.php"><i class="fa-solid fa-gavel"></i> My Products</a></li>
                <li><a href="#" data-toggle="modal" data-target="#addProductModal"><i class="fa-solid fa-trophy"></i> Add Products</a></li> <!-- Open modal -->
                <li><a href="user_account.php"><i class="fa-solid fa-users"></i> User Account</a></li>
            </ul>
        </nav>

        <main class="content">
            <h1>Welcome to your dashboard.</h1>
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
            <h1>Bid History</h1>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding products (from add_products.php) -->
                    <form action="add_products.php" method="POST">
                        <div class="form-group">
                            <label for="truck_name">Truck Name:</label>
                            <input type="text" class="form-control" id="truck_name" name="truck_name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="model_year">Model Year:</label>
                            <input type="number" class="form-control" id="model_year" name="model_year" required>
                        </div>
                        <div class="form-group">
                            <label for="starting_bid">Starting Bid:</label>
                            <input type="number" class="form-control" id="starting_bid" name="starting_bid" required>
                        </div>
                        <div class="form-group">
                            <label for="current_bid">Current Bid:</label>
                            <input type="number" class="form-control" id="current_bid" name="current_bid" required>
                        </div>
                        <div class="form-group">
                            <label for="auction_start">Auction Start Date:</label>
                            <input type="date" class="form-control" id="auction_start" name="auction_start" required>
                        </div>
                        <div class="form-group">
                            <label for="auction_end">Auction End Date:</label>
                            <input type="date" class="form-control" id="auction_end" name="auction_end" required>
                        </div>
                        <div class="form-group">
                            <label for="maker">Truck Maker:</label>
                            <select class="form-control" id="maker" name="maker">
                                <option value="Toyota">Toyota</option>
                                <option value="Isuzu">Isuzu</option>
                                <option value="Mitsubishi">Mitsubishi</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Bootstrap JS for modal -->
</body>

</html>
