<?php

include_once "../../connect.php";
include_once "../../user.php";

$user = new user($conn);
$user->changePassword($_POST['pw']);