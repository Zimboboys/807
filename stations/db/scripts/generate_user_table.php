<?php

include_once("../connect.php");
$sections = ['altosax', 'baritone', 'bassdrum', 'clarinet', 'colorguard', 'cymbals', 'flute', 'horn', 'snaredrum', 'sousaphone', 'tenordrums', 'tenorsax', 'trombone', 'trumpet', 'twirler'];
$htgroups = file_get_contents("/var/www/html/mband.calpoly.edu/.htgroup"); // set to proper path
$groups = explode("\n", $htgroups);

foreach ($groups as $line => $member) {

    // section = $divided[0]
    // members = $divided[1]
    $divided = explode(':', $member);
    if (in_array($divided[0], $sections)) {
        $mems = explode(" ", $divided[1]);
        array_shift($mems);
        foreach ($mems as $m) {
            $m = mysqli_real_escape_string($conn, $m);

            // will not duplicate due to username being unique
            $addUserSQL = "INSERT INTO users (username, section) VALUES ('$m', '$divided[0]')";
            mysqli_query($conn, $addUserSQL);
        }
    }
}