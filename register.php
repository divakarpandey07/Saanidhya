<?php
session_start();
include("includes/db.php");

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Email already exists');</script>";
    } else {

        mysqli_query($conn,"
        INSERT INTO users (name,email,password,role)
        VALUES ('$name','$email','$password','$role')
        ");

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
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
    min-height:100vh;
}

.overlay{
    position:fixed;
    width:100%;
    height:100%;
    backdrop-filter:blur(8px);
    background:rgba(0,0,0,0.6);
}

.content{
    position:relative;
    z-index:2;
    padding-top:100px;
}

.form-box{
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(12px);
    border-radius:20px;
    padding:30px;
    color:white;
}
</style>
</head>

<body>

<div class="overlay"></div>

<div class="container content">
<div class="row justify-content-center">
<div class="col-md-4">

<div class="form-box">

<h3 class="text-center mb-4">Register</h3>

<form method="POST">

<input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<select name="role" class="form-control mb-3">
<option value="customer">Customer</option>
<option value="owner">Owner</option>
</select>

<button type="submit" name="register" class="btn btn-light w-100">
Register
</button>

</form>

</div>
</div>
</div>
</div>

</body>
</html>