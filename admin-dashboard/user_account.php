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



<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "jma");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete button click
if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];
    
    // Use prepared statement to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting user: " . $stmt->error . "');</script>";
    }
    
    $stmt->close();
}

// Fetch all users
$sql = "SELECT user_id, username, email, first_name, middle_name, last_name, contact, age, address, business_permit, govt_id, created_at, email_verified FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>
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
            <h1>User Accounts</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Contact</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Business Permit</th>
                        <th>Government ID</th>
                        <th>Created At</th>
                        <th>Email Verified</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row['user_id'] . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['email'] . "</td>
                                <td>" . $row['first_name'] . "</td>
                                <td>" . $row['middle_name'] . "</td>
                                <td>" . $row['last_name'] . "</td>
                                <td>" . $row['contact'] . "</td>
                                <td>" . $row['age'] . "</td>
                                <td>" . $row['address'] . "</td>
                                <td>" . $row['business_permit'] . "</td>
                                <td>" . $row['govt_id'] . "</td>
                                <td>" . $row['created_at'] . "</td>
                                <td>" . ($row['email_verified'] ? 'Yes' : 'No') . "</td>
                                <td>
                                    <form method='POST' action='' onsubmit='return confirmDelete();'>
                                        <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                                        <button type='submit' name='delete' class='delete-btn'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user? This action cannot be undone.');
        }
    </script>
</body>
<?php include('../includes/footer.php'); ?>

</html>

<?php
$conn->close();
?>
