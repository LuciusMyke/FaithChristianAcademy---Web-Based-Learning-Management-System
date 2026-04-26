<?php
session_start();

$servername = "sql110.infinityfree.com";
$username   = "if0_41176520";
$password   = "1W89jn4xLkI";
$dbname     = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$students = $conn->query("SELECT id, name FROM student WHERE role='student'");
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
     <style>
    .student-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    .student-selector select {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        min-width: 280px;
    }
    .student-selector button {
        padding: 10px 20px;
        background: var(--blue, #0070C0);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }
    .student-selector button:hover { background: #005a9e; }
    #loadingMsg { font-size:13px; color:#555; margin-left:8px; display:none; }
    .save-status { display:inline-block; margin-left:10px; font-size:13px; font-weight:bold; }
    .save-status.ok  { color: green; }
    .save-status.err { color: red; }
    </style>
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
			<li >
				<a href="../teacher/student-list.php">
<i class='bx bxs-bar-chart-alt-2'></i>		<span class="text">Student List</span>
				</a>
			</li>
			<li class="active">
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
   Student Grade <h2><?php echo $student['name']; ?></h2>
</h3>			</div>

		
  <h2>Select Student</h2>

    <div class="student-selector">
        <select id="studentDropdown">
            <option value="">-- Select Student --</option>
            <?php while($row = $students->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['name']) ?> (ID: <?= $row['id'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <button onclick="loadStudent()">Load Report Card</button>
        <span id="loadingMsg">⏳ Loading...</span>
    </div>

    <!-- Report card renders here -->
    <div id="reportContainer"></div>

</section>

<script>
function loadStudent() {
    var id = document.getElementById('studentDropdown').value;
    if (!id) { alert('Please select a student first.'); return; }

    document.getElementById('loadingMsg').style.display = 'inline';
    document.getElementById('reportContainer').innerHTML = '';

    fetch('report_card.php?lrn=' + id)
        .then(function(res){ return res.text(); })
        .then(function(html){
            document.getElementById('loadingMsg').style.display = 'none';
            document.getElementById('reportContainer').innerHTML = html;
        })
        .catch(function(err){
            document.getElementById('loadingMsg').style.display = 'none';
            document.getElementById('reportContainer').innerHTML =
                '<p style="color:red;">Failed to load: ' + err + '</p>';
        });
}

// Save grades via AJAX — defined here so onclick in injected HTML can find it
function saveGrades() {
    var form   = document.getElementById('reportForm');
    var status = document.getElementById('saveStatus');
    var btn    = document.getElementById('saveBtn');

    if (!form) { alert('No report card loaded.'); return; }

    btn.disabled    = true;
    btn.textContent = '⏳ Saving...';
    status.className   = 'save-status';
    status.textContent = '';

    fetch('report_card.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(function(res){ return res.json(); })
    .then(function(json){
        btn.disabled    = false;
        btn.textContent = '💾 Save Grades';
        if (json.ok) {
            status.className   = 'save-status ok';
            status.textContent = '✅ ' + json.msg;
        } else {
            status.className   = 'save-status err';
            status.textContent = '❌ ' + json.msg;
        }
    })
    .catch(function(err){
        btn.disabled    = false;
        btn.textContent = '💾 Save Grades';
        status.className   = 'save-status err';
        status.textContent = '❌ Network error: ' + err;
    });
}

function clearGrades() {
    if (!confirm('Clear all grade inputs? Saved data is NOT deleted until you click Save.')) return;
    document.querySelectorAll(
        '#reportForm input[name="first[]"], #reportForm input[name="second[]"], ' +
        '#reportForm input[name="third[]"], #reportForm input[name="fourth[]"], ' +
        '#reportForm input[name="final[]"], #reportForm input[name="remarks[]"], ' +
        '#reportForm input[name="gen_ave"], #reportForm input[name="gen_remarks"]'
    ).forEach(function(el){ el.value = ''; });
}
</script>
	<script src="../js/script.js"></script>
</body>
</html>