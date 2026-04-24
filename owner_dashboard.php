<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='owner'){
header("Location:login.php");
exit();
}

$owner_id=$_SESSION['user_id'];

$rooms=mysqli_query($conn,"SELECT * FROM rooms WHERE owner_id=$owner_id");

$bookings=mysqli_query($conn,"
SELECT bookings.*,users.name,rooms.title 
FROM bookings
JOIN users ON bookings.user_id=users.id
JOIN rooms ON bookings.room_id=rooms.id
WHERE rooms.owner_id=$owner_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Owner Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?php include __DIR__."/includes/tailwind.php"; ?>
<style>
body{
margin:0;
padding:0;
background:url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688') no-repeat center/cover;
color:white;
}

.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.45);
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
margin-bottom:15px;
}
</style>

</head>

<body>
<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white shadow-sm">
<div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3">
<a class="font-semibold text-slate-900" href="index.php" class="h-8"><img class="h-12" src="./assets/logo.png" alt="Saanidhya"></a>
<div class="flex flex-wrap items-center gap-4 text-lg">
<a class="text-[#1d405c] hover:text-slate-900" href="explore.php">Explore</a>
<a class="text-[#1d405c] hover:text-slate-900" href="#">PG Finder</a>
<a class="text-[#1d405c] hover:text-slate-900" href="#">Hostel Listing</a>
<p class="text-[#1d405c] hover:text-slate-900" >|</p>
<?php
    if(isset($_SESSION['user_id'])){
        echo '
            <a class="rounded-lg bg-[#c64f4f] px-3 py-1.5 font-medium text-white hover:bg-[#ff0000a0]" href="logout.php">Logout</a>
        ';
    }elseif (!isset($_SESSION['user_id'])){
        echo '
            <a class="text-[#1d405c] hover:text-slate-900" href="login.php">Login</a>
            <a class="rounded-lg bg-[#cfab71] px-3 py-1.5 font-medium text-white hover:bg-[#ba8b40]" href="register.php">Register</a>
        ';
    }
?>
</div>
</div>
</nav>
<div class="overlay"></div>

<div class="container content">

<div class="d-flex justify-content-between my-4">
<h3 class="text-4xl text-center">Owner Dashboard</h3>
<a href="add_room.php" class="btn btn-light mb-3">+ Add Room</a>
</div>


<h4 class="text-3xl my-4">Your Rooms</h4>

<?php if(mysqli_num_rows($rooms)>0){ ?>
<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="glass bg-[#aaaa]">
<h5 class="text-2xl"><?php echo $r['title']; ?></h5>
<p class="inline-block bg-[#aaa] px-2 py-1 rounded-3xl">₹<?php echo $r['price']; ?></p>
<p class="text-bold">Verified: <?php echo $r['is_verified']; ?></p>
</div>

<?php } ?>
<?php } else { ?>
<p>No rooms added yet</p>
<?php } ?>

<hr>

<h4 class="text-3xl my-4">Bookings</h4>

<?php if(mysqli_num_rows($bookings)>0){ ?>
<?php while($b=mysqli_fetch_assoc($bookings)){ ?>

<div class="glass bg-[#aaaa]">
<p>User: <?php echo $b['name']; ?></p>
<p>Room: <?php echo $b['title']; ?></p>
<p>Status: <?php echo $b['status']; ?></p>
</div>

<?php } ?>
<?php } else { ?>
<p class="text-xl">No bookings yet</p>
<?php } ?>

</div>

</body>
</html>