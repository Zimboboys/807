<?php

include_once "../header.php";

?>


<div class="container">
    <h4 class="center">Change Password</h4>

    <div class="card-panel">
        <div class="row">
            <div class="input-field col s12">
                <input id="password" type="password" class="validate">
                <label for="password">New Password</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input id="retype_password" type="password" class="validate">
                <label for="retype_password">Retype Password</label>
            </div>
        </div>
    </div>


    <div class='row'>
        <a class='update-password waves-effect waves-light btn green darken-2 col s12 m12 l12'>update password</a>
    </div>

</div>

<?php include_once("../footer.php"); ?>
<script type="text/javascript" src="<?php echo $homeDIR . '/stations/js/profile.js'; ?>"></script>

