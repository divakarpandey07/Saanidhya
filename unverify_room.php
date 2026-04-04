<?php
include("includes/db.php");
$id=intval($_GET['id']);
mysqli_query($conn,"UPDATE rooms SET is_verified='no' WHERE id=$id");
header("Location:admin_dashboard.php");
?>