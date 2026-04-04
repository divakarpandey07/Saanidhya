<?php
session_start();
include("includes/db.php");

if(isset($_POST['register'])){

$name=mysqli_real_escape_string($conn,$_POST['name']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);
$role=$_POST['role'];

$check=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($check)>0){
$error="Email already exists";
}else{
mysqli_query($conn,"INSERT INTO users(name,email,password,role) VALUES('$name','$email','$password','$role')");
header("Location: login.php");
exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f5f5f5;}
.box{max-width:400px;margin:80px auto;padding:30px;background:white;border-radius:10px;}
</style>
</head>

<body>

<div class="box shadow">

<h3 class="text-center mb-4">Register</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<select name="role" class="form-control mb-3">
<option value="customer">Customer</option>
<option value="owner">Owner</option>
</select>

<button type="submit" name="register" class="btn btn-dark w-100">Register</button>

</form>

</div>

</body>
</html>