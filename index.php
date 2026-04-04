<?php
include("includes/db.php");
$cities=mysqli_query($conn,"SELECT * FROM cities");
?>

<!DOCTYPE html>
<html>
<head>
<title>Saanidhya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center/cover;
color:white;
}

.overlay{
position:fixed;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(6px);
z-index:1;
}

.content{
position:relative;
z-index:2;
}

.hero{
min-height:60vh;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
text-align:center;
}

.search-box{
width:50%;
}

.glass{
background:rgba(255,255,255,0.12);
backdrop-filter:blur(12px);
border-radius:15px;
padding:25px;
}

.footer{
background:rgba(0,0,0,0.8);
backdrop-filter:blur(8px);
padding:40px 0;
}
</style>

</head>

<body>

<div class="overlay"></div>

<div class="content">

<div class="hero">

<h1 id="mainText">Find Your Perfect Stay</h1>
<p id="subText">Safe & Verified Rooms for Students</p>

<div class="search-box mt-3">
<input type="text" class="form-control mb-2" placeholder="Search by area or city">
<button class="btn btn-light w-100">Search</button>
</div>

<div class="container mt-4">

<h4 class="mb-3">Explore Cities</h4>

<div class="row justify-content-center">

<?php while($city=mysqli_fetch_assoc($cities)){ ?>

<div class="col-md-3 m-2">
<div class="glass text-center">
<h5><?php echo $city['city_name']; ?></h5>
<a href="city.php?city_id=<?php echo $city['id']; ?>" class="btn btn-light w-100 mt-2">Explore</a>
</div>
</div>

<?php } ?>

</div>

</div>

</div>

<div class="container text-center mt-4">

<h4 class="mb-3">Get Started</h4>

<div class="row justify-content-center">

<div class="col-md-3 m-2">
<div class="glass">
<h5>Login</h5>
<a href="login.php" class="btn btn-light w-100 mt-2">Go to Login</a>
</div>
</div>

<div class="col-md-3 m-2">
<div class="glass">
<h5>Register</h5>
<a href="register.php" class="btn btn-light w-100 mt-2">Create Account</a>
</div>
</div>

</div>

</div>

<div class="container mt-4 text-center">
<h4>Why Choose Saanidhya?</h4>

<div class="row mt-3">
<div class="col-md-3">✔ Verified Rooms</div>
<div class="col-md-3">✔ Affordable</div>
<div class="col-md-3">✔ Easy Booking</div>
<div class="col-md-3">✔ Student Friendly</div>
</div>
</div>

<div class="footer mt-4">
<div class="container">
<div class="row">

<div class="col-md-3">
<h5>About</h5>
<p>Safe & verified stays for students.</p>
<p>Feels Like Home.</p>
<p>Comfort Living Experience.</p>
<p>Affordable Rooms Near Universities.</p>
</div>

<div class="col-md-3">
<h5>Quick Links</h5>
<a href="index.php" class="d-block text-white">Home</a>
<a href="explore.php" class="d-block text-white">Explore</a>
<a href="login.php" class="d-block text-white">Login</a>
<a href="register.php" class="d-block text-white">Register</a>
</div>

<div class="col-md-3">
<h5>Services</h5>
<p>PG Finder</p>
<p>Hostel</p>
</div>

<div class="col-md-3">
<h5>Contact</h5>
<p>Email: pandeydivakar07@gmail.com</p>
</div>

</div>
</div>
</div>

</div>

<script>
const texts=[
["Find Your Perfect Stay","Safe & Verified Rooms"],
["Affordable Rooms Near You","Budget Friendly Options"],
["Comfort Living Experience","Feel Like Home"],
["Trusted Student Housing","Secure & Verified"]
];

let i=0;

setInterval(()=>{
i=(i+1)%texts.length;
document.getElementById("mainText").innerText=texts[i][0];
document.getElementById("subText").innerText=texts[i][1];
},8000);
</script>

</body>
</html>