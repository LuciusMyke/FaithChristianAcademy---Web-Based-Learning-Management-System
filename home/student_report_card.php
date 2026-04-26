<?php
session_start();

// ── SECURITY: Only authenticated students can request this file ──
// No session = redirect. Wrong role = redirect.
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'student') {
    http_response_code(403);
    echo '<p style="color:red;">Access denied.</p>';
    exit;
}

$servername = "sql110.infinityfree.com";
$username   = "if0_41176520";
$password   = "1W89jn4xLkI";
$dbname     = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ── CRITICAL: IGNORE the ?id= param entirely. ──
// Always use the session ID so a student can NEVER view another student's card
// by changing the URL parameter.
$student_id = (int)$_SESSION['user_id'];

$student = null;
$grades  = [];
$gen_row = null;

$stmt = $conn->prepare("SELECT * FROM student WHERE id = ? AND role = 'student'");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    echo '<p style="color:red; padding:20px;">Student record not found.</p>';
    exit;
}

// Load grades
$stmt2 = $conn->prepare("SELECT * FROM grades WHERE student_id = ? ORDER BY id ASC");
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
<style>
/* Scoped styles for the report card only */
.rc-card {
    width: 800px;
    margin: 0 auto 30px;
    border: 3px solid #0070C0;
    padding: 15px;
    background: white;
    font-family: Arial, sans-serif;
    font-size: 12px;
}
.rc-header { text-align: center; line-height: 1.4; margin-bottom: 10px; }
/* Update mo itong part sa loob ng <style> */
.rc-info { 
    margin: 15px 0; 
    width: 100%; 
    border-collapse: collapse; 
    table-layout: fixed; /* Pinipilit ang columns na maging pantay */
}
.rc-info td { 
    padding: 6px 4px; 
    vertical-align: bottom; 
}
.rc-info .label { 
    font-weight: bold; 
    white-space: nowrap;
    width: 120px; /* Fixed width para sa labels para laging pantay */
}
.rc-info .val {
    border-bottom: 1px solid #000; /* Mas madilim na line para kitang-kita */
    padding: 2px 5px;
    word-wrap: break-word;
}
table.rc-grades {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
table.rc-grades th,
table.rc-grades td {
    border: 1px solid #000;
    text-align: center;
    padding: 5px 4px;
}
table.rc-grades td:first-child { text-align: left; padding-left: 6px; }
table.rc-grades tr:nth-child(even) { background: #f9f9f9; }
.grade-val { font-weight: 600; color: #111; }
.grade-empty { color: #bbb; font-style: italic; font-size: 11px; }
.remarks-pass { color: #1a7a1a; font-weight: 600; }
.remarks-fail { color: #c0392b; font-weight: 600; }
.rc-msg { font-size: 11px; margin-top: 15px; line-height: 1.6; }
.rc-sig-table { width: 100%; text-align: center; margin-top: 20px; }
.rc-sig-table td { padding: 5px 20px; }
.sig-line {
    display: inline-block;
    border-bottom: 1px solid #333;
    min-width: 180px;
    font-weight: bold;
    text-transform: uppercase;
    padding: 0 4px;
}
.no-grades-notice {
    text-align: center;
    padding: 30px;
    color: #888;
    font-style: italic;
    font-size: 13px;
}
@media print {
    .rc-card { border: 3px solid #000; }
}
</style>

<div class="rc-card">

    <!-- HEADER -->
    <div class="rc-header">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <img src="../assets/logo.png" style="width:70px; height:70px; object-fit:contain;">
            <div style="flex:1; text-align:center;">
                <b>Republic of the Philippines</b><br>
                <b>DEPARTMENT OF EDUCATION</b><br>
                Region IV – A CALABARZON<br>
                Division of Rizal<br>
                <b>FAITH CHRISTIAN ACADEMY OF RODRIGUEZ</b><br>
                <b>RIZAL</b><br>
                Kasiglahan Village, San Jose, Rodriguez, Rizal<br>
                <b>ELEMENTARY SCHOOL DEPARTMENT</b><br>
                <b>PROGRESS REPORT CARD</b><br>
                School Year <b><?= htmlspecialchars($student['school_year'] ?? '2024-2025') ?></b>
            </div>
            <img src="../assets/deped.png" style="width:70px; height:70px; object-fit:contain;">
        </div>
    </div>

    <!-- STUDENT INFO — all read-only, plain text, no inputs -->
   <table class="rc-info">
    <tr>
        <td class="label">LRN:</td>
        <td class="val" colspan="2"><?= htmlspecialchars($student['id']) ?></td>
        <td colspan="2"></td> </tr>
    <tr>
        <td class="label">NAME:</td>
        <td class="val" colspan="2"><?= htmlspecialchars($student['name']) ?></td>
        <td class="label" style="width: 50px; text-align: right;">Sex:</td>
        <td class="val"><?= htmlspecialchars($student['gender'] ?? '—') ?></td>
    </tr>
    <tr>
        <td class="label">Birthday:</td>
        <td class="val" colspan="2">
            <?php
            $bday = $student['birthday'] ?? '';
            echo $bday ? htmlspecialchars(date('F d, Y', strtotime($bday))) : '—';
            ?>
        </td>
        <td class="label" style="text-align: right;">Age:</td>
        <td class="val"><?= htmlspecialchars($student['age'] ?? '—') ?></td>
    </tr>
    <tr>
        <td class="label">Grade and Section:</td>
        <td class="val"><?= htmlspecialchars($student['section'] ?? '—') ?></td>
        <td style="width: 20px;"></td> <td class="label" style="text-align: right;">Adviser:</td>
        <td class="val"><?= htmlspecialchars($student['adviser'] ?? '—') ?></td>
    </tr>
</table>

    <!-- GRADES TABLE -->
   <table class="rc-grades">
    <tr>
        <th rowspan="2">SUBJECTS</th>
        <th colspan="5">GRADING PERIOD</th>
        <th rowspan="2">REMARKS</th>
    </tr>
    <tr>
        <th>1ST</th>
        <th>2ND</th>
        <th>3RD</th>
        <th>4TH</th>
        <th>Final Rating</th>
    </tr>

    <?php
    // Helper function to display grade cell (pwedeng i-move sa taas ng loop)
    if (!function_exists('gradeCell')) {
        function gradeCell($val) {
            if ($val === null || $val === '' || $val == 0) {
                return '<span class="grade-empty">—</span>';
            }
            return '<span class="grade-val">' . htmlspecialchars($val) . '</span>';
        }
    }

    // Eto ang mahalaga: I-loop ang subjects_list para laging buo ang table
    foreach ($subjects_list as $sub):
        $g = $grades_map[$sub] ?? null; // I-check kung may record sa DB

        // Remarks styling logic
        $rem = $g['remarks'] ?? '';
        $remClass = '';
        if (strtolower($rem) === 'passed' || strtolower($rem) === 'pass') $remClass = 'remarks-pass';
        if (strtolower($rem) === 'failed' || strtolower($rem) === 'fail') $remClass = 'remarks-fail';
    ?>
    <tr>
        <td><?= htmlspecialchars($sub) ?></td>
        <td><?= gradeCell($g['first_grading']  ?? null) ?></td>
        <td><?= gradeCell($g['second_grading'] ?? null) ?></td>
        <td><?= gradeCell($g['third_grading']  ?? null) ?></td>
        <td><?= gradeCell($g['fourth_grading'] ?? null) ?></td>
        <td><?= gradeCell($g['final_rating']   ?? null) ?></td>
        <td>
            <?php if ($rem): ?>
                <span class="<?= $remClass ?>"><?= htmlspecialchars($rem) ?></span>
            <?php else: ?>
                <span class="grade-empty">—</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

    <tr style="background:#e8f4fd; font-weight:bold;">
        <td><b>General Average</b></td>
        <td colspan="4"></td>
        <td>
            <?= (isset($gen_row['final_rating']) && $gen_row['final_rating'] !== '') 
                ? '<span class="grade-val">'.htmlspecialchars($gen_row['final_rating']).'</span>' 
                : '<span class="grade-empty">—</span>' ?>
        </td>
        <td>
            <?php 
                $gr = $gen_row['remarks'] ?? '';
                $grClass = (strtolower($gr) === 'passed' || strtolower($gr) === 'pass') ? 'remarks-pass' : 
                           ((strtolower($gr) === 'failed' || strtolower($gr) === 'fail') ? 'remarks-fail' : '');
                echo $gr ? '<span class="'.$grClass.'">'.htmlspecialchars($gr).'</span>' : '<span class="grade-empty">—</span>';
            ?>
        </td>
    </tr>
</table>

    <?php if (!$has_any_grade): ?>
    <p class="no-grades-notice">
        📋 No grades have been entered yet. Please check back once your teacher has submitted your grades.
    </p>
    <?php endif; ?>

    <!-- PARENT MESSAGE -->
    <p class="rc-msg">
        Dear Parents,<br>
        This report card shows the ability and progress your child has made in the different learning areas
        as well as his/her progressing character development.<br>
        The school welcomes you if you desire to know more about the progress of your child.<br>
        Request appointment with the teacher.
    </p>

    <br>

    <!-- SIGNATURES -->
    <table class="rc-sig-table">
        <tr>
            <td>
                <span class="sig-line"><?= htmlspecialchars($student['adviser'] ?? '') ?></span><br>
                Adviser
            </td>
            <td>
                <span class="sig-line">MRS. ANGELITA M. FRONDA</span><br>
                Principal
            </td>
        </tr>
    </table>

</div>
<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert("Ops! Bawal ang right-click dito.");
    });
</script>
