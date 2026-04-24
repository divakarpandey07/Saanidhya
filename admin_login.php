<?php
session_start();
include("includes/db.php");

if(isset($_SESSION['admin_id'])){
header("Location:admin_dashboard.php");
exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=mysqli_real_escape_string($conn,$_POST['email']);
$password=$_POST['password'];

$result=mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND role='admin'");

if(mysqli_num_rows($result)==1){
$user=mysqli_fetch_assoc($result);

if(password_verify($password,$user['password'])){
$_SESSION['admin_id']=$user['id'];
$_SESSION['admin_name']=$user['name'];

header("Location:admin_dashboard.php");
exit();
}else{
$error="Wrong Password";
}
}else{
$error="Admin not found";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
margin:0;
padding:0;
font-family:sans-serif;
overflow:hidden;
}

.bg{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:url('https://images.unsplash.com/photo-1505691938895-1758d7feb511') no-repeat center/cover;
filter: blur(8px);
z-index:-2;
}

.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.4);
z-index:-1;
}

.box{
position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
width:360px;
padding:30px;
background:rgba(255,255,255,0.15);
border-radius:15px;
backdrop-filter:blur(10px);
box-shadow:0 8px 32px rgba(0,0,0,0.3);
color:white;
text-align:center;
}

input{
background:rgba(255,255,255,0.85) !important;
border:none !important;
}

button{
background:white !important;
color:black !important;
font-weight:bold;
}

</style>

</head>

<body>

<div class="bg"></div>

<div class="overlay"></div>

<div class="box">

<h3 class="mb-4">Admin Login</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="Admin Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn w-100">Login</button>
</form>

</div>

</body>
</html>