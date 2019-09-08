<?php

include_once("../../connect.php");

$checklistID = substr($_POST["id"],2);
$setReq = $_POST["currentReq"] == 0 ? 1 : 0;

$updateChecklistReqSQL = "UPDATE station_checklist_item SET required = $setReq WHERE checklistID=$checklistID";
mysqli_query($conn, $updateChecklistReqSQL);

echo $setReq == 1 ? "star" : "star_border";
