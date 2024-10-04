<?php
// payment_procedure.php

session_start();
require '../includes/config.php'; // Adjust the path as necessary

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page
    header("Location: ../login/login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user details
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$user_id = $user['user_id'];

// Check if the user has any transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE buyer_id = :buyer_id");
$stmt->execute(['buyer_id' => $user_id]);
$transactions = $stmt->fetchAll();

if (empty($transactions)) {
    // Redirect back with a message
    echo "<script>alert('You do not have any transactions.'); window.location.href='../index.php';</script>";
    exit();

}

// Initialize a counter for numbering transactions
$transaction_count = 1;

// Handle session messages
$success_message = '';
$error_message = '';

if (isset($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Procedure</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../paymentprocedure/styles.css">
    
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-secondary back-button">Back to Home</a>
        
        <h2 class="mb-4">Transaction: Payment Procedure Confirmation</h2>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php foreach ($transactions as $transaction): ?>
            <div class="transaction">
                <h4>Transaction <?php echo $transaction_count; ?></h4>
                <p><strong>Truck ID:</strong> <?php echo htmlspecialchars($transaction['truck_id']); ?></p>
                <p><strong>Final Bid:</strong> <?php echo htmlspecialchars($transaction['final_bid']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($transaction['payment_method'] ?? 'None'); ?></p>
                <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($transaction['payment_status']); ?></p>
                
                <?php if ($transaction['payment_status'] === 'pending'): ?>
                    <div class="payment-methods">
                        <!-- Buttons to trigger modals -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cashModal<?php echo $transaction_count; ?>">
                            Pay with Cash
                        </button>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bankTransferModal<?php echo $transaction_count; ?>">
                            Pay via Bank Transfer
                        </button>
                        
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#financeModal<?php echo $transaction_count; ?>">
                            Pay via Finance
                        </button>
                    </div>
                    
                    <!-- Cash Payment Modal -->
                    <div class="modal fade" id="cashModal<?php echo $transaction_count; ?>" tabindex="-1" aria-labelledby="cashModalLabel<?php echo $transaction_count; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="cashModalLabel<?php echo $transaction_count; ?>">Cash Payment Instructions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <ol>
                                <li>Receive a notification confirming your winning bid in the auction.</li>
                                <li>Gather the exact cash amount needed for your bid.</li>
                                <li>Hand over the cash to the staff at the payment location.</li>
                                <li>Receive a receipt as proof of your payment.</li>
                                <li>Wait for confirmation from the auction platform that your payment has been received.</li>
                                <li>Once confirmed, arrangements will be made for you to receive ownership of the truck.</li>
                                <li>Optionally, provide feedback on your experience with the auction platform.</li>
                            </ol>
                          </div>
                          <div class="modal-footer">
                            <form method="POST" action="save_payment_method.php">
                                <input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($transaction['transaction_id']); ?>">
                                <input type="hidden" name="payment_method" value="cash">
                                <button type="submit" class="btn btn-primary">Confirm Cash Payment</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Bank Transfer Modal -->
                    <div class="modal fade" id="bankTransferModal<?php echo $transaction_count; ?>" tabindex="-1" aria-labelledby="bankTransferModalLabel<?php echo $transaction_count; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="bankTransferModalLabel<?php echo $transaction_count; ?>">Bank Transfer Instructions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <ol>
                                <li>Receive a notification confirming your winning bid in the auction.</li>
                                <li>Copy bank account details provided by the auction platform for making the transfer.</li>
                                <li>Log in to your bank's online banking portal or visit a branch to initiate the transfer.</li>
                                <li>Enter the purchase ID and lot # in the transfer description to ensure proper identification.</li>
                                <li>Submit the transfer request and ensure that the bid amount is transferred accurately to the designated bank account.</li>
                                <li>Wait for confirmation from your bank that the transfer has been successfully processed.</li>
                                <li>Once the auction platform receives the transfer, you will receive a notification confirming the payment.</li>
                                <li>With the payment confirmed, arrangements will be made for you to receive ownership of the truck.</li>
                                <li>Optionally, provide feedback on your experience with the auction platform.</li>
                            </ol>
                          </div>
                          <div class="modal-footer">
                            <form method="POST" action="save_payment_method.php">
                                <input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($transaction['transaction_id']); ?>">
                                <input type="hidden" name="payment_method" value="bank_transfer">
                                <button type="submit" class="btn btn-success">Confirm Bank Transfer</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Finance Payment Modal -->
                    <div class="modal fade" id="financeModal<?php echo $transaction_count; ?>" tabindex="-1" aria-labelledby="financeModalLabel<?php echo $transaction_count; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="financeModalLabel<?php echo $transaction_count; ?>">Finance Payment Instructions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <ol>
                                <li>Receive a notification confirming your winning bid in the auction.</li>
                                <li>Contact a financing partner or institution to discuss financing options.</li>
                                <li>Prepare necessary documents, including your winning bid notification and identification.</li>
                                <li>Submit the financing application along with required documentation.</li>
                                <li>Review and agree to the financing terms, including interest rates and repayment schedules.</li>
                                <li>Once approved, funds will be disbursed to the auction platform for the winning bid amount.</li>
                                <li>Wait for confirmation from the auction platform that your payment has been received.</li>
                                <li>With the payment confirmed, arrangements will be made for you to receive ownership of the truck.</li>
                                <li>Optionally, provide feedback on your experience with the auction platform.</li>
                            </ol>
                          </div>
                          <div class="modal-footer">
                            <form method="POST" action="save_payment_method.php">
                                <input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($transaction['transaction_id']); ?>">
                                <input type="hidden" name="payment_method" value="finance">
                                <button type="submit" class="btn btn-warning">Confirm Finance Payment</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php else: ?>
                    <p class="text-success">Payment has been confirmed for this transaction.</p>
                <?php endif; ?>
            </div>
            <?php $transaction_count++; ?>
        <?php endforeach; ?>

        <p class="text-info">If you have any inquiries regarding your payment, please contact our support team for assistance.091223445321</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
