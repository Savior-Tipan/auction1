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
            $success = "You have successfully changed your password.";
        } catch (PDOException $e) {
            $errors[] = "An error occurred while changing the password: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <!-- Link to External CSS -->
    <link rel="stylesheet" type="text/css" href="../myaccount/css/change_password.css">
    <!-- Ensure to adjust the href path based on your directory structure -->
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
                <span class="eye-icon" onclick="togglePasswordVisibility('previous_password', 'eye_icon_previous')">
                    <!-- Eye Icon SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                        <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                        <path d="M1 1l22 22"></path>
                    </svg>
                </span>
            </div>

            <div class="input-container">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
                <span class="eye-icon" onclick="togglePasswordVisibility('new_password', 'eye_icon_new')">
                    <!-- Eye Icon SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                        <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                        <path d="M1 1l22 22"></path>
                    </svg>
                </span>
            </div>

            <div class="input-container">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="eye-icon" onclick="togglePasswordVisibility('confirm_password', 'eye_icon_confirm')">
                    <!-- Eye Icon SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                        <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                        <path d="M1 1l22 22"></path>
                    </svg>
                </span>
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
        function togglePasswordVisibility(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var eyeIcon = document.getElementById(iconId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                        <path d="M1 12s4.5-10 11-10 11 10 11 10-4.5 10-11 10S1 12 1 12z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
            } else {
                passwordField.type = "password";
                eyeIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off">
                        <path d="M17.94 15.06A10.5 10.5 0 0 0 12 4.5 10.5 10.5 0 0 0 6.06 8.94M9.5 10.5A2.5 2.5 0 0 1 12 7.5a2.5 2.5 0 0 1 2.5 2.5M22 12s-4.5 10-11 10-11-10-11-10 4.5-10 11-10 11 10 11 10z"></path>
                        <path d="M1 1l22 22"></path>
                    </svg>
                `;
            }
        }
    </script>
</body>
</html>
