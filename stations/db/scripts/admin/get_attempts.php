<?php

include_once "../../connect.php";
include_once "../../station.php";
include_once "../../user.php";

/*
 * {
 *  stationName: String,
 *  groups: [{
 *      groupName: String,
 *      items: [{
 *          itemName: String,
 *          attempts: [{
 *              evalutor: String,
 *              result: outcome
 *          }],
 *      }],
 *  }...],   <- groups are repeated todo fit all atempts into a smaller json package
 * }
*/

$station = new station($conn, $_GET['id']);
$userID = substr($_GET['u'], 2);

// todo limit amount of records pulled (for UI)
$getAttemptSQL = "SELECT * FROM station_attempts WHERE memberID = $userID AND stationID = " . $station->getID();
$getAttemptQ = mysqli_query($conn, $getAttemptSQL);

$loops = [];

while ($attempts = $getAttemptQ->fetch_assoc()) {
    $evaluator = new user($conn, $attempts['leaderID']);
    $passed = explode('.', $attempts['passedChecks']);
    $failed = explode('.', $attempts['failedChecks']);

    $groups = [];

    foreach ($station->getChecklistGroups() as $g) {
        $items = [];
        foreach ($station->getChecklistItems($g) as $i) {
            $result = 'na';
            if (in_array($i, $passed)) {
                $result = 'passed';
            } else if (in_array($i, $failed)) {
                $result = 'failed';
            }

            $time = new DateTime($attempts['attemptTime']);
            $timezone = new DateTimeZone("America/Los_Angeles"); //each user has their own timezone i store in a session (i.e. - "Americas/Los_Angeles")
            $time->setTimezone($timezone);

            $item = array("evaluator" => $evaluator->getName(), "result" => $result);
            $itemJSON = array("id" => "c_" . $i, "itemName" => $station->getItemName($i), "attempts" => $item, "time" => $time->format("Y-m-d H:i:s"));
            array_push($items, $itemJSON);
        }
        $groupJSON = array("id" => "g_" . $g, "groupName" => $station->getGroupName($g), "items" => $items);
        array_push($groups, $groupJSON);
    }

    array_push($loops, $groups);
}
echo $loops != null ? json_encode($loops) : "no data";

