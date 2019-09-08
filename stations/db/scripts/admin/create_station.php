<?php

include_once("../../connect.php");

$stationName = mysqli_real_escape_string($conn, $_POST["stationName"]);
$stationNumber = $_POST["stationNum"];
$olderSibling = $stationNumber - 1;

$createStationSQL = "INSERT INTO stations (name, number, olderSibling) VALUES ('$stationName', $stationNumber, $olderSibling)";
mysqli_query($conn, $createStationSQL);
$stationID = mysqli_insert_id($conn); // returns ID of entry

$fillerGroupSQL = "INSERT INTO station_checklist_group (stationID, title) VALUES ($stationID, 'example grouping')";
mysqli_query($conn, $fillerGroupSQL);
$groupID = mysqli_insert_id($conn);

$fillerItemSQL = "INSERT INTO station_checklist_item (stationGroupID, item, olderSibling) VALUES ($groupID, 'example item', 0)";
mysqli_query($conn, $fillerItemSQL);

/*
 * a bunch of information for members, evaluator (leaders), and instructors
 */

// gen station info
$fillerInfoSQL = "INSERT INTO station_info (stationID, infoText) VALUES ($stationID, 'station info here')";
mysqli_query($conn, $fillerInfoSQL);

// instructor, environment
$fillerPacketSQL = "INSERT INTO station_packet (stationID, role, purpose) VALUES ($stationID, 'instructor', 'environment')";
mysqli_query($conn, $fillerPacketSQL);
$instructionID = mysqli_insert_id($conn);
$fillerContentSQL = "INSERT INTO station_packet_content (instructionID, commandText) VALUES ($instructionID, 'instructor environment setup explanation')";
mysqli_query($conn, $fillerContentSQL);

// instructor, script
$fillerPacketSQL = "INSERT INTO station_packet (stationID, role, purpose) VALUES ($stationID, 'instructor', 'script')";
mysqli_query($conn, $fillerPacketSQL);
$instructionID = mysqli_insert_id($conn);
$fillerContentSQL = "INSERT INTO station_packet_content (instructionID, commandText) VALUES ($instructionID, 'instructor script')";
mysqli_query($conn, $fillerContentSQL);

// evaluator, environment
$fillerPacketSQL = "INSERT INTO station_packet (stationID, role, purpose) VALUES ($stationID, 'evaluator', 'environment')";
mysqli_query($conn, $fillerPacketSQL);
$instructionID = mysqli_insert_id($conn);
$fillerContentSQL = "INSERT INTO station_packet_content (instructionID, commandText) VALUES ($instructionID, 'evaluator environment setup explanation')";
mysqli_query($conn, $fillerContentSQL);

// evaluator, script
$fillerPacketSQL = "INSERT INTO station_packet (stationID, role, purpose) VALUES ($stationID, 'evaluator', 'script')";
mysqli_query($conn, $fillerPacketSQL);
$instructionID = mysqli_insert_id($conn);
$fillerContentSQL = "INSERT INTO station_packet_content (instructionID, commandText) VALUES ($instructionID, 'evaluator script')";
mysqli_query($conn, $fillerContentSQL);


echo $stationID;