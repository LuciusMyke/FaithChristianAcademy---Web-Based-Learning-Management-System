<?php

include "../config/db.php";

$subject = $_POST['subject'];
$file = $_FILES['excel_file']['tmp_name'];

$handle = fopen($file,"r");

$row = 0;

while(($data = fgetcsv($handle,1000,",")) !== FALSE){

if($row == 0){
$row++;
continue;
}

$student_id = $data[0];
$name = $data[1];
$midterm = $data[2];
$final = $data[3];
$average = $data[4];

mysqli_query($conn,"INSERT INTO gradebook
(student_id,name,subject,midterm,final,average)
VALUES
('$student_id','$name','$subject','$midterm','$final','$average')");

$row++;

}

fclose($handle);

header("Location: ../home/grade_portal.php?success=1&tab=view");
exit();
?>