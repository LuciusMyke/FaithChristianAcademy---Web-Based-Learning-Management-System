<?php
session_start();

// ===== DB CONNECTION =====
$conn = new mysqli(
    "sql110.infinityfree.com",
    "if0_41176520",
    "1W89jn4xLkI",
    "if0_41176520_faith"
);

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

/* ================= ACTIVE TAB CONTROL ================= */
$activeTab = $_GET['tab'] ?? 'create';

/* ================= CREATE TEACHER ================= */
if (isset($_POST['create_teacher'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $role = "teacher";
    $status = "active";

    $check = $conn->prepare("SELECT id FROM student WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {
        $stmt = $conn->prepare("
            INSERT INTO student (name, email, password, role, enrollment_status)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss", $name, $email, $password, $role, $status);
        $stmt->execute();

        $msg = "Teacher created successfully!";
    } else {
        $msg = "Email already exists.";
    }
}

/* ================= ACTIONS ================= */

// DELETE
if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM student WHERE id=$id");

    header("Location: ?tab=manage");
    exit();
}

// SUSPEND / ACTIVATE
if (isset($_POST['toggle_status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE student SET enrollment_status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    header("Location: ?tab=manage");
    exit();
}

/* ================= SEARCH ================= */
$search = $_GET['search'] ?? '';

$stmt = $conn->prepare("
    SELECT id, name, email, role, enrollment_status
    FROM student
    WHERE name LIKE CONCAT('%', ?, '%')
       OR email LIKE CONCAT('%', ?, '%')
       OR role LIKE CONCAT('%', ?, '%')
    ORDER BY id DESC
");

$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$users = $stmt->get_result();
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
body {
    font-family: Arial;
    background: #f4f6fb;
    padding: 20px;
}

.container {
    max-width: 1100px;
    margin: auto;
}

/* NAV */
.nav {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.nav button {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    background: #ddd;
    border-radius: 6px;
}

.nav button.active {
    background: #0a1a3a;
    color: white;
}

/* SECTIONS */
.section {
    display: none;
    background: white;
    padding: 20px;
    border-radius: 10px;
}

.section.active {
    display: block;
}

/* INPUT */
input {
    padding: 10px;
    margin: 5px;
    width: 250px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th {
    background: #0a1a3a;
    color: white;
    padding: 10px;
}

td {
    text-align: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

/* STATUS */
.status-active { color: green; font-weight: bold; }
.status-suspended { color: red; font-weight: bold; }

/* BUTTONS */
button {
    padding: 6px 10px;
    border: none;
    cursor: pointer;
}

.delete-btn { background: red; color: white; }
.suspend-btn { background: orange; color: white; }
.activate-btn { background: green; color: white; }


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
<h2>Admin Panel</h2>

<!-- NAV -->
<div class="nav">
    <button onclick="showTab('create')" class="<?= $activeTab=='create'?'active':'' ?>">Create Teacher</button>
    <button onclick="showTab('manage')" class="<?= $activeTab=='manage'?'active':'' ?>">Manage Accounts</button>
</div>

<?php if(isset($msg)) echo "<p><b>$msg</b></p>"; ?>

<!-- ================= CREATE TEACHER ================= -->
<div id="create" class="section <?= $activeTab=='create'?'active':'' ?>">

<h3>Create Teacher Account</h3>

<form method="POST" action="?tab=create">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="create_teacher" style="background:#0a1a3a;color:white;">
        Create
    </button>
</form>

</div>

<!-- ================= MANAGE ================= -->
<div id="manage" class="section <?= $activeTab=='manage'?'active':'' ?>">

<h3>Manage Accounts</h3>

<!-- SEARCH -->
<form method="GET">
    <input type="hidden" name="tab" value="manage">
    <input type="text" name="search" placeholder="Search user..."
           value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Delete</th>
    <th>Action</th>
</tr>

<?php while($row = $users->fetch_assoc()) { ?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['role'] ?></td>

    <td>
        <?php if ($row['enrollment_status'] == 'suspended') { ?>
            <span class="status-suspended">SUSPENDED</span>
        <?php } else { ?>
            <span class="status-active">ACTIVE</span>
        <?php } ?>
    </td>

    <!-- DELETE -->
    <td>
        <form method="POST" action="?tab=manage" onsubmit="return confirm('Delete user?')">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button class="delete-btn" name="delete">Delete</button>
        </form>
    </td>

    <!-- SUSPEND -->
    <td>
        <form method="POST" action="?tab=manage">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <?php if ($row['enrollment_status'] == 'suspended') { ?>
                <input type="hidden" name="status" value="active">
                <button class="activate-btn" name="toggle_status">Activate</button>
            <?php } else { ?>
                <input type="hidden" name="status" value="suspended">
                <button class="suspend-btn" name="toggle_status">Suspend</button>
            <?php } ?>
        </form>
    </td>
</tr>

<?php } ?>

</table>

</div>

</div>
</section>
<script>
function showTab(tab) {
    window.location.href = "?tab=" + tab;
}
</script>
</body>
</html>