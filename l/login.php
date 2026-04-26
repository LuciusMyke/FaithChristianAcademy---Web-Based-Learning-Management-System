<?php
session_start();

// ===== DATABASE CONNECTION =====
require_once __DIR__ . "/../config/db.php";
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ================= MESSAGES ================= */
$error = null;
$success = null;

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {

    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("
        SELECT id, email, password, role, enrollment_status 
        FROM student 
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            if ($row['enrollment_status'] === 'suspended') {
                $error = "Your account is suspended. Please contact admin.";
            } else {

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email']   = $row['email'];
                $_SESSION['role']    = $row['role'];

                $success = "Login successful! Redirecting...";

                if ($row['role'] == "student") {
                    header("Location: ../home/dashboard.php");
                    exit();
                } elseif ($row['role'] == "teacher") {
                    header("Location: ../teacher/dashboard.php");
                    exit();
                } elseif ($row['role'] == "admin") {
                    header("Location: /admin-office-panel/login/panel/adminpanel.php");
                    exit();
                }
            }

        } else {
            $error = "Invalid email or password.";
        }

    } else {
        $error = "Invalid email or password.";
    }
}

/* ================= REGISTER ================= */
if (isset($_POST['create'])) {

    $id = intval($_POST["id"]);
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];
    $birthday = $_POST["birthday"];
    $gender = $_POST["gender"];
    $section = $conn->real_escape_string($_POST["section"]);

    $birthDate = new DateTime($birthday);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    $role = "student";
    $enrollment_status = "pending";
    $current_step = 1;

    $checkEmail = $conn->query("SELECT email FROM student WHERE email='$email'");

    if ($checkEmail->num_rows > 0) {
        $error = "Email already registered.";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = "INSERT INTO student 
        (id, name, email, password, role, enrollment_status, current_step, birthday, age, section, gender)
        VALUES 
        ('$id', '$name', '$email', '$hashedPassword', 'student', '$enrollment_status', '$current_step', '$birthday', '$age', '$section', '$gender')";

        if ($conn->query($insert)) {
            $success = "Account created successfully!";
        } else {
            $error = "Failed to create account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login / Signup</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<!-- Paper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.12.19/paper-full.min.js"></script>

<style>
:root {
  --font-default: 'Roboto', sans-serif;

  /* NAVY GLASS THEME (MATCH YOUR HOMEPAGE) */
  --navy-1: rgba(10, 25, 60, 0.75);
  --navy-2: rgba(15, 35, 80, 0.55);
  --navy-border: rgba(255,255,255,0.12);

  --text-light: #ffffff;
  --text-muted: rgba(255,255,255,0.7);
}

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: var(--font-default);
  overflow: hidden;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle at top left, #0a1025, #000);
}

/* ================= BACKGROUND ================= */

#back {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: -1;
}

.canvas-back {
  position: absolute;
  width: 100%;
  height: 100%;
}

/* NAVY GLASS SIDES */
.backLeft {
  position: absolute;
  left: 0;
  width: 50%;
  height: 100%;

  background: var(--navy-1);
  backdrop-filter: blur(25px);
  -webkit-backdrop-filter: blur(25px);

  border-right: 1px solid var(--navy-border);
  transition: 0.6s ease;
}

.backRight {
  position: absolute;
  right: 0;
  width: 50%;
  height: 100%;

  background: var(--navy-2);
  backdrop-filter: blur(25px);
  -webkit-backdrop-filter: blur(25px);

  border-left: 1px solid var(--navy-border);
  transition: 0.6s ease;
}

/* ================= SLIDE BOX ================= */

#slideBox {
  width: 50%;
  height: 100%;
  position: absolute;
  margin-left: 50%;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0,0,0,0.6);
}

.topLayer {
  width: 200%;
  height: 100%;
  display: flex;
  position: relative;
  left: -100%;
  transition: margin-left 0.6s ease;
}

/* ================= FORM PANELS ================= */

.left, .right {
  width: 50%;
  height: 100%;
  overflow-y: auto;
  padding: 35px 30px; /* tighter spacing */

  background: #ffffff;
  color: #1a1a1a;

  box-shadow: 0 10px 35px rgba(0,0,0,0.25);
  display: flex;
  align-items: center;
}

/* CONTENT CENTERING */
.left .content,
.right .content {
  width: 85%;
  margin: auto;
}

/* TITLE FIX (no more top gap issue) */
h2 {
  margin: 0 0 10px 0;
  font-size: 28px;
  color: #0a1a3a;
}

/* DESCRIPTION */
p {
  color: #555;
  font-size: 13px;
  margin-bottom: 20px;
}

/* ================= INPUTS ================= */

label {
  font-size: 11px;
  text-transform: uppercase;
  color: #666;
  margin-bottom: 6px;
  display: block;
}

input, select {
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;

  border: 1px solid #ddd;
  background: #f7f9fc;

  outline: none;
  font-size: 14px;
  transition: 0.3s;
}

input:focus, select:focus {
  border-color: #0a1a3a;
  background: #fff;
  box-shadow: 0 0 0 2px rgba(10,25,60,0.1);
}

/* ================= FORM SPACING ================= */

.form-element {
  margin: 12px 0;
}

.form-stack {
  display: flex;
  flex-direction: column;
}

/* ================= CHECKBOX ================= */

