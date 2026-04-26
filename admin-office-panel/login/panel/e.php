<?php
session_start();

// ===== DATABASE CONNECTION =====
$servername = "sql110.infinityfree.com";
$username = "if0_41176520";
$password = "1W89jn4xLkI";
$dbname = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ===== HANDLE STUDENT SELECTION =====
if (isset($_POST['id'])) {
    $_SESSION['id'] = $_POST['id'];
}

// Redirect if no student selected
$student_id = $_SESSION['id'] ?? null;

// ===== FETCH STUDENT DATA =====
if ($student_id) {
    $query = mysqli_query($conn, "SELECT * FROM student WHERE id = '$student_id'");
    $student = mysqli_fetch_assoc($query);

    if (!$student) {
        $student = null;
        $student_id = null;
        unset($_SESSION['id']);
    } else {
        $current_step = (int)$student['current_step'];
        $total_steps = 4;
        $progress = (($current_step - 1) / ($total_steps - 1)) * 100;
    }
}

// ===== HANDLE PROCEED / RETURN =====
if ($student_id && isset($_POST['proceed'])) {
    if ($current_step < 4) {
        $current_step++;
        mysqli_query($conn, "UPDATE student SET current_step = '$current_step' WHERE id = '$student_id'");
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($student_id && isset($_POST['return'])) {
    if ($current_step > 1) {
        $current_step--;
        mysqli_query($conn, "UPDATE student SET current_step = '$current_step' WHERE id = '$student_id'");
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----===== External links ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="../../../css/panel.css">

    <style>
body { font-family: 'Poppins', sans-serif; background: #f5f5f5; margin:0; }
.dashboard .top { display:flex; align-items:center; justify-content:space-between; padding:10px 20px; background:#fff; border-bottom:1px solid #e6e6e6; }
.dashboard .top .search-box { flex:1; margin:0 20px; position:relative; }
.dashboard .top .search-box input { width:100%; padding:8px 10px; border-radius:5px; border:1px solid #ccc; }
.top img { width:35px; height:35px; border-radius:50%; }

.student-selection-container { max-width:900px; margin:15px auto 10px; padding:10px 15px; background:#fff; border-radius:10px; border:1px solid #e6e6e6; box-shadow:0 2px 10px rgba(0,0,0,0.05); position:relative; }
#studentDropdown { width:100%; padding:8px; border-radius:5px; border:1px solid #ccc; }
.dropdown-list { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #ccc; border-top:none; max-height:200px; overflow-y:auto; z-index:10; display:none; }
.dropdown-list div { padding:8px; cursor:pointer; }
.dropdown-list div:hover { background:rgb(6,150,215); color:#fff; }

/* STEPPER STYLES */
.horizontal-stepper { display:flex; justify-content:space-between; align-items:flex-start; max-width:900px; margin:20px auto; position:relative; padding:20px 10px; gap:10px; background:#fff; border-radius:10px; border:1px solid #e6e6e6; box-shadow:0 2px 10px rgba(0,0,0,0.08); overflow-x:auto; }
.horizontal-stepper::before { content:''; position:absolute; top:28px; left:20px; right:20px; height:2px; background-color:#ddd; z-index:1; }
.horizontal-stepper::after { content:''; position:absolute; top:28px; left:20px; height:2px; width:<?= $student_id ? $progress : 0 ?>%; background-color:rgb(6,150,215); z-index:2; transition:width 0.4s ease; }
.horizontal-stepper .step { display:flex; flex-direction:column; align-items:center; min-width:80px; flex-shrink:0; text-align:center; z-index:3; }
.horizontal-stepper .circle { width:20px; height:20px; border-radius:50%; border:3px solid #ccc; background:#fff; }
.horizontal-stepper .step.completed .circle { background:rgb(6,150,215); border-color:rgb(6,150,215); }
.horizontal-stepper .step.active .circle { border-color:rgb(6,150,215); box-shadow:0 0 0 4px rgba(6,150,215,0.2); }
.horizontal-stepper .step.empty .circle { border-color:#ccc; }
.horizontal-stepper .label { margin-top:6px; font-size:12px; font-weight:500; white-space:nowrap; }
.horizontal-stepper .step-instruction { margin-top:4px; font-size:11px; color:#666; max-width:100px; line-height:1.3; text-align:justify; }

.stepper-card { max-width:900px; margin:20px auto; padding:20px; background:#fff; border-radius:10px; border:1px solid #e6e6e6; box-shadow:0 2px 10px rgba(0,0,0,0.08); text-align:center; }
.stepper-card h2 { font-size:18px; font-weight:600; color:rgb(6,150,215); margin-bottom:8px; }
.stepper-card p { font-size:14px; color:#666; line-height:1.5; }
.form-buttons { display:flex; justify-content:center; gap:10px; margin-top:15px; }
.form-buttons button { padding:8px 16px; border:none; border-radius:5px; background:rgb(6,150,215); color:#fff; cursor:pointer; }
.form-buttons button:hover { background:rgb(0,120,200); }

@media(max-width:768px){ .horizontal-stepper{padding:15px 5px;} .horizontal-stepper .step{min-width:70px;} .horizontal-stepper .label{font-size:10px;} .horizontal-stepper .step-instruction{font-size:9px; max-width:70px;} .stepper-card h2{font-size:16px;} .stepper-card p{font-size:12px;} }
</style>

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
            <li><a href="adminpanel.html"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
            <li class="active"><a href="enrollment.html"><i class="uil uil-files-landscapes"></i><span class="link-name">Enrollment</span></a></li>
            <li><a href="#"><i class="uil uil-chart"></i><span class="link-name">Grades</span></a></li>
            <li><a href="#"><i class="uil uil-thumbs-up"></i><span class="link-name">Likes</span></a></li>
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
        <div class="search-box"><i class="uil uil-search"></i><input type="text" placeholder="Search here..."></div>
        <img src="images/profile.jpg" alt="">
    </div>

    <div class="stepper-header">
        <h1>Enrollment Status</h1>
    </div>

 <!-- STUDENT SEARCH + SELECT -->
    <div class="student-selection-container">
        <label>Search & Select Student:</label>
        <input type="text" id="studentSearch" placeholder="Type student name...">
        <div class="dropdown-list" id="dropdownList">
            <?php foreach($all_students as $s): ?>
                <div data-id="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></div>
            <?php endforeach; ?>
        </div>
        <form method="POST" id="studentForm">
            <input type="hidden" name="id" id="selectedStudent">
        </form>
    </div>
    

    <?php if($student_id): ?>
    <!-- STEPPER -->
    <div class="horizontal-stepper">
        <?php
        $steps = [
            1 => ["Student Registration", "Provide personal details to create a student profile."],
            2 => ["Submission Review", "Submitted documents will be verified by the admin."],
            3 => ["Fee Payment", "Complete the payment process to confirm enrollment."],
            4 => ["Successfully Enrolled", "Enrollment is complete and confirmed."]
        ];
        for($i=1;$i<=4;$i++){
            $class = "empty";
            if($i<$current_step) $class="completed";
            elseif($i==$current_step) $class="active";
            echo "<div class='step $class'>
                    <div class='circle'></div>
                    <div class='label'>{$steps[$i][0]}</div>
                    <div class='step-instruction'>{$steps[$i][1]}</div>
                  </div>";
        }
        ?>
    </div>

    <!-- STUDENT INFO -->
    <div class="stepper-card">
        <h2><?= $student['name'] ?></h2>
        <p>Status: Step <?= $current_step ?> of 4</p>
        <form method="POST" class="form-buttons">
            <button type="submit" name="return">Return Step</button>
            <button type="submit" name="proceed">Proceed Step</button>
        </form>
    </div>
    <?php endif; ?>
</section>
    <script>
// ===== DROPDOWN SEARCH =====
const searchInput = document.getElementById('studentSearch');
const dropdownList = document.getElementById('dropdownList');
const selectedInput = document.getElementById('selectedStudent');
const form = document.getElementById('studentForm');

searchInput.addEventListener('input', function(){
    const filter = this.value.toLowerCase();
    dropdownList.style.display = 'block';
    const items = dropdownList.querySelectorAll('div');
    items.forEach(item=>{
        if(item.textContent.toLowerCase().startsWith(filter)){
            item.style.display='block';
        } else {
            item.style.display='none';
        }
    });
});

dropdownList.querySelectorAll('div').forEach(item=>{
    item.addEventListener('click', function(){
        const name = this.textContent;
        const id = this.dataset.id;
        searchInput.value = name;
        selectedInput.value = id;
        form.submit();
    });
});

// Click outside to hide
document.addEventListener('click', function(e){
    if(!e.target.closest('.student-selection-container')){
        dropdownList.style.display='none';
    }
});
</script>
</body>
</html>