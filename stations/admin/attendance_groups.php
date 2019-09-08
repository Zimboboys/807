<?php
include_once "../header.php";
$groupsSQL = "SELECT * FROM attendance_groups";
$groups = mysqli_query($conn, $groupsSQL);

$usersSQL = "SELECT * FROM users ORDER BY section";
$users = mysqli_query($conn, $usersSQL);
?>


<div class="container">
    <h4 class="center">Attendance Groups</h4>

    <div class="card-panel">
        <h5>Groups</h5>
        <ul class="collection" id="groups"><?php
            while ($g = $groups->fetch_assoc()) { ?>
                <li class="collection-item" id="g_<?php echo $g['groupID']; ?>"><?php echo $g['groupName']; ?></li>
            <?php } ?>
        </ul>

        <div class="right-align">
            <a class="btn-floating btn waves-effect waves-light yellow darken-3 create-attendance-group">
                <i class="material-icons">add</i></a>
        </div>
    </div>
</div>


<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/attendance.js'; ?>"></script>
