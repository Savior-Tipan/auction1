<?php
// save_payment_method.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page
    header("Location: ../login/login.php");
    exit();
}

require '../includes/config.php'; // Adjust the path as necessary

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $transaction_id = isset($_POST['transaction_id']) ? intval($_POST['transaction_id']) : 0;
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

    // Validate payment method
    $valid_methods = ['cash', 'bank_transfer', 'finance'];
    if (!in_array($payment_method, $valid_methods)) {
        // Store error in session and redirect back
        $_SESSION['error'] = "Invalid payment method selected.";
        header("Location: payment_procedure.php");
        exit();
    }

    // Fetch user details
    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header("Location: payment_procedure.php");
        exit();
    }

    $user_id = $user['user_id'];

    // Check if the transaction belongs to the user and is pending
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE transaction_id = :transaction_id AND buyer_id = :buyer_id AND payment_status = 'pending'");
    $stmt->execute(['transaction_id' => $transaction_id, 'buyer_id' => $user_id]);
    $transaction = $stmt->fetch();

    if (!$transaction) {
        $_SESSION['error'] = "Transaction not found or already processed.";
        header("Location: payment_procedure.php");
        exit();
    }

    // Update the transaction with the selected payment method
    $stmt = $pdo->prepare("UPDATE transactions SET payment_method = :payment_method WHERE transaction_id = :transaction_id AND buyer_id = :buyer_id AND payment_status = 'pending'");
    $result = $stmt->execute([
        'payment_method' => $payment_method,
        'transaction_id' => $transaction_id,
        'buyer_id' => $user_id
    ]);

    if ($result) {
        $_SESSION['success'] = "Payment method for Transaction ID $transaction_id has been updated to " . ucfirst($payment_method) . ".";
    } else {
        $_SESSION['error'] = "Failed to update payment method for Transaction ID $transaction_id. Please try again.";
    }

    // Redirect back to payment_procedure.php
    header("Location: payment_procedure.php");
    exit();
} else {
    // If accessed without POST data
    header("Location: payment_procedure.php");
    exit();
}
?>
