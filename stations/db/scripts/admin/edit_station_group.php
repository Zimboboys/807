<?php

include_once("../../connect.php");

$groupID = substr($_POST["groupID"],2);
$newName = mysqli_real_escape_string($conn, $_POST["groupName"]);

$updateGroupSQL = "UPDATE station_checklist_group SET title = '$newName' WHERE groupID=$groupID";
mysqli_query($conn, $updateGroupSQL);