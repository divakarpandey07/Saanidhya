<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

$rooms=mysqli_query($conn,"SELECT * FROM rooms WHERE is_verified='no'");

$bookings=mysqli_query($conn,"
SELECT bookings.*,users.name,rooms.title, rooms.description
FROM bookings
JOIN users ON bookings.user_id=users.id
JOIN rooms ON bookings.room_id=rooms.id
");

$rooms=mysqli_query($conn,"
SELECT rooms.*,room_images.image_path
FROM rooms
LEFT JOIN room_images ON rooms.id=room_images.room_id
GROUP BY rooms.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php include __DIR__."/includes/tailwind.php"; ?>
<style>

body{
margin:0;
padding:0;
background:url('https://images.unsplash.com/photo-1494526585095-c41746248156') no-repeat center/cover;
color:white;
}

.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
backdrop-filter:blur(6px);
z-index:0;
}

.content{
position:relative;
z-index:2;
padding-top:20px; 
}

.glass{
background:rgba(255,255,255,0.1);
padding:20px;
border-radius:15px;
backdrop-filter:blur(10px);
margin-bottom:10px;
}

</style>

</head>

<body>

<div class="overlay"></div>

<div class="container content">

<div class="d-flex justify-content-between mb-3">
<h3 class="text-4xl">Admin Dashboard</h3>
<a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<h4 class="text-3xl text-bold my-4">Room Verification</h4>

<?php if(mysqli_num_rows($rooms)>0){ ?>
<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="glass bg-[#aaaa] flex flex-row">
    <div class=""><img src="<?php echo $r['image_path']; ?>" style="width:20rem;height:12rem;object-fit:cover;border-radius:10px;margin-bottom:10px;"></div>
    <div class="px-8 pt-20">
        <h5 class="text-2xl"><?php echo $r['title']; ?></h5>
        <p class="text-lg"><?php echo $r['description']; ?></p>
        <p>Status: <?php echo $r['is_verified']; ?></p>
        
        <a href="verify_room.php?id=<?php echo $r['id']; ?>" class="btn bg-green-400 btn-sm">Verify</a>
        <a href="unverify_room.php?id=<?php echo $r['id']; ?>" class="btn bg-red-400 btn-sm">Reject</a>
    </div>
</div>

<?php } ?>
<?php } else { ?>

<p>No pending rooms</p>

<?php } ?>

<hr>

<h4 class="text-3xl my-4">Bookings</h4>

<?php while($b=mysqli_fetch_assoc($bookings)){ ?>

<div class="glass bg-[#aaaa]">
<p><?php echo $b['name']; ?> booked <?php echo $b['title']; ?></p>
<p>Status: <?php echo $b['status']; ?></p>
</div>

<?php } ?>

</div>

</body>
</html>