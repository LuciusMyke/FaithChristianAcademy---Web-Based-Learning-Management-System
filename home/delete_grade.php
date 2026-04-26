<?php

include "../config/db.php";

$id = $_POST['id'];

mysqli_query($conn,"DELETE FROM gradebook WHERE id='$id'");

header("Location: grade_portal.php");

?>