<?php
session_start();
require '../includes/config.php'; // Include your database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Both fields are required!";
        header("Location: admin_login.php");
        exit;
    }

    // Check if admin exists
    $query = $pdo->prepare("SELECT * FROM Admins WHERE username = :username");
    $query->execute([':username' => $username]);
    $admin = $query->fetch();

    // Verify password
    if ($admin && password_verify($password, $admin['password_hash'])) {
        // Set session variables
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];

        // Redirect to dashboard
        header("Location: ../admin-dashboard/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: admin_login.php");
        exit;
    }
}
$savedUsername = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$savedPassword = isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; // Caution: Avoid storing passwords in plain text
