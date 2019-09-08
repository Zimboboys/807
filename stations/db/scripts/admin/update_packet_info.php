<?php

include_once("../../connect.php");

$id = substr($_POST["id"],2);
$info = mysqli_real_escape_string($conn, $_POST["text"]);

$updateInfoSQL = "UPDATE station_packet_content SET commandText = '$info' WHERE contentID=$id";
mysqli_query($conn, $updateInfoSQL);