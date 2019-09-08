<?php
include_once "../header.php";

$getStationsSQL = "SELECT * FROM stations WHERE hidden = 0 ORDER BY number ASC"; // todo make draggable
$getStationsQ = mysqli_query($conn, $getStationsSQL);
?>

<div class="container">
    <h4 class="center">Director View</h4>

    <div class="card-panel">
        <h5>Members</h5>
        <div class="collection">
            <a class="collection-item black-text" href="edit_members.php">View/edit members</a>
            <a class="collection-item black-text" href="station_progress.php">Station progress</a>
            <div class="collection-item">Station analytics (coming soon)</div>
        </div>
    </div>


    <div class="card-panel">
        <h5>Attendance</h5>
        <div class="collection">
            <a class="collection-item black-text" href="events.php">Create/view events</a>
            <a class="collection-item black-text" href="attendance_groups.php">Attendance groups</a>
            <a class="collection-item black-text" href="assign_groups.php">Assign attendance groups</a>
            <div class="collection-item">Attendance (coming soon)</div>
        </div>
    </div>

    <div class="card-panel">
        <h5 class="card-title">Stations</h5>
        <ul class="collection" id="stations">
            <?php while ($station = $getStationsQ->fetch_assoc()) { ?>
                <li class="collection-item" id="<?php echo 's_' . $station['stationID']; ?>">
                    <span><?php echo $station["name"]; ?></span>
                    <span class="hidden-actions right">
                        <a class='edit-station black-text'
                           href='edit_station.php?id=<?php echo 's_' . $station['stationID']; ?>'><i
                                    class="material-icons">edit</i></a>
                        <a class='delete-station black-text' href='#'><i class="material-icons">delete</i></a></span>
                </li>
            <?php } ?>
        </ul>

        <div class="right-align">
            <a class="btn-floating btn waves-effect waves-light yellow darken-3 create-station">
                <i class="material-icons">add</i></a>
        </div>
    </div>
</div>

<div id="delete-station-modal" class="modal">
    <div class="modal-content">
        <h4>Are you sure you want to delete this station?</h4>
        <p>You can't get it back...</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="modal-close waves-effect waves-red btn-flat">No</a>
        <a href="#" class="modal-close waves-effect waves-green btn-flat" id="delete-station-confirmed">Yes</a>
    </div>
</div>

<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/admin.js'; ?>"></script>

