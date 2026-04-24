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

$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png'];

if(in_array($ext,$allowed)){

$new_name = time().".".$ext;
$path = "uploads/".$new_name;

move_uploaded_file($tmp,$path);

$stmt2 = $conn->prepare("INSERT INTO room_images(room_id,image_path) VALUES(?,?)");
$stmt2->bind_param("is",$room_id,$path);
$stmt2->execute();

}else{
echo "<script>alert('Only JPG, JPEG, PNG allowed');</script>";
}
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
margin:0;
padding:0;
background:url('https://images.unsplash.com/photo-1493809842364-78817add7ffb') no-repeat center/cover;
font-family:sans-serif;
}

.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.4);
backdrop-filter:blur(5px);
z-index:0;
}

.container-box{
position:relative;
z-index:2;
max-width:850px;
margin:40px auto;
padding:35px;
background:rgba(255,255,255,0.06);
border-radius:20px;
backdrop-filter:blur(15px);
box-shadow:0 10px 40px rgba(0,0,0,0.4);
color:white;
}

h3{
text-align:center;
margin-bottom:25px;
font-weight:600;
}

.form-control, .form-select{
background:transparent !important;
border:1px solid rgba(255,255,255,0.3) !important;
color:white !important;
padding:12px !important;
border-radius:10px !important;
}

.form-control::placeholder{
color:rgba(255,255,255,0.7);
}

.form-control:focus, .form-select:focus{
box-shadow:none !important;
border:1px solid white !important;
background:transparent !important;
}

select option{
color:black;
}

button{
background:white !important;
color:black !important;
font-weight:600;
padding:12px;
border-radius:10px;
transition:0.3s;
}

button:hover{
transform:scale(1.02);
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="container-box">

<h3>Add Room</h3>

<form method="POST" enctype="multipart/form-data">

<?php
$cities = mysqli_query($conn,"SELECT * FROM cities");
?>

<select name="city_id" class="form-select mb-3" required>
<option value="">Select City</option>
<?php while($c=mysqli_fetch_assoc($cities)){ ?>
<option value="<?php echo $c['id']; ?>">
<?php echo $c['city_name']; ?>
</option>
<?php } ?>
</select>

<input type="text" name="title" class="form-control mb-3" placeholder="Room Title" required>

<textarea name="description" class="form-control mb-3" placeholder="Description"></textarea>

<input type="number" name="price" class="form-control mb-3" placeholder="Price (₹)" required>

<input type="text" name="address" class="form-control mb-3" placeholder="Address">

<select name="property_type" class="form-select mb-3">
<option value="PG">PG</option>
<option value="Hostel">Hostel</option>
<option value="Flat">Flat</option>
</select>

<input type="number" name="guests" class="form-control mb-3" placeholder="Guests">

<select name="wifi" class="form-select mb-3">
<option value="yes">WiFi Available</option>
<option value="no">No WiFi</option>
</select>

<select name="ac" class="form-select mb-3">
<option value="yes">AC Available</option>
<option value="no">No AC</option>
</select>

<select name="geyser" class="form-select mb-3">
<option value="yes">Geyser Available</option>
<option value="no">No Geyser</option>
</select>

<input type="file" name="image" accept="image/*" class="form-control mb-3" required>

<button class="btn w-100">Add Room</button>

</form>

</div>

</body>
</html>