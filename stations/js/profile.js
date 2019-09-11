
// update password
$(document).on("click", "a.update-password", function () {
    let password = $('#password')[0].value;
    let validate = $('#retype_password')[0].value;

    if (password !== validate) {
        let message = "Update failed. Passwords do not match.";
        Materialize.toast(message, 4000);
    } else if (password.length <= 5) {
        let message = "Update failed. Password is too short.";
        Materialize.toast(message, 4000);
    } else {
        let data = {'pw': password};
        $.post("../db/scripts/profile/update_password.php", data, function (r) {
            console.log(r);
            let message = "Password updated";
            Materialize.toast(message, 4000);
        });
    }
});