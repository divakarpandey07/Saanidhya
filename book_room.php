<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])||$_SESSION['role']!='customer'){
header("Location:login.php");
exit();
}

$user_id=$_SESSION['user_id'];
$room_id=intval($_GET['room_id']);
$check=mysqli_query($conn,"SELECT * FROM bookings WHERE user_id=$user_id AND room_id=$room_id");

if(mysqli_num_rows($check)>0){
header("Location:my_bookings.php");
exit();
}

mysqli_query($conn,"INSERT INTO bookings(user_id,room_id) VALUES($user_id,$room_id)");
header("Location:my_bookings.php");
exit();
?>