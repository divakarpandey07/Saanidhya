<?php
include("includes/db.php");
$cities=mysqli_query($conn,"SELECT * FROM cities");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Saanidhya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:'Segoe UI',system-ui,sans-serif;color:#1e293b;}
.kicker{letter-spacing:.08em;}
.hero-home{
min-height:clamp(420px,68vh,640px);
display:flex;
align-items:center;
color:#fff;
background:
linear-gradient(120deg,rgba(15,23,42,0.82) 0%,rgba(30,41,59,0.55) 45%,rgba(15,23,42,0.7) 100%),
url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') center/cover no-repeat;
}
.hero-home .lead{max-width:34rem;}
.search-bar .form-control{border:0;padding:0.7rem 1rem;}
.search-bar .btn{font-weight:600;padding:0.7rem 1.25rem;}
.city-tile{
border:1px solid #e2e8f0;
transition:box-shadow .2s ease,transform .2s ease;
}
.city-tile:hover{box-shadow:0 12px 28px rgba(15,23,42,0.08);transform:translateY(-2px);}
.step-num{
width:2.25rem;height:2.25rem;border-radius:50%;
background:#0f172a;color:#fff;
font-weight:600;font-size:.9rem;
display:inline-flex;align-items:center;justify-content:center;
}
footer a{color:rgba(255,255,255,0.78);text-decoration:none;}
footer a:hover{color:#fff;text-decoration:underline;}
</style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top shadow-sm">
<div class="container py-1">
<a class="navbar-brand fw-semibold text-dark" href="index.php">Saanidhya</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navMain">
<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
<li class="nav-item"><a class="nav-link" href="explore.php">Explore</a></li>
<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
<li class="nav-item"><a class="btn btn-dark btn-sm px-3 mt-2 mt-lg-0" href="register.php">Register</a></li>
</ul>
</div>
</div>
</nav>

<header class="hero-home">
<div class="container py-5">
<div class="row align-items-center">
<div class="col-lg-7">
<p class="kicker text-white-50 text-uppercase small mb-2">Student housing</p>
<h1 class="display-5 fw-bold mb-3">Rooms that feel closer to campus and closer to home.</h1>
<p class="lead mb-4">Browse verified PGs and hostels in the cities we cover. Pick an area, compare options, and book with confidence.</p>
<form class="search-bar bg-white rounded-3 shadow p-2" action="explore.php" method="get">
<div class="row g-2 align-items-stretch">
<div class="col-md-8">
<label class="visually-hidden" for="q">Search</label>
<input id="q" name="q" type="search" class="form-control rounded-2" placeholder="Search by city, area, or landmark" autocomplete="off">
</div>
<div class="col-md-4 d-grid">
<button type="submit" class="btn btn-dark rounded-2">Search rooms</button>
</div>
</div>
</form>
</div>
</div>
</div>
</header>

<main>
<section class="py-5">
<div class="container">
<div class="row justify-content-between align-items-end mb-4">
<div class="col-md-8">
<h2 class="h3 fw-semibold mb-2">Browse by city</h2>
<p class="text-secondary mb-0">Choose a city to see listings, filters, and map-friendly details.</p>
</div>
<div class="col-md-4 text-md-end mt-3 mt-md-0">
<a class="btn btn-outline-dark" href="explore.php">View all listings</a>
</div>
</div>
<div class="row g-4">
<?php while($city=mysqli_fetch_assoc($cities)){ ?>
<div class="col-sm-6 col-lg-4 col-xl-3">
<div class="card city-tile h-100 rounded-4 overflow-hidden bg-white">
<div class="card-body d-flex flex-column p-4">
<h3 class="h5 card-title mb-1"><?php echo htmlspecialchars($city['city_name']); ?></h3>
<p class="small text-secondary flex-grow-1 mb-3">Rooms and hostels in this city.</p>
<a class="btn btn-dark mt-auto align-self-start" href="city.php?city_id=<?php echo (int)$city['id']; ?>">Open city</a>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
</section>

<section class="py-5 bg-white border-top border-bottom">
<div class="container">
<div class="row justify-content-center text-center mb-5">
<div class="col-lg-8">
<h2 class="h3 fw-semibold mb-3">Why students use Saanidhya</h2>
<p class="text-secondary mb-0">Less guesswork before you move — verified owners, clear expectations, and booking flows built for busy semesters.</p>
</div>
</div>
<div class="row g-4">
<div class="col-md-4">
<div class="p-4 h-100 rounded-4 bg-light border">
<span class="step-num mb-3">1</span>
<h3 class="h5 mt-2">Verified listings</h3>
<p class="text-secondary small mb-0">Rooms go through checks so you are not comparing mystery posts.</p>
</div>
</div>
<div class="col-md-4">
<div class="p-4 h-100 rounded-4 bg-light border">
<span class="step-num mb-3">2</span>
<h3 class="h5 mt-2">Built for budgets</h3>
<p class="text-secondary small mb-0">Student-friendly options with transparent details upfront.</p>
</div>
</div>
<div class="col-md-4">
<div class="p-4 h-100 rounded-4 bg-light border">
<span class="step-num mb-3">3</span>
<h3 class="h5 mt-2">Simple booking</h3>
<p class="text-secondary small mb-0">Request a room, track status, and manage stays from your dashboard.</p>
</div>
</div>
</div>
</div>
</section>

<section class="py-5">
<div class="container">
<h2 class="h3 fw-semibold text-center mb-4">Get started</h2>
<div class="row g-4 justify-content-center">
<div class="col-md-5 col-lg-4">
<div class="card h-100 rounded-4 shadow-sm border-0">
<div class="card-body p-4 d-flex flex-column">
<h3 class="h5">Already have an account?</h3>
<p class="text-secondary small flex-grow-1">Log in to manage bookings, wishlists, and notifications.</p>
<a class="btn btn-outline-dark" href="login.php">Login</a>
</div>
</div>
</div>
<div class="col-md-5 col-lg-4">
<div class="card h-100 rounded-4 shadow-sm border-0">
<div class="card-body p-4 d-flex flex-column">
<h3 class="h5">New here?</h3>
<p class="text-secondary small flex-grow-1">Create a free account to save rooms and send booking requests.</p>
<a class="btn btn-dark" href="register.php">Create account</a>
</div>
</div>
</div>
</div>
</div>
</section>
</main>

<footer class="bg-dark text-white py-5 mt-auto">
<div class="container">
<div class="row g-4">
<div class="col-md-4">
<h3 class="h6 text-white-50 text-uppercase small mb-3">About</h3>
<p class="small mb-2">Safe and verified stays for students.</p>
<p class="small mb-0 text-secondary">Comfortable living near universities.</p>
</div>
<div class="col-md-2">
<h3 class="h6 text-white-50 text-uppercase small mb-3">Links</h3>
<a class="d-block small py-1" href="index.php">Home</a>
<a class="d-block small py-1" href="explore.php">Explore</a>
<a class="d-block small py-1" href="login.php">Login</a>
<a class="d-block small py-1" href="register.php">Register</a>
</div>
<div class="col-md-3">
<h3 class="h6 text-white-50 text-uppercase small mb-3">Services</h3>
<p class="small mb-1">PG finder</p>
<p class="small mb-0">Hostel listings</p>
</div>
<div class="col-md-3">
<h3 class="h6 text-white-50 text-uppercase small mb-3">Contact</h3>
<p class="small mb-0">pandeydivakar07@gmail.com</p>
</div>
</div>
<hr class="border-secondary my-4 opacity-25">
<p class="small text-secondary mb-0 text-center">Saanidhya</p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
