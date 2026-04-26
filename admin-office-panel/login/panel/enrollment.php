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
        /* STEP HEADER */
        .stepper-header {
            width: 100%;
            max-width: 900px;
            margin: 20px auto 10px;
            padding: 15px 20px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e6e6e6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stepper-header h1 {
            font-size: 20px;
            font-weight: 600;
            color: rgb(6,150,215);
        }

        /* HORIZONTAL STEPPER */
        .horizontal-stepper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            position: relative;
            padding: 20px 10px;
            gap: 10px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e6e6e6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }
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
        .horizontal-stepper::after {
            content: '';
            position: absolute;
            top: 28px;
            left: 20px;
            height: 2px;
            width: <?= $student_id ? $progress : 0 ?>%;
            background-color: rgb(6,150,215);
            z-index: 2;
            transition: width 0.4s ease;
        }
        .horizontal-stepper .step {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 80px;
            flex-shrink: 0;
            text-align: center;
            z-index: 3;
        }
        .horizontal-stepper .circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid #ccc;
            background-color: #fff;
        }
        .horizontal-stepper .step.completed .circle {
            background-color: rgb(6,150,215);
            border-color: rgb(6,150,215);
        }
        .horizontal-stepper .step.active .circle {
            border-color: rgb(6,150,215);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(6,150,215,0.2);
        }
        .horizontal-stepper .step.empty .circle {
            border-color: #ccc;
            background: #fff;
        }
        .horizontal-stepper .label {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }
        .horizontal-stepper .step-instruction {
            margin-top: 4px;
            font-size: 11px;
            color: #666;
            max-width: 100px;
            line-height: 1.3;
            text-align: justify;
        }

        /* STUDENT INFO CARD */
        .stepper-card {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            border:1px solid #e6e6e6;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            text-align:center;
        }
        .stepper-card h2 {
            font-size: 18px;
            font-weight: 600;
            color: rgb(6,150,215);
            margin-bottom: 8px;
        }
        .stepper-card p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }

        /* BUTTONS */
        .form-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        .form-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            background: rgb(6,150,215);
            color: #fff;
            cursor: pointer;
        }
        .form-buttons button:hover {
            background: rgb(0,120,200);
        }

        /* SELECT */
        .student-selection {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
        .student-selection select {
            padding: 8px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        @media (max-width:768px){
            .horizontal-stepper { padding: 15px 5px; }
            .horizontal-stepper .step { min-width: 70px; }
            .horizontal-stepper .label { font-size: 10px; }
            .horizontal-stepper .step-instruction { font-size: 9px; max-width: 70px; }
            .stepper-card h2 { font-size: 16px; }
            .stepper-card p { font-size: 12px; }
        }
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
        <div class="search-box"><i class="uil uil-search"></i><input type="text" placeholder="Search here..."></div>
        <img src="images/profile.jpg" alt="">
    </div>

    <div class="stepper-header">
        <h1>Enrollment Status</h1>
    </div>

<div class="stepper-card">

    <form method="POST">
      <div class="student-selection">
        <select name="id" id="studentSelect" onchange="this.form.submit()">
          <option value="">-- Select a Student --</option>
          <?php
          // Only show users with role 'student'
          $students = mysqli_query($conn, "SELECT id, name FROM student WHERE role='student'");
          while($row = mysqli_fetch_assoc($students)){
              $sel = ($student_id == $row['id']) ? "selected" : "";
              echo "<option value='{$row['id']}' $sel>{$row['name']}</option>";
          }
          ?>
        </select>
      </div>
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
</body>
</html>