<?php
session_start();
require '../includes/config.php'; // Include your PDO connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Function to fetch current user data
function getUserData($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Initialize variables
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username_input = trim($_POST['username']);
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $contact = trim($_POST['contact']);
    $age = trim($_POST['age']);
    $address = trim($_POST['address']);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if username is being changed and ensure it's unique
    if ($username_input !== $_SESSION['username']) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username_input]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username already taken.";
        }
    }

    // If no errors, proceed to update
    if (empty($errors)) {
        // Start building the SQL query
        $sql = "UPDATE users SET 
                    username = :new_username,
                    email = :email,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    last_name = :last_name,
                    contact = :contact,
                    age = :age,
                    address = :address
                WHERE username = :current_username";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $params = [
            'new_username' => $username_input,
            'email' => $email,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'contact' => $contact,
            'age' => $age,
            'address' => $address,
            'current_username' => $_SESSION['username']
        ];

        // Execute the update
        try {
            $stmt->execute($params);
            // Update session username if changed
            $_SESSION['username'] = $username_input;
            // Set success message in session
            $_SESSION['success'] = "Account updated successfully.";
            // Redirect to the same page to prevent form resubmission
            header("Location: edit_account.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "An error occurred while updating the account: " . $e->getMessage();
        }
    } else {
        // If there are errors, store them in the session to display after redirect
        $_SESSION['errors'] = $errors;
        // Optionally, store the submitted data to repopulate the form
        $_SESSION['form_data'] = $_POST;
        // Redirect to the same page to display errors
        header("Location: edit_account.php");
        exit();
    }
}

// Fetch the current user data
$user = getUserData($pdo, $_SESSION['username']);

// Retrieve and clear success message from session
$success = "";
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Retrieve and clear errors from session
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// Optionally, retrieve form data to repopulate the form in case of errors
if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
} else {
    $form_data = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Account</title>
    <!-- Link to External CSS -->
    <link rel="stylesheet" type="text/css" href="../myaccount/css/styles.css">
    <!-- Ensure to adjust the href path based on your directory structure -->
</head>
<body>
    <div class="container">
        <h1>Edit Your Account</h1>

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

        <form method="POST" action="edit_account.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" 
                   value="<?php echo htmlspecialchars(isset($form_data['username']) ? $form_data['username'] : $user['username']); ?>" 
                   required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars(isset($form_data['email']) ? $form_data['email'] : $user['email']); ?>" 
                   required><br>

            <label for="first_name">First Name:</label><br>
            <input type="text" id="first_name" name="first_name" 
                   value="<?php echo htmlspecialchars(isset($form_data['first_name']) ? $form_data['first_name'] : $user['first_name']); ?>"><br>

            <label for="middle_name">Middle Name:</label><br>
            <input type="text" id="middle_name" name="middle_name" 
                   value="<?php echo htmlspecialchars(isset($form_data['middle_name']) ? $form_data['middle_name'] : $user['middle_name']); ?>"><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" id="last_name" name="last_name" 
                   value="<?php echo htmlspecialchars(isset($form_data['last_name']) ? $form_data['last_name'] : $user['last_name']); ?>"><br>

            <label for="contact">Contact:</label><br>
            <input type="text" id="contact" name="contact" 
                   value="<?php echo htmlspecialchars(isset($form_data['contact']) ? $form_data['contact'] : $user['contact']); ?>"><br>

            <label for="age">Age:</label><br>
            <input type="number" id="age" name="age" 
                   value="<?php echo htmlspecialchars(isset($form_data['age']) ? $form_data['age'] : $user['age']); ?>"><br>

            <label for="address">Address:</label><br>
            <input type="text" id="address" name="address" 
                   value="<?php echo htmlspecialchars(isset($form_data['address']) ? $form_data['address'] : $user['address']); ?>"><br>

            <button type="submit">Update Account</button>
        </form>

        <div class="links">
            <a href="change_password.php">Change Password</a>
            <a href="myaccount.php">My Account</a>
        </div>
    </div>
</body>
</html>
