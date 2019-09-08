<?php

include_once("../../connect.php");

$id = substr($_POST["id"],2);
$max = $_POST["max"]; // hopefully a number

$updateMaxFailedSQL = "UPDATE stations SET maxFailed = $max WHERE stationID=$id";
mysqli_query($conn, $updateMaxFailedSQL);