.form-checkbox {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.checkbox {
  width: 18px;
  height: 18px;
  margin-right: 10px;
  appearance: none;

  border-radius: 4px;
  border: 1px solid #bbb;
  background: #fff;
  position: relative;
}

.checkbox:checked::after {
  content: "✔";
  position: absolute;
  top: -2px;
  left: 3px;
  font-size: 14px;
  color: #0a1a3a;
}

/* ================= BUTTONS ================= */

button {
  padding: 10px 14px;
  border: none;
  border-radius: 8px;

  font-weight: 600;
  text-transform: uppercase;
  cursor: pointer;

  transition: 0.3s;
  color: #fff;
}

/* NAVY BUTTONS (MATCH HOMEPAGE) */
.signup {
  background: #0a1a3a;
}

.login {
  background: #102a5c;
}

.signup:hover {
  background: #08122a;
}

.login:hover {
  background: #0c1d40;
}

/* secondary button */
.off {
  background: transparent;
  color: #0a1a3a;
  border: 1px solid #0a1a3a;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 768px) {
  #slideBox {
    width: 80%;
    margin-left: 20%;
  }
}
</style>
</head>
<body>


<div id="back">
  <canvas id="canvas" class="canvas-back"></canvas>
  <div class="backRight"></div>
  <div class="backLeft"></div>
</div>

<div id="slideBox">
  <div class="topLayer">
    <!-- Sign Up -->
    <div class="left">
      <div class="content">
        <h2>Sign Up</h2>
          <p style="font-size:13px; color:#777; margin-bottom:20px;">
  Access your student dashboard, grades, and school updates.
</p>
      
           <form id="form-signup" method="POST">
           <div class="form-element form-stack">
               
  <label>Student ID</label>
  <input type="number" name="id" required>
</div>
  <div class="form-element form-stack">
    <label>Full Name</label>
    <input type="text" name="name" required>
  </div>

  <div class="form-element form-stack">
    <label>Email</label>
    <input type="email" name="email" required>
  </div>

  <div class="form-element form-stack">
    <label>Password</label>
    <input type="password" name="password" required>
  </div>

  <div class="form-element form-stack">
    <label>Birthday</label>
    <input type="date" name="birthday" required>
  </div>

  <div class="form-element form-stack">
    <label>Gender</label>
    <select name="gender" required>
      <option value="">Select</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select>
  </div>

  <div class="form-element form-stack">
    <label>Section</label>
    <input type="text" name="section" placeholder="e.g. Grade 10 - A" required>
  </div>

  <div class="form-element form-checkbox">
    <input type="checkbox" class="checkbox" required>
    <label>I agree to the Terms & Privacy</label>
  </div>

  <div class="form-element form-submit">
    <button type="submit" name="create" class="signup">Sign Up</button>
    <button type="button" id="goLeft" class="signup off">Log In</button>
  </div>
</form>
      </div>
    </div>
    <!-- Login -->
    <div class="right">
      <div class="content">
        <h2>Login</h2>
     
        <form id="form-login" method="POST">
          <div class="form-element form-stack"><label>Email</label><input type="email" name="email" required></div>
          <div class="form-element form-stack"><label>Password</label><input type="password" name="password" required></div>
          <div class="form-element form-submit">
            <button type="submit" name="login" class="login">Log In</button>
            <button type="button" id="goRight" class="login off">Sign Up</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle login/signup
$('#goRight').click(function(){
    $('#slideBox').css('margin-left','0');
    $('.topLayer').css('margin-left','100%');
});
$('#goLeft').click(function(){
    if(window.innerWidth>768){
        $('#slideBox').css('margin-left','50%');
    } else {
        $('#slideBox').css('margin-left','20%');
    }
    $('.topLayer').css('margin-left','0');
});

// Paper.js
paper.setup('canvas');
var shapeGroup = new Group();
var positions = [];

function updatePositions(){
    var w=view.size.width,h=view.size.height,mx=w/2,my=h/2;
    positions = [
        {x:mx-50, y:150},
        {x:200, y:my},
        {x:w-130, y:h-75},
        {x:0, y:my+100},
        {x:(mx/2)+100, y:100},
        {x:mx+80, y:h-50},
        {x:w+60, y:my-50},
        {x:mx+100, y:my+100}
    ];
}

function createShapes(){
    updatePositions();
    var paths=[
        'M231,352l445-156L600,0L452,54L331,3L0,48L231,352',
        'M0,0l64,219L29,343l535,30L478,37l-133,4L0,0z',
        'M0,65l16,138l96,107l270-2L470,0L337,4L0,65z',
        'M333,0L0,94l64,219L29,437l570-151l-196-42L333,0',
        'M331.9,3.6l-331,45l231,304l445-156l-76-196l-148,54L331.9,3.6z',
        'M389,352l92-113l195-43l0,0l0,0L445,48l-80,1L122.7,0L0,275.2L162,297L389,352',
        'M50,100L300,150L550,50L750,300L500,250L300,450L50,100',
        'M700,350L500,350L700,500L400,400L200,450L250,350L100,300L150,50L350,100L250,150L450,150L400,50L550,150L350,250L650,150L650,50L700,150L600,250L750,250L650,300L700,350'
    ];
    for(var i=0;i<paths.length;i++){
        var s=new Path({strokeColor:'rgba(255,255,255,0.5)',strokeWidth:2,parent:shapeGroup});
        s.pathData=paths[i];
        s.scale(2);
        s.position=positions[i];
    }
}

createShapes();
view.onFrame=function(){
    for(var i=0;i<shapeGroup.children.length;i++){
        shapeGroup.children[i].rotate(i%2===0?-0.1:0.1);
    }
}
view.onResize=function(){
    updatePositions();
    for(var i=0;i<shapeGroup.children.length;i++){
        shapeGroup.children[i].position=positions[i];
    }
}
</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if (!empty($success)): ?>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: <?= json_encode($success) ?>
});
<?php endif; ?>

<?php if (!empty($error)): ?>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: <?= json_encode($error) ?>
});
<?php endif; ?>
</script>

</body>
</html>