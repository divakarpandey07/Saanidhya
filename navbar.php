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
            <?php
                if(isset($_SESSION['user_id'])){
                    echo '
                        <a class="text-[#1d405c] hover:text-slate-900" href="wishlist.php">Wishlist</a>
                        <a class="text-[#1d405c] hover:text-slate-900" href="customer_dashboard.php">Dashboard</a>
                        <p class="text-[#1d405c] hover:text-slate-900">|</p>
                        <a class="rounded-lg bg-[#c64f4f] px-3 py-1.5 font-medium text-white hover:bg-[#ff0000a0]" href="logout.php">Logout</a>
                        ';
                    } else {
                        echo '
                            <p class="text-[#1d405c] hover:text-slate-900">|</p>
                            <a class="text-[#1d405c] hover:text-slate-900" href="login.php">Login</a>
                            <a class="rounded-lg bg-[#cfab71] px-3 py-1.5 font-medium text-white hover:bg-[#ba8b40]" href="register.php">Register</a>
                    ';
                }
            ?>
        </div>
    </div>
</nav>