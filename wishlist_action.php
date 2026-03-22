<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$room_id = intval($_GET['room_id']);

$check = mysqli_query($conn, "SELECT * FROM wishlist 
 WHERE user_id=$user_id AND room_id=$room_id");

if(mysqli_num_rows($check) > 0){
    mysqli_query($conn, "DELETE FROM wishlist 
     WHERE user_id=$user_id AND room_id=$room_id");
} else {
    mysqli_query($conn, "INSERT INTO wishlist(user_id, room_id) 
    VALUES($user_id, $room_id)");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>