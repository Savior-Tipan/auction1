<?php
session_start();
require '../includes/config.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../adminlogin/admin_login.php");
    exit;
}

// Fetch categories for the dropdown
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$makersStmt = $pdo->query("SELECT * FROM TruckMakers");
$makers = $makersStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $truck_name = isset($_POST['truck_name']) ? trim($_POST['truck_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $model_year = isset($_POST['model_year']) ? trim($_POST['model_year']) : '';
    $starting_bid = isset($_POST['starting_bid']) ? trim($_POST['starting_bid']) : '';
    $auction_start = isset($_POST['auction_start']) ? trim($_POST['auction_start']) : '';
    $auction_end = isset($_POST['auction_end']) ? trim($_POST['auction_end']) : '';
    $category_id = isset($_POST['category_id']) ? trim($_POST['category_id']) : '';
    $maker_id = isset($_POST['maker_id']) ? trim($_POST['maker_id']) : ''; // New field for truck maker
    $status = 'active'; // Default status when creating a new truck auction

    // Validate inputs
    if (empty($truck_name) || empty($model_year) || empty($starting_bid) || empty($auction_start) || empty($auction_end) || empty($category_id)) {
        $_SESSION['error'] = "All required fields must be filled!";
        header("Location: add_products.php");
        exit;
    }

    // Check if auction end date is earlier than the auction start date
    if (strtotime($auction_end) < strtotime($auction_start)) {
        $_SESSION['error'] = "Auction end date cannot be earlier than the start date!";
        header("Location: add_products.php");
        exit;
    }

    // Insert the truck details into the Trucks table
    try {
        $pdo->beginTransaction();

        // Insert the truck details
        $stmt = $pdo->prepare("
            INSERT INTO Trucks (truck_name, description, model_year, starting_bid, current_bid, auction_start, auction_end, category_id, maker_id, status) 
            VALUES (:truck_name, :description, :model_year, :starting_bid, 0.00, :auction_start, :auction_end, :category_id, :maker_id, :status)
        ");

        $stmt->execute([
            ':truck_name' => $truck_name,
            ':description' => $description,
            ':model_year' => $model_year,
            ':starting_bid' => $starting_bid,
            ':auction_start' => $auction_start,
            ':auction_end' => $auction_end,
            ':category_id' => $category_id,
            ':maker_id' => $maker_id, // Truck maker
            ':status' => $status
        ]);

        $truck_id = $pdo->lastInsertId(); // Get the inserted truck ID

        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $image_name) {
                $image_tmp_name = $_FILES['images']['tmp_name'][$key];
                $image_path = 'uploads/images/' . basename($image_name);

                if (move_uploaded_file($image_tmp_name, $image_path)) {
                    $stmt = $pdo->prepare("INSERT INTO TruckImages (truck_id, image_path) VALUES (:truck_id, :image_path)");
                    $stmt->execute(params: [
                        ':truck_id' => $truck_id,
                        ':image_path' => $image_path
                    ]);
                } else {
                    throw new Exception("Failed to upload image: " . $image_name);
                }
            }
        }

        // Allowed MIME types for videos
        $allowed_video_types = ['video/mp4', 'video/avi', 'video/mkv', 'video/x-matroska', 'video/webm'];

        // Handle video uploads
        if (!empty($_FILES['videos']['name'][0])) {
            foreach ($_FILES['videos']['name'] as $key => $video_name) {
                if ($_FILES['videos']['error'][$key] === UPLOAD_ERR_OK) {
                    $video_tmp_name = $_FILES['videos']['tmp_name'][$key];
                    $video_mime_type = mime_content_type($video_tmp_name); // Get the file's MIME type

                    if (in_array($video_mime_type, $allowed_video_types)) {
                        $video_path = 'uploads/videos/' . basename($video_name);

                        if (move_uploaded_file($video_tmp_name, $video_path)) {
                            $stmt = $pdo->prepare("INSERT INTO TruckVideos (truck_id, video_path) VALUES (:truck_id, :video_path)");
                            $stmt->execute([
                                ':truck_id' => $truck_id,
                                ':video_path' => $video_path
                            ]);
                        } else {
                            throw new Exception("Failed to upload video: " . $video_name);
                        }
                    } else {
                        throw new Exception("Unsupported video file type: " . $video_name);
                    }
                } else {
                    throw new Exception("Error uploading video: " . $video_name);
                }
            }
        }

        $pdo->commit();
        $_SESSION['success'] = "Truck added with media successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error adding truck: " . $e->getMessage();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: add_products.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Truck to Auction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            background: linear-gradient(135deg, #ffffff, #f2f2f2);
            font-family: 'Montserrat', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .form-container {
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            margin: 60px auto;
        }
        h3 {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #ff4c4c;
        }
        .form-group label {
            font-weight: 500;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #ff4c4c;
            margin-bottom: 20px;
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
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #e32f2f;
        }
        .preview-container img, .preview-container video {
            max-width: 100px;
            margin-right: 10px;
            margin-top: 10px;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
        }
        @media (min-width: 769px) {
            .form-container {
                width: 50%;
            }
        }
        #truck_name, #model_year {
            margin-bottom: 5px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h3>Add Truck to Auction</h3>
            <form action="add_products.php" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="truck_name">Truck Name</label>
                        <input type="text" name="truck_name" class="form-control" id="truck_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="model_year">Model Year</label>
                        <input type="number" name="model_year" class="form-control" id="model_year" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="starting_bid">Starting Bid (₱)</label>
                        <input type="number" step="0.01" name="starting_bid" class="form-control" id="starting_bid" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="category_id">Category</label>
                        <select name="category_id" class="form-control" id="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id']; ?>"><?= $category['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <label for="maker_id">Truck Maker</label>
                <select name="maker_id" class="form-control" id="maker_id" required>
                    <?php foreach ($makers as $maker): ?>
                        <option value="<?= $maker['maker_id']; ?>"><?= $maker['maker_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="auction_start">Auction Start Date & Time</label>
                        <input type="datetime-local" name="auction_start" class="form-control" id="auction_start" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="auction_end">Auction End Date & Time</label>
                        <input type="datetime-local" name="auction_end" class="form-control" id="auction_end" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="images">Upload Images</label>
                    <input type="file" name="images[]" class="form-control-file" id="images" accept="image/*" multiple>
                    <div id="imagePreview" class="preview-container"></div>
                </div>
                <div class="form-group">
                    <label for="videos">Upload Videos</label>
                    <input type="file" name="videos[]" class="form-control-file" id="videos" accept="video/*" multiple>
                    <div id="videoPreview" class="preview-container"></div>
                </div>
                <button type="submit" class="btn btn-primary">Add Truck</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('images').addEventListener('change', function() {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = ''; // Clear previous previews
            const files = this.files;
            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px';
                        img.style.marginRight = '10px';
                        imagePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        document.getElementById('videos').addEventListener('change', function() {
            const videoPreview = document.getElementById('videoPreview');
            videoPreview.innerHTML = ''; // Clear previous previews
            const files = this.files;
            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        video.style.maxWidth = '100px';
                        videoPreview.appendChild(video);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
        
        <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= $_SESSION['success']; ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?= $_SESSION['error']; ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
