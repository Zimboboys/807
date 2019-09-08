<?php

include_once("../../connect.php");

$stationID = substr($_POST['stationID'], 2);

$fillerGroupSQL = "INSERT INTO station_checklist_group (stationID, title) VALUES ($stationID, 'new grouping')";
mysqli_query($conn, $fillerGroupSQL);
$groupID = mysqli_insert_id($conn);

$fillerItemSQL = "INSERT INTO station_checklist_item (stationGroupID, item, olderSibling) VALUES ($groupID, 'example item', 0)";
mysqli_query($conn, $fillerItemSQL);
$checklistID = mysqli_insert_id($conn);

echo $groupID . "," .$checklistID;