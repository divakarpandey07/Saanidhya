<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Saanidhya</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include __DIR__."/includes/tailwind.php"; ?>
</head>
<body class="bg-[#cfab7110] text-slate-800 antialiased font-serif">

<?php include("navbar.php"); ?>

<header class="relative flex min-h-[clamp(420px,68vh,640px)] items-center bg-cover bg-center text-white" style="background-image:linear-gradient(120deg,rgba(15,23,42,0.82) 0%,rgba(30,41,59,0.55) 45%,rgba(15,23,42,0.7) 100%),url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267');">
<div class="mx-auto w-full max-w-7xl px-4 py-12 md:py-20">
<div class="max-w-2xl py-20">
<p class="mb-6 text-md font-medium uppercase tracking-wider text-white/60">Student housing</p>
<h1 class="mb-8 text-3xl text-[#cfab71] font-bold md:text-6xl" id="mainText">Rooms that feel closer to campus and closer to home.</h1>
<p class="mb-16 max-w-xl text-xl text-white" id="subText">Browse verified PGs and hostels in the cities we cover. Pick an area, compare options, and book with confidence.</p>
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
<section class="py-12 md:pt-24 ">
<div class="mx-auto max-w-7xl p-4">
<div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
<div>
<h2 class="text-5xl font-semibold text-[#1d405c]">Browse by city</h2>
<p class="mt-8 text-slate-600 text-xl">Choose a city to see listings, filters, and map-friendly details.</p>
</div>
<a class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-[#cfab71] px-4 py-2 text-lg font-medium text-white hover:bg-[#ba8b40]" href="explore.php">View all listings</a>
</div>
<div class="grid gap-6 my-16 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
<?php while($city=mysqli_fetch_assoc($cities)){ 
    $image_url = !empty($city['image_link']) ? htmlspecialchars($city['image_link']) : 'https://via.placeholder.com/400x200?text=No+Image';
?>
<div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="h-36 rounded-lg mb-6 w-full bg-cover bg-center" style="background-image: url('<?php echo $image_url; ?>');"></div>
    <h3 class="text-lg font-semibold text-slate-900"><i class="fas fa-map-marker-alt pr-2 text-[#cfab71]"></i><?php echo htmlspecialchars($city['city_name']); ?></h3>
    <p class="mt-1 text-lg text-slate-600">Rooms and hostels in this city.</p>
    <a class="mt-4 inline-flex rounded-lg bg-[#cfab71] px-4 py-2 text-lg font-medium text-white hover:bg-[#ba8b40]" href="city.php?city_id=<?php echo (int)$city['id']; ?>">Open city</a>
</div>
<?php } ?>
</div>
</div>
</section>

<?php
// Get featured rooms for the homepage
$featured_rooms_result = @mysqli_query($conn, "
    SELECT r.*, c.city_name, ri.image_path 
    FROM rooms r 
    LEFT JOIN cities c ON r.city_id = c.id 
    LEFT JOIN room_images ri ON r.id = ri.room_id 
    WHERE r.is_verified = 'yes' 
    GROUP BY r.id 
    ORDER BY r.created_at DESC 
    LIMIT 6
");
$featured_rooms = ($featured_rooms_result !== false) ? $featured_rooms_result : false;
?>

<section class="py-12 md:py-24 from-white to-slate-50 bg-linear-to-b">
<div class="mx-auto max-w-7xl p-4">
<div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
<div>
<h2 class="text-5xl font-semibold text-[#1d405c]">Featured Rooms</h2>
<p class="mt-8 text-slate-600 text-xl">Popular choices among students - verified and ready to move in.</p>
</div>
<a class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-[#cfab71] px-4 py-2 text-lg font-medium text-white hover:bg-[#ba8b40]" href="explore.php">View all rooms</a>
</div>
<?php if($featured_rooms && mysqli_num_rows($featured_rooms) > 0): ?>
<div class="grid my-16 gap-12 sm:grid-cols-2 lg:grid-cols-3">
<?php while($room = mysqli_fetch_assoc($featured_rooms)): ?>
<div class="group rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md overflow-hidden">
    <div class="h-48 m-6 rounded-lg bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($room['image_path'] ?: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400'); ?>')"></div>
    <div class="p-5">
        <div class="flex items-start justify-between gap-2">
            <h3 class="text-2xl text-slate-900"><?php echo htmlspecialchars($room['title']); ?></h3>
            <!-- <span class="shrink-0 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700"><?php echo htmlspecialchars(ucfirst($room['is_verified'])); ?></span> -->
        </div>
        <p class="mt-1 text-lg text-slate-600"><i class="fas fa-map-marker-alt pr-2 text-[#cfab71]"></i><?php echo htmlspecialchars($room['address'] ?: $room['city_name']); ?></p>
        <div class="my-6 flex flex-wrap gap-2 text-xs">
            <?php if($room['ac'] == 'yes'): ?>
                 <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                    <i class="fas fa-snowflake text-[#cfab71] pr-1"></i>
                    <span class=" text-[#1d405c]">AC</span>
                </div>           <?php endif; ?>
            <?php if($room['wifi'] == 'yes'): ?>
                <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                    <i class="fas fa-wifi text-[#cfab71] pr-1"></i>
                    <span class=" text-[#1d405c]">WiFi</span>
                </div>
            <?php endif; ?>
            <?php if($room['geyser'] == 'yes'): ?>
                 <div class="rounded-xl py-1 px-2  bg-orange-100 text-[1rem]">
                    <i class="fas fa-hot-tub text-[#cfab71] pr-1"></i>
                    <span class=" text-[#1d405c]">Geyser</span>
                </div>            <?php endif; ?>
            <span class="rounded-full bg-orange-100 text-[1rem] px-2 py-1 text-[#1d405c]"><i class="fas fa-map-marker-alt pr-2 text-[#cfab71]"></i><?php echo htmlspecialchars($room['property_type']); ?></span>
            <!-- <span class="rounded-full bg-pink-100 px-2 py-1 text-[#1d405c]"><?php echo htmlspecialchars(ucfirst($room['gender_allowed'])); ?></span> -->
        </div>
        <div class="mt-4 flex items-center justify-between">
            <p class="text-2xl font-bold text-[#cfab71]">₹<?php echo number_format($room['price']); ?><span class="text-lg font-normal text-slate-500">/month</span></p>
            <div class="flex items-center gap-2">
                <a class="flex h-10 w-10 items-center justify-center rounded-full border border-[#cfab71] text-[#cfab71] hover:bg-[#cfab71] hover:text-white" href="wishlist_action.php?room_id=<?php echo (int)$room['id']; ?>">
                    <i class="fas fa-heart"></i>
                </a>
                <a class="rounded-lg bg-[#1d405c] px-4 py-2 text-lg font-medium text-white hover:bg-[#1d405ca0]" href="room_details.php?id=<?php echo (int)$room['id']; ?>">View Details</a>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>
<?php else: ?>
<p class="text-center text-slate-500">No rooms available at the moment. Check back soon!</p>
<?php endif; ?>
</div>
</section>

<section class="py-12 md:py-24 bg-white">
<div class="mx-auto max-w-7xl px-4">
<div class="mx-auto mb-10 max-w-2xl text-center">
<h2 class="text-5xl font-semibold text-[#1d405c]">How It Works</h2>
<p class="my-6 text-xl text-slate-600">Finding your perfect room is just a few simple steps away.</p>
</div>
<div class="grid gap-6 md:grid-cols-4 my-8 py-8">
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#cfab71] text-2xl font-bold text-white">1</div>
<h3 class="text-3xl text-slate-900">Search</h3>
<p class="mt-2 text-xl text-slate-600">Browse verified rooms by city, area, or landmark using our search.</p>
</div>
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#cfab71] text-2xl font-bold text-white">2</div>
<h3 class="text-3xl text-slate-900">Compare</h3>
<p class="mt-2 text-xl text-slate-600">View details, photos, amenities, and pricing to find your match.</p>
</div>
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#cfab71] text-2xl font-bold text-white">3</div>
<h3 class="text-3xl text-slate-900">Book</h3>
<p class="mt-2 text-xl text-slate-600">Send a booking request and track its status from your dashboard.</p>
</div>
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#cfab71] text-2xl font-bold text-white">4</div>
<h3 class="text-3xl text-slate-900">Move In</h3>
<p class="mt-2 text-xl text-slate-600">Get verified, pay securely, and enjoy your new space!</p>
</div>
</div>
</div>
</section>

<section class="border-y border-slate-200 bg-[#cfab7170] py-12 md:py-24">
<div class="mx-auto max-w-7xl p-8">
<div class="mx-auto mb-10 max-w-2xl text-center">
<h2 class="text-5xl font-semibold text-[#1d405c]">Why students use Saanidhya</h2>
<p class="mt-8 text-xl text-slate-600">Less guesswork before you move — verified owners, clear expectations, and booking flows built for busy semesters.</p>
</div>
<div class="grid gap-6 md:grid-cols-3 py-6">
<div class="rounded-2xl border border-slate-200 bg-[#ffffffe0] p-6">
<span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1d405c] text-lg font-semibold text-white">1</span>
<h3 class="mt-3 text-3xl text-slate-900">Verified listings</h3>
<p class="mt-2 text-xl text-slate-600">Rooms go through checks so you are not comparing mystery posts.</p>
</div>
<div class="rounded-2xl border border-slate-200 bg-[#ffffffe0] p-6">
<span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1d405c] text-lg font-semibold text-white">2</span>
<h3 class="mt-3 text-3xl text-slate-900">Built for budgets</h3>
<p class="mt-2 text-xl text-slate-600">Student-friendly options with transparent details upfront.</p>
</div>
<div class="rounded-2xl border border-slate-200 bg-[#ffffffe0] p-6">
<span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#1d405c] text-lg font-semibold text-white">3</span>
<h3 class="mt-3 text-3xl text-slate-900">Simple booking</h3>
<p class="mt-2 text-xl text-slate-600">Request a room, track status, and manage stays from your dashboard.</p>
</div>
</div>
</div>
</section>


<section class="py-12 md:py-24 bg-white">
<div class="mx-auto max-w-7xl px-4">
<div class="mx-auto mb-16 max-w-2xl text-center">
    <h2 class="text-5xl font-semibold text-[#1d405c]">What Students Say</h2>
    <p class="mt-6 text-xl text-slate-600">Hear from students who found their perfect stay through Saanidhya.</p>
</div>
<div class="grid gap-6 md:grid-cols-3">
<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
<div class="flex items-center gap-1 text-[#cfab71]">★★★★★</div>
<p class="mt-3 text-lg text-slate-600">"Found a great PG near my college within days. The verification process gave me confidence in my choice."</p>
<div class="mt-4 flex items-center gap-3">
<div class="h-10 w-10 rounded-full bg-[#1d405c] text-white flex items-center justify-center font-semibold">A</div>
<div>
    <p class="text-lg font-medium text-slate-900">Ankit Sharma</p>
    <p class="text-xs text-slate-500">Jalandhar</p>
</div>
</div>
</div>
<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
<div class="flex items-center gap-1 text-[#cfab71]">★★★★★</div>
<p class="mt-3 text-lg text-slate-600">"The booking process was so simple. I could track my request status and communicate with the owner easily."</p>
<div class="mt-4 flex items-center gap-3">
    <div class="h-10 w-10 rounded-full bg-[#1d405c] text-white flex items-center justify-center font-semibold">P</div>
    <div>
        <p class="text-lg font-medium text-slate-900">Priya Singh</p>
        <p class="text-xs text-slate-500">Phagwara</p>
</div>
</div>
</div>
<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
<div class="flex items-center gap-1 text-[#cfab71]">★★★★★</div>
<p class="mt-3 text-lg text-slate-600">"Love the wishlist feature! Could save options and compare them later with my parents."</p>
<div class="mt-4 flex items-center gap-3">
<div class="h-10 w-10 rounded-full bg-[#1d405c] text-white flex items-center justify-center font-semibold">R</div>
<div>
<p class="text-lg font-medium text-slate-900">Rahul Kumar</p>
<p class="text-xs text-slate-500">Jalandhar</p>
</div>
</div>
</div>
</section>

<?php
// Get stats from database with error handling
$total_rooms = @mysqli_fetch_assoc(@mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms WHERE is_verified = 'yes'"));
$total_cities = @mysqli_fetch_assoc(@mysqli_query($conn, "SELECT COUNT(*) as count FROM cities"));
$total_users = @mysqli_fetch_assoc(@mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'customer'"));
$total_bookings = @mysqli_fetch_assoc(@mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings"));

// Fallback values if queries fail
$rooms_count = isset($total_rooms['count']) ? (int)$total_rooms['count'] : 0;
$cities_count = isset($total_cities['count']) ? (int)$total_cities['count'] : 0;
$users_count = isset($total_users['count']) ? (int)$total_users['count'] : 0;
$bookings_count = isset($total_bookings['count']) ? (int)$total_bookings['count'] : 0;
?>

<section class="py-12 md:py-16 bg-[#1d405c] text-white">
<div class="mx-auto max-w-7xl px-4">
<div class="grid gap-6 text-center sm:grid-cols-2 lg:grid-cols-4">
<div class="p-4">
<p class="text-7xl font-bold text-[#cfab71]"><?php echo $rooms_count; ?>+</p>
<p class="mt-2 text-2xl text-slate-300">Verified Rooms</p>
</div>
<div class="p-4">
<p class="text-7xl font-bold text-[#cfab71]"><?php echo $cities_count; ?>+</p>
<p class="mt-2 text-2xl text-slate-300">Cities Covered</p>
</div>
<div class="p-4">
<p class="text-7xl font-bold text-[#cfab71]"><?php echo $users_count; ?>+</p>
<p class="mt-2 text-2xl text-slate-300">Happy Students</p>
</div>
<div class="p-4">
<p class="text-7xl font-bold text-[#cfab71]"><?php echo $bookings_count; ?>+</p>
<p class="mt-2 text-2xl text-slate-300">Bookings Made</p>
</div>
</div>
</div>
</section>

<section class="py-12 md:py-24 bg-slate-50">
<div class="mx-auto max-w-7xl px-4">
<div class="mx-auto mb-10 max-w-2xl text-center">
<h2 class="text-5xl font-semibold text-[#1d405c]">Frequently Asked Questions</h2>
<p class="mt-6 text-xl text-slate-600">Got questions? We've got answers.</p>
</div>
<div class="mx-auto max-w-3xl text-xl space-y-4">
<details class="group rounded-2xl border border-slate-200 bg-white p-6 cursor-pointer">
<summary class="flex items-center justify-between gap-2 font-medium text-slate-900 list-none">
<span>How do I book a room?</span>
<span class="transition group-open:rotate-180">▼</span>
</summary>
<p class="mt-4 text-lg text-slate-600">Simply browse rooms, click "View Details" on any listing, and click "Book Now". You'll receive a notification once the owner reviews your request.</p>
</details>
<details class="group rounded-2xl border border-slate-200 bg-white p-6 cursor-pointer">
<summary class="flex items-center justify-between gap-2 font-medium text-slate-900 list-none">
<span>Are all rooms verified?</span>
<span class="transition group-open:rotate-180">▼</span>
</summary>
<p class="mt-4 text-lg text-slate-600">Yes! All rooms on Saanidhya go through a verification process before being listed. We check ownership, property conditions, and documentation.</p>
</details>
<details class="group rounded-2xl border border-slate-200 bg-white p-6 cursor-pointer">
<summary class="flex items-center justify-between gap-2 font-medium text-slate-900 list-none">
<span>Can I list my property as an owner?</span>
<span class="transition group-open:rotate-180">▼</span>
</summary>
<p class="mt-4 text-lg text-slate-600">Absolutely! Register as an owner, complete your profile, and you can add rooms from your dashboard after verification.</p>
</details>
<details class="group rounded-2xl border border-slate-200 bg-white p-6 cursor-pointer">
<summary class="flex items-center justify-between gap-2 font-medium text-slate-900 list-none">
<span>Is there any booking fee?</span>
<span class="transition group-open:rotate-180">▼</span>
</summary>
<p class="mt-4 text-lg text-slate-600">No hidden fees! You only pay the rent directly to the property owner as per the agreement. Saanidhya is free for students.</p>
</details>
<details class="group rounded-2xl border border-slate-200 bg-white p-6 cursor-pointer">
<summary class="flex items-center justify-between gap-2 font-medium text-slate-900 list-none">
<span>How do I contact the owner?</span>
<span class="transition group-open:rotate-180">▼</span>
</summary>
<p class="mt-4 text-lg text-slate-600">Once you book a room, you can use the messaging feature in your dashboard to communicate with owners directly.</p>
</details>
</div>
</div>
</section>
<section class="py-12 bg-white md:py-24">
<div class="mx-auto max-w-7xl px-4">
<h2 class="mb-12 text-center text-5xl font-semibold text-slate-900">Get started</h2>
<div class="mx-auto grid max-w-4xl gap-6 md:grid-cols-2">
<div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
<h3 class="text-3xl text-slate-900">Already have an account?</h3>
<p class="mt-2 flex-1 text-lg py-4 text-slate-600">Log in to manage bookings, wishlists, and notifications.</p>
<a class="mt-4 inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2 text-lg font-medium text-white bg-[#cfab71] hover:bg-[#cfab71e0]" href="login.php">Login</a>
</div>
<div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
<h3 class="text-3xl text-slate-900">New here?</h3>
<p class="mt-2 flex-1 text-lg py-4 text-slate-600">Create a free account to save rooms and send booking requests.</p>
<a class="mt-4 inline-flex justify-center rounded-lg bg-[#cfab71] px-4 py-2 text-lg font-medium text-white hover:bg-[#cfab71e0]" href="register.php">Create account</a>
</div>
</div>
</div>
</section>

</main>

<footer class="bg-[#1d405c] py-12 text-white">
<div class="mx-auto max-w-7xl px-4">
<div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
<div>
<h3 class="mb-3 text-xl font-semibold uppercase tracking-wide text-slate-400">About</h3>
<p class="text-lg text-slate-300">Safe and verified stays for students.</p>
<p class="mt-2 text-xl text-slate-500">Comfortable living near universities.</p>
</div>
<div>
<h3 class="mb-3 text-xl font-semibold uppercase tracking-wide text-slate-400">Links</h3>
<div class="flex flex-col gap-2 text-lg">
<a class="text-slate-300 hover:text-white hover:underline" href="index.php">Home</a>
<a class="text-slate-300 hover:text-white hover:underline" href="explore.php">Explore</a>
<a class="text-slate-300 hover:text-white hover:underline" href="login.php">Login</a>
<a class="text-slate-300 hover:text-white hover:underline" href="register.php">Register</a>
</div>
</div>
<div>
<h3 class="mb-3 text-xl font-semibold uppercase tracking-wide text-slate-400">Services</h3>
<p class="text-lg text-slate-300">PG finder</p>
<p class="text-lg text-slate-300">Hostel listings</p>
</div>
<div>
<h3 class="mb-3 text-xl font-semibold uppercase tracking-wide text-slate-400">Contact</h3>
<p class="text-lg text-slate-300">pandeydivakar07@gmail.com</p>
<p class="text-lg text-slate-300">akashbauri4702@gmail.com</p>
</div>
</div>
<hr class="my-8 border-slate-600">
<p class="text-center text-lg text-slate-300">Saanidhya</p>
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