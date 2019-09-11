$(document).ready(function () {
    // prepares modal action
    $('.modal').modal();

    let date_input = $('#date')[0];
    if (date_input !== undefined) {
        let curr = new Date(date_input.value);
        let d = new Date();
        let timeOffsetInMS = d.getTimezoneOffset() * 60000;
        date_input.value = new Date(curr.getTime() - timeOffsetInMS).toString();
    }

    let dates = Array.from($('.datetime'));
    dates.forEach(function (d) {
        let db = new Date(d.innerText);
        let tz = new Date();
        let timezone = new Date(db.getTime() - (tz.getTimezoneOffset() * 60000));
        d.innerText = formatDate(timezone);
    });
});

// adds new group collection item
$(document).on("click", "a.create-attendance-group", function () {
    let new_row = "<li class='collection-item valign-wrapper row' id='new_column'>" +
        "<span class='input-field left-align col s9 m10 l10'>" +
        "<label for='attendance_group_name'>Group Name</label>" +
        "<input type='text' class='validate' id='attendance_group_name'></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='add-attendance-group' href='#!'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='delete-collection-item' href='#!'><i class='material-icons prefix red-text'>close</i></a></span></li>";

    $('#groups').append(new_row);
});

// removes new collection item
$(document).on("click", "a.delete-collection-item", function () {
    $('li#new_column').remove();
});

// adds new stations to the database
$(document).on("click", "a.add-attendance-group", function () {
    let name = $('#attendance_group_name').val();
    let num = $('#groups').children().length;
    let data = {'name': name, 'num': num};

    if (name !== "") {
        $.post("../db/scripts/admin/create_attendance_group.php", data, function (response) {
            let added_row = "<li class='collection-item' id='g_" + response + "'><span>" + name + "</span>" +
                "<span class='hidden-actions right'><a class='edit-attendance-group black-text' href='edit_group.php?id=g_" + response + "'>" +
                "<i class='material-icons'>edit</i></a> <a class='delete-attendance-group black-text' href='#!'>" +
                "<i class='material-icons'>delete</i></a></span></li>";
            $('#new_column').remove();
            $('#groups').append(added_row);
        });
    }
});

// confirm station removal
$(document).on("click", "a.delete-station", function () {
    $('.modal').modal('open');
    let stationID = $(this).closest('.collection-item').attr('id');
    $('.modal-footer').attr('id', stationID);
});

$(document).on("click", "a.save-attendance-assignment", function () {
    let section = $(this).closest('.card-panel').find('.collection')[0];
    let members = Array.from($(section).children());

    let checked = [];
    let unchecked = [];

    members.forEach(function (mem) {
        let checkboxes = Array.from($(mem).find('input[type=checkbox]'));
        checkboxes.forEach(function (band) {
            if ($(band).is(':checked')) {
                checked.push($(band).attr('id'));
            } else {
                unchecked.push($(band).attr('id'));
            }
        });
    });

    let data = {'unchecked': unchecked, 'checked': checked};
    $.post("../db/scripts/admin/assign_groups.php", data, function (response) {
        // nothing here
    });
});


/*
 * CREATING EVENTS
 */

// show edit tools on hover
$(document).on({
    mouseover: function () {
        $(this).find('span:last-child.hidden-actions').css("visibility", "visible");
    },
    touchstart: function () {
        if ($(this).find('span:last-child.hidden-actions').css("visibility") === "visible") {
            $(this).find('span:last-child.hidden-actions').css("visibility", "hidden");
        } else {
            $(this).find('span:last-child.hidden-actions').css("visibility", "visible");
        }
    },
    mouseout: function () {
        $(this).find('span:last-child.hidden-actions').css("visibility", "hidden");
    }
}, '.collection-item');

// adds new event card
$(document).on("click", "a.create-event", function () {
    let new_row = "<li class='collection-item valign-wrapper row' id='new_column'>" +
        "<span class='input-field left-align col s9 m10 l10'>" +
        "<label for='station_name'>Event Name</label>" +
        "<input type='text' class='validate' id='event_name'></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='add-event' href='#!'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='delete-collection-item' href='#!'><i class='material-icons prefix red-text'>close</i></a></span></li>";

    $('#events').append(new_row);
});

// removes new collection item
$(document).on("click", "a.delete-collection-item", function () {
    $('li#new_column').remove();
});

// adds new stations to the database
$(document).on("click", "a.add-event", function () {
    let name = $('#event_name').val();
    let data = {'eventName': name};

    if (name !== "") {
        $.post("../db/scripts/admin/create_event.php", data, function (response) {
            console.log(response);
            let added_row = "<li class='collection-item' id='s_" + response + "'><span>" + name + "</span>" +
                "<span class='hidden-actions right'><a class='edit-event black-text' href='edit_event.php?id=e_" + response + "'>" +
                "<i class='material-icons'>edit</i></a> <a class='delete-event black-text' href='#!'>" +
                "<i class='material-icons'>delete</i></a></span></li>";
            $('#new_column').remove();
            $('#events').append(added_row);
        });
    }
});


// edit event name
$(document).on("click", "a.edit-event-name", function () {
    let currTitle = $('#event-name').text();

    let titleInput = "<div class='col s12 center-alig event-title' id='max-failed'><div class='input-field inline'>" +
        "<input type='text' class='s3 m3 l3' id='event-name' value='" + currTitle + "'>" +
        "</div><a class='update-event-title' href='#!'><i class='material-icons prefix green-text'>done</i></a>" +
        "<a class='remove-event-title-update' href='#!' title='remove changes'><i class='material-icons prefix red-text'>close</i></a></div>";
    $('.event-title').replaceWith(titleInput);
});

