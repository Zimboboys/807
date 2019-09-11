<?php
include_once "header.php";
include_once "db/station.php";

$getStationSQL = "SELECT * FROM stations ORDER BY number ASC";
$getStationQ = mysqli_query($conn, $getStationSQL);

?>

    <div class="container">
        <div class="card-panel row">
            <h5 class="card-title">Stations</h5>
            <div class="card-content">
                <p>Stations are designed as a way to ensure all marching band members are able to perform to a high
                    standard.</p>
                <p>More info regarding each station below</p>
                <strong>Your progress</strong>
                <ul class="collection" id="stations"><?php
                    while ($stationLoop = $getStationQ->fetch_assoc()) {
                        $station = new station($conn, $stationLoop['stationID']);
                        ?><a class="collection-item black-text" href="<?php echo '#' . $station->getID(); ?>">
                        <span class="station-name"><?php echo $station->getStationName(); ?></span>
                        <span class="right"><i class="material-icons"><?php
                                $progress = $user->getStationProgress($station->getID());
                                if ($progress == 0) {
                                    echo "star_border";
                                } else if ($progress == 1) {
                                    echo "star_half";
                                } else if ($progress == 2) {
                                    echo "star";
                                } ?></i></span>
                        </a>
                    <?php } ?>
                </ul>
                <div class="right">
                    <div class="valign-wrapper"><i class="material-icons">star_border</i> no attempt</div>
                    <div class="valign-wrapper"><i class="material-icons">star_half</i> attempted</div>
                    <div class="valign-wrapper"><i class="material-icons">star</i> passed</div>
                </div>
            </div>
        </div>
    </div>
<?php
mysqli_data_seek($getStationQ, 0); // reset the while loop
while ($stationLoop = $getStationQ->fetch_assoc()) {
    $station = new station($conn, $stationLoop['stationID']); ?>

    <div class="container" id="<?php echo $station->getID(); ?>">
        <div class="card row">
            <div class="card-content">
                <h5 class="card-title"><?php echo $station->getStationName(); ?></h5>
                <span class="general-info"><?php echo preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $station->getInfoText()); ?></span>
            </div>
            <?php if ($user->checkGroup('evaluator')) { ?>
                <!-- <div class="card-action">
                <a href="station_info.php?sid=<?php echo $station->getID(); ?>#instructor">Instructor Info</a>
                <a href="station_info.php?sid=<?php echo $station->getID(); ?>#evaluator">Evaluator Info</a>
            </div> -->

                <div class="card-tabs">
                    <ul class="collapsible">
                        <li>
                            <div class="collapsible-header">Instructor Info</div>
                            <div class="collapsible-body general-info"><?php

                                echo "<h5>Environment</h5>";
                                $packet = $station->getPacketContent('instructor', 'environment');
                                foreach ($packet as $id => $content) {
                                    echo $content == null ? "no info provided" : preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $content);
                                }

                                echo "<h5>Script</h5>";
                                $packet = $station->getPacketContent('instructor', 'script');
                                foreach ($packet as $id => $content) {
                                    echo $content == null ? "no info provided" : preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $content);
                                }

                                ?></div>
                        </li>
                        <li>
                            <div class="collapsible-header">Evaluator Info</div>
                            <div class="collapsible-body general-info"><?php

                                echo "<h5>Environment</h5>";
                                $packet = $station->getPacketContent('evaluator', 'environment');
                                foreach ($packet as $id => $content) {
                                    echo $content == null ? "no info provided" : preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $content);
                                }

                                echo "<h5>Script</h5>";
                                $packet = $station->getPacketContent('evaluator', 'script');
                                foreach ($packet as $id => $content) {
                                    echo $content == null ? "no info provided" : preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $content);
                                }

                                ?></div>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>

<?php } ?>

<?php include_once "footer.php"; ?>