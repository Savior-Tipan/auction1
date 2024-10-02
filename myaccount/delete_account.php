<?php
session_start();
require '../includes/config.php'; // Include your PDO connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

$username = $_SESSION['username'];
$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize the password input
    $password_input = trim($_POST['password']);

    if (empty($password_input)) {
        $errors[] = "Please enter your password to confirm account deletion.";
    } else {
        try {
            // Fetch the user's current password hash from the database
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password_input, $user['password_hash'])) {
                // Begin transaction to ensure data integrity
                $pdo->beginTransaction();

                // Delete the user from the database
                $stmt = $pdo->prepare("DELETE FROM users WHERE username = :username");
                $stmt->execute(['username' => $username]);

                // Commit the transaction
                $pdo->commit();

                // Destroy the session
                session_unset();
                session_destroy();

                // Redirect to the Account Deleted page
                header("Location: account_deleted.php");
                exit();
            } else {
                $errors[] = "Incorrect password. Please try again.";
            }
        } catch (PDOException $e) {
            // Rollback the transaction if something failed
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors[] = "An error occurred while deleting your account. Please try again later.";
            // Log the actual error message securely (e.g., to a file or monitoring system)
            // error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #ccc;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #d9534f; /* Bootstrap danger color */
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            color: #d9534f; /* Red for errors */
            margin-bottom: 15px;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            color: #5cb85c; /* Green for success */
            margin-bottom: 15px;
            background-color: #dff0d8;
            padding: 10px;
            border-radius: 5px;
        }
        button {
            background-color: #d9534f; /* Red button */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%; /* Full-width button */
        }
        button:hover {
            background-color: #c9302c; /* Darker red on hover */
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
            color: #5bc0de; /* Bootstrap info color */
            text-decoration: underline;
            font-weight: bold;
        }
        a:hover {
            color: #31b0d5; /* Darker blue on hover */
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333; /* Darker text for labels */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Your Account</h1>

        <?php 
        // Display errors
        if (!empty($errors)) {
            echo "<div class='error'><ul>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul></div>";
        }

        // Display success message if redirected with a message
        if (isset($_GET['message'])) {
            echo "<div class='success'>" . htmlspecialchars($_GET['message']) . "</div>";
        }

        // If the form has not been submitted yet, show the confirmation form
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ?>
            <p style="text-align: center;">Are you sure you want to delete your account? This action cannot be undone.</p>
            <form method="POST" action="delete_account.php">
                <!-- Optional: Add a hidden CSRF token here for enhanced security -->
                <label for="password">Enter Your Password to Confirm:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Yes, Delete My Account</button>
                <a href="myaccount.php">Cancel</a>
            </form>
        <?php 
        }
        ?>
    </div>
</body>
</html>
