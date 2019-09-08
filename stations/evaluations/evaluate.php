<?php

include_once "../header.php";
include_once "../db/station.php";

$getStationSQL = "SELECT * FROM stations ORDER BY number ASC";
$getStationQ = mysqli_query($conn, $getStationSQL);

$target = new user($conn);
$target->setUsername($conn, $_GET['id']);

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
                            // todo make stars and text NOT overlap (see evaluation on station 5)
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

    <div id="unfilled-items" class="modal">
        <div class="modal-content">
            <h4>You didn't complete the assessment</h4>
            <p>You have to do this before you submit an attempt</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="modal-close waves-effect waves-gree btn-flat">Ok</a>
        </div>
    </div>
</div>
<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/evaluate.js'; ?>"></script>