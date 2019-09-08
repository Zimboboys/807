<?php

include_once("../../connect.php");

$itemName = mysqli_real_escape_string($conn, $_POST["itemName"]);
$groupID = substr($_POST["stationGroupID"], 2);

$addChecklistItemSQL = "INSERT INTO station_checklist_item (stationGroupID, item) VALUES ($groupID, '$itemName')";
mysqli_query($conn, $addChecklistItemSQL);

$itemID = mysqli_insert_id($conn);
echo $itemID;