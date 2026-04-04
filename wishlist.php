<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$query=mysqli_query($conn,"
SELECT rooms.*,room_images.image_path
FROM wishlist
JOIN rooms ON wishlist.room_id=rooms.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE wishlist.user_id='$user_id'
GROUP BY rooms.id
ORDER BY wishlist.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Wishlist</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{background:#f5f5f5;}
.card:hover{transform:translateY(-5px);transition:0.3s;}
</style>
</head>

<body>

<div class="container mt-4">

<h3 class="text-center mb-4">My Wishlist</h3>

<div class="row">

<?php if(mysqli_num_rows($query)>0){ ?>
<?php while($row=mysqli_fetch_assoc($query)){ ?>

<div class="col-md-4 mb-4">
<div class="card shadow">

<img src="<?php echo $row['image_path']; ?>" style="height:200px;object-fit:cover;">

<div class="card-body">
<h5><?php echo $row['title']; ?></h5>
<p>₹<?php echo $row['price']; ?>/month</p>

<a href="<?php echo $row['map_link']; ?>" target="_blank" class="btn btn-outline-dark btn-sm">Map</a>

<a href="wishlist_action.php?remove=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Remove</a>

</div>

</div>
</div>

<?php } ?>
<?php } else { ?>

<div class="text-center">
<h5>No rooms in wishlist</h5>
</div>

<?php } ?>

</div>

</div>

</body>
</html>