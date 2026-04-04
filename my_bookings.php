<?php
session_start();
include("includes/db.php");

$user_id=$_SESSION['user_id'];

$data=mysqli_query($conn,"
SELECT bookings.*,rooms.title
FROM bookings
JOIN rooms ON bookings.room_id=rooms.id
WHERE bookings.user_id=$user_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Bookings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{background:url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85') no-repeat center/cover;color:white;}
.overlay{position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(6px);}
.content{position:relative;z-index:2;}
.glass{background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);padding:20px;border-radius:15px;}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container mt-4 content">

<h3 class="text-center mb-4">My Bookings</h3>

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<div class="glass mb-3">
<h5><?php echo $row['title']; ?></h5>

<?php if($row['status']=='approved'){ ?>
<span class="badge bg-success">Approved</span>
<?php }elseif($row['status']=='rejected'){ ?>
<span class="badge bg-danger">Rejected</span>
<?php }else{ ?>
<span class="badge bg-warning">Pending</span>
<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>