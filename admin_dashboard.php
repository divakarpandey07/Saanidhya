<?php
session_start();
include("includes/db.php");

$rooms=mysqli_query($conn,"SELECT * FROM rooms");

$bookings=mysqli_query($conn,"
SELECT bookings.*,users.name,rooms.title 
FROM bookings
JOIN users ON bookings.user_id=users.id
JOIN rooms ON bookings.room_id=rooms.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:url('https://images.unsplash.com/photo-1494526585095-c41746248156') no-repeat center/cover;
color:white;
}
.overlay{position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(6px);}
.content{position:relative;z-index:2;}
.glass{background:rgba(255,255,255,0.1);padding:20px;border-radius:15px;}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container mt-4 content">

<div class="d-flex justify-content-between mb-3">
<h3>Admin Dashboard</h3>
<a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<h4>Room Verification</h4>

<?php while($r=mysqli_fetch_assoc($rooms)){ ?>
<div class="glass mb-2">
<h5><?php echo $r['title']; ?></h5>
<p>Status: <?php echo $r['is_verified']; ?></p>

<a href="verify_room.php?id=<?php echo $r['id']; ?>" class="btn btn-success btn-sm">Verify</a>
<a href="unverify_room.php?id=<?php echo $r['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
</div>
<?php } ?>

<hr>

<h4>Bookings</h4>

<?php while($b=mysqli_fetch_assoc($bookings)){ ?>
<div class="glass mb-2">
<p><?php echo $b['name']; ?> booked <?php echo $b['title']; ?></p>
<p>Status: <?php echo $b['status']; ?></p>
</div>
<?php } ?>

</div>

</body>
</html>