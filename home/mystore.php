<?php
session_start();

// DB CONNECTION
require_once __DIR__ . "/../config/db.php";
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// AJAX HANDLER
if (isset($_GET['fetch'])) {
    if (!isset($_SESSION['email'])) {
        echo json_encode(["error" => "No session"]);
        exit();
    }

    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT current_step FROM student WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode($data);
    exit();
}

// NORMAL PAGE LOAD
if (!isset($_SESSION['email'])) {
    header("Location: enrollment.php");
    exit();
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT * FROM student WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student record not found.";
    exit();
}

$current_step = (int)$student['current_step'];
$total_steps = 4;
$progress = (($current_step - 1) / ($total_steps - 1)) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='stylesheet'>
<link rel="stylesheet" href="../css/admin.css">
	<!-- My CSS -->
<script src="admin.js"></script>
	<title>FCARR - Enrollment Status</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
	<a href="#" class="brand">
    <img src="../assets/logo.png" alt="Logo">
    <span class="text">FCARR</span>
</a>
		<ul class="side-menu top">
			<li >
				<a href="../home/dashboard.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="../home/mystore.php">
<i class='bx bxs-bar-chart-alt-2'></i>
                    <span class="text">Enrollment Status</span>
				</a>
			</li>
			<li >
				<a href="../home/analytics.php">
<i class='bx bxs-book-add'></i>
                    <span class="text">Grade</span>
				</a>
			</li>
			<li>
				<a href="../home/message.php">
					<i class='bx bxs-message-dots bx-sm' ></i>
					<span class="text">Message</span>
				</a>
			</li>
			<li>
				<a href="../home/team.php">
					<i class='bx bxs-group bx-sm' ></i>
					<span class="text">Team</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu bottom">
			<li>
				<a href="#">
					<i class='bx bxs-cog bx-sm bx-spin-hover' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="#" class="logout">
					<i class='bx bx-power-off bx-sm bx-burst-hover' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
<nav>
    <i class='bx bx-menu bx-sm' ></i>
    <a href="#" class="nav-link">Categories</a>
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
        </div>
    </form>
    <input type="checkbox" class="checkbox" id="switch-mode" hidden />
    <label class="swith-lm" for="switch-mode">
        <i class="bx bxs-moon"></i>
        <i class="bx bx-sun"></i>
        <div class="ball"></div>
    </label>

    <!-- Notification Bell -->
    <a href="#" class="notification" id="notificationIcon">
        <i class='bx bxs-bell bx-tada-hover' ></i>
        <span class="num">8</span>
    </a>
    <div class="notification-menu" id="notificationMenu">
        <ul>
            <li>New message from John</li>
            <li>Your order has been shipped</li>
            <li>New comment on your post</li>
            <li>Update available for your app</li>
            <li>Reminder: Meeting at 3PM</li>
        </ul>
    </div>

    <!-- Profile Menu -->
    <a href="#" class="profile" id="profileIcon">
        <img src="https://placehold.co/600x400/png" alt="Profile">
    </a>
    <div class="profile-menu" id="profileMenu">
        <ul>
            <li><a href="#">My Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Log Out</a></li>
        </ul>
    </div>
</nav>
<!-- NAVBAR -->

<main>
   <div class="stepper-header">
  <h1>Enrollment Status</h1>
</div>

<div class="horizontal-stepper" style="margin-top: 16px;">

  <?php for ($i = 1; $i <= 4; $i++): 
      $class = "empty";

      if ($i < $current_step) {
          $class = "completed";
      } elseif ($i == $current_step) {
          $class = "active";
      }
  ?>
  
  <div class="step <?php echo $class; ?>">
    <div class="circle"></div>
    <div class="label">Step <?php echo $i; ?></div>
    <div class="step-instruction">
      <?php 
        if ($i == 1) {
            echo "<strong>Student Registration</strong><br>";
            echo "Provide your personal details to create a student profile.";
        }
        elseif ($i == 2) {
            echo "<strong>Submission Review</strong><br>";
            echo "Your submitted documents will be verified by the admin.";
        }
        elseif ($i == 3) {
            echo "<strong>Fee Payment</strong><br>";
            echo "Complete the payment process to confirm enrollment.";
        }
        elseif ($i == 4) {
            echo "<strong>Successfully Enrolled</strong><br>";
            echo "Your enrollment is complete and confirmed.";
        }
    ?>
    </div>
  </div>

  <?php endfor; ?>
      

</div>
   <section class="stepper-card">
  <div class="student-info">
    <h2><?php echo $student['name']; ?></h2>
    <p>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
    </p>
  </div>
</section>

</main>
</section>

<style>
/* Horizontal Stepper - Responsive & Fixed */
.horizontal-stepper {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 100%;
  margin: 20px auto;
  position: relative;
  padding: 20px 10px;
  box-sizing: border-box;
  overflow-x: auto; /* mobile scroll */
  gap: 10px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  border: 1px solid #e6e6e6;
}

/* ================= BACKGROUND LINE ================= */
.horizontal-stepper::before {
  content: '';
  position: absolute;
  top: 28px;
  left: 20px;
  right: 20px;
  height: 2px;
  background-color: #ddd;
  z-index: 1;
}

