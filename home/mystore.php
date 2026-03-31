<?php
session_start();
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
	<title>AdminHub</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile  bx-lg'></i>
			<span class="text">AdminHub</span>
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
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">My Store</span>
				</a>
			</li>
			<li >
				<a href="../home/analytics.php">
					<i class='bx bxs-doughnut-chart bx-sm' ></i>
					<span class="text">Analytics</span>
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
<div class="enrollment-wrapper" style="max-width: 500px; min-height: 201px; margin: 0; padding: 0; box-sizing:border-box; position: relative; z-index: 800;">

  <div class="enrollment-status" style="background: #fff; text-align:left; margin: 0; padding: 10px 12px; font-family:inherit; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <h2 style="margin:0; font-size:1.2rem; color:#064B8E;">Enrollment Status</h2>
    <p style="margin:4px 0 0; color:#555;">In progress: complete each step to finalize enrollment.</p>
  </div>
  <div class="horizontal-stepper" style="margin-top: 16px;">

  <div class="step completed">
    <div class="circle"></div>
    <div class="label">Step 1</div>
    <div class="step-instruction">Lorem ipsum dolor sit amet.</div>
  </div>
  <div class="step active">
    <div class="circle"></div>
    <div class="label">Step 2</div>
    <div class="step-instruction">Consectetur adipiscing elit.</div>
  </div>
  <div class="step empty">
    <div class="circle"></div>
    <div class="label">Step 3</div>
    <div class="step-instruction">Sed do eiusmod tempor.</div>
  </div>
  <div class="step empty">
    <div class="circle"></div>
    <div class="label">Step 4</div>
    <div class="step-instruction">Ut labore et dolore magna aliqua.</div>
  </div>
</div>

<div class="enrollment-description" style="max-width:780px; margin: 12px auto 24px; padding: 0 16px; color:#444; font-size:0.95rem; line-height:1.6; text-align:center;">
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac lacus et mauris condimentum aliquet a nec odio. Donec gravida, justo in facilisis molestie, nibh quam molestie lectus, et sodales sem tellus ut nibh. Curabitur varius egestas risus, non lobortis est vestibulum eu. Sed blandit ligula ac orci placerat, sit amet pretium ligula luctus.</p>
</div>
</main>
</section>

<style>
/* Horizontal Stepper - Responsive & Fixed */
.horizontal-stepper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  max-width: 100%;
  margin: 0 auto;
  position: relative;
  padding: 18px 12px;
  box-sizing: border-box;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  border: 1px solid #e6e6e6;
}

.enrollment-wrapper {
  max-width: 500px;
  min-height: 201px;
  margin: 0;
  padding: 0;
}

@media (max-width: 768px) {
  .enrollment-wrapper {
    width: 100% !important;
    margin: 20px 0 !important;
    padding-left: 20px !important;
    padding-right: 20px !important;
  }
  .horizontal-stepper .step-instruction {
    max-width: 80px;
    font-size: 10px;
  }
}

.horizontal-stepper::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 16px;
  right: 16px;
  height: 2px;
  background-color: #ddd;
  transform: translateY(-50%);
  z-index: 1;
}

.horizontal-stepper::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 16px;
  height: 2px;
  width: 33.333%; /* Step 2 active (1 completed + active) out of 3 intervals */
  background-color: rgb(6,150,215);
  transform: translateY(-50%);
  z-index: 2;
}

.horizontal-stepper .step {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  text-align: center;
  z-index: 3;
}

.horizontal-stepper .step .circle {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 3px solid #ccc;
  background-color: white;
  position: relative;
  z-index: 2;
}

.horizontal-stepper .step.completed .circle {
  background-color: rgb(6,150,215);
  border-color: rgb(6,150,215);
}

.horizontal-stepper .step.active .circle {
  border-color: rgb(6,150,215);
  background-color: white;
  box-shadow: 0 0 0 4px rgba(6,150,215,0.18);
}

.horizontal-stepper .step.empty .circle {
  border-color: #ccc;
  background-color: white;
}

.horizontal-stepper .step.completed .label,
.horizontal-stepper .step.active .label {
  color: rgb(6,150,215);
  font-weight: 600;
}

.horizontal-stepper .step-instruction {
  margin-top: 4px;
  font-size: 11px;
  color: #666;
  max-width: 100px;
  line-height: 1.2;
  white-space: normal;
}

.horizontal-stepper .label {
  margin-top: 6px;
  font-size: 12px;
  white-space: nowrap;
}
</style>
	<!-- CONTENT -->
	

	<script src="../js/script.js"></script>
</body>
</html>