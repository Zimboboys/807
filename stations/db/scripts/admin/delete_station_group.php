<?php

include_once("../../connect.php");

// "decodes" the string that was passed in: s_XX -> XX
$groupID = substr($_POST["groupID"],2);

$createStationSQL = "DELETE FROM station_checklist_group WHERE groupID = $groupID";
mysqli_query($conn, $createStationSQL);
