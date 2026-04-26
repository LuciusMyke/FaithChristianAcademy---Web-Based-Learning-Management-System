<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql110.infinityfree.com";
$username   = "if0_41176520";
$password   = "1W89jn4xLkI";
$dbname     = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

/* =========================
   SAVE GRADES (POST via fetch)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $student_id = (int)($_POST['student_id'] ?? 0);
    if (!$student_id) {
        echo json_encode(['ok' => false, 'msg' => 'No student ID received.']);
        exit;
    }

    // Update student info fields
    $stmtU = $conn->prepare("UPDATE student SET birthday=?, age=?, section=?, gender=?, adviser=? WHERE id=?");
    $stmtU->bind_param("sisssi",
        $_POST['birthday'],
        $_POST['age'],
        $_POST['grade_section'],
        $_POST['sex'],
        $_POST['adviser'],
        $student_id
    );
    if (!$stmtU->execute()) {
        echo json_encode(['ok' => false, 'msg' => 'Failed updating student: ' . $stmtU->error]);
        exit;
    }

    // Delete old grades for this student
    $stmtDel = $conn->prepare("DELETE FROM grades WHERE student_id=?");
    $stmtDel->bind_param("i", $student_id);
    $stmtDel->execute();

    // Insert subject grades
    if (!empty($_POST['subject'])) {
        foreach ($_POST['subject'] as $i => $sub) {
            $first   = (isset($_POST['first'][$i])   && $_POST['first'][$i]   !== '') ? (int)$_POST['first'][$i]   : null;
            $second  = (isset($_POST['second'][$i])  && $_POST['second'][$i]  !== '') ? (int)$_POST['second'][$i]  : null;
            $third   = (isset($_POST['third'][$i])   && $_POST['third'][$i]   !== '') ? (int)$_POST['third'][$i]   : null;
            $fourth  = (isset($_POST['fourth'][$i])  && $_POST['fourth'][$i]  !== '') ? (int)$_POST['fourth'][$i]  : null;
            $final   = (isset($_POST['final'][$i])   && $_POST['final'][$i]   !== '') ? (int)$_POST['final'][$i]   : null;
            $remarks = $_POST['remarks'][$i] ?? '';

            $stmtG = $conn->prepare("
                INSERT INTO grades (student_id, subject, first_grading, second_grading, third_grading, fourth_grading, final_rating, remarks)
                VALUES (?,?,?,?,?,?,?,?)
            ");
            $stmtG->bind_param("isiiiiis",
                $student_id, $sub,
                $first, $second, $third, $fourth, $final,
                $remarks
            );
            if (!$stmtG->execute()) {
                echo json_encode(['ok' => false, 'msg' => 'Failed saving grade for ' . $sub . ': ' . $stmtG->error]);
                exit;
            }
        }
    }

    // Insert general average as special row
    $gen_ave     = (isset($_POST['gen_ave']) && $_POST['gen_ave'] !== '') ? (int)$_POST['gen_ave'] : null;
    $gen_remarks = $_POST['gen_remarks'] ?? '';
    $gen_subject = '__general__';
    $stmtGen = $conn->prepare("INSERT INTO grades (student_id, subject, final_rating, remarks) VALUES (?,?,?,?)");
    $stmtGen->bind_param("isis", $student_id, $gen_subject, $gen_ave, $gen_remarks);
    if (!$stmtGen->execute()) {
        echo json_encode(['ok' => false, 'msg' => 'Failed saving general average: ' . $stmtGen->error]);
        exit;
    }

    echo json_encode(['ok' => true, 'msg' => 'Grades saved successfully!']);
    exit;
}

/* =========================
   LOAD DATA (GET)
========================= */
$student_id = isset($_GET['lrn']) ? (int)$_GET['lrn'] : 0;
$student    = null;
$grades     = [];
$gen_row    = null;

