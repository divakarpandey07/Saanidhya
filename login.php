<?php
session_start();
include("includes/db.php");
include("includes/mailer.php");
include("includes/config.php");

$tab = $_GET['tab'] ?? 'password';

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['login_type'])){

  if($_POST['login_type']=='password'){
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $result   = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($result)==1){
      $user = mysqli_fetch_assoc($result);
      if(password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['email']   = $user['email'];
        $redir = ['owner'=>'owner_dashboard.php','customer'=>'customer_dashboard.php','admin'=>'admin_dashboard.php'];
        header("Location:" . ($redir[$user['role']] ?? 'index.php'));
        exit();
      } else { $error = "Wrong password."; }
    } else { $error = "Account not found."; }

  } elseif($_POST['login_type']=='otp_send'){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE email='$email'"));
    if($user){
      $otp = rand(100000, 999999);
      $_SESSION['otp']       = $otp;
      $_SESSION['otp_email'] = $email;
      $_SESSION['otp_time']  = time();
      $body = emailTemplate('Your OTP Code', "Hi <strong>{$user['name']}</strong>,<br><br>Your Saanidhya login OTP is:<br><br><div style='font-size:36px;font-weight:800;color:#c9a84c;text-align:center;letter-spacing:8px;'>$otp</div><br>This OTP expires in 10 minutes. Do not share it with anyone.");
      sendMail($email, $user['name'], 'Your Saanidhya OTP', $body);
      $otpSent = true;
      $tab = 'otp';
    } else { $error = "Email not registered."; $tab = 'otp'; }

  } elseif($_POST['login_type']=='otp_verify'){
    $entered   = trim($_POST['otp_code']);
    $otp_email = $_SESSION['otp_email'] ?? '';
    if(!isset($_SESSION['otp']) || time() - ($_SESSION['otp_time']??0) > 600){
      $error = "OTP expired. Please request a new one."; $tab = 'otp';
    } elseif($entered == $_SESSION['otp']){
      $user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE email='".mysqli_real_escape_string($conn,$otp_email)."'"));
      if($user){
        unset($_SESSION['otp'],$_SESSION['otp_email'],$_SESSION['otp_time']);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['email']   = $user['email'];
        $redir = ['owner'=>'owner_dashboard.php','customer'=>'customer_dashboard.php','admin'=>'admin_dashboard.php'];
        header("Location:" . ($redir[$user['role']] ?? 'index.php'));
        exit();
      }
    } else { $error = "Incorrect OTP. Try again."; $tab = 'otp'; }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login &mdash; Saanidhya</title>
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
html,body{height:100%;font-family:var(--sans);background:var(--dark);color:var(--text);overflow-x:hidden;}

.split-container{display:flex;min-height:100vh;width:100%;}

/* LEFT SIDE - Visual Section */
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
  background:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200') center/cover no-repeat;
  opacity:0.15;
  filter:blur(2px);
}

.visual-bg::after{
  content:'';
  position:absolute;
  inset:0;
  background:linear-gradient(135deg,rgba(201,168,76,0.08) 0%,transparent 50%,rgba(99,102,241,0.05) 100%);
}

