<?php
include("includes/db.php");

$price=isset($_GET['price'])?$_GET['price']:'';

$query="SELECT rooms.*,cities.city_name,room_images.image_path
FROM rooms
JOIN cities ON rooms.city_id=cities.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE rooms.is_verified='yes'";

if($price!=''){
$query.=" AND rooms.price<=$price";
}

$query.=" GROUP BY rooms.id";

$rooms=mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white shadow-sm">
<div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3">
<a class="font-semibold text-slate-900" href="index.php" class="h-8"><img class="h-12" src="./assets/logo.png" alt="Saanidhya"></a>
<div class="flex flex-wrap items-center gap-4 text-lg">
<a class="text-[#1d405c] hover:text-slate-900" href="explore.php">Explore</a>
<a class="text-[#1d405c] hover:text-slate-900" href="#">PG Finder</a>
<a class="text-[#1d405c] hover:text-slate-900" href="#">Hostel Listing</a>
<p class="text-[#1d405c] hover:text-slate-900" >|</p>
<?php
    if(isset($_SESSION['role'])){
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

<div class="container content mt-4">

<h3 class="text-center mb-4 text-4xl">Explore Rooms</h3>

<!-- FILTER -->
<form method="GET" class="glass bg-[#aaaa] mb-4">
<div class="row">
<div class="col-md-4">
<input type="number" name="price" placeholder="Max Price" class="form-control" value="<?php echo $price; ?>">
</div>
<div class="col-md-4">
<button class="btn btn-light w-100">Apply Filter</button>
</div>
</div>
</form>

<div class="row justify-content-center">

<?php while($r=mysqli_fetch_assoc($rooms)){ ?>

<div class="col-md-1 w-[23%] m-2">
<div class="glass bg-[#aaaa]">
<img src="<?php echo $r['image_path']; ?>" class="w-100 rounded-xl mb-2" style="height:150px;object-fit:cover;">
<div class="my-4">
    <h2 class="bold text-xl"><?php echo $r['title']; ?></h2>
    <p><?php echo $r['city_name']; ?></p>
    <div class="inline-block bg-[#aaa] px-2 py-1 rounded-3xl my-2">₹<?php echo $r['price']; ?></div>
</div>
<a href="room_details.php?id=<?php echo $r['id']; ?>" class="btn btn-light w-100">View</a>
</div>
</div>

<?php } ?>

</div>


</div>

</body>
</html>