/* ================= PROGRESS LINE ================= */
.horizontal-stepper::after {
  content: '';
  position: absolute;
  top: 28px;
  left: 20px;
  height: 2px;
  width: <?php echo $progress; ?>%;
  background-color: rgb(6,150,215);
  z-index: 2;
  transition: width 0.4s ease;
}

/* ================= STEP ITEM ================= */
.horizontal-stepper .step {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 80px; /* prevents squishing */
  flex-shrink: 0;
  text-align: center;
  z-index: 3;
}

/* ================= CIRCLE ================= */
.horizontal-stepper .circle {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 3px solid #ccc;
  background-color: white;
}

/* ================= STATES ================= */
.horizontal-stepper .step.completed .circle {
  background-color: rgb(6,150,215);
  border-color: rgb(6,150,215);
}

.horizontal-stepper .step.active .circle {
  border-color: rgb(6,150,215);
  background-color: white;
  box-shadow: 0 0 0 4px rgba(6,150,215,0.2);
}

.horizontal-stepper .step.empty .circle {
  border-color: #ccc;
  background-color: white;
}

/* ================= LABEL ================= */
.horizontal-stepper .label {
  margin-top: 6px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
}

/* ================= DESCRIPTION ================= */
.horizontal-stepper .step-instruction {
  margin-top: 4px;
  font-size: 11px;
  color: #666;
  max-width: 100px;
  line-height: 1.2;
      text-align: justify;  /* ✅ Added justification */

}

/* ================= ACTIVE TEXT ================= */
.horizontal-stepper .step.active .label,
.horizontal-stepper .step.completed .label {
  color: rgb(6,150,215);
  font-weight: 600;
}
.stepper-card {
  width: 100%;
  max-width: 900px;
  margin: 20px auto 40px;
  padding: 20px;
  background: #fff; /* same as stepper */
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  border: 1px solid #e6e6e6;
  text-align: center;
}

.stepper-card .student-info h2 {
  font-size: 18px;
  font-weight: 600;
  color: rgb(6,150,215); /* match active step color */
  margin-bottom: 8px;
}

.stepper-card .student-info p {
  font-size: 14px;
  color: #666;
  line-height: 1.5;
}
    .stepper-header {
  width: 100%;
  max-width: 900px;
  margin: 20px auto 0;
  padding: 15px 20px;
  background: #fff; /* match stepper */
  border-radius: 10px;
  border: 1px solid #e6e6e6;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  text-align: center;
}

.stepper-header h1 {
  font-size: 20px;
  font-weight: 600;
  color: rgb(6,150,215);
  margin: 0;
}

/* ================= MOBILE FIX ================= */
@media (max-width: 768px) {
  .horizontal-stepper {
    justify-content: flex-start;
    padding: 15px 5px;
  }

  .horizontal-stepper .step {
    min-width: 70px;
  }

  .horizontal-stepper .label {
    font-size: 10px;
  }

  .horizontal-stepper .step-instruction {
    display: block;              /* ✅ SHOW AGAIN */
    font-size: 9px;              /* smaller text */
    max-width: 70px;             /* prevent overflow */
    word-wrap: break-word;
    line-height: 1.2;
  }
     .stepper-card {
    padding: 15px 10px;
  }

  .stepper-card .student-info h2 {
    font-size: 16px;
  }

  .stepper-card .student-info p {
    font-size: 12px;
  }
}

/* ================= SCROLLBAR (OPTIONAL CLEAN LOOK) ================= */
.horizontal-stepper::-webkit-scrollbar {
  height: 4px;
}

.horizontal-stepper::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 10px;
}
</style>
	<!-- CONTENT -->
	

	<script src="../js/script.js"></script>
    <script>
function fetchStatus() {
    fetch(window.location.pathname + '?fetch=1') // SAME FILE CALL
        .then(response => response.json())
        .then(data => {
            let step = parseInt(data.current_step);
            updateStepper(step);
        });
}

function updateStepper(current_step) {
    const steps = document.querySelectorAll('.step');
    const total_steps = 4;

    // ✅ If step is beyond total → force full completion
    if (current_step > total_steps) {
        current_step = total_steps + 1; // special case
    }

    steps.forEach((stepEl, index) => {
        let i = index + 1;

        stepEl.classList.remove('completed', 'active', 'empty');

        if (current_step > total_steps) {
            // ✅ ALL COMPLETED
            stepEl.classList.add('completed');
        } 
        else if (i < current_step) {
            stepEl.classList.add('completed');
        } 
        else if (i === current_step) {
            stepEl.classList.add('active');
        } 
        else {
            stepEl.classList.add('empty');
        }
    });

    // ✅ Progress bar full if step 5
    let progress;
    if (current_step > total_steps) {
        progress = 100;
    } else {
        progress = ((current_step - 1) / (total_steps - 1)) * 100;
    }

    document.querySelector('.horizontal-stepper')
        .style.setProperty('--progress', progress + '%');
}

// run every 3 seconds
setInterval(fetchStatus, 3000);
</script>
</body>
</html>