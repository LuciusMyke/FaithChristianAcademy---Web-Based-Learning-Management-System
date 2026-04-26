<?php
    
session_start();
require_once __DIR__ . "/../config/db.php";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT name FROM student WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();


if (!isset($_SESSION['email'])) {
    header("Location: ../l/login.php");
    exit();
}
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
	<title>FCARR - Dashboard</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
	<a href="#" class="brand">
    <img src="../assets/logo.png" alt="Logo">
    <span class="text">FCARR</span>
</a>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="../teacher/dashboard.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../teacher/student-list.php">
<i class='bx bxs-bar-chart-alt-2'></i>		<span class="text">Student List</span>
				</a>
			</li>
			<li>
				<a href="../teacher/grades.php">
<i class='bx bxs-graduation'></i>				<span class="text">Grade</span>
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
				<a href="../home/logout.php" class="logout">
					<i class='bx bx-power-off bx-sm bx-burst-hover' ></i>
					<span href="../home/logout.php" class="text">Logout</span>
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
            <li><a href="../home/logout.php">Log Out</a></li>
        </ul>
    </div>
</nav>
<!-- NAVBAR -->


<main>


	<div class="table-data">
		<!-- MAIN CARD -->
		<div class="order">
			<div class="head">
<h3>
    <i class='bx bxs-graduation'></i> 
    Welcome <h2><?php echo $student['name']; ?></h2>
</h3>			</div>

			<div style="padding: 25px; line-height: 1.7;">
				<p style="font-size: 15px; opacity: 0.85;">
					Welcome to the FCARR Student Portal. This dashboard helps you manage your
					enrollment, monitor grades, and stay updated with school activities.
				</p>

				<br>

				<!-- ENROLLMENT -->
				<div style="margin-bottom: 20px;">
					<h4 style="display:flex; align-items:center; gap:10px;">
						<i class='bx bxs-edit-alt' style="color:#3C91E6;"></i>
						Enrollment Guide
					</h4>
					<ul style="padding-left: 25px; margin-top: 10px;">
						<li>Go to the Enrollment Section</li>
						<li>Fill out your personal details</li>
						<li>Select your subjects</li>
						<li>Submit and wait for approval</li>
					</ul>
				</div>

				<!-- GRADES -->
				<div style="margin-bottom: 20px;">
					<h4 style="display:flex; align-items:center; gap:10px;">
						<i class='bx bxs-bar-chart-alt-2' style="color:#00C49A;"></i>
						Grades Monitoring
					</h4>
					<p style="margin-top: 8px; opacity: 0.85;">
						View your academic performance once teachers upload your grades.
						Check regularly to stay on track.
					</p>
				</div>

				<!-- FEATURES -->
				<div>
					<h4 style="display:flex; align-items:center; gap:10px;">
						<i class='bx bxs-rocket' style="color:#FF6B6B;"></i>
						Coming Soon
					</h4>
					<ul style="padding-left: 25px; margin-top: 10px;">
						<li>Online Enrollment Tracking</li>
						<li>Real-time Grade Updates</li>
						<li>Student-Teacher Messaging</li>
						<li>Downloadable Report Cards</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- SIDE CARD -->
		<div class="todo">
			<div class="head">
				<h3><i class='bx bxs-bell-ring'></i> Reminders</h3>
			</div>

			<ul class="todo-list">
				<li class="not-completed">
					<p><i class='bx bx-time-five'></i> Complete your enrollment form</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="not-completed">
					<p><i class='bx bx-bell'></i> Check announcements regularly</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="completed">
					<p><i class='bx bx-check-circle'></i> Prepare required documents</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="not-completed">
					<p><i class='bx bx-refresh'></i> Wait for grade updates</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
			</ul>
		</div>
	</div>
</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="../js/script.js"></script>
</body>
</html>