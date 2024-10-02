<?php
// Database configuration
$host = 'localhost';          // Database host (use 'localhost' if the database is on the same server)
$dbname = 'jma';        // Name of the database
$username = 'root';         // Database username
$password = '';     // Database password

// Create a connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set the PDO error mode to exception (for better error handling)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optionally, use this to set PDO to fetch associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Success message (for testing, remove or comment out in production)
    // echo "Connected successfully"; 

} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}

