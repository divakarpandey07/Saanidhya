<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"UPDATE rooms SET is_verified='yes' WHERE id=$id");

if($result){
    header("Location: admin_dashboard.php");
} else {
    echo "Error verifying room";
}

exit();
?>