<?php
include('../includes/config.php');
session_start(); // Start session to use session variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $middle_name = htmlspecialchars(trim($_POST['middle_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $age = intval($_POST['age']);
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $email = htmlspecialchars(trim($_POST['email']));
    $govt_id = htmlspecialchars(trim($_POST['govt_id']));

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($address) || empty($contact) || empty($age) || empty($username) || empty($password) || empty($email) || empty($govt_id)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: signup.php");
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: signup.php");
        exit;
    }

    // Check if password is strong enough
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
        header("Location: signup.php");
        exit;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload for business license (optional)
    $doc_new_name = null;
    if (isset($_FILES['doc-upload']) && $_FILES['doc-upload']['error'] == 0) {
        $target_dir = "uploads/";
        $doc_name = basename($_FILES["doc-upload"]["name"]);
        $doc_new_name = $target_dir . uniqid() . '_' . $doc_name; // Add unique ID to avoid file name collisions

        // Check for valid file types (optional: only allow certain file types, e.g., PDF, JPG, PNG)
        $file_type = strtolower(pathinfo($doc_new_name, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['error'] = "Only PDF, JPG, JPEG, and PNG files are allowed for the business permit.";
            header("Location: signup.php");
            exit;
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES["doc-upload"]["tmp_name"], $doc_new_name)) {
            $_SESSION['error'] = "File upload error. Please try again.";
            header("Location: signup.php");
            exit;
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO users (first_name, middle_name, last_name, address, contact, age, username, password_hash, email, business_permit, govt_id, email_verified)
            VALUES (:first_name, :middle_name, :last_name, :address, :contact, :age, :username, :password, :email, :business_permit, :govt_id, 1)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':first_name', $first_name);
        $stmt->bindValue(':middle_name', $middle_name);
        $stmt->bindValue(':last_name', $last_name);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':contact', $contact);
        $stmt->bindValue(':age', $age, PDO::PARAM_INT);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password_hash);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':business_permit', $doc_new_name);
        $stmt->bindValue(':govt_id', $govt_id);

        if ($stmt->execute()) {
            header("Location: signup.php?signup=success");
            exit;
        } else {
            $_SESSION['error'] = "Unable to register user. Please try again.";
            header("Location: signup.php");
            exit;
        }
    } catch (PDOException $e) {
        // Check for duplicate entry error (SQLSTATE 23000)
        if ($e->getCode() == 23000) {
            if (strpos($e->getMessage(), 'username') !== false) {
                $_SESSION['error'] = "The username is already taken. Please choose a different username.";
            } elseif (strpos($e->getMessage(), 'email') !== false) {
                $_SESSION['error'] = "The email is already registered. Please use a different email.";
            } else {
                $_SESSION['error'] = "Duplicate entry found. Please check your input.";
            }
        } else {
            $_SESSION['error'] = "Database error. Please try again.";
        }
        header("Location: signup.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: signup.php");
    exit;
}
