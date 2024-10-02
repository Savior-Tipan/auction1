<?php
session_start();
require '../includes/config.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch categories for the dropdown
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $truck_name = isset($_POST['truck_name']) ? trim($_POST['truck_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $model_year = isset($_POST['model_year']) ? trim($_POST['model_year']) : '';
    $starting_bid = isset($_POST['starting_bid']) ? trim($_POST['starting_bid']) : '';
    $auction_start = isset($_POST['auction_start']) ? trim($_POST['auction_start']) : '';
    $auction_end = isset($_POST['auction_end']) ? trim($_POST['auction_end']) : '';
    $category_id = isset($_POST['category_id']) ? trim($_POST['category_id']) : '';
    $status = 'active'; // Default status when creating a new truck auction

    // Validate inputs
    if (empty($truck_name) || empty($model_year) || empty($starting_bid) || empty($auction_start) || empty($auction_end) || empty($category_id)) {
        $_SESSION['error'] = "All required fields must be filled!";
        header("Location: add_products.php");
        exit;
    }

    // Insert the truck details into the Trucks table
    try {
        $pdo->beginTransaction();

        // Insert the truck details
        $stmt = $pdo->prepare("
            INSERT INTO Trucks (truck_name, description, model_year, starting_bid, current_bid, auction_start, auction_end, category_id, status) 
            VALUES (:truck_name, :description, :model_year, :starting_bid, 0.00, :auction_start, :auction_end, :category_id, :status)
        ");
        $stmt->execute([
            ':truck_name' => $truck_name,
            ':description' => $description,
            ':model_year' => $model_year,
            ':starting_bid' => $starting_bid,
            ':auction_start' => $auction_start,
            ':auction_end' => $auction_end,
            ':category_id' => $category_id,
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
                    $stmt->execute([
                        ':truck_id' => $truck_id,
                        ':image_path' => $image_path
                    ]);
                } else {
                    throw new Exception("Failed to upload image: " . $image_name);
                }
            }
        }

        // Handle video uploads
        if (!empty($_FILES['videos']['name'][0])) {
            foreach ($_FILES['videos']['name'] as $key => $video_name) {
                if ($_FILES['videos']['error'][$key] === UPLOAD_ERR_OK) {
                    $video_tmp_name = $_FILES['videos']['tmp_name'][$key];
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
                    // Handle file upload errors
                    switch ($_FILES['videos']['error'][$key]) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            throw new Exception("File is too large: " . $video_name);
                        case UPLOAD_ERR_PARTIAL:
                            throw new Exception("File was only partially uploaded: " . $video_name);
                        case UPLOAD_ERR_NO_FILE:
                            throw new Exception("No file was uploaded: " . $video_name);
                        default:
                            throw new Exception("Unknown error occurred while uploading: " . $video_name);
                    }
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
            background: linear-gradient(135deg, #ffffff, #e0e0e0);
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: #fff;
            color: #333;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin: 60px auto;
        }
        h3 {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            color: #ff4c4c;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #ff4c4c;
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
        }
        .btn-primary:hover {
            background-color: #e32f2f;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Add Truck to Auction</h3>
        <form action="add_products.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="truck_name">Truck Name</label>
                <input type="text" name="truck_name" class="form-control" id="truck_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description"></textarea>
            </div>
            <div class="form-group">
                <label for="model_year">Model Year</label>
                <input type="number" name="model_year" class="form-control" id="model_year" required>
            </div>
            <div class="form-group">
                <label for="starting_bid">Starting Bid (₱)</label>
                <input type="number" step="0.01" name="starting_bid" class="form-control" id="starting_bid" required>
            </div>
            <div class="form-group">
                <label for="auction_start">Auction Start Date & Time</label>
                <input type="datetime-local" name="auction_start" class="form-control" id="auction_start" required>
            </div>
            <div class="form-group">
                <label for="auction_end">Auction End Date & Time</label>
                <input type="datetime-local" name="auction_end" class="form-control" id="auction_end" required>
            </div>
            <div class="form-group">
                <label for="category_id">Truck Category</label>
                <select name="category_id" class="form-control" id="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="images">Truck Images</label>
                <input type="file" name="images[]" class="form-control-file" id="images" multiple>
            </div>
            <div class="form-group">
                <label for="videos">Truck Videos</label>
                <input type="file" name="videos[]" class="form-control-file" id="videos" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Add Truck</button>
        </form>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const images = document.getElementById('images').files;
        const videos = document.getElementById('videos').files;
        const maxFileSize = 256 * 1024 * 1024; // 256MB limit for videos
        
        for (let i = 0; i < videos.length; i++) {
            if (videos[i].size > maxFileSize) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: `The video ${videos[i].name} exceeds the 256MB limit.`,
                });
                return;
            }
        }
    });
    </script>
</body>
</html>


