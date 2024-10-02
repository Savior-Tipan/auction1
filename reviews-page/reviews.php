<?php
session_start();
include '../includes/config.php'; // Include your database connection file

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch user ID
$query = "SELECT user_id FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$user = $stmt->fetch();
$user_id = $user['user_id'] ?? null;

$reviews = [];
$hasCompletedTransaction = false;

// Check if the user has completed any transactions
$transactionQuery = "SELECT COUNT(*) AS count FROM transactions WHERE buyer_id = ? AND payment_status = 'completed'";
$stmt = $pdo->prepare($transactionQuery);
$stmt->execute([$user_id]);
$transactionCount = $stmt->fetch();
if ($transactionCount['count'] > 0) {
    $hasCompletedTransaction = true; // User has completed a transaction
}

// Initialize messages
$warningMessage = ''; 
$successMessage = '';

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'] ?? null;
    $review_text = $_POST['review_text'] ?? '';

    // Validate rating value
    if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
        $warningMessage = "Invalid rating value. Please provide a rating between 1 and 5."; // Set warning message
    }

    // Initialize arrays
    $images = [];
    $videos = [];
    
    // Handle image uploads
    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['name'] as $key => $name) {
            if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
                // Skip file if there's an upload error
                continue;
            }

            $file_type = $_FILES['images']['type'][$key];
            $original_name = basename($name);
            $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

            // Define allowed extensions for security
            $allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

            // Adjust the target directory based on your directory structure
            $target_dir = "uploads/images/"; // Use a separate directory for images

            // Ensure the target directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Sanitize file name to prevent security issues
            $safe_name = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $original_name);
            $target_file = $target_dir . uniqid() . "_" . $safe_name; // Prefix with unique ID

            // Check for valid file type and size (max 5MB for images)
            if (in_array($file_extension, $allowed_image_extensions) && $_FILES['images']['size'][$key] < 5000000) {
                if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                    $images[] = basename($target_file);
                } else {
                    $warningMessage = "Failed to upload image: " . htmlspecialchars($original_name);
                }
            } else {
                $warningMessage = "Invalid image file or file too large: " . htmlspecialchars($original_name);
            }
        }
    }

    // Handle video uploads
    if (isset($_FILES['videos'])) {
        foreach ($_FILES['videos']['name'] as $key => $name) {
            if ($_FILES['videos']['error'][$key] !== UPLOAD_ERR_OK) {
                // Skip file if there's an upload error
                continue;
            }

            $file_type = $_FILES['videos']['type'][$key];
            $original_name = basename($name);
            $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

            // Define allowed extensions for security
            $allowed_video_extensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'];

            // Adjust the target directory based on your directory structure
            $target_dir = "uploads/videos/"; // Use a separate directory for videos

            // Ensure the target directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Sanitize file name to prevent security issues
            $safe_name = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $original_name);
            $target_file = $target_dir . uniqid() . "_" . $safe_name; // Prefix with unique ID

            // Check for valid file type and size (max 50MB for videos)
            if (in_array($file_extension, $allowed_video_extensions) && $_FILES['videos']['size'][$key] < 50000000) {
                if (move_uploaded_file($_FILES['videos']['tmp_name'][$key], $target_file)) {
                    $videos[] = basename($target_file);
                } else {
                    $warningMessage = "Failed to upload video: " . htmlspecialchars($original_name);
                }
            } else {
                $warningMessage = "Invalid video file or file too large: " . htmlspecialchars($original_name);
            }
        }
    }

    // Get the last completed transaction
    $transactionQuery = "SELECT transaction_id FROM transactions WHERE buyer_id = ? AND payment_status = 'completed' ORDER BY transaction_time DESC LIMIT 1";
    $stmt = $pdo->prepare($transactionQuery);
    $stmt->execute([$user_id]);
    $transaction = $stmt->fetch();
    $transaction_id = $transaction['transaction_id'] ?? null;

    // Proceed if no warnings and transaction exists
    if (empty($warningMessage) && $transaction_id) {
        $query = "INSERT INTO userreviews (transaction_id, username, rating, review_text, review_time, images, videos) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$transaction_id, $username, $rating, $review_text, json_encode($images), json_encode($videos)])) {
            $_SESSION['success_message'] = "Your review has been posted!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $errorInfo = $stmt->errorInfo();
            $warningMessage = "Error: " . $errorInfo[2]; // Set warning message
        }
    } elseif (!$transaction_id) {
        $warningMessage = "No completed transactions found.";
    }
}

// Fetch reviews ordered by the most recent
$reviews_query = "SELECT * FROM userreviews ORDER BY review_time DESC";
$stmt = $pdo->query($reviews_query);
$reviews = $stmt->fetchAll();

