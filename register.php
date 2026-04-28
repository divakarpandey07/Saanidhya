<?php
session_start();
include("includes/db.php");

if(isset($_POST['register'])){
  $name     = mysqli_real_escape_string($conn, $_POST['name']);
  $email    = mysqli_real_escape_string($conn, $_POST['email']);
  
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role     = $_POST['role'];

  $check = mysqli_query($conn,"SELECT id FROM users WHERE email='$email'");
  if(mysqli_num_rows($check)>0){
    $error = "Email already exists.";
  } else {
    mysqli_query($conn,"INSERT INTO users(name,email,phone,password,role) VALUES('$name','$email','$phone','$password','$role')");
    header("Location: login.php"); exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register &mdash; Saanidhya</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{
  --dark:#06080f;
  --navy:#0d1117;
  --card:rgba(255,255,255,0.04);
  --border:rgba(255,255,255,0.08);
  --gold:#c9a84c;
  --gold-l:#e8c76a;
  --text:#e8eaf0;
  --muted:#7c8591;
  --serif:'Playfair Display',serif;
  --sans:'Inter',sans-serif;
}
*{margin:0;padding:0;box-sizing:border-box;}
html,body{height:100%;font-family:var(--sans);background:var(--dark);color:var(--text);}

.split-container{display:flex;min-height:100vh;width:100%;}

/* LEFT SIDE */
.visual-side{
  flex:1;
  position:relative;
  background:var(--navy);
  display:flex;
  flex-direction:column;
  justify-content:center;
  padding:4rem;
  overflow:hidden;
}

.visual-bg{
  position:absolute;
  inset:0;
  z-index:0;
}

.visual-bg::before{
  content:'';
  position:absolute;
  inset:0;
  background:url('https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=1200') center/cover no-repeat;
  opacity:0.12;
}

.visual-bg::after{
  content:'';
  position:absolute;
  inset:0;
  background:linear-gradient(135deg,rgba(201,168,76,0.06) 0%,transparent 60%);
}

.visual-orb{
  position:absolute;
  border-radius:50%;
  filter:blur(80px);
  opacity:0.12;
}
.orb-1{width:350px;height:350px;background:var(--gold);top:10%;right:-80px;}
.orb-2{width:280px;height:280px;background:#6366f1;bottom:15%;left:-60px;}

.visual-content{
  position:relative;
  z-index:2;
  max-width:480px;
}

.visual-logo{
  font-family:var(--serif);
  font-size:2.5rem;
  font-weight:700;
  color:var(--gold);
  margin-bottom:0.5rem;
}
.visual-logo span{color:#fff;}

.visual-tagline{
  font-size:1.125rem;
  color:var(--muted);
  margin-bottom:2.5rem;
}

.benefits-list{
  list-style:none;
  margin-bottom:2.5rem;
}

.benefits-list li{
  display:flex;
  align-items:center;
  gap:1rem;
  margin-bottom:1.25rem;
  font-size:1rem;
  color:rgba(255,255,255,0.85);
}

.benefit-icon{
  width:48px;
  height:48px;
  border-radius:12px;
  background:rgba(201,168,76,0.1);
  border:1px solid rgba(201,168,76,0.2);
  display:flex;
  align-items:center;
  justify-content:center;
  color:var(--gold);
  font-size:1.25rem;
  flex-shrink:0;
}

.benefit-text strong{
  display:block;
  color:#fff;
  font-weight:600;
  margin-bottom:0.25rem;
}

.benefit-text span{
  font-size:0.875rem;
  color:var(--muted);
}

.visual-image-grid{
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:1rem;
  margin-top:2rem;
}

.grid-img{
  width:100%;
  height:140px;
  object-fit:cover;
  border-radius:12px;
  opacity:0.8;
  transition:all 0.3s;
}

.grid-img:hover{
  opacity:1;
  transform:scale(1.02);
}

/* RIGHT SIDE */
.form-side{
  width:520px;
  background:var(--dark);
  display:flex;
  flex-direction:column;
  justify-content:center;
  padding:3rem;
  position:relative;
  border-left:1px solid var(--border);
  overflow-y:auto;
}

.form-side::before{
  content:'';
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  background:
    radial-gradient(ellipse at top right,rgba(201,168,76,0.06) 0%,transparent 50%),
    radial-gradient(ellipse at bottom left,rgba(99,102,241,0.04) 0%,transparent 50%);
  pointer-events:none;
}

.form-container{
  position:relative;
  z-index:2;
  width:100%;
  max-width:420px;
  margin:0 auto;
}

.form-header{text-align:center;margin-bottom:2rem;}
.form-header h2{
  font-family:var(--serif);
  font-size:1.875rem;
  font-weight:700;
  color:#fff;
  margin-bottom:0.5rem;
}
.form-header p{
  font-size:0.875rem;
  color:var(--muted);
}

.role-selector{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:1rem;
  margin-bottom:1.5rem;
}

.role-card{
  position:relative;
  padding:1.25rem;
  background:var(--card);
  border:2px solid var(--border);
  border-radius:12px;
  cursor:pointer;
  transition:all 0.2s;
  text-align:center;
}

.role-card:hover{
  border-color:rgba(201,168,76,0.3);
  background:rgba(255,255,255,0.06);
}

.role-card.active{
  border-color:var(--gold);
  background:rgba(201,168,76,0.08);
}

.role-card input{
  position:absolute;
  opacity:0;
}

.role-icon{
  font-size:1.75rem;
  margin-bottom:0.75rem;
  color:var(--gold);
}

.role-title{
  font-weight:600;
  color:#fff;
  font-size:0.9375rem;
  margin-bottom:0.25rem;
}

.role-desc{
  font-size:0.75rem;
  color:var(--muted);
}

.form-group{
  margin-bottom:1.25rem;
}

.form-label{
  display:block;
  font-size:0.8125rem;
  font-weight:500;
  color:var(--muted);
  margin-bottom:0.5rem;
}

.form-input{
  width:100%;
  padding:0.875rem 1rem;
  background:rgba(255,255,255,0.05);
  border:1px solid var(--border);
  border-radius:10px;
  color:#fff;
  font-size:0.9375rem;
  transition:all 0.2s;
}

.form-input:focus{
  outline:none;
  border-color:rgba(201,168,76,0.4);
  background:rgba(255,255,255,0.08);
}

.form-input::placeholder{color:rgba(255,255,255,0.3);}

.input-with-icon{
  position:relative;
}

.input-with-icon i{
  position:absolute;
  left:1rem;
  top:50%;
  transform:translateY(-50%);
  color:var(--muted);
  font-size:0.875rem;
}

.input-with-icon .form-input{
  padding-left:2.75rem;
}

.submit-btn{
  width:100%;
  padding:1rem;
  background:linear-gradient(135deg,var(--gold),var(--gold-l));
  border:none;
  border-radius:10px;
  color:var(--dark);
  font-size:0.9375rem;
  font-weight:700;
  cursor:pointer;
  transition:all 0.2s;
  margin-top:0.5rem;
}

.submit-btn:hover{
  transform:translateY(-2px);
  box-shadow:0 8px 25px rgba(201,168,76,0.3);
}

.terms-text{
  text-align:center;
  font-size:0.75rem;
  color:var(--muted);
  margin-top:1rem;
  line-height:1.6;
}

.terms-text a{
  color:var(--gold);
  text-decoration:none;
}

.form-footer{
  text-align:center;
  margin-top:1.5rem;
  padding-top:1.5rem;
  border-top:1px solid var(--border);
  font-size:0.875rem;
  color:var(--muted);
}

.form-footer a{
  color:var(--gold);
  text-decoration:none;
  font-weight:600;
}

.alert{
  padding:0.875rem 1rem;
  border-radius:10px;
  font-size:0.875rem;
  margin-bottom:1rem;
}

.alert-danger{
  background:rgba(248,113,113,0.1);
  border:1px solid rgba(248,113,113,0.25);
  color:#fca5a5;
}

@media(max-width:1024px){
  .visual-side{display:none;}
  .form-side{width:100%;border-left:none;}
}

@media(max-width:480px){
  .form-side{padding:2rem 1.5rem;}
  .role-selector{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<div class="split-container">
  <!-- LEFT SIDE -->
  <div class="visual-side">
    <div class="visual-bg"></div>
    <div class="visual-orb orb-1"></div>
    <div class="visual-orb orb-2"></div>
    
    <div class="visual-content">
      <div class="visual-logo">Saani<span>dhya</span></div>
      <p class="visual-tagline">Join India's trusted student housing community</p>
      
      <ul class="benefits-list">
        <li>
          <div class="benefit-icon"><i class="fas fa-shield-check"></i></div>
          <div class="benefit-text">
            <strong>Verified Listings</strong>
            <span>Every room is AI & admin verified</span>
          </div>
        </li>
        <li>
          <div class="benefit-icon"><i class="fas fa-bolt"></i></div>
          <div class="benefit-text">
            <strong>Instant Booking</strong>
            <span>Book your room in under 2 minutes</span>
          </div>
        </li>
        <li>
          <div class="benefit-icon"><i class="fas fa-headset"></i></div>
          <div class="benefit-text">
            <strong>24/7 Support</strong>
            <span>AI assistant always ready to help</span>
          </div>
        </li>
        <li>
          <div class="benefit-icon"><i class="fas fa-wallet"></i></div>
          <div class="benefit-text">
            <strong>Secure Payments</strong>
            <span>Razorpay powered transactions</span>
          </div>
        </li>
      </ul>
      
      <div class="visual-image-grid">
        <img src="https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=300" class="grid-img" alt="Room 1">
        <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=300" class="grid-img" alt="Room 2">
        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=300" class="grid-img" alt="Room 3">
        <img src="https://images.unsplash.com/photo-1507089947368-19c1da9775ae?w=300" class="grid-img" alt="Room 4">
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE -->
  <div class="form-side">
    <div class="form-container">
      <div class="form-header">
        <h2>Create Account</h2>
        <p>Start your journey to find the perfect stay</p>
      </div>

      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle" style="margin-right:8px;"></i><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="role-selector">
          <label class="role-card active" onclick="selectRole(this, 'customer')">
            <input type="radio" name="role" value="customer" checked>
            <div class="role-icon"><i class="fas fa-user"></i></div>
            <div class="role-title">I WANT TO RENT</div>
            <div class="role-desc">Find & book rooms</div>
          </label>
          <label class="role-card" onclick="selectRole(this, 'owner')">
            <input type="radio" name="role" value="owner">
            <div class="role-icon"><i class="fas fa-building"></i></div>
            <div class="role-title">I HAVE ROOMS</div>
            <div class="role-desc">List your property</div>
          </label>
        </div>

        <div class="form-group">
          <label class="form-label">Full Name</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" name="name" class="form-input" placeholder="Enter your full name" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Email Address</label>
          <div class="input-with-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-input" placeholder="Enter your email" required>
          </div>
        </div>

    

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-with-icon">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-input" placeholder="Create a strong password" required minlength="6">
          </div>
        </div>

        <button type="submit" name="register" class="submit-btn">Create Account</button>

        <p class="terms-text">
          By creating an account, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
        </p>
      </form>

      <div class="form-footer">
        <p>Already have an account? <a href="login.php">Sign in</a></p>
        <p style="margin-top:0.75rem;"><a href="index.php"><i class="fas fa-arrow-left" style="margin-right:6px;"></i>Back to Home</a></p>
      </div>
    </div>
  </div>
</div>

<script>
function selectRole(card, role){
  document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
  card.classList.add('active');
  card.querySelector('input').checked = true;
}
</script>

</body>
</html>