<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deleted</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Techy font */
            background-image: url('../myaccount/css/images/hero-bg.png'); /* Add your background image URL here */
            background-size: cover;
            background-position: center;
            color: #ffffff; /* Text color */
            padding: 20px;
            text-align: center;
            margin: 0;
            height: 100vh; /* Full height */
        }
        .message-box {
            background-color: rgba(0, 0, 0, 0.7); /* Black see-through background */
            padding: 30px;
            border-radius: 10px;
            margin: auto;
            max-width: 400px; /* Centered container */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        h1 {
            color: #ff4d4d; /* Red color for the title */
            font-size: 24px;
            margin-bottom: 15px;
        }
        p {
            color: #e0e0e0; /* Light gray color for the paragraph */
            font-size: 16px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #ff4d4d; /* Red color for the link */
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid #ff4d4d; /* Border matching the link color */
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
        }
        a:hover {
            background-color: #ff4d4d; /* Change background on hover */
            color: #fff; /* Change text color on hover */
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>We're Sorry to See You Go!</h1>
        <p>Your account has been successfully deleted.</p>
        <a href="../login/login.php">Return to Login</a>
    </div>
</body>
</html>
