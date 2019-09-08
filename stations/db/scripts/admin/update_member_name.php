<?php

include_once("../../connect.php");

$username = $_POST["id"];
$name = mysqli_real_escape_string($conn, $_POST["name"]);

$updateInfoSQL = "UPDATE users SET name = '$name' WHERE username='$username'";
mysqli_query($conn, $updateInfoSQL);