<?php

include_once("../header.php");
include_once("../db/station.php");
include_once("../db/checklist.php");

$stationID = substr($_GET['id'], 2);
$station = new station($conn, $stationID);
?>

<div class="container">
    <div class="row center-align">
        <div class="station-title">
            <span class="header h4" id="station-name"><?php echo $station->getStationName(); ?></span>

            <a class='edit-station-name black-text' href='#!'>
                <i class="material-icons large-edit-pencil">edit</i></a></div>
        <div class="center-align" id="max-failed">max failed:
            <span id="num-max"><?php echo $station->getMaxFailed(); ?></span>
            <a class='edit-max-failed black-text' href='#!'>
                <i class="material-icons edit-pencil">edit</i></a></div>
    </div>

    <div class="row">
        <ul class="tabs">
            <div class="indicator green" style="z-index:1"></div>
            <li class="tab col s3"><a class="active black-text" href="#checklist_items">Checklist Items</a></li>
            <li class="tab col s3"><a class="black-text" href="#general_info">General Info</a></li>
            <li class="tab col s3"><a class="black-text" href="#instructor_info">Instructor Info</a></li>
            <li class="tab col s3"><a class="black-text" href="#evaluator_info">Evaluator Info</a></li>
        </ul>
    </div>

    <div id="checklist_items">
        <?php foreach ($station->getChecklistGroups() as $group => $item) { ?>
            <div class="row card-panel" id="<?php echo 'g_' . $item; ?>">
                <div class="card-title_<?php echo 'g_' . $item; ?>">
                    <span class="header"><?php echo $station->getGroupName($item); ?></span>
                    <span class="right">
                        <a class='edit-checklist-group black-text' href='#!' id='<?php echo 'g_' . $item; ?>'>
                            <i class="material-icons">edit</i></a>
                        <a class='delete-checklist-group black-text' href='#' id='<?php echo 'g_' . $item; ?>'>
                            <i class="material-icons">delete</i></a>
                    </span>
                </div>
                <ul class="card-content collection" id="<?php echo 'g_' . $item; ?>">
                    <?php foreach ($station->getChecklistItems($item) as $checklistID) {
                        $checklist = new checklist($conn, $checklistID); ?>
                        <li class="collection-item" id="<?php echo 'c_' . $checklistID; ?>">
                            <a class='set-required black-text' href='#!'><i
                                        class="star material-icons yellow-text text-darken-2"><?php echo $checklist->translateReq(); ?></i></a>
                            <span class="checklist-name"><?php echo $station->getItemName($checklistID); ?></span>
                            <span class="hidden-actions right">
                        <a class='edit-checklist-item black-text' href='#!' id='<?php echo 'c_' . $checklistID; ?>'>
                            <i class="material-icons">edit</i></a>
                        <a class='delete-checklist-item black-text' href='#' id='<?php echo 'c_' . $checklistID; ?>'>
                            <i class="material-icons">delete</i></a></span>
                        </li>
                    <?php } ?>
                </ul>

                <div class="right-align">
                    <a class="btn-floating btn waves-effect waves-light yellow darken-3 create-checklist-item">
                        <i class="material-icons">add</i></a>
                </div>
            </div>
        <?php } ?>
        <div class="row right-align bottom-button">
            <a class="btn-floating btn waves-effect waves-light yellow darken-3 create-group">
                <i class="material-icons">add</i></a>
        </div>
    </div>

    <div id="general_info">
        <div class="row card-panel" id="<?php echo 'i_' . $station->getInfoID(); ?>">
            <div class="card-title">
                <span class="header">General Info</span>
                <span class="right">
                <a class='edit-general-info black-text' href='#!'>
                    <i class="material-icons">edit</i></a>
            </span></div>
            <div class="general-info"><?php
                // allows for video links
                echo preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $station->getInfoText());
                ?></div>
        </div>
    </div>

    <!-- todo remove duplication (loop) -->

    <div id="instructor_info">
        <?php
        $contentID = $command = "";
        foreach ($station->getPacketContent('instructor', 'environment') as $id => $text) {
            $contentID = $id;
            $command = $text;
        }
        ?>
        <div class="row card-panel" id="<?php echo 'i_' . $id; ?>">
            <div class="card-title">
                <span class="header">Instructor Environment</span>
                <span class="right">
                <a class='edit-packet-info black-text' href='#!'>
                    <i class="material-icons">edit</i></a>
            </span></div>
            <div class="general-info"><?php
                echo preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $command);;
                ?></div>
        </div>

        <?php
        $contentID = $command = "";
        foreach ($station->getPacketContent('instructor', 'script') as $id => $text) {
            $contentID = $id;
            $command = $text;
        }
        ?>
        <div class="row card-panel" id="<?php echo 'i_' . $id; ?>">
            <div class="card-title">
                <span class="header">Instructor Script</span>
                <span class="right">
                <a class='edit-packet-info black-text' href='#!'>
                    <i class="material-icons">edit</i></a>
            </span></div>
            <div class="general-info"><?php

                // allows for video links
                echo preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $command);
                ?></div>
        </div>
    </div>

    <div id="evaluator_info">
        <?php
        $contentID = $command = "";
        foreach ($station->getPacketContent('evaluator', 'environment') as $id => $text) {
            $contentID = $id;
            $command = $text;
        }
        ?>
        <div class="row card-panel" id="<?php echo 'i_' . $id; ?>">
            <div class="card-title">
                <span class="header">Evaluator Environment</span>
                <span class="right">
                <a class='edit-packet-info black-text' href='#!'>
                    <i class="material-icons">edit</i></a>
            </span></div>
            <div class="general-info"><?php

                // allows for video links
                echo preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $command);
                ?></div>
        </div>

        <?php
        $contentID = $command = "";
        foreach ($station->getPacketContent('evaluator', 'script') as $id => $text) {
            $contentID = $id;
            $command = $text;
        }
        ?>
        <div class="row card-panel" id="<?php echo 'i_' . $id; ?>">
            <div class="card-title">
                <span class="header">Evaluator Script</span>
                <span class="right">
                <a class='edit-packet-info black-text' href='#!'>
                    <i class="material-icons">edit</i></a>
            </span></div>
            <div class="general-info"><?php

                // allows for video links
                echo preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $command);
                ?></div>
        </div>
    </div>

    <div id="delete-group-modal" class="modal">
        <div class="modal-content">
            <h4>Are you sure you want to delete this grouping?</h4>
            <p>You can't get it back...</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">No</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat" id="delete-group-confirmed">Yes</a>
        </div>
    </div>
</div>
</div>


<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/admin.js'; ?>"></script>
