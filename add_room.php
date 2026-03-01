<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner'){
    header("Location: login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];
$cities = mysqli_query($conn, "SELECT * FROM cities");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $city_id = $_POST['city_id'];
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $landmark = mysqli_real_escape_string($conn, $_POST['landmark']);
    $map_link = mysqli_real_escape_string($conn, $_POST['map_link']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $sharing_type = $_POST['sharing_type'];
    $food_available = $_POST['food_available'];
    $gender_allowed = $_POST['gender_allowed'];
    $ac_available = $_POST['ac_available'];


    $image_name = $_FILES['room_image']['name'];
    $image_tmp = $_FILES['room_image']['tmp_name'];

    $upload_dir = "uploads/";
    if(!is_dir($upload_dir)){
        mkdir($upload_dir);
    }

    $new_image_name = time() . "_" . $image_name;
    $upload_path = $upload_dir . $new_image_name;

    move_uploaded_file($image_tmp, $upload_path);

    $query = "INSERT INTO rooms 
        (owner_id, city_id, area, landmark, map_link, title, description, price, sharing_type, food_available, gender_allowed, ac_available, is_verified)
        VALUES 
        ('$owner_id', '$city_id', '$area', '$landmark', '$map_link', '$title', '$description', '$price', '$sharing_type', '$food_available', '$gender_allowed', '$ac_available', 'no')";

    mysqli_query($conn, $query);

    $room_id = mysqli_insert_id($conn);

    mysqli_query($conn, "INSERT INTO room_images (room_id, image_path) VALUES ('$room_id', '$upload_path')");

    header("Location: owner_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Room - Saanidhya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            background: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
        }

        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px);
            background: rgba(0,0,0,0.5);
            z-index: 1;
        }

        .glass-card {
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            color: white;
        }

        .form-control, select {
            border-radius: 30px;
        }

        .btn-custom {
            border-radius: 30px;
            background: white;
            font-weight: 600;
        }

        .btn-custom:hover {
            background: black;
            color: white;
        }
    </style>
</head>

<body>

<div class="overlay"></div>

<div class="glass-card shadow-lg">

    <h3 class="text-center mb-4">Add New Room</h3>

    <form method="POST" enctype="multipart/form-data">

        <select name="city_id" class="form-control mb-3" required>
            <option value="">Select City</option>
            <?php while($row = mysqli_fetch_assoc($cities)) { ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['city_name']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="text" name="area" class="form-control mb-3" placeholder="Area / Locality" required>

        <input type="text" name="landmark" class="form-control mb-3" placeholder="Nearby Landmark (e.g. LPU Gate 2)" required>

        <input type="text" name="map_link" class="form-control mb-3" placeholder="Google Map Link (Paste URL)" required>

        <input type="file" name="room_image" class="form-control mb-3" required>

        <input type="text" name="title" class="form-control mb-3" placeholder="Room Title" required>

        <textarea name="description" class="form-control mb-3" placeholder="Room Description" required></textarea>

        <input type="number" name="price" class="form-control mb-3" placeholder="Price per Month" required>

        <select name="sharing_type" class="form-control mb-3">
            <option value="single">Single</option>
            <option value="double">Double</option>
            <option value="triple">Triple</option>
        </select>

        <select name="food_available" class="form-control mb-3">
            <option value="yes">Food Available</option>
            <option value="no">No Food</option>
        </select>

        <select name="gender_allowed" class="form-control mb-3">
            <option value="boys">Boys</option>
            <option value="girls">Girls</option>
            <option value="both">Both</option>
        </select>

        <select name="ac_available" class="form-control mb-3">
            <option value="yes">AC Available</option>
            <option value="no">No AC</option>
        </select>

        <button type="submit" class="btn btn-custom w-100">Add Room</button>

    </form>

</div>

</body>
</html>