<?php

include_once "../../connect.php";
include_once "../../user.php";

$user = new user($conn);
$leader = $user->getID();
$eventID = substr($_POST['eventID'], 2);

foreach ($_POST['ontime'] as $mem) {
    $mem = substr($mem, 2);
    $passedSQL = "INSERT INTO attendance (eventID, leaderID, memberID, attended) VALUES ($eventID, $leader, $mem, 0)";
    mysqli_query($conn, $passedSQL);
}

foreach ($_POST['late'] as $mem) {
    $mem = substr($mem, 2);
    $lateSQL = "INSERT INTO attendance (eventID, leaderID, memberID, attended) VALUES ($eventID, $leader, $mem, 1)";
    mysqli_query($conn, $lateSQL);
}

foreach ($_POST['noshow'] as $mem) {
    $mem = substr($mem, 2);
    $noshowSQL = "INSERT INTO attendance (eventID, leaderID, memberID, attended) VALUES ($eventID, $leader, $mem, 2)";
    mysqli_query($conn, $noshowSQL);
}
