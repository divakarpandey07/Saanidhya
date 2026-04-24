<?php
session_start();
include("includes/db.php");

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=$_POST['email'];
$password=$_POST['password'];

$result=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($result)==1){

$user=mysqli_fetch_assoc($result);

if(password_verify($password,$user['password'])){

$_SESSION['user_id']=$user['id'];
$_SESSION['role']=$user['role'];

if($user['role']=="admin"){
header("Location:admin_dashboard.php");
}elseif($user['role']=="owner"){
header("Location:owner_dashboard.php");
}else{
header("Location:customer_dashboard.php");
}
exit();

}else{
$error="Wrong Password";
}

}else{
$error="User not found";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
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
background:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center/cover;
filter: blur(8px);
z-index:-2;
}

.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.3);
z-index:-1;
}

.box{
position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
width:350px;
padding:30px;
background:rgba(255,255,255,0.15);
border-radius:15px;
backdrop-filter:blur(10px);
box-shadow:0 8px 32px rgba(0,0,0,0.3);
color:white;
text-align:center;
}

input{
background:rgba(255,255,255,0.8) !important;
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

<h3 class="mb-4">Login</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn w-100">Login</button>
</form>

</div>

</body>
</html>