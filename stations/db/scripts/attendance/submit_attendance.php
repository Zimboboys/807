<?php

include_once "../../connect.php";
include_once "../../user.php";

$user = new user($conn);
$leader = $user->getID();
$eventID = substr($_POST['eventID'], 2);

$verification = $user->checkGroup('sectionleader') ? 0 : $user->checkGroup('personnel') ? 1 : 2;

if (!$user->setEventAttendance($eventID)) { // no more leaders spamming attendance
    foreach ($_POST['ontime'] as $mem) {
        $mem = substr($mem, 2);
        $passedSQL = "INSERT INTO attendance (eventID, leaderID, memberID, attended, verified) VALUES ($eventID, $leader, $mem, 0, $verification)";
        mysqli_query($conn, $passedSQL);
    }

    foreach ($_POST['late'] as $mem) {
        $mem = substr($mem, 2);
        $lateSQL = "INSERT INTO attendance (eventID, leaderID, memberID, attended, verified) VALUES ($eventID, $leader, $mem, 1, $verification)";
        mysqli_query($conn, $lateSQL);
    }

    foreach ($_POST['noshow'] as $mem) {
        $mem = substr($mem, 2);
        $noshowSQL = "INSERT INTO attendance (eventID, leaderID, memberID, attended, verified) VALUES ($eventID, $leader, $mem, 2, $verification)";
        mysqli_query($conn, $noshowSQL);
    }
}