<?php
// Start the session
session_start();

require '../includes/config.php'; // Database connection using PDO

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adminlogin/admin_login.php");
    exit();
}

// Fetch admin data
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT username, email, password_hash FROM admins WHERE admin_id = :admin_id");
$stmt->execute([':admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    $_SESSION['error'] = "Admin not found!";
    header("Location: ../index.php");
    exit();
}
?>
<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Current password
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($username) || empty($email)) {
        $_SESSION['error'] = "Username and email are required!";
        header("Location: ../admin-dashboard/personal_profile.php");
        exit();
    }

    // Verify the current password before updating
    if (!password_verify($password, $admin['password_hash'])) {
        $_SESSION['error'] = "Current password is incorrect!";
        header("Location: ../admin-dashboard/personal_profile.php");
        exit();
    }

    // Check if the new password fields are filled and match
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords do not match!";
        header("Location: ../admin-dashboard/personal_profile.php");
        exit();
    }

    try {
        // Update admin data (including password if set)
        if (!empty($new_password)) {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare("UPDATE admins SET username = :username, email = :email, password_hash = :password_hash WHERE admin_id = :admin_id");
            $update_stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':admin_id' => $admin_id
            ]);
        } else {
            $update_stmt = $pdo->prepare("UPDATE admins SET username = :username, email = :email WHERE admin_id = :admin_id");
            $update_stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':admin_id' => $admin_id
            ]);
        }

        if ($update_stmt->rowCount() > 0) {
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "No changes were made.";
        }

    } catch (PDOException $e) {
        // Handle duplicate entry error (SQLSTATE[23000])
        if ($e->getCode() == 23000) {
            if (strpos($e->getMessage(), 'username') !== false) {
                $_SESSION['error'] = "Username is already taken, please try a different one.";
            } elseif (strpos($e->getMessage(), 'email') !== false) {
                $_SESSION['error'] = "Email is already used, please try a different one.";
            }
        } else {
            // For other PDO exceptions
            $_SESSION['error'] = "An error occurred while updating. Please try again.";
        }
    }

    header("Location: ../admin-dashboard/personal_profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
</head>
<?php include('../includes/header.php'); ?>

<body>
<div class="container">
    <nav class="sidebar">
        <!-- Display user's actual name and email -->
        <h2><?php echo htmlspecialchars($admin['username']); ?></h2>
        <p><?php echo htmlspecialchars($admin['email']); ?></p>
        <ul>
            <li><a href="index.php"><i class="fa-solid fa-home"></i> Dashboard</a></li>
            <li><a href="personal_profile.php"><i class="fa-solid fa-user"></i> Personal Profile</a></li>
            <li><a href="my_products.php"><i class="fa-solid fa-gavel"></i> My Products</a></li>
            <li><a href="add_products.php"><i class="fa-solid fa-trophy"></i> Add Products</a></li>
            <li><a href="user_account.php"><i class="fa-solid fa-users"></i> User Account</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h3>Manage Profile</h3>
        <form action="personal_profile.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label><br>
                <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($admin['email']); ?>" required>
            </div>
            <div class="form-group eye-icon">
                <label for="password">Current Password:</label><br>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter current password" required>
                <i class="fa fa-eye-slash" id="togglePassword"></i>
            </div>
            <div class="form-group eye-icon">
                <label for="new_password">New Password (Optional):</label><br>
                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter new password">
                <i class="fa fa-eye-slash" id="toggleNewPassword"></i>
            </div>
            <div class="form-group eye-icon">
                <label for="confirm_password">Confirm New Password:</label><br>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm new password">
                <i class="fa fa-eye-slash" id="toggleConfirmPassword"></i>
            </div>
            <button type="submit" class="btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});

const toggleNewPassword = document.querySelector('#toggleNewPassword');
const newPassword = document.querySelector('#new_password');

toggleNewPassword.addEventListener('click', function () {
    const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    newPassword.setAttribute('type', type);
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});

const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
const confirmPassword = document.querySelector('#confirm_password');

toggleConfirmPassword.addEventListener('click', function () {
    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPassword.setAttribute('type', type);
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});
</script>
</body>
<?php include('../includes/footer.php'); ?>

</html>
