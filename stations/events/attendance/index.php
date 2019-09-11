<?php

include_once "../../header.php";
include_once "../../db/event.php";

// $user->setUsername($conn, 'mchaffin');
$event = new event($conn, substr($_GET['id'], 2));

$userSections = [];
$sections = ['altosax', 'baritone', 'bassdrum', 'clarinet', 'colorguard', 'cymbals', 'flute', 'horn', 'snaredrum', 'sousaphone', 'tenordrums', 'tenorsax', 'trombone', 'trumpet', 'twirler'];

if ($user->checkGroup('sectionleader')) {
    foreach ($user->getGroups() as $group) {
        if (in_array($group, $sections)) {
            array_push($userSections, $group);
        }
    }
} else if ($user->checkGroup('personnel')) {
    $getPersonnelSectionsSQL = "SELECT * FROM personnel_sections WHERE userID = " . $user->getID();
    $getPersonnelSections = mysqli_query($conn, $getPersonnelSectionsSQL);
    while ($group = $getPersonnelSections->fetch_assoc()) {
        array_push($userSections, $group['section']);
    }
} else { // drum majors see all of them
    $userSections = $sections;
}

$sqlSections = "'";
$sqlSections .= implode("', '", $userSections);
$sqlSections .= "'";

$eventGroups = implode(", ", $event->getGroups());

// limits section leader view as well as allows for broad DM/personnel manager view
$generateMembersSQL = "SELECT DISTINCT users.userID, username, name, section FROM users "
    . "INNER JOIN member_groups ON users.userID = member_groups.userID WHERE "
    . "section IN ($sqlSections) AND groupID IN ($eventGroups) ORDER BY section, name, username";
$generateMembersQ = mysqli_query($conn, $generateMembersSQL);

?>

<div class="container">
    <h4 class="center">Attendance</h4>
    <h5 class="center event-title" id="<?php echo 'e_' . $event->getID(); ?>"><?php echo $event->getTitle(); ?></h5>

    <?php if (!$user->checkGroup('sectionleader')) { ?> <!-- todo fix this search box
        <div class="card-panel">
            <div class="input-field">
                <i class="material-icons prefix">search</i>
                <input id="name_search" type="text" placeholder="name search">
            </div>
        </div> -->
    <?php } ?>

    <?php
    $currentSection = "";
    while ($userLoop = $generateMembersQ->fetch_assoc()) {
    $member = new user($conn);
    $member->setUsername($conn, $userLoop['username']);

    $memid = $member->getID();
    $eventid = $event->getID();

    $getEventAttendanceSQL = "SELECT * FROM attendance WHERE eventID = $eventid AND memberID = $memid ORDER BY attendanceID DESC LIMIT 1";
    $getEventAttendance = mysqli_query($conn, $getEventAttendanceSQL);


    if ($currentSection == "") {
    $currentSection = $userLoop['section']; ?>

<div class="card-panel">
<h5 class="card-title"><?php echo $currentSection; ?></h5>
<ul class="collection" id="stations">
<?php } else if ($userLoop['section'] != $currentSection) {
$currentSection = $userLoop['section']; ?>
<!-- todo add an autofilling write in for other section members -->

</ul>
</div>
    <div class="card-panel">
        <h5 class="card-title"><?php echo $currentSection; ?></h5>

        <ul class="collection" id="stations">
            <?php }
            $hasAttendance = false;
            $showLate = ($user->checkGroup('personnel') || $user->checkGroup('drummajor'));
            while ($memAttendance = $getEventAttendance->fetch_assoc()) { // hopefully this pulls last one
                $hasAttendance = true;

                if ($memAttendance['attended'] == 0 && $user->checkGroup('sectionleader')) { ?>
                    <li class="collection-item black-text on-time" id="<?php echo 'u_' . $userLoop['userID']; ?>">
                        <?php echo $member->getName() ?>
                        <span class="right">
                            <a class="no-show-item black-text" href="#!"><i class="material-icons">remove_circle_outline</i></a>
                                <!-- <a class="late-item black-text" href="#!"><i class="material-icons">hourglass_empty</i></a> -->
                            <a class="on-time-item green-text text-darken-1" href="#!"><i class="material-icons">check_circle</i></a>
                        </span>
                    </li>
                <?php } else if ($memAttendance['attended'] == 1) { ?>
                    <li class="collection-item black-text late" id="<?php echo 'u_' . $userLoop['userID']; ?>">
                        <?php echo $member->getName() ?>
                        <span class="right <?php if ($showLate) echo 'personnel'; ?>">
                            <a class="no-show-item black-text" href="#!"><i class="material-icons">remove_circle_outline</i></a>
                            <?php if ($showLate) { ?><a class="late-item blue-text text-darken-2" href="#!"><i class="material-icons">hourglass_full</i></a><?php } ?>
                            <a class="on-time-item black-text" href="#!"><i class="material-icons">check_circle_outline</i></a>
                        </span>
                    </li>
                <?php } else if ($memAttendance['attended'] == 2) { ?>
                    <li class="collection-item black-text no-show" id="<?php echo 'u_' . $userLoop['userID']; ?>">
                        <?php echo $member->getName() ?>
                        <span class="right <?php if ($showLate) echo 'personnel'; ?>">
                            <a class="no-show-item red-text text-darken-1" href="#!"><i class="material-icons">remove_circle</i></a>
                            <?php if ($showLate) { ?><a class="late-item black-text" href="#!"><i class="material-icons">hourglass_empty</i></a><?php } ?>
                            <a class="on-time-item black-text" href="#!"><i class="material-icons">check_circle_outline</i></a>
                        </span>
                    </li>
                <?php }
            }


            if (!$hasAttendance) { ?>
                <li class="collection-item black-text" id="<?php echo 'u_' . $userLoop['userID']; ?>">
                    <?php echo $member->getName() ?>
                    <span class="right <?php if ($showLate) echo 'personnel'; ?>">
                        <a class="no-show-item black-text" href="#!"><i class="material-icons">remove_circle_outline</i></a>
                        <?php if ($showLate) { ?><a class="late-item black-text" href="#!"><i class="material-icons">hourglass_empty</i></a><?php } ?>
                        <a class="on-time-item black-text" href="#!"><i class="material-icons">check_circle_outline</i></a>
                    </span>
                </li>
            <?php }
            } ?>
    </div>

    <?php
    // todo personnel manager check

    if (!$user->setEventAttendance($event->getID())) { ?>
        <div class='row'>
            <a class='submit-attendance waves-effect waves-light btn green darken-2 col s12 m12 l12'>submit attendance</a>
        </div>
    <?php } ?>
</div>


<div id="unfilled-items" class="modal">
    <div class="modal-content">
        <h4>You didn't mark all members</h4>
        <p>Make sure you mark all people in your section</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="modal-close waves-effect waves-gree btn-flat">Ok</a>
    </div>
</div>

<?php include_once("../../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/attendance.js'; ?>"></script>