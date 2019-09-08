<?php
include_once "../header.php";
include_once "../db/evaluation.php";

$sqlSections = "'";
$sections = ['altosax', 'baritone', 'bassdrum', 'clarinet', 'colorguard', 'cymbals', 'flute', 'horn', 'snaredrum', 'sousaphone', 'tenordrums', 'tenorsax', 'trombone', 'trumpet', 'twirler'];
$sqlSections .= implode("', '", $sections);
$sqlSections .= "'";

$generateSectionSQL = "SELECT * FROM users WHERE section IN ($sqlSections) ORDER BY section, name";
$generateSection = mysqli_query($conn, $generateSectionSQL);

$getStationSQL = "SELECT * FROM stations ORDER BY number ASC";
$getStationQ = mysqli_query($conn, $getStationSQL);

// todo loop this and just add people to the proper section
// if no section found, create card
?>

<div class="container">
    <h4 class="center">Station Progress</h4>

    <div class="card-panel">
        <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input id="name_search" type="text" placeholder="name search">
        </div>
    </div>

    <?php
    $currentSection = "";
    while ($userLoop = $generateSection->fetch_assoc()) {
    $member = new user($conn);
    $member->setUsername($conn, $userLoop['username']);
    if ($currentSection == "") {
    $currentSection = $userLoop['section']; ?>

    <div class="card-panel">
        <h5 class="card-title"><?php echo $currentSection; ?></h5>
        <ul class="collapsible">
            <?php } else if ($userLoop['section'] != $currentSection) {
            $currentSection = $userLoop['section']; ?>
        </ul>
    </div>
    <div class="card-panel">
        <h5 class="card-title"><?php echo $currentSection; ?></h5>

        <ul class="collapsible">
            <?php } ?>

            <li>
                <div class="collapsible-header" id="<?php echo $member->getUsername(); ?>">
                    <span class="member"><?php echo $member->getName() ?></span>
                    <span class="right"><?php
                        $uid = $member->getID();

                        mysqli_data_seek($getStationQ, 0);
                        while ($station = $getStationQ->fetch_assoc()) {

                            $progress = $member->getStationProgress($station['stationID']);
                            $star = "star_border"; // set here in case no entries
                            if ($progress == 2) {
                                $star = "star";
                            } else if ($progress == 1) {
                                $star = "star_half";
                            }

                            echo "<a class='black-text material-icons' href='#!' title='" . $station['name'] . "'>" . $star . "</a>";
                        }

                        ?></span>
                </div>
                <div class="collapsible-body">
                    <div class="collection"><?php
                        mysqli_data_seek($getStationQ, 0);
                        while ($station = $getStationQ->fetch_assoc()) {
                            $evals = new evaluation($conn, $station['stationID'], $member->getID());

                            $progress = $member->getStationProgress($station['stationID']);
                            $star = "star_border"; // set here in case no entries
                            if ($progress == 2) {
                                $star = "star";
                            } else if ($progress == 1) {
                                $star = "star_half";
                            } ?>

                            <div class="collection-item valign-wrapper row">
                                <i class='material-icons col'><?php echo $star; ?></i>
                                <span class='station-name col'><?php echo $station['name']; ?> </span><?php
                                if ($evals->getProgID() != null) {  // todo make this link actually go to the station they click
                                    ?><a class='attempts col'
                                         href='attempt.php?id=a_<?php echo $evals->getProgID(); ?>'><?php echo $evals->getEvalCount(); ?>
                                    attempts</a><?php
                                } else { ?>
                                    <span class='attempts col'>0 attempts</span>
                                <?php }

                                $progEval = $evals->getProgressEvaluator();
                                if ($progEval != null) {
                                    //echo "evaluated by <a class='leader-passed col' href='member.php'>" . $progEval . "</a>";
                                }
                                ?></div>
                        <?php }
                        ?></div>
                </div>
            </li>
        <?php
        } ?></div>


</div>

<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="../js/members.js"></script>