.visual-orb{
  position:absolute;
  border-radius:50%;
  filter:blur(80px);
  opacity:0.15;
}
.orb-1{width:400px;height:400px;background:var(--gold);top:-100px;right:-100px;}
.orb-2{width:300px;height:300px;background:#5b21b6;bottom:-50px;left:-50px;}

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
  letter-spacing:0.02em;
}
.visual-logo span{color:#fff;}

.visual-tagline{
  font-size:1.125rem;
  color:var(--muted);
  margin-bottom:3rem;
  font-weight:400;
}

.visual-image-stack{
  position:relative;
  width:100%;
  height:320px;
  margin-bottom:2.5rem;
}

.stack-img{
  position:absolute;
  border-radius:16px;
  box-shadow:0 20px 60px rgba(0,0,0,0.5);
  transition:all 0.6s cubic-bezier(0.4,0,0.2,1);
  object-fit:cover;
}

.stack-img:nth-child(1){
  width:280px;height:200px;top:0;left:0;z-index:3;
  transform:rotate(-3deg);
}
.stack-img:nth-child(2){
  width:260px;height:180px;top:60px;left:140px;z-index:2;
  transform:rotate(5deg);
}
.stack-img:nth-child(3){
  width:240px;height:160px;top:120px;left:60px;z-index:1;
  transform:rotate(-2deg);
}

.stack-img:hover{
  transform:rotate(0deg) scale(1.05);
  z-index:10;
}

.rotating-text-container{
  min-height:80px;
  position:relative;
}

.rotating-text{
  font-family:var(--serif);
  font-size:2.25rem;
  font-weight:600;
  color:#fff;
  line-height:1.3;
  opacity:0;
  transform:translateY(20px);
  transition:all 0.6s ease;
  position:absolute;
  top:0;
  left:0;
  width:100%;
}

.rotating-text.active{
  opacity:1;
  transform:translateY(0);
}

.visual-dots{
  display:flex;
  gap:8px;
  margin-top:1.5rem;
}

.dot{
  width:8px;
  height:8px;
  border-radius:50%;
  background:rgba(255,255,255,0.2);
  transition:all 0.3s;
  cursor:pointer;
}

.dot.active{
  background:var(--gold);
  width:24px;
  border-radius:4px;
}

.visual-stats{
  display:flex;
  gap:2.5rem;
  margin-top:auto;
  padding-top:3rem;
}

.stat-v{text-align:center;}
.stat-v-num{
  font-family:var(--serif);
  font-size:2rem;
  font-weight:700;
  color:var(--gold);
}
.stat-v-lbl{
  font-size:0.75rem;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:0.08em;
  margin-top:0.25rem;
}

/* RIGHT SIDE - Form Section */
.form-side{
  width:480px;
  background:var(--dark);
  display:flex;
  flex-direction:column;
  justify-content:center;
  padding:3rem;
  position:relative;
  border-left:1px solid var(--border);
}

.form-side::before{
  content:'';
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  background:
    radial-gradient(ellipse at top right,rgba(201,168,76,0.08) 0%,transparent 50%),
    radial-gradient(ellipse at bottom left,rgba(92, 95, 229, 0.05) 0%,transparent 50%);
  pointer-events:none;
}

.form-container{
  position:relative;
  z-index:2;
  width:100%;
  max-width:380px;
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

.tab-switcher{
  display:flex;
  background:var(--card);
  border:1px solid var(--border);
  border-radius:12px;
  padding:4px;
  margin-bottom:1.5rem;
}

.tab-btn{
  flex:1;
  padding:0.75rem 1rem;
  border:none;
  background:transparent;
  color:var(--muted);
  font-size:0.875rem;
  font-weight:600;
  cursor:pointer;
  border-radius:8px;
  transition:all 0.2s;
}

.tab-btn.active{
  background:var(--gold);
  color:var(--dark);
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
  font-family:var(--sans);
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

.divider{
  display:flex;
  align-items:center;
  gap:1rem;
  margin:1.5rem 0;
  color:var(--muted);
  font-size:0.75rem;
}

.divider::before,.divider::after{
  content:'';
  flex:1;
  height:1px;
  background:var(--border);
}

.social-btn{
  width:100%;
  padding:0.875rem;
  background:var(--card);
  border:1px solid var(--border);
  border-radius:10px;
  color:var(--text);
  font-size:0.875rem;
  cursor:pointer;
  transition:all 0.2s;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:0.75rem;
}

.social-btn:hover{
  border-color:rgba(255,255,255,0.2);
  background:rgba(255,255,255,0.08);
}

.form-footer{
  text-align:center;
  margin-top:1.5rem;
  font-size:0.875rem;
  color:var(--muted);
}

.form-footer a{
  color:var(--gold);
  text-decoration:none;
  font-weight:600;
}

.form-footer a:hover{text-decoration:underline;}

.otp-display{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:12px;
  padding:1.25rem;
  text-align:center;
  margin-bottom:1.25rem;
}

.otp-display p{
  font-size:0.875rem;
  color:var(--muted);
  margin-bottom:0.5rem;
}

.otp-display strong{
  color:#fff;
  font-size:0.9375rem;
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

.alert-success{
  background:rgba(52,211,153,0.1);
  border:1px solid rgba(52,211,153,0.25);
  color:#a7f3d0;
}

.hidden{display:none;}

/* Responsive */
@media(max-width:1024px){
  .visual-side{display:none;}
  .form-side{width:100%;border-left:none;}
}

@media(max-width:480px){
  .form-side{padding:2rem 1.5rem;}
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
      <p class="visual-tagline">Premium Student Housing Platform</p>
      
      <div class="visual-image-stack">
        <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400" class="stack-img" alt="Modern room">
        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400" class="stack-img" alt="Cozy PG">
        <img src="https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=400" class="stack-img" alt="Student hostel">
      </div>
      
      <div class="rotating-text-container" id="rotatingContainer">
        <div class="rotating-text active" data-index="0">Find your perfect stay near campus</div>
        <div class="rotating-text" data-index="1">Verified PGs with AI-powered trust</div>
        <div class="rotating-text" data-index="2">Book rooms in 3 simple steps</div>
        <div class="rotating-text" data-index="3">Transparent pricing, no hidden fees</div>
        <div class="rotating-text" data-index="4">WiFi, AC, Geyser — filter by amenities</div>
        <div class="rotating-text" data-index="5">Real photos, real rooms, real owners</div>
        <div class="rotating-text" data-index="6">Jalandhar & Phagwara's best stays</div>
        <div class="rotating-text" data-index="7">Instant booking confirmation</div>
        <div class="rotating-text" data-index="8">Safe & secure payment options</div>
        <div class="rotating-text" data-index="9">Your home away from home</div>
      </div>
      
      <div class="visual-dots" id="textDots"></div>
      
      <div class="visual-stats">
        <div class="stat-v">
          <div class="stat-v-num">500+</div>
          <div class="stat-v-lbl">Verified Rooms</div>
        </div>
        <div class="stat-v">
          <div class="stat-v-num">10</div>
          <div class="stat-v-lbl">Cities</div>
        </div>
        <div class="stat-v">
          <div class="stat-v-num">2000+</div>
          <div class="stat-v-lbl">Happy Students</div>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE -->
  <div class="form-side">
    <div class="form-container">
      <div class="form-header">
        <h2>Welcome Back</h2>
        <p>Sign in to continue your room search</p>
      </div>

      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle" style="margin-right:8px;"></i><?php echo $error; ?></div>
      <?php endif; ?>
      <?php if(isset($otpSent)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle" style="margin-right:8px;"></i>OTP sent to your email!</div>
      <?php endif; ?>

      <!-- Password Login -->
      <div id="tab-password" class="<?php echo $tab!='password'?'hidden':''; ?>">
        <form method="POST">
          <input type="hidden" name="login_type" value="password">
          
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
              <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
            </div>
          </div>
          
          <div style="text-align:right;margin-bottom:1rem;">
            <a href="#" style="font-size:0.8125rem;color:var(--muted);text-decoration:none;">Forgot password?</a>
          </div>
          
          <button type="submit" class="submit-btn">Sign In</button>
        </form>
      </div>

    

      <div class="form-footer">
        <p>Don't have an account? <a href="register.php">Create one</a></p>
        <p style="margin-top:0.75rem;"><a href="index.php"><i class="fas fa-arrow-left" style="margin-right:6px;"></i>Back to Home</a></p>
      </div>
    </div>
  </div>
</div>

<script>
// Rotating text animation
const texts = document.querySelectorAll('.rotating-text');
const dotsContainer = document.getElementById('textDots');
let currentIndex = 0;
const intervalTime = 8000;

// Create dots
texts.forEach((_,i) => {
  const dot = document.createElement('div');
  dot.className = 'dot' + (i===0?' active':'');
  dot.onclick = () => showText(i);
  dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll('.dot');

function showText(index){
  texts.forEach(t => t.classList.remove('active'));
  dots.forEach(d => d.classList.remove('active'));
  
  texts[index].classList.add('active');
  dots[index].classList.add('active');
  currentIndex = index;
}

function nextText(){
  const next = (currentIndex + 1) % texts.length;
  showText(next);
}

setInterval(nextText, intervalTime);

// Tab switching
function switchTab(tab){
  document.getElementById('tab-password').classList.toggle('hidden', tab !== 'password');
  document.getElementById('tab-otp').classList.toggle('hidden', tab !== 'otp');
  
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.toggle('active', 
      (tab === 'password' && btn.textContent.includes('Password')) ||
      (tab === 'otp' && btn.textContent.includes('OTP'))
    );
  });
}
</script>

</body>
</html>