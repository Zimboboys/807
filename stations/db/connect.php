<?php

$server = "localhost";
$username = "root";
$password = "4crU5rXa8G47";
$db = "807_app";

// Create connection
$conn = new mysqli($server, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

