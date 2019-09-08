<?php

include_once("../../connect.php");

$id = substr($_POST["id"],2);
$name = mysqli_real_escape_string($conn, $_POST["name"]);

$updateStationNameSQL = "UPDATE stations SET name = '$name' WHERE stationID=$id";
mysqli_query($conn, $updateStationNameSQL);
