<?php
session_start();

// Prevent duplicate form submission by redirecting if the form has already been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // After processing, redirect to the same page to clear POST data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve error message from the session if present
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']); // Clear error after displaying it

// Retrieve cookies for 'remember me' functionality
$savedUsername = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$savedPassword = isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; // Caution: Avoid storing passwords in plain text
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JMA Trucks Solution Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #ffffff, #e0e0e0); /* Example gradient */
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        .login-container {
            display: flex;
            width: 90%; /* Adjust width for smaller screens */
            max-width: 1100px; /* Optional: set a maximum width */
            max-height: 550px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            overflow: hidden;
        }

        .login-container .left-section {
            background: linear-gradient(135deg, #ff6f6f, #f44336); /* Stronger red gradient */
            color: white;
            padding: 60px 50px;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Center horizontally */
        }

        .login-container .left-section h1 {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-container .left-section h2 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .login-container .left-section p {
            font-size: 18px;
            margin-top: 15px;
            line-height: 1.6;
        }

        .login-container .left-section p strong {
            font-size: 20px;
        }

        .login-container .left-section .register-link {
            margin-top: 30px;
        }

        .login-container .left-section .register-link a {
            color: #ff4c4c;
            background-color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-container .left-section .register-link a:hover {
            background-color: #ff3232;
            color: white;
        }

        .login-container .right-section {
            padding: 60px;
            background: linear-gradient(135deg, #ffffff, #e0e0e0); /* Example gradient */
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-container .right-section h2 {
            margin-bottom: 35px;
            font-weight: bold;
            color: #ff4c4c;
            font-size: 28px;
        }

        .form-control {
            border-radius: 30px;
            border: 2px solid #ff4c4c;
            padding: 10px 20px;
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

        .form-group.custom-form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-text-custom {
            font-size: 14px;
            color: #ff4c4c;
            text-align: right;
            margin-left: auto; /* Aligns the link to the right */
        }

        .login-container .right-section p {
            font-size: 16px;
            color: #666;
        }

        .logo {
            max-width: 140px; /* Adjust size as needed */
            height: auto;
            margin-bottom: 15px; /* Space between logo and heading */
        }

        /* Eye Icon Styling */
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

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column; /* Stack sections vertically */
                padding: 0 15px; /* Add padding for smaller screens */
            }
            .login-container .left-section {
                display: none; /* Hide left section on small screens */
            }
            .login-container .right-section {
                width: 100%; /* Full width for the right section */
                padding: 20px;
                box-shadow: none; /* Optional: remove shadow on small screens */
                border-radius: 0; /* Optional: remove border radius on small screens */
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Section -->
        <div class="left-section">
            <img src="../logo/jmalogo.png" alt="JMA Logo" class="logo">
            <h1>JMA Trucks Solution</h1>
            <h2>Online Auction</h2>
            <p><strong>Hey there!</strong></p>
            <p>Welcome! You are just one step away from your dream truck.</p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <h2>Log In</h2>

            <?php
            // Display error message if present
            if ($error) {
                echo "<div class='alert alert-danger' role='alert'>{$error}</div>";
            }
            ?>

            <form action="authenticate.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($savedUsername); ?>" required>
                </div>
                <div class="form-group eye-icon">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($savedPassword); ?>" required>
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>
                <!-- Remember Me Checkbox -->
                <div class="form-group custom-form-group">
                    <span class="form-text-custom"><a href="forgot_password.php">Forgot password?</a></span>
                </div>
                <button type="submit" class="btn btn-custom">Log In</button>
            </form>
            <p class="mt-3 text-center">Don’t have an account? <a href="signup.php" class="btn-link">REGISTER NOW</a></p>
            
            <p class="mt-3 text-center">"Driven by quality, powered by performance"</p>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // Toggle the eye / eye-slash icon
            this.classList.toggle('fa-eye-slash');
        });

        // Display SweetAlert if error is present
        <?php if ($error): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?php echo $error; ?>',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>
        
    </script>
</body>
</html>
