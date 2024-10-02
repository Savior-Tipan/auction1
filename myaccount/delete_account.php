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

    // Validate input
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
            // Log the actual error message securely
            // error_log($e->getMessage());
        }
    }

    // If there are errors, store them in the session and redirect to avoid form resubmission
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: delete_account.php");
        exit();
    }
}

// Retrieve messages from session
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../myaccount/css/delete_account.css">
    <!-- Google Fonts for Techy Font -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
    <style>
        /* Background Image */
        body {
            margin: 0;
            padding: 0;
            background: url('../myaccount/css/images/hero-bg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Main Container */
        .container {
            width: 400px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent black */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
            color: #fff;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #ff4d4d; /* Red color for the header */
            font-family: 'Orbitron', sans-serif; /* Techy font */
        }

        /* Error and Success Messages */
        .error-list {
            background: rgba(255, 0, 0, 0.1);
            border-left: 5px solid #ff4d4d;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .error-list li {
            color: #ff4d4d;
        }

        .success-message {
            background: rgba(0, 255, 0, 0.1);
            border-left: 5px solid #4CAF50;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
        }

        .input-container label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
            font-weight: bold;
        }

        .input-container input {
            width: 88%;
            padding: 10px 40px 10px 10px; /* Padding to accommodate the eye icon */
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .input-container input:focus {
            outline: none;
            border-color: #ff4d4d;
            background: rgba(255, 255, 255, 0.2);
        }

        .eye-icon {
            position: absolute;
            top: 35px;
            right: 10px;
            cursor: pointer;
            width: 24px;
            height: 24px;
            fill: #ff4d4d;
            transition: fill 0.3s ease;
        }

        .eye-icon:hover {
            fill: #fff;
        }

        /* Submit and Action Buttons */
        .submit-btn, .action-btn {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            font-family: 'Orbitron', sans-serif; /* Techy font */
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }

        .submit-btn:hover, .action-btn:hover {
            background-color: #e60000;
            transform: scale(1.05);
        }

        /* Links as Buttons */
        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            display: inline-block;
            background-color: #ff4d4d;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            margin: 0 10px;
            font-family: 'Orbitron', sans-serif; /* Techy font */
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .links a:hover {
            background-color: #e60000;
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 450px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Your Account</h1>

        <?php 
        // Display errors
        if (!empty($errors)) {
            echo "<div class='error-list'><ul>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul></div>";
        }

        // Display success message if redirected with a message
        if (!empty($success)) {
            echo "<div class='success-message'>" . htmlspecialchars($success) . "</div>";
        }

        // Show the confirmation form only if the form hasn't been successfully submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($errors)) {
        ?>
            <p style="text-align: center;">Are you sure you want to delete your account? This action cannot be undone.</p>
            <form method="POST" action="delete_account.php">
                <div class="input-container">
                    <label for="password">Enter Your Password to Confirm:</label>
                    <input type="password" id="password" name="password" required>
                    <svg class="eye-icon" onclick="togglePasswordVisibility('password', this)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <!-- Initial Eye Closed Icon -->
                        <path d="M12 4.5C7.5 4.5 3.3 7.9 1.7 12c1.6 4.1 5.8 7.5 10.3 7.5 4.5 0 8.7-3.4 10.3-7.5C20.7 7.9 16.5 4.5 12 4.5zm0 12c-3.3 0-6.3-2.7-7.5-6.1 1.2-3.4 4.2-6.1 7.5-6.1 3.3 0 6.3 2.7 7.5 6.1-1.2 3.4-4.2 6.1-7.5 6.1zm0-10.9c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" />
                    </svg>
                </div>
                <button type="submit" class="submit-btn">Delete Account</button>
            </form>
            <div class="links">
                <a href="my_account.php" class="action-btn">My Account</a>
            </div>
        <?php } ?>
    </div>
    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility(inputId, eyeIcon) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                eyeIcon.innerHTML = '<path d="M12 4.5C7.5 4.5 3.3 7.9 1.7 12c1.6 4.1 5.8 7.5 10.3 7.5 4.5 0 8.7-3.4 10.3-7.5C20.7 7.9 16.5 4.5 12 4.5zm0 12c-3.3 0-6.3-2.7-7.5-6.1 1.2-3.4 4.2-6.1 7.5-6.1 3.3 0 6.3 2.7 7.5 6.1-1.2 3.4-4.2 6.1-7.5 6.1zm0-10.9c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4z" />';
            } else {
                input.type = "password";
                eyeIcon.innerHTML = '<path d="M12 4.5C7.5 4.5 3.3 7.9 1.7 12c1.6 4.1 5.8 7.5 10.3 7.5 4.5 0 8.7-3.4 10.3-7.5C20.7 7.9 16.5 4.5 12 4.5zm0 12c-3.3 0-6.3-2.7-7.5-6.1 1.2-3.4 4.2-6.1 7.5-6.1 3.3 0 6.3 2.7 7.5 6.1-1.2 3.4-4.2 6.1-7.5 6.1zm0-10.9c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4z" />';
            }
        }
    </script>
</body>
</html>
