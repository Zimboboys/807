<?php

include_once("../header.php");
include_once("../db/event.php");

$eventID = substr($_GET['id'], 2);
$event = new event($conn, $eventID);

$groupSQL = "SELECT * FROM attendance_groups";
$groups = mysqli_query($conn, $groupSQL);
?>

<div class="container">
    <div class="row center-align">
        <div class="event-title">
            <span class="header h4" id="event-name"><?php echo $event->getTitle(); ?></span>

            <a class='edit-event-name black-text' href='#!'>
                <i class="material-icons large-edit-pencil">edit</i></a></div>
    </div>

    <div class="card-panel">
        <h5 class="card-title">Date/time</h5>
        <input type="text" id="date" placeholder="Date/time" value="<?php echo $event->getTime(); ?>">
        <p>Please enter in the following format: (Day Month Year Time Timezone)<br>
            Example: 07 August 2019 9:18 PM PDT<br>
            Note: This is will be updated soon to make it easier :)
        </p>
    </div>

    <div class="card-panel" id="attendance_groups">
        <h5 class="card-title">Attendance Group</h5><?php
        while ($group = $groups->fetch_assoc()) {
            $groupID = 'g_' . $group['groupID']; ?>
            <span>
            <input type="checkbox" class="filled-in" id="<?php echo $groupID; ?>" <?php if ($event->containsGroup($group['groupID'])) {echo 'checked=checked'; }?>>
                    <label for="<?php echo $groupID; ?>"><?php echo $group['groupName']; ?></label>
            </span><?php }
        ?></div>


    <div class="right-align">
        <span id="error"></span>
        <a class="btn-floating btn waves-effect waves-light yellow darken-3 update-event">
            <i class="material-icons">save</i></a>
    </div>
</div>


<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/attendance.js'; ?>"></script>
