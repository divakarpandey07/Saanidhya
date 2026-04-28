<?php
session_start();
include("includes/db.php");
$cities = mysqli_query($conn, "SELECT * FROM cities");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Saanidhya</title>
<?php include __DIR__."/includes/tailwind.php"; ?>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-serif">

<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white shadow-sm">
<div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3">
<a class="font-semibold text-slate-900" href="index.php"><img class="h-12" src="./assets/logo.png" alt="Saanidhya"></a>
<div class="flex flex-wrap items-center gap-4 text-lg">
<a class="text-[#1d405c] hover:text-slate-900" href="explore.php">Explore</a>
<a class="text-[#1d405c] hover:text-slate-900" href="#">PG Finder</a>
<a class="text-[#1d405c] hover:text-slate-900" href="#">Hostel Listing</a>
<p class="text-[#1d405c] hover:text-slate-900">|</p>
<?php
    if(isset($_SESSION['user_id'])){
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

<header class="relative flex min-h-[clamp(420px,68vh,640px)] items-center bg-cover bg-center text-white" style="background-image:linear-gradient(120deg,rgba(15,23,42,0.82) 0%,rgba(30,41,59,0.55) 45%,rgba(15,23,42,0.7) 100%),url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267');">
<div class="mx-auto w-full max-w-7xl px-4 py-12 md:py-16">
<div class="max-w-2xl">
<p class="mb-2 text-xs font-medium uppercase tracking-wider text-white/60">Student housing</p>
<h1 class="mb-3 text-3xl text-[#cfab71] font-bold md:text-4xl" id="mainText">Rooms that feel closer to campus and closer to home.</h1>
<p class="mb-6 max-w-xl text-lg text-white" id="subText">Browse verified PGs and hostels in the cities we cover. Pick an area, compare options, and book with confidence.</p>
<form class="rounded-xl bg-white p-2 shadow-lg" action="explore.php" method="get">
<div class="flex flex-col gap-2 sm:flex-row sm:items-stretch">
<label class="sr-only" for="q">Search</label>
<input id="q" name="q" type="search" class="min-w-0 flex-1 rounded-lg border-0 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-slate-900/20" placeholder="Search by city, area, or landmark" autocomplete="off">
<button type="submit" class="shrink-0 rounded-lg bg-[#cfab71] px-6 py-3 font-semibold text-white hover:bg-[#ba8b40]">Search rooms</button>
</div>
</form>
</div>
</div>
</header>

<main>
<section class="py-12 md:py-16">
<div class="mx-auto max-w-7xl px-4">
<div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
<div>
<h2 class="text-2xl font-semibold text-slate-900">Browse by city</h2>
<p class="mt-1 text-slate-600">Choose a city to see listings, filters, and map-friendly details.</p>
</div>
<a class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-[#cfab71] px-4 py-2 text-sm font-medium text-white hover:bg-[#ba8b40]" href="explore.php">View all listings</a>
</div>
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
<?php while($city = mysqli_fetch_assoc($cities)) { 
    $image_url = !empty($city['image_link']) ? htmlspecialchars($city['image_link']) : 'https://via.placeholder.com/400x200?text=No+Image';
?>
<div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="h-36 rounded-lg mb-6 w-full bg-cover bg-center" style="background-image: url('<?php echo $image_url; ?>');"></div>
    <h3 class="text-lg font-semibold text-slate-900"><?php echo htmlspecialchars($city['city_name']); ?></h3>
    <p class="mt-1 text-sm text-slate-600">Rooms and hostels in this city.</p>
    <a class="mt-4 inline-flex rounded-lg bg-[#cfab71] px-4 py-2 text-sm font-medium text-white hover:bg-[#ba8b40]" href="city.php?city_id=<?php echo (int)$city['id']; ?>">Open city</a>
</div>
<?php } ?>
</div>
</div>
</section>

<section class="border-y border-slate-200 bg-[#cfab71a0] py-12 md:py-16">
<div class="mx-auto max-w-7xl px-4">
<div class="mx-auto mb-10 max-w-2xl text-center">
<h2 class="text-2xl font-semibold text-slate-900">Why students use Saanidhya</h2>
<p class="mt-2 text-slate-600">Less guesswork before you move — verified owners, clear expectations, and booking flows built for busy semesters.</p>
</div>
<div class="grid gap-6 md:grid-cols-3">
<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
<span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1d405c] text-sm font-semibold text-white">1</span>
<h3 class="mt-3 font-semibold text-slate-900">Verified listings</h3>
<p class="mt-2 text-sm text-slate-600">Rooms go through checks so you are not comparing mystery posts.</p>
</div>
<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
<span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1d405c] text-sm font-semibold text-white">2</span>
<h3 class="mt-3 font-semibold text-slate-900">Built for budgets</h3>
<p class="mt-2 text-sm text-slate-600">Student-friendly options with transparent details upfront.</p>
</div>
<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
<span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1d405c] text-sm font-semibold text-white">3</span>
<h3 class="mt-3 font-semibold text-slate-900">Simple booking</h3>
<p class="mt-2 text-sm text-slate-600">Request a room, track status, and manage stays from your dashboard.</p>
</div>
</div>
</div>
</section>