// Retrieve and clear success message
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    
</head>
<body>
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="../index.php" class="logo">
                <img src="./images/jma-logo.png" width="128" height="63" alt="Logo">
            </a>
            <nav class="navbar">
                <ul class="navbar-list">
                    <li><a href="../index.php" class="navbar-link">Home</a></li>
                    <li><a href="#" class="navbar-link">About</a></li>
                    <li><a href="../login/logout.php" class="navbar-link">Logout</a></li>
                    <li><a href="#" class="navbar-link">Product</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container mt-5">
        <h2 class="page-title">Reviews/Feedback</h2>

        <?php if ($hasCompletedTransaction): ?>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success mb-3">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($warningMessage)): ?>
                <div class="alert alert-warning mb-3">
                    <?= htmlspecialchars($warningMessage) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="mb-4" id="reviewForm">
                <div class="form-group">
                    <label for="rating">Rating<span class="text-danger"></span></label>
                    <div id="starRating" class="star-rating">
                        <span class="star" data-value="1" tabindex="0">&#9733;</span>
                        <span class="star" data-value="2" tabindex="0">&#9733;</span>
                        <span class="star" data-value="3" tabindex="0">&#9733;</span>
                        <span class="star" data-value="4" tabindex="0">&#9733;</span>
                        <span class="star" data-value="5" tabindex="0">&#9733;</span>
                    </div>
                    <input type="hidden" name="rating" id="rating" required>
                    <div class="error" id="ratingError" style="display: none;">Please provide a rating.</div>
                </div>
                <div class="form-group">
                    <label for="review_text">Review Comment (Optional)</label>
                    <textarea class="form-control" id="review_text" name="review_text" rows="3" placeholder="Share your experience..."></textarea>
                </div>
                <div class="form-group">
                    <label for="images">Upload Photos (Max 5 Images) (Optional)</label>
                    <input type="file" class="form-control-file" id="images" name="images[]" accept="image/*" multiple>
                    <div class="media-preview" id="imagesPreview"></div>
                    <div class="error" id="imagesLimitError" style="display: none;">Only 5 images are allowed.</div>
                </div>
                <div class="form-group">
                    <label for="videos">Upload Videos (Max 2 Videos) (Optional)</label>
                    <input type="file" class="form-control-file" id="videos" name="videos[]" accept="video/*" multiple>
                    <div class="media-preview" id="videosPreview"></div>
                    <div class="error" id="videosLimitError" style="display: none;">Only 2 videos are allowed.</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        <?php else: ?>
            <p class="text-noreviewpost">You need to complete a transaction to post a review.</p>
        <?php endif; ?>

        <h4 class="mb-3" id="allReviewsHeader">All Reviews</h4>

        <div class="reviews">
            <?php if (empty($reviews)): ?>
                <div class="no-reviews-message">
                <p>No reviews yet. Be the first to submit a review!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title d-flex align-items-center justify-content-between">
                                <span><?= htmlspecialchars($review['username']) ?></span>
                                <span class="review-stars">
                                    <?php 
                                    $rating = (int)$review['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $rating) ? '&#9733;' : '&#9734;'; // Filled or empty star
                                    }
                                    ?>
                                </span>
                            </h5>
                            <?php if (!empty($review['review_text'])): ?>
                                <p class="card-text"><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                            <?php endif; ?>
                            <p class="card-text"><small class="text-muted"><?= htmlspecialchars($review['review_time']) ?></small></p>
                            <?php 
                            $images = json_decode($review['images'], true);
                            $videos = json_decode($review['videos'], true);
                            ?>
                            <div class="media-preview">
                                <?php if (!empty($images)): ?>
                                    <?php foreach ($images as $image): ?>
                                        <div class="media-item">
                                            <img src="uploads/images/<?= htmlspecialchars($image) ?>" alt="Review Image" data-toggle="modal" data-target="#mediaModal" data-type="image" data-src="uploads/images/<?= htmlspecialchars($image) ?>">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (!empty($videos)): ?>
                                    <?php foreach ($videos as $video): ?>
                                        <div class="media-item">
                                            <video controls data-toggle="modal" data-target="#mediaModal" data-type="video" data-src="uploads/videos/<?= htmlspecialchars($video) ?>">
                                                <source src="uploads/videos/<?= htmlspecialchars($video) ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="mediaModalLabel">Media Viewer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mediaContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS (Use the non-slim version of jQuery for AJAX if needed) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" 
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" 
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');
            const ratingError = document.getElementById('ratingError');

            // Handle star selection
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;
                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i < value; i++) {
                        stars[i].classList.add('selected');
                    }
                    ratingError.style.display = 'none'; // Hide error message on valid rating
                });

                star.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        this.click();
                        e.preventDefault();
                    }
                });
            });

            // Handle form submission for rating validation
            const reviewForm = document.getElementById('reviewForm');
            reviewForm.addEventListener('submit', function(e) {
                if (!ratingInput.value) {
                    e.preventDefault();
                    ratingError.style.display = 'block'; // Show error message if no rating
                } else {
                    ratingError.style.display = 'none'; // Hide error message on valid rating
                }
            });

            // Media Preview for Images
            const imagesInput = document.getElementById('images');
            const imagesPreview = document.getElementById('imagesPreview');
            const imagesLimitError = document.getElementById('imagesLimitError');

            imagesInput.addEventListener('change', function() {
                updateImagePreview(this.files);
            });

            function updateImagePreview(files) {
                imagesPreview.innerHTML = '';
                let fileArray = Array.from(files);

                if (fileArray.length > 5) {
                    imagesLimitError.style.display = 'block';
                    fileArray = fileArray.slice(0, 5);
                    imagesInput.files = createFileList(fileArray);
                } else {
                    imagesLimitError.style.display = 'none';
                }

                fileArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const mediaItem = document.createElement('div');
                        mediaItem.classList.add('media-item');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.setAttribute('data-type', 'image');
                        img.setAttribute('data-src', e.target.result);

                        // Create Delete Button
                        const deleteButton = document.createElement('button');
                        deleteButton.classList.add('delete-btn');
                        deleteButton.title = 'Delete Image';
                        deleteButton.innerHTML = '&times;';
                        deleteButton.onclick = (e) => {
                            e.preventDefault();
                            mediaItem.remove();

                            // Create a new FileList excluding the deleted file
                            const newFiles = Array.from(imagesInput.files).filter((_, i) => i !== index);
                            imagesInput.files = createFileList(newFiles);
                            updateImagePreview(newFiles);
                        };

                        mediaItem.appendChild(img);
                        mediaItem.appendChild(deleteButton);
                        imagesPreview.appendChild(mediaItem);
                    }
                    reader.readAsDataURL(file);
                });
            }

            // Media Preview for Videos
            const videosInput = document.getElementById('videos');
            const videosPreview = document.getElementById('videosPreview');
            const videosLimitError = document.getElementById('videosLimitError');

            videosInput.addEventListener('change', function() {
                updateVideoPreview(this.files);
            });

            function updateVideoPreview(files) {
                videosPreview.innerHTML = '';
                let fileArray = Array.from(files);

                if (fileArray.length > 2) {
                    videosLimitError.style.display = 'block';
                    fileArray = fileArray.slice(0, 2);
                    videosInput.files = createFileList(fileArray);
                } else {
                    videosLimitError.style.display = 'none';
                }

                fileArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const mediaItem = document.createElement('div');
                        mediaItem.classList.add('media-item');

                        const video = document.createElement('video');
                        video.controls = true;
                        video.src = e.target.result;
                        video.setAttribute('data-type', 'video');
                        video.setAttribute('data-src', e.target.result);

                        // Create Delete Button
                        const deleteButton = document.createElement('button');
                        deleteButton.classList.add('delete-btn');
                        deleteButton.title = 'Delete Video';
                        deleteButton.innerHTML = '&times;';
                        deleteButton.onclick = (e) => {
                            e.preventDefault();
                            mediaItem.remove();

                            // Create a new FileList excluding the deleted file
                            const newFiles = Array.from(videosInput.files).filter((_, i) => i !== index);
                            videosInput.files = createFileList(newFiles);
                            updateVideoPreview(newFiles);
                        };

                        mediaItem.appendChild(video);
                        mediaItem.appendChild(deleteButton);
                        videosPreview.appendChild(mediaItem);
                    }
                    reader.readAsDataURL(file);
                });
            }

            // Function to create a FileList from an array of files
            function createFileList(files) {
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                return dataTransfer.files;
            }

            // Media Modal for Image and Video Viewing
            const mediaModal = document.getElementById('mediaModal');
            const mediaContainer = document.getElementById('mediaContainer');

            $('.media-preview').on('click', 'img, video', function() {
                const type = $(this).data('type');
                const src = $(this).data('src');

                mediaContainer.innerHTML = '';
                if (type === 'image') {
                    const img = document.createElement('img');
                    img.src = src;
                    img.className = 'img-fluid';
                    mediaContainer.appendChild(img);
                } else if (type === 'video') {
                    const video = document.createElement('video');
                    video.controls = true;
                    video.className = 'img-fluid';
                    const source = document.createElement('source');
                    source.src = src;
                    source.type = 'video/mp4';
                    video.appendChild(source);
                    mediaContainer.appendChild(video);
                }
                $(mediaModal).modal('show');
            });
        });
    </script>
</body>
</html>

