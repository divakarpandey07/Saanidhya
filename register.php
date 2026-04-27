<?php
session_start();
include("includes/db.php");

if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists";
    } else {
        mysqli_query($conn, "INSERT INTO users(name,email,password,role) VALUES('$name','$email','$password','$role')");
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            overflow: hidden;
        }

        .bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1493809842364-78817add7ffb') no-repeat center/cover;
            filter: blur(8px);
            z-index: -2;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }

        .box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: white;
            text-align: center;
        }

        input,
        select {
            background: rgba(255, 255, 255, 0.85) !important;
            border: none !important;
        }

        button {
            background: white !important;
            color: black !important;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="bg"></div>
    <div class="overlay"></div>

    <div class="box">

        <h3 class="mb-4">Register</h3>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST" onsubmit="return validateForm()">

            <input type="text" name="name" id="name" class="form-control mb-3" placeholder="Full Name" required>

            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <select name="role" class="form-control mb-3">
                <option value="customer">Customer</option>
                <option value="owner">Owner</option>
            </select>

            <button type="submit" name="register" class="btn w-100">Register</button>

        </form>

    </div>

</body>
<script>
function validateForm() {
    var name = document.getElementById('name').value;
    var email = document.querySelector('input[name="email"]').value;
    
    if (/[0-9]/.test(name)) {
        alert("Name cannot contain numbers.");
        return false;
    }
    
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    
    return true;
}
</script>

</html>