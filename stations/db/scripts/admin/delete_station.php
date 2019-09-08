<?php

include_once("../../connect.php");

$stationID = substr($_POST["stationID"], 2);

$createStationSQL = "DELETE FROM stations WHERE stationID = $stationID";
mysqli_query($conn, $createStationSQL);
