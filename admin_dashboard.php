<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$rooms = mysqli_query($conn, "SELECT * FROM rooms ");

$bookings = mysqli_query($conn, "
SELECT bookings.*,users.name,rooms.title, rooms.description
FROM bookings
JOIN users ON bookings.user_id=users.id
JOIN rooms ON bookings.room_id=rooms.id
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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include __DIR__ . "/includes/tailwind.php"; ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('https://images.unsplash.com/photo-1494526585095-c41746248156') no-repeat center/cover;
            color: white;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 2;
            padding-top: 20px;
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            margin-bottom: 10px;
        }
    </style>

</head>

<body>
    <?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection if not already included
if (!isset($conn)) {
    include(__DIR__ . "/includes/db.php");
}

// Get cities for potential use
$cities = isset($conn) ? mysqli_query($conn, "SELECT * FROM cities") : false;
?>

<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3">
        <a class="font-semibold text-slate-900" href="index.php"><img class="h-12" src="./assets/logo.png" alt="Saanidhya"></a>
        <div class="flex flex-wrap items-center gap-4 text-lg">
            <a class="text-[#1d405c] hover:text-slate-900" href="explore.php">Explore</a>
            <a class="text-[#1d405c] hover:text-slate-900" href="explore.php?property_type=flat">PG Finder</a>
            <a class="text-[#1d405c] hover:text-slate-900" href="explore.php?property_type=hostel">Hostel Listing</a>
            <p class="text-[#1d405c] hover:text-slate-900">|</p>
            <?php
                if(isset($_SESSION['admin_id'])){
                    echo '
                        <a class="rounded-lg bg-[#c64f4f] px-3 py-1.5 font-medium text-white hover:bg-[#ff0000a0]" href="logout.php">Logout</a>
                    ';
                } else {
                    echo '
                        <a class="text-[#1d405c] hover:text-slate-900" href="login.php">Login</a>
                        <a class="rounded-lg bg-[#cfab71] px-3 py-1.5 font-medium text-white hover:bg-[#ba8b40]" href="register.php">Register</a>
                    ';
                }
            ?>
        </div>
    </div>
</nav>
    <div class="overlay"></div>

    <div class="container content">

        <div class="d-flex justify-content-between my-6">
            <h3 class="text-4xl text-center w-full">Admin Dashboard</h3>
        </div>

        <hr class="mt-4">
        
        <h4 class="text-3xl text-bold my-4">Room Verification</h4>

        <div class="flex flex-row gap-3 ">
        <?php if (mysqli_num_rows($rooms) > 0) { ?>
            <?php while ($r = mysqli_fetch_assoc($rooms)) { ?>
                                    
                <div class="row justify-content-center">

                <?php while($r=mysqli_fetch_assoc($rooms)){ ?>

                <div class="col-md-1 w-[23%] m-2">
                <div class="glass bg-[#aaaa]">
                <img src="<?php echo $r['image_path']; ?>" class="w-100 rounded-xl mb-2" style="height:150px;object-fit:cover;">
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
                        <p class="text-2xl font-bold text-[#cfab71]">₹<?php echo number_format($r['price']); ?><span class="text-lg font-normal text-slate-100">/month</span></p>
                </div>
                 <?php if ($r['is_verified']== 'pending'): ?>
                                <a href="verify_room.php?id=<?php echo $r['id']; ?>" style="border: 1px solid #05df72;" class="p-2 rounded-md  text-green-400 btn-sm">Verify</a>
                                <a href="unverify_room.php?id=<?php echo $r['id']; ?> "style="border: 1px solid #ffd9d9;"  class="p-2 rounded-md text-red-400 btn-sm">Reject</a>
                            <?php elseif ($r['is_verified']== 'rejected'): ?>
                                <a href="verify_room.php?id=<?php echo $r['id']; ?>" style="border: 1px solid #05df72;" class="p-2 rounded-md  text-green-400 btn-sm">Verify</a>
                            <?php elseif ($r['is_verified']== 'verified'): ?>
                                <a href="unverify_room.php?id=<?php echo $r['id']; ?> "style="border: 1px solid #ffd9d9;"  class="p-2 rounded-md text-red-400 btn-sm">Reject</a>                                
                            <?php endif; ?>
                </div>
                </div>


                <?php } ?>

                </div>

            
                    <?php }} else{?>            
                <p>No pending rooms</p>
            <?php } ?>
        </div>


        <hr class="mt-4">

        <h4 class="text-3xl my-4">Bookings</h4>

        <div class="row">
            <?php while ($b = mysqli_fetch_assoc($bookings)) { ?>
                <div class="col-md-4 mb-3">
                    <div class="glass bg-[#aaaa] p-4 rounded-xl">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-xl font-semibold"><?php echo htmlspecialchars($b['name']); ?></h6>
                            <span class="badge <?php echo $b['status'] == 'approved' ? 'bg-success' : ($b['status'] == 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                <?php echo ucfirst($b['status']); ?>
                            </span>
                        </div>
                        <p class="text-sm mb-1"><i class="fas fa-home text-[#cfab71]"></i> <?php echo htmlspecialchars($b['title']); ?></p>
                        <p class="text-sm text-slate-300">Booked: <?php echo date('d M Y', strtotime($b['booking_date'])); ?></p>
                        <div class="mt-3 d-flex gap-2">
                            <?php if ($b['status'] == 'pending'): ?>
                                <a class="p-2 rounded-md  text-green-400" style="border: 1px solid #05df72;" href="approve.php?id=<?php echo $b['id']; ?>" class="">Approve</a>
                                <a class="p-2 rounded-md  text-red-400" style="border: 1px solid #ffd9d9;" href="reject.php?id=<?php echo $b['id']; ?>" class="">Reject</a>
                                <?php elseif ($b['status'] == 'approved'): ?>
                                    <a class="p-2 rounded-md  text-red-400" style="border: 1px solid #ffd9d9;" href="reject.php?id=<?php echo $b['id']; ?>" class="">Reject</a>
                                <?php elseif ($b['status'] == 'rejected'): ?>
                                    <a class="p-2 rounded-md  text-green-400" style="border: 1px solid #05df72;" href="approve.php?id=<?php echo $b['id']; ?>" class="">Approve</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>

</body>

</html>