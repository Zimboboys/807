<?php

include_once "db/connect.php";
require_once "db/user.php";

$user = new User($conn);
date_default_timezone_set('America/Los_Angeles');
$homeDIR = substr(dirname(__DIR__, 1), 13);

?>

<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?php echo $homeDIR . '/stations/css/materialize.css'; ?>"
          media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?php echo $homeDIR . '/stations/css/custom.css' ?>">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body class="green lighten-4">
<nav>
    <div class="nav-wrapper green darken-4">
        <a href="../" class="brand-logo truncate">Mustang Band</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="<?php echo $homeDIR; ?>">Home</a></li>
            <li><a href="<?php echo $homeDIR . '/stations/events'; ?>">Events</a></li>
            <li><a href="<?php echo $homeDIR . '/stations'; ?>">Stations</a></li>
            <?php if ($user->checkGroup("drummajor") || $user->checkGroup("evaluator") || $user->checkGroup("director")) { ?>
                <li><a href='<?php echo $homeDIR . '/stations/evaluations/'; ?>'>Evaluate</a></li>
            <?php }
            if ($user->checkGroup("drummajor") || $user->checkGroup("director")) { ?>
                <li><a href='<?php echo $homeDIR . '/stations/admin/'; ?>'>Overview</a></li>
            <?php } ?>
        </ul>
        <ul class="side-nav" id="mobile-demo">
            <li><a href="<?php echo $homeDIR; ?>">Home</a></li>
            <li><a href="<?php echo $homeDIR . '/stations/events'; ?>">Events</a></li>
            <li><a href="<?php echo $homeDIR . '/stations'; ?>">Stations</a></li>
            <?php if ($user->checkGroup("drummajor") || $user->checkGroup("evaluator") || $user->checkGroup("director")) { ?>
                <li><a href='<?php echo $homeDIR . '/stations/evaluations/'; ?>'>Evaluate</a></li>
            <?php }
            if ($user->checkGroup("drummajor") || $user->checkGroup("director")) { ?>
                <li><a href='<?php echo $homeDIR . '/stations/admin/'; ?>'>Overview</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>