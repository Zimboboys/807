<?php

include_once "../../connect.php";

// gross, but passes all the necessary info

// [
//      'groupName' =>
//          [
//              'checklistID' => 'checklist_text',
//              'checklistID' => 'checklist_text',
//              ...
//          ],
//  ]
$stationData = [];

$getGroupSQL = "SELECT * FROM station_checklist_group WHERE stationID = " . $_GET['id'];
$getGroupQ = mysqli_query($conn, $getGroupSQL);

$groups = [];
while ($getGroup = $getGroupQ->fetch_assoc()) {
    $groups[$getGroup['groupID']] = $getGroup['title'];
}

foreach ($groups as $gID => $gTitle) {
    $groupInfo = [];
    $getChecklistInfoSQL = "SELECT * FROM station_checklist_item WHERE stationGroupID = $gID";
    $getChecklistInfoQ = mysqli_query($conn, $getChecklistInfoSQL);
    while ($getChecklistInfo = $getChecklistInfoQ->fetch_assoc()) {
        $groupInfo[$getChecklistInfo['checklistID']] = $getChecklistInfo['item'];
    }
    $stationData[$gTitle] = $groupInfo;
}

echo json_encode($stationData);