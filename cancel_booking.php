<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='customer'){
    header("Location:login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['booking_id']);

// Verify the booking belongs to this user
$check = mysqli_query($conn,"SELECT * FROM bookings WHERE room_id=$booking_id AND user_id=$user_id");

if(mysqli_num_rows($check)==0){
    header("Location:customer_dashboard.php");
    exit();
}

$booking = mysqli_fetch_assoc($check);

// Only allow cancellation of pending bookings
if($booking['status']=='pending'){
    mysqli_query($conn,"DELETE FROM bookings WHERE room_id=$booking_id AND user_id=$user_id");
    $_SESSION['message'] = "Booking cancelled successfully!";
} else {
    $_SESSION['error'] = "You can only cancel pending bookings!";
}

header("Location:customer_dashboard.php");
exit();
?>