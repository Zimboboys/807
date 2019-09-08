<?php

include_once("../../connect.php");

$itemID = substr($_POST["itemID"],2);

$createStationSQL = "DELETE FROM station_checklist_item WHERE checklistID = $itemID";
mysqli_query($conn, $createStationSQL);
