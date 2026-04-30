<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
SELECT rooms.*,room_images.image_path
FROM wishlist
JOIN rooms ON wishlist.room_id=rooms.id
LEFT JOIN room_images ON rooms.id=room_images.room_id
WHERE wishlist.user_id='$user_id'
GROUP BY rooms.id
ORDER BY wishlist.id DESC
");

$rooms = mysqli_query($conn, "
SELECT rooms.*,room_images.image_path, cities.city_name
FROM rooms
LEFT JOIN room_images ON rooms.id=room_images.room_id
JOIN cities ON rooms.city_id = cities.id
GROUP BY rooms.id
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include __DIR__."/includes/tailwind.php"; ?>
    <style>
        body {
            background: #f5f5f5;
        }

        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
        
        .content {
            position: relative;
            z-index: 2;
            padding-top: 20px;
        }

        .glass {
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include("navbar.php"); ?>
    <div class="container content mt-4">

        <h3 class="text-center text-4xl mb-4">My Wishlist</h3>


        <div class="flex flex-row gap-3 w-full text-[#1d405c]">
            <?php if (mysqli_num_rows($query) > 0) { ?>
                <?php while ($r = mysqli_fetch_assoc($query)) { ?>
                
                <div class="row justify-content-center w-full">

                <?php while($r=mysqli_fetch_assoc($query)){ ?>

                <div class="col-md-1 w-[23%] m-2">
                <div class="glass bg-white">
                <img src="<?php echo $r['image_path']; ?>" class="w-100 rounded-xl mb-2" >
                <div class="my-4">
                    <div class="flex items-center justify-between">
                        <h2 class="bold text-2xl font-semibold"><?php echo $r['title']; ?></h2>
                        <span class="badge <?php echo $r['is_verified'] == 'verified' ? 'bg-success' : ($r['is_verified'] == 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                            <?php echo ucfirst($r['is_verified']); ?>
                        </span>
                    </div>
                    <p class="mt-1 text-xl"><i class="fas fa-map-marker-alt text-[#cfab71] pr-1"></i>
                        <?php echo $r['city_name']; ?></p>
                        <div class="my-6 flex flex-wrap gap-2 text-xs">
                            <?php if($r['ac'] == 'yes'): ?>
                                <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                                    <i class="fas fa-snowflake text-[#cfab71] pr-1"></i>
                                    <span class=" text-[#1d405c]">AC</span>
                                </div>           <?php endif; ?>
                            <?php if($r['wifi'] == 'yes'): ?>
                                <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                                    <i class="fas fa-wifi text-[#cfab71] pr-1"></i>
                                    <span class=" text-[#1d405c]">WiFi</span>
                                </div>
                            <?php endif; ?>
                            <?php if($r['geyser'] == 'yes'): ?>
                                <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                                    <i class="fas fa-hot-tub text-[#cfab71] pr-1"></i>
                                    <span class=" text-[#1d405c]">Geyser</span>
                                </div>            <?php endif; ?>
                            <span class="rounded-full bg-orange-100 text-[1rem] px-2 py-1 text-[#1d405c]"><i class="fas fa-map-marker-alt pr-2 text-[#cfab71]"></i><?php echo htmlspecialchars($r['property_type']); ?></span>
                            <!-- <span class="rounded-full bg-pink-100 px-2 py-1 text-[#1d405c]"><?php echo htmlspecialchars(ucfirst($r['gender_allowed'])); ?></span> -->
                        </div>
                        <p class="text-3xl font-bold text-[#cfab71]">₹<?php echo number_format($r['price']); ?><span class="text-lg font-normal">/month</span></p>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <a href="wishlist_action.php?room_id=<?php echo $r['id']; ?>" class="btn border-red-600 bg-[#ff00008a]  w-1/2 btn-sm">Remove</a>
                    <a href="book_room.php?room_id=<?php echo $r['id']; ?>" class="btn bg-orange-100  text-[#1d405c] w-1/2 btn-sm">Book Now</a>
                </div>
                    
                </div>
                </div>


                <?php } } } else{ ?>

                </div>
      
                <p>No pending rooms</p>

                <?php } ?>

        </div>

    </div>

</body>

</html>