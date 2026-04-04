<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='owner'){
header("Location: login.php");
exit();
}

$error='';

if($_SERVER["REQUEST_METHOD"]==="POST"){

$owner_id=(int)$_SESSION['user_id'];
$city_id=isset($_POST['city_id'])?(int)$_POST['city_id']:0;
$title=isset($_POST['title'])?trim($_POST['title']):'';
$price=isset($_POST['price'])?(int)$_POST['price']:0;
$description=isset($_POST['description'])?trim($_POST['description']):'';
$address=isset($_POST['address'])?trim($_POST['address']):'';
$property_type=isset($_POST['property_type'])?trim($_POST['property_type']):'';
$guests=isset($_POST['guests'])&&$_POST['guests']!==''?trim($_POST['guests']):'';
$wifi=isset($_POST['wifi'])?$_POST['wifi']:'no';
$ac=isset($_POST['ac'])?$_POST['ac']:'no';
$geyser=isset($_POST['geyser'])?$_POST['geyser']:'no';

$area=function_exists('mb_substr')?mb_substr($address,0,150):substr($address,0,150);
$ac_available=($ac==='yes')?'yes':'no';

$extra=[];
if($property_type!==''){$extra[]='Type: '.$property_type;}
if($guests!==''){$extra[]='Guests: '.$guests;}
if($wifi!==''){$extra[]='WiFi: '.$wifi;}
if($geyser!==''){$extra[]='Geyser: '.$geyser;}
if($extra){
$description=$description===''?implode(' | ',$extra):$description."\n\n".implode(' | ',$extra);
}

if($city_id<=0){
$error='Please select a city.';
}elseif($title===''){
$error='Please enter a title.';
}elseif($price<=0){
$error='Please enter a valid price.';
}else{

$hasVerified=false;
$colCheck=$conn->query("SHOW COLUMNS FROM rooms LIKE 'is_verified'");
if($colCheck){
$hasVerified=$colCheck->num_rows>0;
$colCheck->free();
}

$sql="INSERT INTO rooms (owner_id,city_id,title,description,area,price,ac_available";
if($hasVerified){
$sql.=",is_verified";
}
$sql.=") VALUES (?,?,?,?,?,?,?";
if($hasVerified){
$sql.=",'no'";
}
$sql.=")";

$stmt=$conn->prepare($sql);
if(!$stmt){
$error='Could not prepare: '.$conn->error;
}else{
$stmt->bind_param("iisssis",$owner_id,$city_id,$title,$description,$area,$price,$ac_available);
if(!$stmt->execute()){
$error='Could not save room: '.$stmt->error;
$stmt->close();
}else{
$room_id=(int)$conn->insert_id;
$stmt->close();

if(!empty($_FILES['image']['name'])){
if(isset($_FILES['image']['error'])&&$_FILES['image']['error']===UPLOAD_ERR_OK&&is_uploaded_file($_FILES['image']['tmp_name'])){
$uploadDir=__DIR__.'/uploads';
if(!is_dir($uploadDir)){
mkdir($uploadDir,0755,true);
}
$ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
$allowed=['jpg','jpeg','png','gif','webp'];
if(in_array($ext,$allowed,true)){
$safe='room_'.$room_id.'_'.bin2hex(random_bytes(8)).'.'.$ext;
$path='uploads/'.$safe;
if(move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir.'/'.$safe)){
$ins=$conn->prepare("INSERT INTO room_images(room_id,image_path) VALUES(?,?)");
if($ins){
$ins->bind_param("is",$room_id,$path);
$ins->execute();
$ins->close();
}
}
}
}
}

if($error===''){
header("Location: owner_dashboard.php");
exit();
}
}
}
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add room</title>
<?php include __DIR__."/includes/tailwind.php"; ?>
</head>
<body class="min-h-screen bg-[url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267')] bg-cover bg-center bg-fixed font-sans text-slate-800 antialiased">
<div class="min-h-screen bg-slate-950/55">

<nav class="border-b border-white/20 bg-white/85 shadow-sm backdrop-blur">
<div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3">
<a class="font-semibold text-slate-900" href="index.php">Saanidhya</a>
<a class="text-sm text-slate-700 hover:text-slate-900" href="owner_dashboard.php">Dashboard</a>
</div>
</nav>

<div class="mx-auto mt-8 max-w-lg px-4 pb-12">
<div class="rounded-2xl border border-white/30 bg-white/92 p-6 shadow-xl backdrop-blur md:p-8">
<h1 class="mb-6 text-center text-2xl font-semibold">Add room</h1>

<?php if($error!==''){ ?>
<div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950"><?php echo htmlspecialchars($error); ?></div>
<?php } ?>

<form method="POST" enctype="multipart/form-data" class="space-y-4">

<?php
$cities=mysqli_query($conn,"SELECT * FROM cities");
?>

<select name="city_id" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" required>
<option value="">Select city</option>
<?php while($c=mysqli_fetch_assoc($cities)){ ?>
<option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['city_name']); ?></option>
<?php } ?>
</select>

<input type="text" name="title" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Title" required value="<?php echo isset($_POST['title'])?htmlspecialchars($_POST['title']):''; ?>">

<textarea name="description" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" rows="3" placeholder="Description"><?php echo isset($_POST['description'])?htmlspecialchars($_POST['description']):''; ?></textarea>

<input type="number" name="price" min="1" step="1" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Price" required value="<?php echo isset($_POST['price'])?htmlspecialchars((string)(int)$_POST['price']):''; ?>">

<input type="text" name="address" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Address / area" value="<?php echo isset($_POST['address'])?htmlspecialchars($_POST['address']):''; ?>">

<select name="property_type" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
<option value="PG" <?php echo (!isset($_POST['property_type'])||$_POST['property_type']==='PG')?'selected':''; ?>>PG</option>
<option value="Hostel" <?php echo (isset($_POST['property_type'])&&$_POST['property_type']==='Hostel')?'selected':''; ?>>Hostel</option>
<option value="Flat" <?php echo (isset($_POST['property_type'])&&$_POST['property_type']==='Flat')?'selected':''; ?>>Flat</option>
</select>

<input type="number" name="guests" min="0" step="1" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Guests" value="<?php echo isset($_POST['guests'])?htmlspecialchars((string)$_POST['guests']):''; ?>">

<select name="wifi" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
<option value="yes" <?php echo (!isset($_POST['wifi'])||$_POST['wifi']==='yes')?'selected':''; ?>>WiFi available</option>
<option value="no" <?php echo (isset($_POST['wifi'])&&$_POST['wifi']==='no')?'selected':''; ?>>No WiFi</option>
</select>

<select name="ac" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
<option value="yes" <?php echo (!isset($_POST['ac'])||$_POST['ac']==='yes')?'selected':''; ?>>AC available</option>
<option value="no" <?php echo (isset($_POST['ac'])&&$_POST['ac']==='no')?'selected':''; ?>>No AC</option>
</select>

<select name="geyser" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
<option value="yes" <?php echo (!isset($_POST['geyser'])||$_POST['geyser']==='yes')?'selected':''; ?>>Geyser available</option>
<option value="no" <?php echo (isset($_POST['geyser'])&&$_POST['geyser']==='no')?'selected':''; ?>>No geyser</option>
</select>

<input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-medium file:text-slate-900 hover:file:bg-slate-200">

<button type="submit" class="w-full rounded-lg bg-slate-900 py-3 font-semibold text-white hover:bg-slate-800">Add room</button>

</form>
</div>
</div>

</div>
</body>
</html>
