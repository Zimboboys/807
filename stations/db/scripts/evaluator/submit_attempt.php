<?php

include_once "../../connect.php";
include_once "../../user.php";
include_once "../../station.php";

$user = new user($conn);

$leader = $user->getID();
$member = substr($_POST['memberID'], 2);
$stationID = substr($_POST['stationID'], 2);

$station = new station($conn, $stationID);

$passed = "";
foreach ($_POST['passedChecks'] as $item) {
    $passed .= substr($item, 2) . ".";
}
$passed = rtrim($passed, '.');

$failed = "";
foreach ($_POST['failedChecks'] as $item) {
    $failed .= substr($item, 2) . ".";
}
$failed = rtrim($failed, '.');

$pushAttemptSQL = "INSERT INTO station_attempts (leaderID, memberID, stationID, passedChecks, failedChecks) " .
    "VALUES ($leader, $member, $stationID, '$passed', '$failed')";
mysqli_query($conn, $pushAttemptSQL);
$attemptID = mysqli_insert_id($conn);


// update station progress
$failedItems = explode('.', $failed);
$maxFailed = $station->getMaxFailed();
$requiredItems = $station->getRequiredItems();

$failedRequired = false;
foreach ($failedItems as $item) {
    if (in_array($item, $requiredItems)) {
        $failedRequired = true;
    }
}

$status = 1; // made an attempt
if ((count($failedItems) <= $maxFailed || $failedItems[0] == null)&& !$failedRequired) { // successful attempt
    echo "passed";
    $status = 2;
} else {
    echo "failed";
}

$progressID = null;
$origStatus = 0;
$getProgressSQL = "SELECT * FROM station_progress WHERE userID = $member AND stationID = $stationID";
$getProgressQ = mysqli_query($conn, $getProgressSQL);
while ($progress = $getProgressQ->fetch_assoc()) {
    $progressID = $progress['progressID'];
    $origStatus = $progress['progress'];
}

if ($origStatus <= $status) {
    if ($progressID != null) {
        $updateProgressSQL = "UPDATE station_progress SET progress=$status, evaluationID=$attemptID WHERE progressID=$progressID";
    } else {
        $updateProgressSQL = "INSERT INTO station_progress (userID, evaluationID, stationID, progress) " .
            "VALUES ($member, $attemptID, $stationID, $status)";
    }
    mysqli_query($conn, $updateProgressSQL);
}