<section class="py-12 md:py-16">
<div class="mx-auto max-w-7xl px-4">
<h2 class="mb-8 text-center text-2xl font-semibold text-slate-900">Get started</h2>
<div class="mx-auto grid max-w-3xl gap-6 md:grid-cols-2">
<div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
<h3 class="font-semibold text-slate-900">Already have an account?</h3>
<p class="mt-2 flex-1 text-sm text-slate-600">Log in to manage bookings, wishlists, and notifications.</p>
<a class="mt-4 inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-white bg-[#1d405c] hover:bg-[#1d405ca0]" href="login.php">Login</a>
</div>
<div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
<h3 class="font-semibold text-slate-900">New here?</h3>
<p class="mt-2 flex-1 text-sm text-slate-600">Create a free account to save rooms and send booking requests.</p>
<a class="mt-4 inline-flex justify-center rounded-lg bg-[#1d405c] px-4 py-2 text-sm font-medium text-white hover:bg-[#1d405ca0]" href="register.php">Create account</a>
</div>
</div>
</div>
</section>
</main>

<footer class="bg-[#1d405c] py-12 text-white">
<div class="mx-auto max-w-7xl px-4">
<div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
<div>
<h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">About</h3>
<p class="text-sm text-slate-300">Safe and verified stays for students.</p>
<p class="mt-2 text-sm text-slate-500">Comfortable living near universities.</p>
</div>
<div>
<h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Links</h3>
<div class="flex flex-col gap-2 text-sm">
<a class="text-slate-300 hover:text-white hover:underline" href="index.php">Home</a>
<a class="text-slate-300 hover:text-white hover:underline" href="explore.php">Explore</a>
<a class="text-slate-300 hover:text-white hover:underline" href="login.php">Login</a>
<a class="text-slate-300 hover:text-white hover:underline" href="register.php">Register</a>
</div>
</div>
<div>
<h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Services</h3>
<p class="text-sm text-slate-300">PG finder</p>
<p class="text-sm text-slate-300">Hostel listings</p>
</div>
<div>
<h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Contact</h3>
<p class="text-sm text-slate-300">pandeydivakar07@gmail.com</p>
<p class="text-sm text-slate-300">akashbauri4702@gmail.com</p>
</div>
</div>
<hr class="my-8 border-slate-700">
<p class="text-center text-sm text-slate-500">Saanidhya</p>
</div>
</footer>

<script>
const texts = [
    ["Find Your Perfect Stay","Safe & Verified Rooms"],
    ["Affordable Rooms Near You","Budget Friendly Options"],
    ["Comfort Living Experience","Feel Like Home"],
    ["Trusted Student Housing","Secure & Verified"]
];
let i = 0;
setInterval(() => {
    i = (i + 1) % texts.length;
    document.getElementById("mainText").innerText = texts[i][0];
    document.getElementById("subText").innerText = texts[i][1];
}, 8000);
</script>
</body>
</html>