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
	<title>FCARR - Grades</title>
     <style>

    .rc-wrap {

        padding: 10px 0;

    }

    .greeting {

        font-size: 18px;

        font-weight: 600;

        margin-bottom: 6px;

        color: var(--dark, #333);

    }

    .greeting span {

        color: var(--blue, #0070C0);

    }

    #loadingMsg {

        font-size: 13px;

        color: #555;

        padding: 20px;

        display: block;

    }

    /* Print button sits outside the card */

    .print-bar {

        text-align: center;

        margin: 10px 0 20px;

    }

    .btn-print {

        background: #0070C0;

        color: white;

        padding: 10px 24px;

        border: none;

        border-radius: 6px;

        cursor: pointer;

        font-size: 13px;

        font-weight: 500;

    }

    .btn-print:hover { background: #005a9e; }

    @media print {

        #sidebar, nav, .greeting, .print-bar { display: none !important; }

    }

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
				<a href="../home/dashboard.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../home/mystore.php">
<i class='bx bxs-bar-chart-alt-2'></i>		<span class="text">Enrollment Status</span>
				</a>
			</li>
			<Li class="active">
				<a href="../home/analytics.php">
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

<div class="rc-wrap">

       <div style="
    background:#fff;
    padding:14px 16px;
    border-radius:10px;
    border:1px solid #e5e5e5;
    box-shadow:0 2px 8px rgba(0,0,0,0.06);
    margin-bottom:10px;
    font-size:18px;
    font-weight:600;
">
    <span style="color:#0070C0;">
      
    </span> This section displays your academic grades. For privacy and security purposes, students are permitted only to capture screenshots or save a copy of the displayed content. Actions such as right-clicking, viewing page source, or inspecting elements are restricted.
</div>



       



        <!-- Report card loads here automatically -->

        <div id="reportContainer">

            <p id="loadingMsg">⏳ Loading your report card...</p>

        </div>
    </div>
<div style="text-align:center; margin:20px 0;">
    <button class="btn-print" onclick="downloadReportCard()">
        📥 Save Report Card as Image
    </button>
</div>
</section>



<script>

// Auto-load the student's own report card using their session ID (injected server-side)

// The student cannot change this value — it comes from PHP, not from a user input

(function() {

    var studentId = <?= (int)$me['id'] ?>;



    fetch('student_report_card.php?id=' + studentId)

        .then(function(res) { return res.text(); })

        .then(function(html) {

            document.getElementById('reportContainer').innerHTML = html;

        })

        .catch(function(err) {

            document.getElementById('reportContainer').innerHTML =

                '<p style="color:red; padding:20px;">Could not load report card. Please try refreshing.</p>';

        });

})();

</script>
<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert("Ops! Bawal ang right-click dito.");
    });
</script>

<script>
    document.onkeydown = function(e) {
        // F12 key
        if(event.keyCode == 123) return false;

        // Ctrl+Shift+I (Inspect)
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false;

        // Ctrl+Shift+J (Console)
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false;

        // Ctrl+U (View Source)
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false;
    };
</script>

	<script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
function downloadReportCard() {

    const card = document.getElementById("reportContainer");

    html2canvas(card, {
        scale: 2,
        useCORS: true,
        backgroundColor: "#ffffff"
    }).then(canvas => {

        const fileName = "report-card.png";

        const link = document.createElement("a");
        link.download = fileName;
        link.href = canvas.toDataURL("image/png");

        link.click();
    });

}
</script>
</body>
</html>