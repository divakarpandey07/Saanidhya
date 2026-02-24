<?php
session_start();
include("includes/db.php");

if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] === 'owner'){
        header("Location: owner_dashboard.php");
    } else {
        header("Location: customer_dashboard.php");
    }
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            if($user['role'] === 'owner'){
                header("Location: owner_dashboard.php");
            } else {
                header("Location: customer_dashboard.php");
            }
            exit();

        } else {
            $error = "Incorrect Password!";
        }

    } else {
        $error = "Email not registered!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Saanidhya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            background: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px);
            background: rgba(0,0,0,0.5);
        }

        .glass-card {
            position: relative;
            z-index: 2;
            width: 380px;
            padding: 30px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            color: white;
        }

        .form-control {
            border-radius: 30px;
        }

        .btn-custom {
            border-radius: 30px;
            background: white;
            font-weight: 600;
        }

        .btn-custom:hover {
            background: black;
            color: white;
        }
    </style>
</head>

<body>

<div class="overlay"></div>

<div class="glass-card shadow-lg">
    <h3 class="text-center mb-4">Login</h3>

    <?php if(isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button type="submit" class="btn btn-custom w-100">Login</button>
    </form>

    <div class="text-center mt-3">
        <a href="register.php" class="text-white">Create Account</a>
    </div>
</div>

</body>
</html>