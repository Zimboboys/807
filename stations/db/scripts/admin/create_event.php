<?php

include_once("../../connect.php");

$eventName = $_POST['eventName'];

$addEventSQL = "INSERT INTO events (title) VALUES ('$eventName')";
mysqli_query($conn, $addEventSQL);
echo mysqli_insert_id($conn);