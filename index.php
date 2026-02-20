<?php
session_start();
include("includes/db.php");

/*
   Fetch cities from cities table
   (Relational structure compatible)
*/
$query = "SELECT * FROM cities";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Saanidhya - Find Your Stay</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body, html {
    height: 100%;
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
}

.bg-image {
    background: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
    height: 100%;
    position: fixed;
    width: 100%;
    z-index: -2;
}

.overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(6px);
    background: rgba(0, 0, 0, 0.5);
    z-index: -1;
}

.hero {
    min-height: 50vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    text-align: center;
}

.hero h1 {
    font-weight: bold;
    text-shadow: 2px 2px 15px rgba(0,0,0,0.6);
}

#dynamicSubheading {
    transition: opacity 0.5s ease;
}

.city-card {
    border-radius: 20px;
    transition: 0.3s;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
}

.city-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.btn-custom {
    background: linear-gradient(45deg, #ffffff, #e6e6e6);
    color: black;
    font-weight: 600;
    border-radius: 30px;
    padding: 10px 25px;
    transition: 0.3s;
}

.btn-custom:hover {
    background: linear-gradient(45deg, #000000, #333333);
    color: white;
}
</style>
</head>

<body>

<div class="bg-image"></div>
<div class="overlay"></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="#">Saanidhya</a>

    <div>
        <?php if(isset($_SESSION['user_id'])) { ?>

            <span class="text-white me-3">
                Welcome, <?php echo $_SESSION['name']; ?>
            </span>

            <a href="logout.php" class="btn btn-outline-light">Logout</a>

        <?php } else { ?>

            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="register.php" class="btn btn-light">Register</a>

        <?php } ?>
    </div>
  </div>
</nav>

<div class="container">

    <!-- Hero Section -->
    <div class="hero">
        <h1 class="display-4">Welcome to Saanidhya</h1>

        <p class="lead mt-3" id="dynamicSubheading">
            Find Safe & Verified Rooms in Jalandhar & Phagwara
        </p>
    </div>

    <!-- City Cards -->
    <div class="row justify-content-center mb-5">

        <?php if(mysqli_num_rows($result) > 0) { ?>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>

                <div class="col-md-4 mb-4">
                    <div class="card city-card shadow-lg text-center p-4">
                        <h3 class="fw-bold">
                            <?php echo htmlspecialchars($row['city_name']); ?>
                        </h3>

                        <a href="explore.php?city_id=<?php echo $row['id']; ?>" 
                           class="btn btn-custom mt-3">
                            Explore Rooms
                        </a>
                    </div>
                </div>

            <?php } ?>
        <?php } else { ?>

            <div class="text-center text-white">
                <h4>No cities available.</h4>
            </div>

        <?php } ?>

    </div>

</div>

<!-- Dynamic Subheading Script -->
<script>
const messages = [
"Find Safe & Verified Rooms in Jalandhar & Phagwara",
"Affordable PG & Hostels Near LPU Campus",
"Comfortable Living Spaces Designed for Students",
"100% Owner Verified & Trusted Properties",
"Easy Booking with Transparent Pricing",
"Modern Amenities for Hassle-Free Living",
"Safe & Secure Accommodation for Boys & Girls",
"Single, Double & Triple Sharing Options Available",
"Rooms Near Colleges, Markets & Transport",
"Your Perfect Stay Starts with Saanidhya"
];

let index = 0;
const textElement = document.getElementById("dynamicSubheading");

setInterval(() => {
    textElement.style.opacity = 0;

    setTimeout(() => {
        index = (index + 1) % messages.length;
        textElement.innerText = messages[index];
        textElement.style.opacity = 1;
    }, 500);

}, 8000);
</script>

</body>
</html>