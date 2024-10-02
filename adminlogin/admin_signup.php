<?php
session_start();
require '../includes/config.php'; // Include your database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required!";
    } else {
        // Check if username or email is already taken
        $query = $pdo->prepare("SELECT * FROM Admins WHERE username = :username OR email = :email");
        $query->execute([':username' => $username, ':email' => $email]);
        if ($query->rowCount() > 0) {
            $_SESSION['error'] = "Username or email already exists!";
        } else {
            // Hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into database
            $stmt = $pdo->prepare("INSERT INTO Admins (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)");
            if ($stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':role' => $role
            ])) {
                $_SESSION['success'] = "Admin account created successfully!";
            } else {
                $_SESSION['error'] = "Error creating admin account!";
            }
        }
    }
    // Redirect to the same page to show alert
    header("Location: admin_signup.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    
    <style>
        /* Your CSS styles remain unchanged */
        body {
            background: linear-gradient(135deg, #ffffff, #e0e0e0);
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
            padding: 20px;
        }
        .form-container h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
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
        .position-relative {
            position: relative;
        }
        .fas.fa-eye {
            color: #aaa;
            font-size: 18px;
        }
        #togglePassword {
                position: absolute;
                top: 50%;
                right: 15px; /* Adjust the right value as needed */
                transform: translateY(-50%);
                cursor: pointer;
                color: #aaa; /* Optional: Change the color to gray */
                font-size: 18px; /* Adjust the size if necessary */
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                margin: 0 10px;
            }
            .form-container h1 {
                font-size: 24px;
            }
            .form-control {
                margin-bottom: 15px;
            }
            .btn-custom {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="signup-container">
    <div class="form-container">
        <h1>Admin Sign Up</h1>

        <form action="admin_signup.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
            </div>
            <div class="form-group position-relative">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                <span id="togglePassword" class="fas fa-eye"></span>
            </div>
            <div class="form-group">
                <select name="role" class="form-control" id="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="manager">Manager</option>
                </select>
            </div>
            <button type="submit" class="btn btn-custom">Sign Up</button>
        </form>
    </div>
</div>

<!-- SweetAlert2 and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    // Check for session messages and show SweetAlert
    <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $_SESSION['error']; unset($_SESSION['error']); ?>',
        });
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $_SESSION['success']; unset($_SESSION['success']); ?>',
        }).then(() => {
            window.location.href = "admin_login.php"; // Redirect after success
        });
    <?php endif; ?>

    // Toggle password visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
