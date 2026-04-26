<?php
session_start();

$servername = "sql110.infinityfree.com";
$username   = "if0_41176520";
$password   = "1W89jn4xLkI";
$dbname     = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* =======================
   DASHBOARD COUNTS
======================= */

// Students
$studentCount = $conn->query("
    SELECT COUNT(*) as total 
    FROM student 
    WHERE role='student'
")->fetch_assoc()['total'] ?? 0;

// Teachers
$teacherCount = $conn->query("
    SELECT COUNT(*) as total 
    FROM student 
    WHERE role='teacher'
")->fetch_assoc()['total'] ?? 0;

// Overall users
$overallUsers = $conn->query("
    SELECT COUNT(*) as total 
    FROM student
")->fetch_assoc()['total'] ?? 0;


/* =======================
   USERS LIST (MORE SECTION)
======================= */

$result = $conn->query("
    SELECT name, email, role, enrollment_status, age, section, gender, birthday 
    FROM student 
    ORDER BY id DESC
");

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
<link rel="stylesheet" href="../../../css/panel.css">    
<script src="../../../js/panel.js" defer></script>

<title>Admin Panel</title>
</head>

<body>

<nav>
    <div class="logo-name">
        <div class="logo-image">
            <img src="images/logo.png" alt="">
        </div>
        <span class="logo_name">HC Admin</span>
    </div>

    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="../../../admin-office-panel/login/panel/adminpanel.php"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
            <li ><a href="../../../admin-office-panel/login/panel/enrollment.php"><i class="uil uil-files-landscapes"></i><span class="link-name">Enrollment Status</span></a></li>
            <li><a href="../../../admin-office-panel/login/panel/grades.php"><i class="uil uil-chart"></i><span class="link-name">Grades</span></a></li>
            <li><a href="../../../admin-office-panel/login/panel/grades.php"><i class="uil uil-users-alt"></i><span class="link-name">Accounts</span></a></li>
            <li><a href="#"><i class="uil uil-comments"></i><span class="link-name">Comments</span></a></li>
            <li><a href="#"><i class="uil uil-share"></i><span class="link-name">Share</span></a></li>
        </ul>

        <ul class="logout-mode">
            <li><a href="#"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
            <li class="mode">
                <a href="#"><i class="uil uil-moon"></i><span class="link-name">Dark Mode</span></a>
                <div class="mode-toggle"><span class="switch"></span></div>
            </li>
        </ul>
    </div>
</nav>

<section class="dashboard">

    <div class="top">
        <i class="uil uil-bars sidebar-toggle"></i>

        <div class="search-box">
            <i class="uil uil-search"></i>
            <input type="text" placeholder="Search here...">
        </div>

        <img src="images/profile.jpg" alt="">
    </div>

    <div class="dash-content">

        <!-- ================= OVERVIEW ================= -->
        <div class="overview">
            <div class="title">
                <i class="uil uil-tachometer-fast-alt"></i>
                <span class="text">Dashboard</span>
            </div>

            <div class="boxes">

                <div class="box box1">
                    <i class="uil uil-users-alt"></i>
                    <span class="text">Total Students</span>
                    <span class="number"><?php echo $studentCount; ?></span>
                </div>

                <div class="box box2">
                    <i class="uil uil-user-md"></i>
                    <span class="text">Total Teachers</span>
                    <span class="number"><?php echo $teacherCount; ?></span>
                </div>

                <div class="box box3">
                    <i class="uil uil-users-alt"></i>
                    <span class="text">Overall Users</span>
                    <span class="number"><?php echo $overallUsers; ?></span>
                </div>

            </div>
        </div>

        <!-- ================= MORE SECTION ================= -->
        <div class="activity">
            <div class="title">
                <i class="uil uil-list-ul"></i>
                <span class="text">More</span>
            </div>

            <div class="activity-data">

                <div class="data names">
                    <span class="data-title">Name</span>
                    <?php foreach ($users as $u) { ?>
                        <span class="data-list"><?php echo $u['name']; ?></span>
                    <?php } ?>
                </div>

                <div class="data email">
                    <span class="data-title">Email</span>
                    <?php foreach ($users as $u) { ?>
                        <span class="data-list"><?php echo $u['email']; ?></span>
                    <?php } ?>
                </div>

                <div class="data joined">
                    <span class="data-title">Role</span>
                    <?php foreach ($users as $u) { ?>
                        <span class="data-list"><?php echo $u['role']; ?></span>
                    <?php } ?>
                </div>

                <div class="data type">
                    <span class="data-title">Status</span>
                    <?php foreach ($users as $u) { ?>
                        <span class="data-list"><?php echo $u['enrollment_status']; ?></span>
                    <?php } ?>
                </div>

            </div>
        </div>

    </div>
</section>

</body>
</html>