<?php
include_once "../header.php";

$sqlSections = "'";
$sections = ['altosax', 'baritone', 'bassdrum', 'clarinet', 'colorguard', 'cymbals', 'flute', 'horn', 'snaredrum', 'sousaphone', 'tenordrums', 'tenorsax', 'trombone', 'trumpet', 'twirler'];
$sqlSections .= implode("', '", $sections);
$sqlSections .= "'";

$generateSectionSQL = "SELECT * FROM users WHERE section IN ($sqlSections) ORDER BY section, name";
$generateSection = mysqli_query($conn, $generateSectionSQL);

?>

<div class="container">
    <h4 class="center">View/edit Members</h4>

    <div class="card-panel">
        <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input id="name_search" type="text" placeholder="name search">
            TODO fix this search <!-- todo fix simple search -->
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
        <ul class="collection">
            <?php } else if ($userLoop['section'] != $currentSection) {
            $currentSection = $userLoop['section']; ?>
        </ul>
    </div>
    <div class="card-panel">
        <h5 class="card-title"><?php echo $currentSection; ?></h5>

        <ul class="collection">
            <?php } ?>
            <li class="collection-item" id="<?php echo $member->getUsername(); ?>">
                <span class="member"><?php echo $member->getName() ?></span>
                <a class="right hidden-actions black-text material-icons change-name" href="#!">edit</a></li><?php
        } ?></div>


</div>

<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="../js/members.js"></script>