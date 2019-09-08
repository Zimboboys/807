<?php
include_once "../header.php";

$sqlSections = "'";
$sections = ['altosax', 'baritone', 'bassdrum', 'clarinet', 'colorguard', 'cymbals', 'flute', 'horn', 'snaredrum', 'sousaphone', 'tenordrums', 'tenorsax', 'trombone', 'trumpet', 'twirler'];
$sqlSections .= implode("', '", $sections);
$sqlSections .= "'";

$generateSectionSQL = "SELECT * FROM users WHERE section IN ($sqlSections) ORDER BY section, name";
$generateSection = mysqli_query($conn, $generateSectionSQL);

$getGroupsSQL = "SELECT * FROM attendance_groups";
$getGroups = mysqli_query($conn, $getGroupsSQL);

?>

<div class="container">
    <h4 class="center">Assign Attendance Groups</h4>

    <!-- todo fix this search
    <div class="card-panel">
        <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input id="name_search" type="text" placeholder="name search">
        </div>
    </div> -->

    <?php
    $currentSection = "";
    while ($userLoop = $generateSection->fetch_assoc()) {
    $member = new user($conn);
    $member->setUsername($conn, $userLoop['username']);
    if ($currentSection == "") {
    $currentSection = $userLoop['section']; ?>

    <div class="card-panel">
        <h5 class="card-title"><?php echo $currentSection; ?></h5>
        <ul class="collection">
            <?php } else if ($userLoop['section'] != $currentSection) {
            $currentSection = $userLoop['section']; ?>
        </ul>

        <div class="right-align">
            <a class="btn waves-effect waves-light yellow darken-3 save-attendance-assignment">
                <i class="material-icons">save</i>
            </a>
        </div>
    </div>
    <div class="card-panel">
        <h5 class="card-title"><?php echo $currentSection; ?></h5>

        <ul class="collection">
            <?php } ?>
            <li class="collection-item" id="<?php echo $member->getUsername(); ?>">
                <span class="member"><?php echo $member->getName() ?></span>
                <form class="right" action="#"><?php

                    mysqli_data_seek($getGroups, 0);
                    while ($groups = $getGroups->fetch_assoc()) {
                        $clickID = 'u_' . $member->getID() . '_b_' . $groups['groupID'];

                        ?>
                        <span>
                            <input type="checkbox" class="filled-in" id="<?php echo $clickID; ?>" <?php if ($member->checkBand($groups['groupID'])) echo "checked='checked'";  ?>>
                            <label for="<?php echo $clickID; ?>"><?php echo $groups['groupName']; ?></label>
                        </span>
                    <?php }
                    ?></form>
            </li><?php
        } ?></div>


</div>

<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="../js/attendance.js"></script>