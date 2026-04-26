<?php
session_start();

// ===== DATABASE CONNECTION =====
$servername = "sql110.infinityfree.com";
$username = "if0_41176520";
$password = "1W89jn4xLkI";
$dbname = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$error = "";
$success = "";

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Check if user exists
    $query = "SELECT * FROM student WHERE email='$email'"; // can be student or teacher table
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            // Create session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on role
            if ($row['role'] == "student") {
                header("Location: ../s/dashboard.php");
                exit();
            } elseif ($row['role'] == "teacher") {
                header("Location: ../home/dashboard.php");
                exit();
            } elseif ($row['role'] == "admin") {
                header("Location: admin/dashboard.php");
                exit();
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

    // Compute age
    $birthDate = new DateTime($birthday);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    $role = "student";
    $enrollment_status = "pending";
    $current_step = 1;

    // 🔒 Check if ID already exists
    $checkId = $conn->query("SELECT id FROM student WHERE id='$id'");
    if ($checkId->num_rows > 0) {
        $error = "Student ID already exists.";
    } else {
        // 🔒 Check if email exists
        $checkEmail = $conn->query("SELECT email FROM student WHERE email='$email'");
        if ($checkEmail->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = "INSERT INTO student 
            (id, name, email, password, role, enrollment_status, current_step, birthday, age, section, gender)
            VALUES 
            ('$id', '$name', '$email', '$hashedPassword', 'student', '$enrollment_status', '$current_step', '$birthday', '$age', '$section', '$gender')";

            if ($conn->query($insert) === TRUE) {
                $success = "Account created successfully!";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
/* ================= FORCE LOGIN ================= */
/**
 * Use this snippet at the top of every protected page:
 *
 * session_start();
 * if(!isset($_SESSION['user_id'])){
 *     header("Location: ../login.php");
 *     exit();
 * }
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
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

  /* NAVY GLASS THEME */
  --navy-deep: rgba(7, 18, 45, 0.85);
  --navy-mid: rgba(10, 28, 70, 0.65);
  --navy-soft: rgba(15, 40, 95, 0.55);

  --glass-border: rgba(255,255,255,0.15);
  --white: #ffffff;
}

/* RESET */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: var(--font-default);
  height: 100%;
  overflow: hidden;
  width: 100%;
  background: radial-gradient(circle at top left, #050b1a, #000);
}

/* BACKGROUND WRAPPER */
#back {
  width: 100%;
  height: 100%;
  position: absolute;
  z-index: -1;
}

.canvas-back {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* =========================
   NAVY GLASS SIDE PANELS
   ========================= */

.backLeft {
  position: absolute;
  left: 0;
  width: 50%;
  height: 100%;

  background: var(--navy-mid);
  backdrop-filter: blur(28px);
  -webkit-backdrop-filter: blur(28px);

  border-right: 1px solid var(--glass-border);
}

.backRight {
  position: absolute;
  right: 0;
  width: 50%;
  height: 100%;

  background: var(--navy-soft);
  backdrop-filter: blur(28px);
  -webkit-backdrop-filter: blur(28px);

  border-left: 1px solid var(--glass-border);
}

/* MAIN SLIDE */
#slideBox {
  width: 50%;
  height: 100%;
  position: absolute;
  margin-left: 50%;
  overflow: hidden;
  box-shadow: 0 25px 70px rgba(0,0,0,0.6);
}

.topLayer {
  width: 200%;
  height: 100%;
  position: relative;
  left: -100%;
  display: flex;
  transition: margin-left 0.6s ease;
}

/* =========================
   FORM CARDS (WHITE CLEAN UI)
   ========================= */

.left,
.right {
  width: 50%;
  height: 100%;
  overflow-y: auto;

  padding: 28px 32px; /* compact spacing */

  background: var(--white);
  color: #1a1a1a;

  box-shadow: 0 18px 45px rgba(0,0,0,0.25);
}

/* CONTENT CENTER FIX */
.left .content,
.right .content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
  width: 85%;
  margin: 0 auto;
}

/* TITLE FIX (REMOVE BIG GAP ISSUE) */
h2 {
  margin: 0 0 8px 0;
  font-size: 26px;
  font-weight: 700;
  color: #0a1f44;
}

/* DESCRIPTION */
p {
  margin: 0 0 14px 0;
  font-size: 13px;
  color: #666;
  line-height: 1.4;
}

/* =========================
   FORM FIELDS (COMPACT)
   ========================= */

.form-element {
  margin: 0.7em 0; /* tighter spacing */
}

.form-stack {
  display: flex;
  flex-direction: column;
}

label {
  font-size: 0.7em;
  text-transform: uppercase;
  margin-bottom: 4px;
  color: #555;
  letter-spacing: 0.5px;
}

/* INPUTS */
input,
select {
  width: 100%;
  padding: 9px 10px;

  background: #f6f8fc;
  border: 1px solid #d8e1ef;
  border-radius: 8px;

  outline: none;
  font-size: 0.95em;
  color: #1a1a1a;
  transition: 0.25s ease;
}

input:focus,
select:focus {
  border-color: #0a1f44;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(10,31,68,0.15);
}

/* CHECKBOX */
.form-checkbox {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.checkbox {
  width: 16px;
  height: 16px;
  appearance: none;
  border: 1px solid #bbb;
  border-radius: 4px;
  margin-right: 10px;
  position: relative;
}

.checkbox:checked::after {
  content: "✔";
  position: absolute;
  font-size: 12px;
  left: 2px;
  top: -2px;
  color: #0a1f44;
}

/* =========================
   BUTTONS (NAVY MODERN)
   ========================= */

button {
  padding: 0.7em 1.1em;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  text-transform: uppercase;
  cursor: pointer;
  transition: 0.25s;
  color: #fff;
}

/* PRIMARY NAVY BUTTONS */
.signup {
  background: linear-gradient(135deg, #081a3a, #0c2f6b);
}

.login {
  background: linear-gradient(135deg, #0b2a5a, #123d7a);
}

.signup:hover {
  transform: translateY(-2px);
  background: #06142b;
}

.login:hover {
  transform: translateY(-2px);
  background: #0a2047;
}

/* SWITCH BUTTON */
.off {
  background: transparent;
  color: #0a1f44;
  border: 1px solid #0a1f44;
}

/* NUMBER INPUT FIX */
input[type="number"] {
  appearance: textfield;
}

/* MOBILE */
@media (max-width: 768px) {
  #slideBox {
    width: 80%;
    margin-left: 20%;
  }

  .left,
  .right {
    padding: 22px;
  }
}
</style>
</head>
<body>

<?php if($error) echo "<script>alert('$error');</script>"; ?>
<?php if($success) echo "<script>alert('$success');</script>"; ?>

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
</body>
</html>