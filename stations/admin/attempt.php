<?php

include_once "../header.php";
include_once "../db/station.php";

$getStationSQL = "SELECT * FROM stations";
$getStationQ = mysqli_query($conn, $getStationSQL);

$targetID;
$getTargetSQL = "SELECT * FROM station_attempts WHERE evaluationID = " . substr($_GET['id'], 2);
$targetQ = mysqli_query($conn, $getTargetSQL);
while ($target = $targetQ->fetch_assoc()) {
    $targetID = $target['memberID'];
}

$target = new user($conn);
$target->setID($conn, $targetID);
?>

<div class="container">
    <div class="card-panel row">
        <h5 class="card-title target-name" id="u_<?php echo $target->getID(); ?>"><?php echo $target->getName(); ?></h5>
        <div class="card-action">
            <a class='dropdown-button btn col green darken-2 s12 m12 l12' href='#' data-activates='station_dropdown'>
                <i class="material-icons left">d</i><span class="dropdown-name">Station Select</span><i class="material-icons right">expand_more</i></a>
        </div>
    </div>

    <ul id='station_dropdown' class='dropdown-content'>
        <?php while ($stationLoop = $getStationQ->fetch_assoc()) {
            $station = new station($conn, $stationLoop['stationID']); ?>
            <li><a href="#!" class="station-select black-text" id="<?php echo $station->getID(); ?>">
                    <span class="station-name"><?php echo $station->getStationName(); ?></span>
                    <span class="right"><i class="material-icons"><?php
                            $progress = $target->getStationProgress($station->getID());
                            if ($progress == 0) {
                                echo "star_border";
                            } else if ($progress == 1) {
                                echo "star_half";
                            } else if ($progress == 2) {
                                echo "star";
                            }
                            ?></i></span>
                </a></li>
            <li class="divider"></li>
        <?php } ?>
    </ul>

    <div class="station-info"></div>

</div>
<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/admin.js'; ?>"></script>