<?php
session_start();
include("includes/db.php");

if(!isset($_GET['id'])){
header("Location:index.php");
exit();
}

$id=intval($_GET['id']);

$room=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT rooms.*,cities.city_name,room_images.image_path
FROM rooms
LEFT JOIN cities ON rooms.city_id=cities.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE rooms.id=$id AND rooms.is_verified='yes'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $room['title']; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f5f5f5;}
</style>
</head>

<body>

<div class="container mt-4">

<div class="row">

<div class="col-md-6">
<img src="<?php echo $room['image_path']; ?>" class="w-100" style="height:300px;object-fit:cover;">
</div>

<div class="col-md-6">

<h3><?php echo $room['title']; ?></h3>
<p><?php echo $room['area']; ?> - <?php echo $room['city_name']; ?></p>
<p>₹<?php echo $room['price']; ?>/month</p>
<p><?php echo $room['description']; ?></p>
<p>Sharing: <?php echo $room['sharing_type']; ?></p>
<p>Food: <?php echo $room['food_available']; ?></p>
<p>AC: <?php echo $room['ac_available']; ?></p>

<a href="<?php echo $room['map_link']; ?>" target="_blank" class="btn btn-outline-dark mb-2">View Map</a>

<?php if(isset($_SESSION['user_id']) && $_SESSION['role']=='customer'){ ?>
<a href="book_room.php?room_id=<?php echo $room['id']; ?>" class="btn btn-dark w-100">Book Now</a>
<?php } ?>

</div>

</div>

</div>

</body>
</html>