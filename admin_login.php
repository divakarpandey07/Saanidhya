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
body{background:#f5f5f5;}
.box{max-width:400px;margin:100px auto;padding:30px;background:white;border-radius:10px;}
</style>
</head>
<body>

<div class="box shadow">
<h3 class="text-center mb-4">Admin Login</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn btn-dark w-100">Login</button>
</form>
</div>

</body>
</html>