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
                <li class="collection-item" id="<?php echo 'e_' . $event->getID(); ?>">
                    <!-- <span class="dt"><?php //echo $event->getTime(); ?></span> - -->
                    <span><?php echo $event->getTitle(); ?></span>
                    <span class="hidden-actions right">
                        <a class='edit-station black-text'
                           href='edit_event.php?id=<?php echo 'e_' . $event->getID(); ?>'><i
                                    class="material-icons">edit</i></a>
                        <a class='delete-station black-text' href='#'><i class="material-icons">delete</i></a></span>
                </li>
            <?php } ?>
        </ul>

        <div class="right-align">
            <a class="btn-floating btn waves-effect waves-light yellow darken-3 create-event">
                <i class="material-icons">add</i></a>
        </div>
    </div>

    <div class="card-panel">
        <h5 class="card-title">Past Events</h5>
        <ul class="collection" id="events">
            <?php while ($e = $pastEventsQ->fetch_assoc()) {
                $event = new event($conn, $e['eventID']);

                ?>
                <li class="collection-item" id="<?php echo 'e_' . $event->getID(); ?>">
                    <!-- <span class="dt"><?php // echo $event->getTime(); ?></span> - -->
                    <span><?php echo $event->getTitle(); ?></span>
                    <span class="hidden-actions right">
                        <a class='edit-station black-text'
                           href='edit_event.php?id=<?php echo 'e_' . $event->getID(); ?>'><i
                                    class="material-icons">edit</i></a>
                        <a class='delete-station black-text' href='#'><i class="material-icons">delete</i></a></span>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>


<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/attendance.js'; ?>"></script>
