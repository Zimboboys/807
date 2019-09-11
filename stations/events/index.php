<?php
include_once "../header.php";
include_once "../db/event.php";

// $user->setUsername($conn, 'afmaxwel');  // random section leader
// $user->setUsername($conn, 'rsplitge'); // random personnel manager

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
                    <!-- <span class="dt"><?php //echo $event->getTime(); ?></span> - -->
                    <span><?php echo $event->getTitle(); ?></span>
                    <?php if ($event->hasBand($user->getBands())) { ?>
                        <strong class="red-text text-lighten-1">*</strong>
                    <?php } ?>

                    <?php
                    if ($user->checkGroup('sectionleader')) {

                        $userSections = [];
                        $sections = ['altosax', 'baritone', 'bassdrum', 'clarinet', 'colorguard', 'cymbals', 'flute', 'horn', 'snaredrum', 'sousaphone', 'tenordrums', 'tenorsax', 'trombone', 'trumpet', 'twirler'];
                        foreach ($user->getGroups() as $group) {
                            if (in_array($group, $sections)) {
                                array_push($userSections, $group);
                            }
                        }
                        $sqlSections = "'";
                        $sqlSections .= implode("', '", $userSections);
                        $sqlSections .= "'";


                        $eid = $event->getID();

                        // for section leader duplication check
                        $findAttendanceSQL = "SELECT * FROM attendance INNER JOIN users ON attendance.memberID = users.userID WHERE eventID = $eid AND users.section IN ($sqlSections)";
                        $findAttendance = mysqli_query($conn, $findAttendanceSQL);
                    }


                    // see $event->openAttendance for times where events are open
                    if (
                        ($user->checkGroup("director"))
                        ||
                        ($user->checkGroup("sectionleader") && $event->openAttendance('sectionleader') && mysqli_num_rows($findAttendance) == 0)
                        ||
                        ($user->checkGroup('personnel') && $event->openAttendance('personnel'))
                        ||
                        ($user->checkGroup('drummajor') && $event->openAttendance('drummajor'))
                    ) { ?>
                        <a href="attendance/?id=<?php echo 'e_' . $event->getID(); ?>" class="secondary-content"><i
                                    class="material-icons">assignment_ind</i></a>
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
                    <!-- todo change class to 'datetime' ---   also in admin -->
                    <!-- <span class="dt"><?php //echo $event->getTime(); ?></span> - -->
                    <span><?php echo $event->getTitle(); ?></span>
                    <?php if ($event->hasBand($user->getBands())) { ?>
                        <strong class="red-text text-lighten-1">*</strong>
                    <?php } ?>

                    <?php // section leaders cannot see
                    if (($user->checkGroup("drummajor") || $user->checkGroup("personnel") || $user->checkGroup("director"))) { ?>
                        <a href="attendance/?id=<?php echo 'e_' . $event->getID(); ?>" class="secondary-content"><i
                                    class="material-icons">assignment_ind</i></a>
                    <?php }
                    ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>


<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/attendance.js'; ?>"></script>
