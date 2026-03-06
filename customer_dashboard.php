<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='customer'){
    header("Location: login.php");
    exit();
}

$jalandhar = mysqli_query($conn,"
SELECT rooms.*, room_images.image_path 
FROM rooms 
LEFT JOIN room_images ON rooms.id = room_images.room_id
WHERE rooms.city_id=1
GROUP BY rooms.id
ORDER BY rooms.id DESC LIMIT 3
");

$phagwara = mysqli_query($conn,"
SELECT rooms.*, room_images.image_path 
FROM rooms 
LEFT JOIN room_images ON rooms.id = room_images.room_id
WHERE rooms.city_id=2
GROUP BY rooms.id
ORDER BY rooms.id DESC LIMIT 3
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
    min-height:100vh;
}

.overlay{
    position:fixed;
    width:100%;
    height:100%;
    backdrop-filter:blur(8px);
    background:rgba(0,0,0,0.6);
}

.content{
    position:relative;
    z-index:2;
    padding:40px 0;
    color:white;
}

.navbar{
    backdrop-filter:blur(10px);
    background:rgba(0,0,0,0.5);
}

.room-card{
    border-radius:20px;
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(10px);
    border:1px solid rgba(255,255,255,0.2);
    transition:0.3s;
}

.room-card:hover{
    transform:translateY(-5px);
}

.room-img{
    height:180px;
    object-fit:cover;
    border-radius:20px 20px 0 0;
}
</style>
</head>

<body>

<div class="overlay"></div>

<nav class="navbar navbar-expand-lg navbar-dark">
<div class="container">
<span class="navbar-brand">Welcome, <?php echo $_SESSION['name']; ?></span>
<div>
<a href="my_bookings.php" class="btn btn-outline-light me-2">My Bookings</a>
<a href="wishlist.php" class="btn btn-outline-light me-2">Wishlist</a>
<a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</div>
</nav>

<div class="container content">

<h2 class="mt-4">Jalandhar</h2>
<div class="row">

<?php if(mysqli_num_rows($jalandhar)>0){ ?>
<?php while($room=mysqli_fetch_assoc($jalandhar)){ ?>

<div class="col-md-4 mb-4">
<div class="card room-card text-white">

<?php if($room['image_path']){ ?>
<img src="<?php echo $room['image_path']; ?>" class="room-img w-100">
<?php } ?>

<div class="card-body">
<h5><?php echo $room['title']; ?></h5>
<p>₹<?php echo $room['price']; ?>/month</p>

<a href="wishlist_action.php?room_id=<?php echo $room['id']; ?>" 
class="btn btn-outline-danger btn-sm">
❤️ Wishlist
</a>

</div>
</div>
</div>

<?php } ?>
<?php } else { ?>
<p>No rooms available in Jalandhar</p>
<?php } ?>

</div>


<h2 class="mt-5">Phagwara</h2>
<div class="row">

<?php if(mysqli_num_rows($phagwara)>0){ ?>
<?php while($room=mysqli_fetch_assoc($phagwara)){ ?>

<div class="col-md-4 mb-4">
<div class="card room-card text-white">

<?php if($room['image_path']){ ?>
<img src="<?php echo $room['image_path']; ?>" class="room-img w-100">
<?php } ?>

<div class="card-body">
<h5><?php echo $room['title']; ?></h5>
<p>₹<?php echo $room['price']; ?>/month</p>

<a href="wishlist_action.php?room_id=<?php echo $room['id']; ?>" 
class="btn btn-outline-danger btn-sm">
❤️ Wishlist
</a>

</div>
</div>
</div>

<?php } ?>
<?php } else { ?>
<p>No rooms available in Phagwara</p>
<?php } ?>

</div>

</div>

</body>
</html>