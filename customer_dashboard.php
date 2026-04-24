<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='customer'){
header("Location:login.php");
exit();
}

$user_id=$_SESSION['user_id'];

$bookings=mysqli_query($conn,"
SELECT bookings.*,rooms.*
FROM bookings
JOIN rooms ON bookings.room_id=rooms.id
WHERE bookings.user_id=$user_id
");

$rooms=mysqli_query($conn,"
SELECT rooms.*,cities.city_name,room_images.image_path
FROM rooms
JOIN cities ON rooms.city_id=cities.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE rooms.is_verified='yes'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?php include __DIR__."/includes/tailwind.php"; ?>
<style>
body{
margin:0;
padding:0;
background:url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85') no-repeat center/cover;
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
margin-bottom:15px;
}

.card{
background:rgba(255,255,255,0.1);
color:white;
border:none;
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

<div class="d-flex justify-content-between mb-3">
<h3 class="text-4xl">Customer Dashboard</h3>
</div>

<h4 class="text-3xl my-4">My Bookings</h4>
<hr>

<?php if(mysqli_num_rows($bookings)>0){ ?>
<?php while($b=mysqli_fetch_assoc($bookings)){ ?>

<div class="glass bg-[#aaaa]">
<h5><?php echo $b['title']; ?></h5>

<?php if($b['status']=='approved'){ ?>
<span class="badge bg-success">Approved</span>
<?php }elseif($b['status']=='rejected'){ ?>
<span class="badge bg-danger">Rejected</span>
<?php }else{ ?>
<span class="badge bg-warning">Pending</span>
<?php } ?>

</div>

<?php } ?>
<?php } else { ?>
<p class="text-lg my-4 text-center">No bookings yet</p>
<?php } ?>


<h4 class="text-3xl my-4">Available Rooms</h4>
<hr>

<div class="row mt-6">

<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="col-md-3">
<div class="card mb-3 bg-[#aaaa] p-4 rounded-lg">

<img src="<?php echo $r['image_path']; ?>" style="height:150px;object-fit:cover;" class="rounded-lg">

<div class="p-2">
<h6 class="text-xl"><?php echo $r['title']; ?></h6>
<p><?php echo $r['city_name']; ?></p>
<p class="inline-block bg-[#aaa] px-2 py-1 rounded-3xl my-2">₹<?php echo $r['price']; ?></p>

<a href="book_room.php?room_id=<?php echo $r['id']; ?>" class="btn btn-light btn-sm mt-4 w-100">Book Now</a>

</div>

</div>
</div>

<?php } ?>

</div>

<a href="wishlist.php" class="btn btn-light mt-3">Wishlist</a>

</div>

</body>
</html>