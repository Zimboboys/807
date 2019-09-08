<?php

include_once("../../connect.php");

$add = [];
$remove = [];
// array carries band ==> all users to add/remove from it

foreach ($_POST['checked'] as $i => $c) {
    $entry = explode('_', $c);
    $add[$entry[3]] .= $entry[1] . '.';
}

foreach ($_POST['unchecked'] as $i => $c) {
    $entry = explode('_', $c);
    $remove[$entry[3]] .= $entry[1] . '.';
}

// generate the SQL strings

$addSQL = "INSERT INTO member_groups (userID, groupID) VALUES ";
foreach ($add as $band => $userList) {
    $u = rtrim($userList, '.');
    $users = explode('.', $u);

    foreach ($users as $i => $u) {
        $addSQL .= "($u, $band), ";
    }
}

$addSQL = rtrim($addSQL, ', ');
$addSQL .= ';';

mysqli_query($conn, $addSQL);


$removeSQL = "DELETE FROM member_groups WHERE ";
foreach ($remove as $band => $userList) {
    $u = rtrim($userList, '.');
    $users = explode('.', $u);

    foreach ($users as $i => $u) {
        $removeSQL .= "(userID = $u AND groupID = $band) OR ";
    }
}

$removeSQL = rtrim($removeSQL, ' OR ');
$removeSQL .= ';';

mysqli_query($conn, $removeSQL);