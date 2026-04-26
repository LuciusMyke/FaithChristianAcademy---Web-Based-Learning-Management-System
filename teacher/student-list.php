<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['email'])) {
    header("Location: ../l/login.php");
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ================= STEP 1: GET TEACHER NAME ================= */
$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT name FROM student WHERE email = ? AND role = 'teacher'");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$teacherData = $res->fetch_assoc();

$teacherName = $teacherData['name'] ?? '';

/* ================= STEP 2: GET STUDENTS BY ADVISER NAME ================= */
$stmt = $conn->prepare("SELECT * FROM student WHERE adviser = ?");
$stmt->bind_param("s", $teacherName);
$stmt->execute();

$result = $stmt->get_result();
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
	<title>FCARR - Student List</title>
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
			<li >
				<a href="../teacher/dashboard.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
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
   Student List <h2><?php echo $student['name']; ?></h2>
</h3>			</div>

		
<table class="student-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Section</th>
            <th>Gender</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td>
                    <div class="student-name">
                        <span class="avatar">
                            <?= strtoupper(substr($row['name'], 0, 1)) ?>
                        </span>
                        <?= htmlspecialchars($row['name']) ?>
                    </div>
                </td>

                <td><?= htmlspecialchars($row['section']) ?></td>
                <td><?= htmlspecialchars($row['gender']) ?></td>

                <td>
                    <button class="view-btn">View</button>
                    <button class="edit-btn">Edit</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
		<!-- SIDE CARD -->
		
</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="../js/script.js"></script>
</body>
</html>