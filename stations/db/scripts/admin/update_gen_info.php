<?php

include_once("../../connect.php");

$id = substr($_POST["id"],2);
$info = mysqli_real_escape_string($conn, $_POST["text"]);

$updateInfoSQL = "UPDATE station_info SET infoText = '$info' WHERE infoID=$id";
mysqli_query($conn, $updateInfoSQL);