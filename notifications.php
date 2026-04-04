<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
header("Location:login.php");
exit();
}
$user_id=$_SESSION['user_id'];
$data=mysqli_query($conn,"SELECT * FROM notifications WHERE user_id=$user_id ORDER BY id DESC");
mysqli_query($conn,"UPDATE notifications SET is_read='yes' WHERE user_id=$user_id");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h3 class="text-center mb-4">Notifications</h3>

<?php while($row=mysqli_fetch_assoc($data)){ ?>

<div class="alert <?php echo $row['is_read']=='no'?'alert-warning':'alert-info'; ?>">
<?php echo $row['message']; ?>
</div>

<?php } ?>

</div>
</body>
</html>