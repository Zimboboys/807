<?php
include_once "../header.php";
include_once "../db/event.php";

$pastEventsSQL = "SELECT * FROM events WHERE datetime < CURDATE() ORDER BY datetime DESC";
$pastEventsQ = mysqli_query($conn, $pastEventsSQL);

$futureEventsSQL = "SELECT * FROM events WHERE datetime >= CURDATE() ORDER BY datetime ASC";
$futureEventsQ = mysqli_query($conn, $futureEventsSQL);

?>


<div class="container">
    <h4 class="center">Event View</h4>

    <div class="card-panel">
        <h5 class="card-title">Upcoming Events</h5>
        <ul class="collection" id="events">
            <?php while ($e = $futureEventsQ->fetch_assoc()) {
                $event = new event($conn, $e['eventID']);
                ?>
                <li class="collection-item row" id="<?php echo 'e_' . $event->getID(); ?>">
                    <span class="datetime"><?php echo $event->getTime(); ?></span> -
                    <span><?php echo $event->getTitle(); ?></span>
                    <?php if ($event->hasBand($user->getBands())) { ?>
                        <strong class="red-text text-lighten-1">*</strong>
                    <?php } ?>

                    <?php

                    // section leaders can only see when it is 15before - 20after the event
                    if (
                            ($user->checkGroup("drummajor") || $user->checkGroup("personnel") || $user->checkGroup("director"))
                            ||
                            ($user->checkGroup("sectionleader") && $event->openAttendance())
                    ) { ?>
                        <a href="attendance/?id=<?php echo 'e_' . $event->getID(); ?>" class="secondary-content"><i class="material-icons">assignment_ind</i></a>
                    <?php }
                    ?>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="card-panel">
        <h5 class="card-title">Past Events</h5>
        <ul class="collection" id="events">
            <?php while ($e = $pastEventsQ->fetch_assoc()) {
                $event = new event($conn, $e['eventID']);
                ?>
                <li class="collection-item row" id="<?php echo 'e_' . $event->getID(); ?>">
                    <span class="datetime"><?php echo $event->getTime(); ?></span> -
                    <span><?php echo $event->getTitle(); ?></span>
                    <?php if ($event->hasBand($user->getBands())) { ?>
                        <strong class="red-text text-lighten-1">*</strong>
                    <?php } ?>

                    <?php // section leaders cannot see
                    if (($user->checkGroup("drummajor") || $user->checkGroup("personnel") || $user->checkGroup("director"))) { ?>
                        <a href="attendance/?id=<?php echo 'e_' . $event->getID(); ?>" class="secondary-content"><i class="material-icons">assignment_ind</i></a>
                    <?php }
                    ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>


<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/attendance.js'; ?>"></script>
