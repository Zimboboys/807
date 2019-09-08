<?php

include_once("../../connect.php");

$id = substr($_POST["id"],2);
$name = mysqli_real_escape_string($conn, $_POST["name"]);

$updateStationNameSQL = "UPDATE events SET title = '$name' WHERE eventID=$id";
mysqli_query($conn, $updateStationNameSQL);
