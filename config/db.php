<?php

session_start();

$servername = "sql110.infinityfree.com";
$username = "if0_41176520";
$password = "1W89jn4xLkI";
$dbname = "if0_41176520_faith";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

?>