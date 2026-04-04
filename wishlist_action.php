<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_GET['room_id'])){
    $room_id = intval($_GET['room_id']);

    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id=? AND room_id=?");
    $stmt->bind_param("ii", $user_id, $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id=? AND room_id=?");
        $stmt->bind_param("ii", $user_id, $room_id);
        $stmt->execute();
    }else{
        $stmt = $conn->prepare("INSERT INTO wishlist(user_id,room_id) VALUES(?,?)");
        $stmt->bind_param("ii", $user_id, $room_id);
        $stmt->execute();
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>