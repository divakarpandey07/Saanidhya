<?php
include("includes/db.php");

$price=isset($_GET['price'])?$_GET['price']:'';

$query="SELECT rooms.*,cities.city_name,room_images.image_path
FROM rooms
JOIN cities ON rooms.city_id=cities.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE rooms.is_verified='yes'";

if($price!=''){
$query.=" AND rooms.price<=$price";
}

$query.=" GROUP BY rooms.id";

$rooms=mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:url('https://images.unsplash.com/photo-1493809842364-78817add7ffb') no-repeat center/cover;
color:white;
}
.overlay{
position:fixed;width:100%;height:100%;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(6px);
}
.content{position:relative;z-index:2;}
.glass{
background:rgba(255,255,255,0.1);
backdrop-filter:blur(10px);
padding:20px;
border-radius:15px;
}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container content mt-4">

<h3 class="text-center mb-4">Explore Rooms</h3>

<!-- FILTER -->
<form method="GET" class="glass mb-4">
<div class="row">
<div class="col-md-4">
<input type="number" name="price" placeholder="Max Price" class="form-control" value="<?php echo $price; ?>">
</div>
<div class="col-md-4">
<button class="btn btn-light w-100">Apply Filter</button>
</div>
</div>
</form>

<div class="row justify-content-center">

<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="col-md-3 m-2">
<div class="glass">
<img src="<?php echo $r['image_path']; ?>" class="w-100 mb-2" style="height:150px;object-fit:cover;">
<h5><?php echo $r['title']; ?></h5>
<p><?php echo $r['city_name']; ?></p>
<p>₹<?php echo $r['price']; ?></p>
<a href="room_details.php?id=<?php echo $r['id']; ?>" class="btn btn-light w-100">View</a>
</div>
</div>

<?php } ?>

</div>

<a href="logout.php" class="btn btn-danger mt-4">Logout</a>

</div>

</body>
</html>