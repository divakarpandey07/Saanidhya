<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='owner'){
    header("Location: login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){

$owner_id = $_SESSION['user_id'];

$city_id = $_POST['city_id'];
$title = $_POST['title'];
$price = $_POST['price'];
$description = $_POST['description'];
$address = $_POST['address'];
$property_type = $_POST['property_type'];
$guests = $_POST['guests'];
$wifi = $_POST['wifi'];
$ac = $_POST['ac'];
$geyser = $_POST['geyser'];

$stmt = $conn->prepare("INSERT INTO rooms(owner_id,city_id,title,description,price,address,property_type,guests,wifi,ac,geyser,is_verified) VALUES(?,?,?,?,?,?,?,?,?,?,?,'no')");
$stmt->bind_param("iissssissss",$owner_id,$city_id,$title,$description,$price,$address,$property_type,$guests,$wifi,$ac,$geyser);
$stmt->execute();

$room_id = $stmt->insert_id;

if(!empty($_FILES['image']['name'])){
    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $path = "uploads/".$img;
    move_uploaded_file($tmp,$path);

    $stmt2 = $conn->prepare("INSERT INTO room_images(room_id,image_path) VALUES(?,?)");
    $stmt2->bind_param("is",$room_id,$path);
    $stmt2->execute();
}

header("Location: owner_dashboard.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Room</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae') no-repeat center/cover;
color:white;
}
.overlay{position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(6px);}
.content{position:relative;z-index:2;}
.glass{background:rgba(255,255,255,0.1);padding:30px;border-radius:15px;}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container mt-5 content">
<div class="glass">

<h3 class="text-center mb-4">Add Room</h3>

<form method="POST" enctype="multipart/form-data">

<?php
$cities = mysqli_query($conn,"SELECT * FROM cities");
?>

<select name="city_id" class="form-control mb-3" required>
<option value="">Select City</option>
<?php while($c=mysqli_fetch_assoc($cities)){ ?>
<option value="<?php echo $c['id']; ?>">
<?php echo $c['city_name']; ?>
</option>
<?php } ?>
</select>

<input type="text" name="title" class="form-control mb-3" placeholder="Title" required>

<textarea name="description" class="form-control mb-3" placeholder="Description"></textarea>

<input type="number" name="price" class="form-control mb-3" placeholder="Price" required>

<input type="text" name="address" class="form-control mb-3" placeholder="Address">

<select name="property_type" class="form-control mb-3">
<option value="PG">PG</option>
<option value="Hostel">Hostel</option>
<option value="Flat">Flat</option>
</select>

<input type="number" name="guests" class="form-control mb-3" placeholder="Guests">

<select name="wifi" class="form-control mb-3">
<option value="yes">WiFi Available</option>
<option value="no">No WiFi</option>
</select>

<select name="ac" class="form-control mb-3">
<option value="yes">AC Available</option>
<option value="no">No AC</option>
</select>

<select name="geyser" class="form-control mb-3">
<option value="yes">Geyser Available</option>
<option value="no">No Geyser</option>
</select>

<input type="file" name="image" class="form-control mb-3">

<button class="btn btn-light w-100">Add Room</button>

</form>

</div>
</div>

</body>
</html>