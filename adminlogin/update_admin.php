<?php
session_start();
require '../includes/config.php'; // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
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
        header("Location: update_admin.php");
        exit;
    }

    // Verify the current password before updating
    if (!password_verify($password, $admin['password_hash'])) {
        $_SESSION['error'] = "Current password is incorrect!";
        header("Location: update_admin.php");
        exit;
    }

    // Check if the new password fields are filled and match
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords do not match!";
        header("Location: update_admin.php");
        exit;
    }

    try {
        // Update admin data (including password if set)
        if (!empty($new_password)) {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare("UPDATE Admins SET username = :username, email = :email, password_hash = :password_hash WHERE admin_id = :admin_id");
            $update_stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':admin_id' => $admin_id
            ]);
        } else {
            $update_stmt = $pdo->prepare("UPDATE Admins SET username = :username, email = :email WHERE admin_id = :admin_id");
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

    header("Location: update_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            background: linear-gradient(135deg, #ffffff, #e0e0e0); /* Background gradient */
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        .form-container {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            background-color: #fff;
            color: #333;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin: 60px auto;
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
            top: 72%;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h3>My Profile</h3>
        <form action="update_admin.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($admin['email']); ?>" required>
            </div>
            <div class="form-group eye-icon">
                <label for="password">Current Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter current password" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <div class="form-group eye-icon">
                <label for="new_password">New Password (Optional)</label>
                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter new password">
                <i class="fas fa-eye" id="toggleNewPassword"></i>
            </div>
            <div class="form-group eye-icon">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm new password">
                <i class="fas fa-eye" id="toggleConfirmPassword"></i>
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
