<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='customer'){
header("Location:login.php");
exit();
}

$user_id=$_SESSION['user_id'];

$bookings=mysqli_query($conn,"
SELECT bookings.*,rooms.*,cities.city_name,room_images.image_path
FROM bookings
JOIN rooms ON bookings.room_id=rooms.id
LEFT JOIN cities ON rooms.city_id=cities.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
<?php include("navbar.php"); ?>
<div class="overlay"></div>

<div class="container content">

<div class="d-flex justify-content-between mb-3">
<h3 class="text-4xl my-6 w-full text-center font-bold">Customer Dashboard</h3>
</div>

<h4 class="text-3xl my-4">My Bookings</h4>
<hr class="mb-4">

<?php if(mysqli_num_rows($bookings)>0){ ?>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
<?php while($b=mysqli_fetch_assoc($bookings)){ ?>
<div class="glass bg-white/30 rounded-xl overflow-hidden">
    <div class="h-40 bg-cover bg-center rounded-xl" style="background-image: url('<?php echo !empty($b['image_path']) ? htmlspecialchars($b['image_path']) : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400'; ?>')"></div>
    <div class="py-4">
        <h5 class="text-2xl font-semibold text-white"><?php echo htmlspecialchars($b['title']); ?></h5>
        <p class="text-lg text-white/80 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($b['city_name'] ?? 'Location not specified'); ?></p>
        <p class="text-xl font-bold text-[#cfab71] mt-2">₹<?php echo number_format($b['price']); ?><span class="text-sm font-normal text-white/60">/month</span></p>
        <div class="flex items-center justify-between mt-3">
            <?php if($b['status']=='approved'){ ?>
            <span class="rounded-full bg-green-500 px-3 py-1 text-sm font-medium text-white">Approved</span>
            <?php }elseif($b['status']=='rejected'){ ?>
            <span class="rounded-full bg-red-500 px-3 py-1 text-sm font-medium text-white">Rejected</span>
            <?php }else{ ?>
            <span class="rounded-full bg-yellow-500 px-3 py-1 text-sm font-medium text-white">Pending</span>
            <?php } ?>
            <span class="text-md text-white"><?php echo date('d M Y', strtotime($b['booking_date'])); ?></span>
        </div>
        <?php if($b['status']=='pending'){ ?>
        <div class="mt-3">
            <a href="cancel_booking.php?booking_id=<?php echo (int)$b['id']; ?>" class="inline-block w-full text-center rounded-lg text-red-500 bg-[#e0cdad] hover:bg-[#e2c085] px-3 py-2 text-sm font-medium transition" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel Booking</a>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
</div>
<?php } else { ?>
<p class="text-lg my-4 text-center">No bookings yet</p>
<?php } ?>


<h4 class="text-3xl my-4">Available Rooms</h4>
<hr class="mb-4">

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    
<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="glass bg-white/30 rounded-xl overflow-hidden hover:bg-white/20 transition">
    <div class="h-36 bg-cover bg-center rounded-xl" style="background-image: url('<?php echo !empty($r['image_path']) ? htmlspecialchars($r['image_path']) : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400'; ?>')"></div>
    <div class="py-4">
        <h6 class="text-2xl font-semibold text-white"><?php echo htmlspecialchars($r['title']); ?></h6>
        <p class="text-lg text-white/70 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($r['city_name']); ?></p>
        <div class="my-6 flex flex-wrap gap-2 text-xs">
            <?php if($r['ac'] == 'yes'): ?>
                <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                    <i class="fas fa-snowflake text-[#cfab71] pr-1"></i>
                    <span class=" text-[#1d405c]">AC</span>
                </div>           <?php endif; ?>
            <?php if($r['wifi'] == 'yes'): ?>
                <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                    <i class="fas fa-wifi text-[#cfab71] pr-1"></i>
                    <span class=" text-[#1d405c]">WiFi</span>
                </div>
                <?php endif; ?>
                <?php if($r['geyser'] == 'yes'): ?>
                    <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                    <i class="fas fa-hot-tub text-[#cfab71] pr-1"></i>
                    <span class=" text-[#1d405c]">Geyser</span>
                </div>            <?php endif; ?>
            <span class="rounded-full bg-orange-100 text-[1rem] px-2 py-1 text-[#1d405c]"><i class="fas fa-map-marker-alt pr-2 text-[#cfab71]"></i><?php echo htmlspecialchars($r['property_type']); ?></span>
        </div>
        <p class="text-xl font-bold text-[#cfab71] mt-3">₹<?php echo number_format($r['price']); ?><span class="text-sm font-normal text-white/60">/month</span></p>
        <a href="book_room.php?room_id=<?php echo (int)$r['id']; ?>" class="mt-3 inline-block w-full text-center rounded-lg bg-[#cfab71] px-3 py-2 text-sm font-medium text-white hover:bg-[#ba8b40]">Book Now</a>
    </div>
</div>

<?php } ?>

</div>



</div>

</body>
</html>