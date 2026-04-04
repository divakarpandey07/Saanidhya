<?php
include("includes/db.php");

$city_id=intval($_GET['city_id']);

$rooms=mysqli_query($conn,"
SELECT rooms.*,room_images.image_path
FROM rooms
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE rooms.city_id=$city_id AND rooms.is_verified='yes'
GROUP BY rooms.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>City Rooms</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae') no-repeat center/cover;
color:white;
}
.overlay{
position:fixed;width:100%;height:100%;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(6px);
}
.content{position:relative;z-index:2;}
.glass{background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);padding:20px;border-radius:15px;}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container mt-4 content">

<h3 class="text-center mb-4">Available Rooms</h3>

<div class="row justify-content-center">

<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="col-md-3 m-2">
<div class="glass">
<img src="<?php echo $r['image_path']; ?>" class="w-100 mb-2" style="height:150px;object-fit:cover;">
<h5><?php echo $r['title']; ?></h5>
<p>₹<?php echo $r['price']; ?></p>
<a href="room_details.php?id=<?php echo $r['id']; ?>" class="btn btn-light w-100">View</a>
</div>
</div>

<?php } ?>

</div>

</div>

</body>
</html>