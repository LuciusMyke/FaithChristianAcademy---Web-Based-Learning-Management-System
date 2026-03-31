<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="../css/admin.css">
	<title>AdminHub - Grade Portal</title>

	<style>
	/* Enhanced Tab Design */
	.grade-tabs {
		display: flex;
		background: var(--sidebar);
		border-radius: 10px 10px 0 0;
		overflow: hidden;
		margin: 20px 0;
	}
	.tab-btn {
		flex: 1;
		padding: 15px 20px;
		cursor: pointer;
		background: rgba(255,255,255,0.1);
		color: white;
		font-weight: 500;
		text-align: center;
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
		border: none;
		outline: none;
	}
	.tab-btn.active {
		background: var(--blue);
		color: #fff;
		box-shadow: 0 4px 10px rgba(0,0,0,0.2);
	}
	.tab-btn:hover {
		background: rgba(255,255,255,0.2);
	}
	.tab-content {
		display: none;
		padding: 20px;
		background: #fff;
		border-radius: 0 0 10px 10px;
		box-shadow: 0 2px 5px rgba(0,0,0,0.1);
		margin-bottom: 20px;
	}
	.tab-content.active {
		display: block;
	}

	/* Form Styles */
	.tab-content form input[type="text"],
	.tab-content form input[type="file"] {
		width: 100%;
		padding: 12px;
		margin-top: 5px;
		margin-bottom: 20px;
		border-radius: 8px;
		border: 1px solid #ccc;
		font-size: 14px;
	}
	.tab-content form button {
		background: var(--blue);
		color: white;
		padding: 12px 25px;
		border: none;
		border-radius: 8px;
		cursor: pointer;
		font-weight: 500;
		transition: background 0.3s;
	}
	.tab-content form button:hover {
		background: #0056b3;
	}
	.tab-content iframe {
		width: 100%;
		height: 500px;
		border: none;
		border-radius: 10px;
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	}
	</style>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile bx-lg'></i>
			<span class="text">AdminHub</span>
		</a>
		<ul class="side-menu top">
			<li><a href="../home/dashboard.php"><i class='bx bxs-dashboard bx-sm'></i><span class="text">Dashboard</span></a></li>
			<li><a href="../home/mystore.php"><i class='bx bxs-shopping-bag-alt bx-sm'></i><span class="text">My Store</span></a></li>
			<li class="active"><a href="../home/analytics.php"><i class='bx bxs-doughnut-chart bx-sm'></i><span class="text">Analytics</span></a></li>
			<li><a href="../home/message.php"><i class='bx bxs-message-dots bx-sm'></i><span class="text">Message</span></a></li>
			<li><a href="../home/team.php"><i class='bx bxs-group bx-sm'></i><span class="text">Team</span></a></li>
		</ul>
		<ul class="side-menu bottom">
			<li><a href="#"><i class='bx bxs-cog bx-sm bx-spin-hover'></i><span class="text">Settings</span></a></li>
			<li><a href="#" class="logout"><i class='bx bx-power-off bx-sm bx-burst-hover'></i><span class="text">Logout</span></a></li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu bx-sm'></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<input type="checkbox" class="checkbox" id="switch-mode" hidden />
			<label class="swith-lm" for="switch-mode">
				<i class="bx bxs-moon"></i>
				<i class="bx bx-sun"></i>
				<div class="ball"></div>
			</label>
			<a href="#" class="notification" id="notificationIcon">
				<i class='bx bxs-bell bx-tada-hover'></i>
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

		<!-- Tabs -->
		<div class="grade-tabs">
			<button class="tab-btn active" onclick="openTab('upload')"><i class='bx bx-upload'></i> Upload Grades</button>
			<button class="tab-btn" onclick="openTab('view')"><i class='bx bx-folder-open'></i> View Grades</button>
		</div>

		<!-- Upload Grades Tab -->
		<div id="upload" class="tab-content active">
			<div class="head">
				<h3>Upload Student Grades</h3>
				<i class='bx bx-upload'></i>
			</div>
			<form action="../home/process_grades.php" method="POST" enctype="multipart/form-data">
				<div>
					<label>Subject</label><br>
					<input type="text" name="subject" required>
				</div>
				<div>
					<label>Upload CSV / Excel File</label><br>
					<input type="file" name="excel_file" required>
				</div>
				<button type="submit">Upload Grades</button>
			</form>
		</div>

		<!-- View Grades Tab -->
		<div id="view" class="tab-content">
			<iframe src="../home/grade_portal.php"></iframe>
		</div>
	</section>

	<script src="../js/script.js"></script>
	<script>
		function openTab(tabName) {
			// Hide all tab contents
			document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
			// Remove active class from all buttons
			document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
			// Show selected tab content
			document.getElementById(tabName).classList.add('active');
			// Activate the clicked button
			event.currentTarget.classList.add('active');
		}
	</script>
</body>
</html>