if ($student_id) {
    $stmt = $conn->prepare("SELECT * FROM student WHERE id=?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if ($student) {
        $stmt2 = $conn->prepare("SELECT * FROM grades WHERE student_id=? ORDER BY id ASC");
        $stmt2->bind_param("i", $student_id);
        $stmt2->execute();
        $res = $stmt2->get_result();
        while ($row = $res->fetch_assoc()) {
            if ($row['subject'] === '__general__') {
                $gen_row = $row;
            } else {
                $grades[] = $row;
            }
        }
    }
}

$subjects_list = [
    'Filipino',
    'English',
    'Mathematics',
    'Science',
    'Technology and Livelihood Education (TLE)',
    'Araling Panlipunan (AP)',
    'Growing in Values (GMRC)',
    'MAPEH',
    '*Music',
    '*Arts',
    '*Physical Education',
    '*Health'
];

$grades_map = [];
foreach ($grades as $g) {
    $grades_map[$g['subject']] = $g;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Progress Report Card</title>
<style>
body { font-family: Arial; font-size: 12px; background: #f4f4f4; }
.card { width: 800px; margin: 20px auto; border: 3px solid #0070C0; padding: 15px; background: white; }
.header { text-align: center; line-height: 1.3; }
.info { margin: 15px 0; }
.info td { padding: 3px 5px; }
table.grades { width: 100%; border-collapse: collapse; margin-top: 10px; }
table.grades th, table.grades td { border: 1px solid #000; text-align: center; padding: 4px; }
table.grades td:first-child { text-align: left; padding-left: 5px; }
input { width: 95%; border: 1px solid #ccc; text-align: center; background: #f0f8ff; font-size: 12px; padding: 2px; box-sizing: border-box; }
input.long { width: 220px; text-align: left; padding-left: 3px; }
input[readonly] { background: #e9ecef; color: #333; cursor: default; }
.btn { background: #0070C0; color: white; padding: 10px 20px; border: none; cursor: pointer; margin: 5px; border-radius: 4px; font-size: 13px; }
.btn:hover { background: #005a9e; }
.btn-danger { background: #dc3545; }
.btn-danger:hover { background: #b02a37; }
.save-status { display:inline-block; margin-left:10px; font-size:13px; font-weight:bold; }
.save-status.ok  { color: green; }
.save-status.err { color: red; }
@media print { .no-print { display: none; } input { border: none; background: none; } }
</style>
</head>
<body>

<?php if (!$student_id || !$student): ?>
<div class="card" style="text-align:center; padding:40px; color:#888;">
    No student selected. Please select a student from the dropdown above.
</div>
<?php else: ?>

<!-- id used as the form identifier, NOT submitted as lrn name -->
<form id="reportForm">
<input type="hidden" name="student_id" value="<?= $student['id'] ?>">

<div class="card">

    <!-- HEADER -->
    <div class="header">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <img src="../assets/logo.png" style="width:70px; height:70px; object-fit:contain;">
            <div style="text-align:center; flex:1;">
                <b>Republic of the Philippines</b><br>
                <b>DEPARTMENT OF EDUCATION</b><br>
                Region IV – A CALABARZON<br>
                Division of Rizal<br>
                <b>FAITH CHRISTIAN ACADEMY OF RODRIGUEZ</b><br>
                <b>RIZAL</b><br>
                Kasiglahan Village, San Jose, Rodriguez, Rizal<br>
                <b>ELEMENTARY SCHOOL DEPARTMENT</b><br>
                <b>PROGRESS REPORT CARD</b><br>
                School Year
                <input type="text" name="school_year"
                    value="<?= htmlspecialchars($student['school_year'] ?? '2024-2025') ?>"
                    style="width:80px">
            </div>
            <img src="../assets/deped.png" style="width:70px; height:70px; object-fit:contain;">
        </div>
    </div>

    <!-- STUDENT INFO -->
    <table class="info">
        <tr>
            <td><b>LRN:</b>
                <input class="long" value="<?= htmlspecialchars($student['id']) ?>" readonly>
            </td>
        </tr>
        <tr>
            <td><b>NAME:</b>
                <input class="long" value="<?= htmlspecialchars($student['name']) ?>" readonly>
            </td>
            <td><b>Sex:</b>
                <input name="sex" value="<?= htmlspecialchars($student['gender'] ?? '') ?>" style="width:60px">
            </td>
        </tr>
        <tr>
            <td><b>Birthday:</b>
                <input type="date" name="birthday" value="<?= htmlspecialchars($student['birthday'] ?? '') ?>">
            </td>
            <td><b>Age:</b>
                <input name="age" value="<?= htmlspecialchars($student['age'] ?? '') ?>" style="width:50px">
            </td>
        </tr>
        <tr>
            <td><b>Grade and Section:</b>
                <input class="long" name="grade_section" value="<?= htmlspecialchars($student['section'] ?? '') ?>">
            </td>
            <td><b>Adviser:</b>
                <input class="long" name="adviser" value="<?= htmlspecialchars($student['adviser'] ?? '') ?>">
            </td>
        </tr>
    </table>

    <!-- GRADES TABLE -->
    <table class="grades">
        <tr>
            <th rowspan="2">SUBJECTS</th>
            <th colspan="5">GRADING PERIOD</th>
            <th rowspan="2">REMARKS</th>
        </tr>
        <tr>
            <th>1ST</th><th>2ND</th><th>3RD</th><th>4TH</th><th>Final Rating</th>
        </tr>

        <?php foreach ($subjects_list as $sub):
            $g = $grades_map[$sub] ?? [];
        ?>
        <tr>
            <td>
                <input type="hidden" name="subject[]" value="<?= htmlspecialchars($sub) ?>">
                <?= htmlspecialchars($sub) ?>
            </td>
            <td><input name="first[]"   value="<?= htmlspecialchars($g['first_grading']  ?? '') ?>"></td>
            <td><input name="second[]"  value="<?= htmlspecialchars($g['second_grading'] ?? '') ?>"></td>
            <td><input name="third[]"   value="<?= htmlspecialchars($g['third_grading']  ?? '') ?>"></td>
            <td><input name="fourth[]"  value="<?= htmlspecialchars($g['fourth_grading'] ?? '') ?>"></td>
            <td><input name="final[]"   value="<?= htmlspecialchars($g['final_rating']   ?? '') ?>"></td>
            <td><input name="remarks[]" value="<?= htmlspecialchars($g['remarks']        ?? '') ?>"></td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td><b>General Average</b></td>
            <td colspan="4"></td>
            <td><input name="gen_ave"     value="<?= htmlspecialchars($gen_row['final_rating'] ?? '') ?>"></td>
            <td><input name="gen_remarks" value="<?= htmlspecialchars($gen_row['remarks']      ?? '') ?>"></td>
        </tr>
    </table>

    <!-- PARENT MESSAGE -->
    <p style="font-size:11px; margin-top:15px;">
        Dear Parents,<br>
        This report card shows the ability and progress your child has made in the different learning areas
        as well as his/her progressing character development.<br>
        The school welcomes you if you desire to know more about the progress of your child.<br>
        Request appointment with the teacher.
    </p>

    <br><br>

    <!-- SIGNATURES -->
    <table style="width:100%; text-align:center;">
        <tr>
            <td>
                <u>
                    <input type="text" name="adviser_sig"
                        value="<?= htmlspecialchars($student['adviser'] ?? '') ?>"
                        style="border:none; text-align:center; font-weight:bold; text-transform:uppercase;">
                </u><br>Adviser
            </td>
            <td>
                <u>MRS. ANGELITA M. FRONDA</u><br>Principal
            </td>
        </tr>
    </table>

</div><!-- .card -->

<!-- BUTTONS — functions are defined in analytics.php since this HTML is injected via innerHTML -->
<center class="no-print" style="margin-top:10px;">
    <button type="button" class="btn" id="saveBtn" onclick="saveGrades()">💾 Save Grades</button>
    <button type="button" class="btn" onclick="window.print()">🖨️ Print</button>
    <button type="button" class="btn btn-danger" onclick="clearGrades()">🗑️ Clear Grades</button>
    <span class="save-status" id="saveStatus"></span>
</center>

</form>

<?php endif; ?>
</body>
</html>