<?php
include "../config/db.php";

$result = mysqli_query($conn,"SELECT * FROM gradebook");
?>

<div class="table-data">

<div class="order">

<div class="head">
<h3>Student Grades</h3>
<input type="text" id="searchInput" placeholder="Search Student">
</div>

<table id="gradeTable">

<thead>

<tr>
<th>ID</th>
<th>Student ID</th>
<th>Name</th>
<th>Subject</th>
<th>Midterm</th>
<th>Final</th>
<th>Average</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['student_id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['midterm']; ?></td>
<td><?php echo $row['final']; ?></td>
<td><?php echo $row['average']; ?></td>

<td>

<form action="delete_grade.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<button class="delete-btn">
Delete
</button>

</form>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>