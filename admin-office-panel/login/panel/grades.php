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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
<link rel="stylesheet" href="../../../css/panel.css">    
<script src="../../../js/panel.js" defer></script>

<title>Admin Panel</title>
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
            <li><a href="../../../admin-office-panel/login/panel/accounts.php"><i class="uil uil-users-alt"></i><span class="link-name">Accounts</span></a></li>
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





    </div>
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

    fetch('/report_card.php', {
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
</body>
</html>