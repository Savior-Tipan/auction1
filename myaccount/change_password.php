<?php
session_start();
require '../includes/config.php'; // Include your PDO connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Initialize variables
$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $previous_password = trim($_POST['previous_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters long.";
    }
    if ($new_password !== $confirm_password) {
        $errors[] = "New password and confirmation do not match.";
    }

    // Check if the previous password is correct
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = :username");
    $stmt->execute(['username' => $_SESSION['username']]);
    $user = $stmt->fetch();

    if ($user && !password_verify($previous_password, $user['password_hash'])) {
        $errors[] = "Previous password is incorrect.";
    }

    // If no errors, proceed to update
    if (empty($errors)) {
        // Hash the new password
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT); // Using bcrypt for better security

        // Update the password in the database
        $stmt = $pdo->prepare("UPDATE users SET password_hash = :password_hash WHERE username = :username");
        $params = [
            'password_hash' => $password_hash,
            'username' => $_SESSION['username']
        ];

        // Execute the update
        try {
            $stmt->execute($params);
            $_SESSION['success'] = "You have successfully changed your password.";
            header("Location: change_password.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "An error occurred while changing the password: " . $e->getMessage();
        }
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: change_password.php");
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
    <title>Change Password</title>
    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../myaccount/css/change_password.css">
    <!-- Google Fonts for Techy Button Font -->
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
            font-family: 'Orbitron', sans-serif; /* Techy font */
            text-align: center;
            margin-bottom: 10px;
            color: #ff4d4d; /* Red color for the header */
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

        /* Submit Button */
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
        <h1>Change Your Password</h1>

        <?php 
        // Display errors
        if (!empty($errors)) {
            echo "<ul class='error-list'>";
            foreach ($errors as $error) {
                echo "<li>".htmlspecialchars($error)."</li>";
            }
            echo "</ul>";
        }

        // Display success message
        if (!empty($success)) {
            echo "<p class='success-message'>".htmlspecialchars($success)."</p>";
        }
        ?>

        <form method="POST" action="change_password.php">
            <div class="input-container">
                <label for="previous_password">Previous Password:</label>
                <input type="password" name="previous_password" id="previous_password" required>
                <svg class="eye-icon" onclick="togglePasswordVisibility('previous_password', this)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <!-- Eye-Off Icon -->
                    <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                    <path d="M1 1l22 22"></path>
                </svg>
            </div>

            <div class="input-container">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
                <svg class="eye-icon" onclick="togglePasswordVisibility('new_password', this)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <!-- Eye-Off Icon -->
                    <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                    <path d="M1 1l22 22"></path>
                </svg>
            </div>

            <div class="input-container">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <svg class="eye-icon" onclick="togglePasswordVisibility('confirm_password', this)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <!-- Eye-Off Icon -->
                    <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                    <path d="M1 1l22 22"></path>
                </svg>
            </div>

            <button type="submit" class="submit-btn">Change Password</button>
        </form>

        <div class="links">
            <a href="edit_account.php">Edit Account</a>
            <a href="myaccount.php">My Account</a>
        </div>
    </div>

    <!-- JavaScript for toggling password visibility -->
    <script>
        function togglePasswordVisibility(fieldId, eyeIconElement) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = eyeIconElement;

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.innerHTML = `
                    <!-- Eye Icon -->
                    <path d="M1 12s4.5-10 11-10 11 10 11 10-4.5 10-11 10S1 12 1 12z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            } else {
                passwordField.type = "password";
                eyeIcon.innerHTML = `
                    <!-- Eye-Off Icon -->
                    <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                    <path d="M1 1l22 22"></path>
                `;
            }
        }
    </script>
</body>
</html>
