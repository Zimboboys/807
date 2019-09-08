<?php

include_once("../../connect.php");

$id = substr($_POST["id"],2);
$date = mysqli_real_escape_string($conn, $_POST["date"]);
$groups = $_POST['assigned'];

$updateStationSQL = "UPDATE events SET datetime = '$date', memberGroups = '$groups' WHERE eventID=$id";
mysqli_query($conn, $updateStationSQL);

echo $date;