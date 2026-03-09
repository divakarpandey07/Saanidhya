<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner'){
    header("Location: login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

$query = "SELECT rooms.*, room_images.image_path 
          FROM rooms 
          LEFT JOIN room_images ON rooms.id = room_images.room_id 
          WHERE rooms.owner_id = $owner_id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Owner Dashboard - Saanidhya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    min-height: 100vh;
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

.hero {
    position: relative;
    z-index: 2;
    text-align: center;
    padding-top: 120px;
    color: white;
}

.btn-main {
    border-radius: 30px;
    padding: 12px 35px;
    font-size: 18px;
    background: white;
    font-weight: 600;
}

.btn-main:hover {
    background: black;
    color: white;
}

.glass-card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    margin-top: 30px;
    color: white;
}

.room-section {
    position: relative;
    z-index: 2;
    margin-top: 50px;
}
img {
    border-radius: 10px;
    height: 180px;
    object-fit: cover;
}
.logout-btn {
    position: absolute;
    top: 25px;
    right: 30px;
    z-index: 3;
}
</style>
</head>

<body>

<div class="overlay"></div>

<a href="logout.php" class="btn btn-outline-light logout-btn">Logout</a>

<div class="hero container">

    <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>

    <div class="mt-4">
        <a href="add_room.php" class="btn btn-main">+ Add Room</a>
    </div>

    <?php if(mysqli_num_rows($result) == 0){ ?>
        <div class="glass-card col-md-6 mx-auto">
            <h4>No Rooms Added Yet</h4>
            <p>Click on "Add Room" to list your property.</p>
        </div>
    <?php } ?>

</div>

<?php if(mysqli_num_rows($result) > 0){ ?>

<div class="container room-section">
    <div class="row justify-content-center">

        <?php while($room = mysqli_fetch_assoc($result)){ ?>

            <div class="col-md-4">
                <div class="glass-card text-center">

                    <?php if($room['image_path']){ ?>
                        <img src="<?php echo $room['image_path']; ?>" class="img-fluid mb-2">
                    <?php } ?>

                    <h5><?php echo $room['title']; ?></h5>
                    <p><?php echo $room['area']; ?></p>
                    <p>₹<?php echo $room['price']; ?>/month</p>

                    <a href="<?php echo $room['map_link']; ?>" target="_blank" class="btn btn-light btn-sm">
                        View on Map
                    </a>

                </div>
            </div>

        <?php } ?>

    </div>
</div>

<?php } ?>

</body>
</html>