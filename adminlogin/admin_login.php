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
        header("Location: update_admin.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: admin_login.php");
        exit;
    }
}
$savedUsername = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$savedPassword = isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; // Caution: Avoid storing passwords in plain text
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffffff, #e0e0e0); /* Background gradient */
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
            padding: 20px;
        }
        .form-container h2 {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #ff4c4c;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #ff4c4c;
        }
        .btn-custom {
            background-color: #ff4c4c;
            color: white;
            border-radius: 25px;
            padding: 12px 25px;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
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

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .form-container {
                padding: 20px;
                margin: 0 10px;
            }
            .form-container h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="form-container">
        <h2>Admin Login</h2>

        <!-- Display error message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="admin_login.php" method="POST">
        <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($savedUsername); ?>" required>
            </div>
            <div class="form-group eye-icon">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($savedPassword); ?>" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <button type="submit" class="btn btn-custom">Login</button>
        </form>
    </div>
</div>
<script>
    const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // Toggle the eye / eye-slash icon
            this.classList.toggle('fa-eye-slash');
        });
</script>
</body>
</html>
