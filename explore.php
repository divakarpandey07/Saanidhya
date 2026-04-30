<?php
session_start();
include("includes/db.php");

$price=isset($_GET['price'])?$_GET['price']:'';
$min_price=isset($_GET['min_price'])?$_GET['min_price']:'';
$property_type=isset($_GET['property_type'])?$_GET['property_type']:'';

$query="SELECT rooms.*,cities.city_name,room_images.image_path
FROM rooms
JOIN cities ON rooms.city_id=cities.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE rooms.is_verified='verified'";

if($price!=''){
$query.=" AND rooms.price<=$price";
}

if($min_price!=''){
$query.=" AND rooms.price>=$min_price";
}

if($property_type!=''){
$query.=" AND rooms.property_type='$property_type'";
}

$query.=" GROUP BY rooms.id";

$rooms=mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include __DIR__."/includes/tailwind.php"; ?>
<style>
body{
background:url('https://images.unsplash.com/photo-1493809842364-78817add7ffb') no-repeat center/cover;
color:white;
}
.overlay{
position:fixed;width:100%;height:100%;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(6px);
margin-top: -1.5rem;
}
.content{position:relative;z-index:2;}
.glass{
backdrop-filter:blur(10px);
padding:20px;
border-radius:15px;
}
</style>
</head>

<body>

<?php include("navbar.php"); ?>

<div class="overlay"></div>

<div class="container content mt-4">

<h3 class="text-center mb-4 text-4xl">Explore Rooms</h3>

<!-- FILTER -->
<form method="GET" class="glass bg-[#aaaa] mb-4">
<div class="row">
<div class="col-md-3">
<input type="number" name="min_price" placeholder="Min Price" class="form-control" value="<?php echo $min_price; ?>">
</div>
<div class="col-md-3">
<input type="number" name="price" placeholder="Max Price" class="form-control" value="<?php echo $price; ?>">
</div>
<div class="col-md-3">
<select name="property_type" class="form-control">
    <option value="">All Types</option>
    <option value="flat" <?php echo ($property_type=='flat')?'selected':''; ?>>Flat</option>
    <option value="hostel" <?php echo ($property_type=='hostel')?'selected':''; ?>>Hostel</option>
</select>
</div>
<div class="col-md-3">
<button class="btn bg-orange-100 w-100">Apply Filter</button>
</div>
</div>
</form>

<div class="row justify-content-center">

<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="col-md-1 w-[23%] m-2">
<div class="glass bg-[#aaaa]">
<img src="<?php echo $r['image_path']; ?>" class="w-100 rounded-xl mb-2" style="height:150px;object-fit:cover;">
<div class="mt-4 mb-2 relative min-h-[270px]">
    <h2 class="bold text-2xl font-semibold"><?php echo $r['title']; ?></h2>
    <p class="mt-1 text-xl"><i class="fas fa-map-marker-alt text-[#cfab71] pr-1"></i>
<?php echo $r['city_name']; ?></p>
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
            <!-- <span class="rounded-full bg-pink-100 px-2 py-1 text-[#1d405c]"><?php echo htmlspecialchars(ucfirst($r['gender_allowed'])); ?></span> -->
        </div>
        <div class="absolute bottom-0 w-full">
            <p class="text-2xl font-bold text-[#cfab71]">₹<?php echo number_format($r['price']); ?><span class="text-lg font-normal text-slate-100">/month</span></p>
            <div class="flex items-center justify-between mt-4">
                <a class="flex h-10 w-10 items-center justify-center rounded-full border border-[#cfab71] text-[#cfab71] hover:bg-[#cfab71] hover:text-white" href="wishlist_action.php?room_id=<?php echo (int)$r['id']; ?>">
                    <i class="fas fa-heart"></i>
                </a>
                <a class="rounded-lg right-0 bg-[#1d405c] px-4 py-2 text-lg font-medium text-white hover:bg-[#1d405ca0]" href="room_details.php?id=<?php echo (int)$r['id']; ?>">View Details</a>
            </div>
        </div>
</div>
<?php if(isset($_SESSION['user_id']) && $_SESSION['role']=='customer'){ ?>
<a href="book_room.php?room_id=<?php echo $r['id']; ?>" class="btn bg-slate-100 w-100">Book Now</a>
<?php } ?>
</div>
</div>


<?php } ?>

</div>


</div>

</body>
</html>