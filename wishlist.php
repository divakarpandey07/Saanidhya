<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,"
SELECT rooms.*, room_images.image_path
FROM wishlist
JOIN rooms ON wishlist.room_id = rooms.id
LEFT JOIN room_images ON rooms.id = room_images.room_id
WHERE wishlist.user_id = '$user_id'
GROUP BY rooms.id
ORDER BY wishlist.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Wishlist</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    margin:0;
    background: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
    min-height:100vh;
    font-family: 'Segoe UI', sans-serif;
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
    padding:60px 0;
    color:white;
}

.card-custom{
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(10px);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.2);
}
.room-img{
    height:200px;
    object-fit:cover;
    border-radius:20px 20px 0 0;
}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container content">
<h2 class="text-center mb-5">My Wishlist</h2>

<div class="row">
<?php if(mysqli_num_rows($query)>0){ ?>
<?php while($row=mysqli_fetch_assoc($query)){ ?>

<div class="col-md-4 mb-4">
<div class="card card-custom text-white">

<?php if($row['image_path']){ ?>
<img src="<?php echo $row['image_path']; ?>" class="room-img w-100">
<?php } ?>

<div class="card-body">
<h5><?php echo $row['title']; ?></h5>
<p>₹<?php echo $row['price']; ?>/month</p>
<a href="wishlist_action.php?remove=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Remove</a>
</div>

</div>
</div>

<?php } ?>
<?php } else { ?>

<div class="text-center">
<h4>No Rooms in Wishlist</h4>
</div>

<?php } ?>
</div>
</div>

</body>
</html>