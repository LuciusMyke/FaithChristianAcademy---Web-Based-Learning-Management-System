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
          //  $_SESSION['user_id'] = $row['id'];
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
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Check if email exists
    $check = $conn->query("SELECT * FROM student WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO student (name, email, password, role) VALUES ('$name', '$email', '$hashedPassword', 'student')";
        if ($conn->query($insert) === TRUE) {
            $success = "Account created successfully!";
        } else {
            $error = "Error creating account: " . $conn->error;
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
  --theme-signup: #03A9F4;
  --theme-signup-darken: #0288D1;
  --theme-signup-background: #2C3034;
  --theme-login: #673AB7;
  --theme-login-darken: #512DA8;
  --theme-login-background: #f9f9f9;
  --theme-dark: #212121;
  --theme-light: #e3e3e3;
  --font-default: 'Roboto', sans-serif;
}

* { box-sizing: border-box; }
body { margin:0; font-family:var(--font-default); height:100%; overflow:hidden; width:100%; }

/* Background & Canvas */
#back { width:100%; height:100%; position:absolute; z-index:-1; }
.canvas-back { position:absolute; top:0; left:0; width:100%; height:100%; z-index:1; }
.backRight { position:absolute; right:0; width:50%; height:100%; background:var(--theme-signup);}
.backLeft { position:absolute; left:0; width:50%; height:100%; background:var(--theme-login);}

/* SlideBox */
#slideBox { width:50%; height:100%; position:absolute; margin-left:50%; overflow:hidden; box-shadow:0 14px 28px rgba(0,0,0,0.25),0 10px 10px rgba(0,0,0,0.22);}
.topLayer { width:200%; height:100%; position:relative; left:-100%; display:flex; transition:margin-left 0.5s ease;}

/* Panels */
.left, .right { width:50%; height:100%; overflow-y:auto; padding:50px 30px; }
.left { background:var(--theme-signup-background); color:var(--theme-light);}
.right { background:var(--theme-login-background); color:var(--theme-dark);}

/* Form */
label { font-size:0.8em; text-transform:uppercase; display:block; margin-bottom:5px; }
input { width:100%; padding:8px 1px; margin-top:0.1em; background:transparent; border:0; border-bottom:1px solid; outline:none; font-size:1em;}
.left input { border-color:var(--theme-light); color:var(--theme-light);}
.left input:focus { border-color:var(--theme-signup); color:var(--theme-signup);}
.right input { border-color:var(--theme-dark);}
.right input:focus { border-color:var(--theme-login);}
.left a { color:var(--theme-signup);}
.right a { color:var(--theme-login); }

.form-element { margin:1.6em 0; }
.form-stack { display:flex; flex-direction:column; }
.form-checkbox { display:flex; align-items:center; margin-top:1em; }
.checkbox { width:18px; height:18px; -webkit-appearance:none; outline:none; background:var(--theme-light); border:1px solid var(--theme-light); border-radius:4px; position:relative; margin-right:10px; flex-shrink:0; }
.checkbox:checked::after { content:'✔'; position:absolute; top:-2px; left:2px; font-size:14px; color:var(--theme-signup); font-weight:bold; }

button { padding:0.8em 1.2em; margin:0 10px 0 0; border:none; outline:none; border-radius:3px; font-weight:600; text-transform:uppercase; cursor:pointer; transition:all 0.25s; color:#fff;}
.signup{ background:var(--theme-signup);}
.login{ background:var(--theme-login);}
.off{ background:none; color:inherit; box-shadow:none; margin:0;}
button:hover{opacity:0.9;}
.left .content, .right .content{display:flex; flex-direction:column; justify-content:center; height:100%; width:80%; margin:0 auto;}

@media (max-width:768px){ #slideBox{ width:80%; margin-left:20%; } }
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
        <form id="form-signup" method="POST">
          <div class="form-element form-stack"><label>Email</label><input type="email" name="email" required></div>
          <div class="form-element form-stack"><label>Username</label><input type="text" name="name" required></div>
          <div class="form-element form-stack"><label>Password</label><input type="password" name="password" required></div>
          <div class="form-element form-checkbox">
            <input type="checkbox" class="checkbox" required>
            <label>I agree to the <a href="#">Terms</a> & <a href="#">Privacy</a></label>
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