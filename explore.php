<?php
include("includes/db.php");

$query = "SELECT rooms.*, cities.city_name, room_images.image_path
          FROM rooms
          LEFT JOIN cities ON rooms.city_id = cities.id
          LEFT JOIN room_images ON rooms.id = room_images.room_id
          ORDER BY rooms.id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore Rooms - Saanidhya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
    min-height:100vh;
}
.overlay{
    position:fixed;
    width:100%;
    height:100%;
    backdrop-filter:blur(6px);
    background:rgba(0,0,0,0.6);
}
.content{
    position:relative;
    z-index:2;
    padding-top:80px;
    color:white;
}
.card{
    border-radius:15px;
}
img{
    height:200px;
    object-fit:cover;
}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container content">

<h2 class="text-center mb-5">Available Rooms</h2>

<div class="row">

<?php while($room = mysqli_fetch_assoc($result)){ ?>

<div class="col-md-4 mb-4">
<div class="card">

<?php if($room['image_path']){ ?>
<img src="<?php echo $room['image_path']; ?>" class="card-img-top">
<?php } ?>

<div class="card-body">
<h5><?php echo $room['title']; ?></h5>
<p><?php echo $room['area']; ?> - <?php echo $room['city_name']; ?></p>
<p>₹<?php echo $room['price']; ?>/month</p>
<p>Sharing: <?php echo $room['sharing_type']; ?></p>

<a href="<?php echo $room['map_link']; ?>" target="_blank" class="btn btn-dark btn-sm">
View on Map
</a>

</div>
</div>
</div>

<?php } ?>

</div>
</div>

</body>
</html>