// update station name
$(document).on("click", "a.update-event-title", function () {
    let inputfield = $('#event-name')[0];
    let newName = inputfield.value;
    let origName = inputfield.defaultValue;
    let eventID = getUrlParameter('id');
    let data = {'id': eventID, 'name': newName};

    if (newName !== origName) { // saves a query
        $.post("../db/scripts/admin/update_event_name.php", data, function () {
            // nothing to see here
        });
    }

    setEventTitle(newName);
});

$(document).on("click", "a.remove-event-title-update", function () {
    let origTotal = $('#event-name')[0].defaultValue;
    setEventTitle(origTotal);
});

function setEventTitle(name) {
    let title = "<div class='event-title'><span class='header h4' id='event-name'>" + name + "</span>" +
        "<a class='edit-event-name black-text' href='#!'><i class='material-icons large-edit-pencil'>edit</i></a></div>";
    $('.event-title').replaceWith(title);
}

// straight from stack overflow
let getUrlParameter = function getUrlParameter(sParam) {
    let sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName;

    for (let i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

$(document).on("click", "a.update-event", function () {
    let eventID = getUrlParameter('id');
    let raw_date = $('#date')[0].value;
    let event = new Date(raw_date);

    if (event.toString() === "Invalid Date") {
        $('#error')[0].innerText = "Please properly format your date";
    } else {
        let date = event.toMysqlFormat();
        let assigned = "";

        let groups = Array.from($('#attendance_groups').find('input[type=checkbox]'));
        groups.forEach(function (band) {
            if ($(band).is(':checked')) {
                assigned += $(band).attr('id') + '.';
            }
        });

        let data = {'id': eventID, 'date': date, 'assigned': assigned};
        $.post("../db/scripts/admin/update_event.php", data, function (response) {
            console.log(date, response);
        });
    }
});


function twoDigits(d) {
    if (0 <= d && d < 10) return "0" + d.toString();
    if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
    return d.toString();
}

Date.prototype.toMysqlFormat = function () {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " " + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
};

function formatDate(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return (date.getMonth() + 1 + "/" + date.getDate() + "/" + date.getFullYear() + " " + strTime).toString();
}

$(document).on("click", "a.on-time-item", function () {
    let buttons = "";
    let grandpa = $(this).closest('.collection-item');
    let markElement = $(this).find('i');
    let hourglass = $(this).closest('.personnel').length !== 0 ? "<a class='late-item black-text' href='#!'><i class='material-icons'>hourglass_empty</i></a>" : "";

    if (markElement.text() === "check_circle_outline") {
        buttons = "<a class='no-show-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a>" +
            hourglass +
            "<a class='on-time-item green-text text-darken-1' href='#!'><i class='material-icons'>check_circle</i></a>";
        grandpa.attr('class', 'collection-item on-time');
    } else {
        buttons = "<a class='no-show-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a>" +
            hourglass +
            "<a class='on-time-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item');
    }

    $(this).closest('.right').html(buttons);
});


$(document).on("click", "a.late-item", function () {
    let buttons = "";
    let grandpa = $(this).closest('.collection-item');
    let markElement = $(this).find('i');

    if (markElement.text() === "hourglass_empty") {
        buttons = "<a class='no-show-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a>" +
            "<a class='late-item blue-text text-darken-2' href='#!'><i class='material-icons'>hourglass_full</i></a>" +
            "<a class='on-time-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item late');
    } else {
        buttons = "<a class='no-show-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a>" +
            "<a class='late-item black-text' href='#!'><i class='material-icons'>hourglass_empty</i></a>" +
            "<a class='on-time-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item');
    }

    $(this).closest('.right').html(buttons);
});


$(document).on("click", "a.no-show-item", function () {
    let buttons = "";
    let grandpa = $(this).closest('.collection-item');
    let markElement = $(this).find('i');
    let hourglass = $(this).closest('.personnel').length !== 0 ? "<a class='late-item black-text' href='#!'><i class='material-icons'>hourglass_empty</i></a>" : "";

    if (markElement.text() === "remove_circle_outline") {
        buttons = "<a class='no-show-item red-text text-darken-1' href='#!'><i class='material-icons'>remove_circle</i></a> " +
            hourglass +
            "<a class='on-time-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item no-show');
    } else {
        buttons = "<a class='no-show-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a>" +
            hourglass +
            "<a class='on-time-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item');
    }

    $(this).closest('.right').html(buttons);
});

$(document).on("click", "a.submit-attendance", function () {
    let ontime = $('.collection-item.on-time');
    let late = $('.collection-item.late');
    let noshow = $('.collection-item.no-show');

    if ($('.collection-item').length !== ontime.length + late.length + noshow.length) {
        $('.modal').modal('open');
    } else {
        let ontimeMembers = [];
        let lateMembers = [];
        let noshowMembers = [];

        ontime.each(function () {
            ontimeMembers.push(this.id);
        });

        late.each(function () {
            lateMembers.push(this.id);
        });

        noshow.each(function () {
            noshowMembers.push(this.id);
        });

        let data = {
            'eventID': $('.event-title').attr('id'),
            'ontime': ontimeMembers,
            'late': lateMembers,
            'noshow': noshowMembers
        };

        $.post("../../db/scripts/attendance/submit_attendance.php", data, function () {
            let message = "Attendance submitted";
            Materialize.toast(message, 4000);
        });
    }
});