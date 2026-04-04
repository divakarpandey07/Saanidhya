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
echo "Wrong Password";
}

}else{
echo "User not found";
}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white">

<div class="container mt-5">

<h3 class="text-center">Login</h3>

<form method="POST" class="w-50 mx-auto">
<input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
<button class="btn btn-light w-100">Login</button>
</form>

</div>

</body>
</html>