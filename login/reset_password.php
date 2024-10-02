<?php
require '../includes/config.php'; // Include your database connection

$showForm = true; // Flag to determine whether to show the form
$message = ''; // Message to display to the user

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql = "SELECT user_id, reset_token, token_expiry FROM users WHERE reset_token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if token has expired
        if ($user['token_expiry'] > date("Y-m-d H:i:s")) {
            if (isset($_POST['password'])) {
                $newPasswordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);

                // Update user's password and clear the reset token
                $sql = "UPDATE users 
                        SET password_hash = :password_hash, reset_token = NULL, token_expiry = NULL 
                        WHERE user_id = :user_id AND reset_token = :token";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':password_hash', $newPasswordHash);
                $stmt->bindParam(':user_id', $user['user_id']);
                $stmt->bindParam(':token', $token);

                if ($stmt->execute()) {
                    $message = 'Your password has been successfully reset.';
                    $showForm = false; // Hide form on success
                } else {
                    $message = 'There was an error resetting your password. Please try again.';
                }
            }
        } else {
            // Token has expired
            $message = 'This reset token has expired. Please request a new password reset.';
            $showForm = false; // Hide form if token is expired
        }
    } else {
        // Invalid token
        $message = 'Invalid or expired reset link. Please request a new one.';
        $showForm = false; // Hide form if token is invalid
    }
} else {
    $message = 'No token provided. Please try again.';
    $showForm = false; // Hide form if no token
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | JMA Trucks Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #ffffff, #e0e0e0);
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        .forgot-password-container {
            background-color: #fff;
            padding: 20px; /* Reduced padding for smaller devices */
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 90%; /* Make it more responsive on smaller devices */
            text-align: center;
        }
        h2 {
            color: #ff4c4c;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .form-control {
            border-radius: 30px;
            border: 2px solid #ff4c4c;
            padding: 12px 20px;
            font-size: 16px;
        }
        .btn-custom {
            background-color: #ff4c4c;
            color: white;
            border-radius: 30px;
            padding: 12px 25px;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 15px;
        }
        .btn-custom:hover {
            background-color: #ff3232;
            transform: scale(1.05);
        }
        .error-message {
            color: black; /* Change message color to black */
            margin-top: 20px;
            font-size: 20px; /* Adjust font size for readability */
        }
    </style>
</head>
<body>

<?php if ($showForm): ?>
<div class="forgot-password-container">
    <h2 class="text-center">Reset Password</h2>
    
    <form id="resetPasswordForm" action="" method="post">
        <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">Reset Password</button>
    </form>
</div>
<?php endif; ?>

<?php if ($message): ?>
<div class="error-message text-center">
    <?= $message; ?>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Handle success message display using SweetAlert
    <?php if (!$showForm && $message): ?>
    Swal.fire({
        title: 'Notice!',
        text: '<?= $message; ?>',
        icon: 'info',
        confirmButtonText: 'OK'
    }).then(() => {
        // Redirect to the login page if password reset was successful
        <?php if ($successMessage): ?>
        window.location.href = 'login.php';
        <?php endif; ?>
    });
    <?php endif; ?>
</script>

</body>
</html>
