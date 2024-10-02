<?php
session_start(); // Start session for storing messages
require '../includes/config.php'; // Include your database connection
require '../login/phpmailer/vendor/autoload.php'; // Load Composer's autoloader for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Initialize message variables
$message = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

        // Update token in the database
        $sql = "UPDATE users SET reset_token = :token, token_expiry = :expiry WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            // Prepare the reset link
            $resetLink = "http://localhost/auction/login/reset_password.php?token=" . urlencode($token);

            // Set up PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'ryanmanalo172@gmail.com'; // SMTP username
                $mail->Password = 'gzyk vrnz xemh kdsb'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom('no-reply@yourwebsite.com', 'JMA Trucks Solution');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Hello, <br><br> You requested a password reset. Click the link below to reset your password:<br>
                              <a href='$resetLink'>$resetLink</a><br><br> If you did not request this, please ignore this email.";

                $mail->send();

                // Store success message in session
                $_SESSION['successMessage'] = 'A password reset link has been sent to your email address. Please check your inbox.';
                header('Location: forgot_password.php');
                exit();
            } catch (Exception $e) {
                // Error sending the email
                $_SESSION['message'] = 'Message could not be sent. Please try again later.';
                header('Location: forgot_password.php');
                exit();
            }
        } else {
            // Error updating the token
            $_SESSION['message'] = 'There was an error updating your request. Please try again.';
            header('Location: forgot_password.php');
            exit();
        }
    } else {
        // Email not found in the database
        $_SESSION['message'] = 'No account associated with this email. Please try again.';
        header('Location: forgot_password.php');
        exit();
    }
}

// Retrieve messages from session if available
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']); // Clear after displaying
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | JMA Trucks Solution</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 90%;
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
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>

        <form id="forgotPasswordForm" action="" method="post">
            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
            </div>
            <button type="submit" class="btn btn-custom">Send Reset Link</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($successMessage): ?>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo addslashes($successMessage); ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php'; // Redirect to login page
            }
        });
        <?php endif; ?>

        <?php if ($message): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo addslashes($message); ?>',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        <?php endif; ?>
    });
</script>

</body>
</html>
