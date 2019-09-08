<?php

include_once("../../connect.php");

$checklistID = substr($_POST["itemID"], 2);
$newName = mysqli_real_escape_string($conn, $_POST["itemName"]);

$updateChecklistItemSQL = "UPDATE station_checklist_item SET item = '$newName' WHERE checklistID=$checklistID";
mysqli_query($conn, $updateChecklistItemSQL);