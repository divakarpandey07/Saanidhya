<?php
session_start();
include("includes/db.php");

if (!isset($_GET['city_id'])) {
    header("Location: index.php");
    exit();
}

$city_id = intval($_GET['city_id']);

$city = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM cities WHERE id=$city_id"));

$rooms = mysqli_query($conn,"
SELECT rooms.*, room_images.image_path
FROM rooms
LEFT JOIN room_images ON rooms.id = room_images.room_id
WHERE rooms.city_id = $city_id
ORDER BY rooms.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $city['city_name']; ?> - Rooms</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="mb-4">Rooms in <?php echo $city['city_name']; ?></h2>

<div class="row">

<?php while($room = mysqli_fetch_assoc($rooms)){ ?>

<div class="col-md-4 mb-4">
<div class="card shadow">

<?php if($room['image_path']){ ?>
<img src="<?php echo $room['image_path']; ?>" class="card-img-top" style="height:200px;object-fit:cover;">
<?php } ?>

<div class="card-body">
<h5><?php echo $room['title']; ?></h5>
<p>₹<?php echo $room['price']; ?>/month</p>

<?php if(isset($_SESSION['user_id']) && $_SESSION['role']=='customer'){ ?>

<a href="wishlist_action.php?room_id=<?php echo $room['id']; ?>" 
class="btn btn-outline-danger btn-sm">
❤️ Wishlist
</a>

<?php } ?>

</div>
</div>
</div>

<?php } ?>

</div>
</div>

</body>
</html>