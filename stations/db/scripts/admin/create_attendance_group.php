<?php

include_once("../../connect.php");

$name = mysqli_real_escape_string($conn, $_POST['name']);

$groupSQL = "INSERT INTO attendance_groups (groupID, groupName) VALUES (0, '$name')";
echo $groupSQL;
mysqli_query($conn, $groupSQL);
echo mysqli_insert_id($conn);
