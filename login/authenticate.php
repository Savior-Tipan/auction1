<?php
session_start();
require '../includes/config.php'; // Include your database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to check if the user exists in the database
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the user record from the database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password_hash'])) {
        // Set the session user_id after successful login
        $_SESSION['user_id'] = $user['user_id'];
        
        // Redirect to index.php
        header("Location: ../index.php");
        exit();
    } else {
        // If login fails, set an error message and stay on login.php
        $error = "Invalid username or password.";
    }
}
