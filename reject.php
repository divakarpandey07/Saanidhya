<?php
include("includes/db.php");

$id=intval($_GET['id']);

$data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM bookings WHERE id=$id"));
$user_id=$data['user_id'];

mysqli_query($conn,"UPDATE bookings SET status='rejected' WHERE id=$id");

mysqli_query($conn,"INSERT INTO notifications(user_id,message) VALUES($user_id,'Your booking has been rejected')");

header("Location:admin_dashboard.php");
?>