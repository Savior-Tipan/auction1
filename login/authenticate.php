<?php
session_start(); // Start session to manage user login status
require '../includes/config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['rememberMe']); // Check if "Remember Me" is set

    // Ensure no empty values are processed
    if (!empty($username) && !empty($password)) {
        // Validate credentials
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify if user exists and if the password matches
        if ($user && password_verify($password, $user['password_hash'])) {
            // If the credentials are valid, set session and redirect to the dashboard
            $_SESSION['username'] = $username;
            
            // Set cookies if "Remember Me" is checked
            if ($rememberMe) {
                // Set cookies for 30 days
                setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('password', $password, time() + (86400 * 30), "/"); // Caution: Avoid storing passwords in plain text
            } else {
                // Clear cookies if not checked
                setcookie('username', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            // Prevent resubmission with PRG by redirecting
            header("Location: ../index.php");
            exit(); // Stop further script execution after redirection
        } else {
            // If login fails, redirect back to the login page with an error message
            $_SESSION['error'] = "Invalid credentials!";
            header("Location: login.php");
            exit(); // Stop script execution after redirection
        }
    } else {
        // Handle missing username or password
        $_SESSION['error'] = "Please enter both username and password.";
        header("Location: login.php");
        exit(); // Stop script execution after redirection
    }
} else {
    // If the request is not POST, redirect to the login page
    header("Location: login.php");
    exit(); // Stop script execution after redirection
}
