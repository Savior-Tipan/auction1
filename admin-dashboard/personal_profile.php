<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "jma");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and user_id is set in the session
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch the user's actual name and email from the database
    $stmt = $conn->prepare("SELECT username, email FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($username, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    // Redirect to login page if user is not logged in
    header("Location: ../adminlogin/admin_login.php");
    exit();
}
?>

<?php
require '../includes/config.php'; // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adminlogin/admin_login.php");
    exit;
}

// Fetch admin data
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT username, email, password_hash FROM Admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    $_SESSION['error'] = "Admin not found!";
    header("Location: ../index.php");
    exit;
}

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
        exit;
    }

    // Verify the current password before updating
    if (!password_verify($password, $admin['password_hash'])) {
        $_SESSION['error'] = "Current password is incorrect!";
        header("Location: ../admin-dashboard/personal_profile.php");
        exit;
    }

    // Check if the new password fields are filled and match
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords do not match!";
        header("Location: ../admin-dashboard/personal_profile.php");
        exit;
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
    exit;
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
    <style>
        /* General Styling */
        body {
            background: linear-gradient(135deg, #ffffff, #e0e0e0); /* Background gradient */
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        .form-container {
            width: 800px;
            padding: 30px;
            background-color: #fff;
            color: #333;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin: 60px auto;
            height: 500px;
        }
        h3 {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            color: #ff4c4c;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #ff4c4c;
            width: 775px;
            padding: 10px;
            margin: 5px;
        }
        .btn-primary {
            background-color: #ff4c4c;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            text-transform: uppercase;
            margin: 10px;
        }
        .btn-primary:hover {
            background-color: #e32f2f;
        }
        .eye-icon {
            position: relative;
        }
        .eye-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
            h3 {
                font-size: 20px;
            }
            .btn-primary {
                font-size: 14px;
            }
            .form-control {
                margin-bottom: 15px;
            }
        }
        label {
            margin: 10px 0 5px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
        <nav class="sidebar">
            <!-- Display user's actual name and email -->
            <h2><?php echo $username; ?></h2>
            <p><?php echo $email; ?></p>
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
                <label for="username"><strong>Username:<strong></label><br>
                <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($admin['email']); ?>" required>
            </div>
            <div class="form-group eye-icon">
                <label for="password">Current Password:</label><br>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter current password" required>
                <i class="fa-eye-slash" id="togglePassword"></i>
            </div>
            <div class="form-group eye-icon">
                <label for="new_password">New Password (Optional):</label><br>
                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter new password">
                <i class="fa-eye-slash" id="toggleNewPassword"></i>
            </div>
            <div class="form-group eye-icon">
                <label for="confirm_password">Confirm New Password:</label><br>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm new password">
                <i class="fa-eye-slash" id="toggleConfirmPassword"></i>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['error']; unset($_SESSION['error']); ?>',
            });
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['success']; unset($_SESSION['success']); ?>',
            });
        <?php endif; ?>

        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        const toggleNewPassword = document.querySelector('#toggleNewPassword');
        const new_password = document.querySelector('#new_password');
        toggleNewPassword.addEventListener('click', function () {
            const type = new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            new_password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirm_password = document.querySelector('#confirm_password');
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
