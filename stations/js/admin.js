$(document).ready(function () {
    // prepares modal action
    $('.modal').modal();
});

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

// adds new station card
$(document).on("click", "a.create-station", function () {
    let new_row = "<li class='collection-item valign-wrapper row' id='new_column'>" +
        "<span class='input-field left-align col s9 m10 l10'>" +
        "<label for='station_name'>Station Name</label>" +
        "<input type='text' class='validate' id='station_name'></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='add-station' href='#!'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='delete-collection-item' href='#!'><i class='material-icons prefix red-text'>close</i></a></span></li>";

    $('#stations').append(new_row);
});

// removes new collection item
$(document).on("click", "a.delete-collection-item", function () {
    $('li#new_column').remove();
});

// adds new stations to the database
$(document).on("click", "a.add-station", function () {
    let stationName = $('#station_name').val();
    let stationNum = $('#stations').children().length;
    let data = {'stationName': stationName, 'stationNum': stationNum};

    if (stationName !== "") {
        $.post("../db/scripts/admin/create_station.php", data, function (response) {
            let added_row = "<li class='collection-item' id='s_" + response + "'><span>" + stationName + "</span>" +
                "<span class='hidden-actions right'><a class='edit-station black-text' href='edit_station.php?id=s_" + response + "'>" +
                "<i class='material-icons'>edit</i></a> <a class='delete-station black-text' href='#!'>" +
                "<i class='material-icons'>delete</i></a></span></li>";
            $('#new_column').remove();
            $('#stations').append(added_row);
        });
    }
});

// confirm station removal
$(document).on("click", "a.delete-station", function () {
    $('.modal').modal('open');
    let stationID = $(this).closest('.collection-item').attr('id');
    $('.modal-footer').attr('id', stationID);
});

// proceed with removal
$(document).on("click", "a#delete-station-confirmed", function () {
    let stationID = $(this).parent().attr('id'); // certainly there is a better way to pass this around
    let data = {'stationID': stationID};

    $.post("../db/scripts/admin/delete_station.php", data, function () {
        $('#' + stationID).remove();
    });
});

// create checklist item
$(document).on("click", "a.create-checklist-item", function () {
    let collectionID = $(this).closest('.card-panel').find('ul').attr('id');
    let new_row = "<li class='collection-item valign-wrapper row' id='new_column'>" +
        "<span class='input-field left-align col s9 m10 l10'>" +
        "<label for='station_name'>Checklist Item</label>" +
        "<input type='text' class='validate' id='checklist_name'></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='add-checklist-item' href='#!'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='delete-collection-item' href='#!'><i class='material-icons prefix red-text'>close</i></a></span></li>";

    $("ul#" + collectionID).append(new_row);
});

// adds new checklist item to the database
$(document).on("click", "a.add-checklist-item", function () {
    let itemName = $('#checklist_name').val();
    let groupID = $(this).closest('.card-panel').find('ul').attr('id');
    let data = {'itemName': itemName, 'stationGroupID': groupID};

    if (itemName !== "") {
        $.post("../db/scripts/admin/add_checklist_item.php", data, function (response) {
            let added_row = "<li class='collection-item' id='c_" + response + "'>" +
                "<a class='set-required black-text' href='#!'><i class='star material-icons yellow-text text-darken-2'>star_border</i></a>" +
                "<span class='checklist-name'>" + itemName + "</span><span class='hidden-actions right'>" +
                "<a class='edit-checklist-item black-text' href='#!' id='c_" + response + "'><i class='material-icons'>edit</i></a> " +
                "<a class='delete-checklist-item black-text' href='#!' id='c_" + response + "'><i class='material-icons'>delete</i></a></span></li>";
            $('li#new_column').remove();
            $('ul#' + groupID).append(added_row);
        });
    }
});

// delete checklist item
$(document).on("click", "a.delete-checklist-item", function () {
    let itemID = $(this).attr('id');
    let data = {'itemID': itemID};

    $.post("../db/scripts/admin/delete_checklist_item.php", data, function () {
        $('#' + itemID).remove();
    });
});

// edit checklist item
$(document).on("click", "a.edit-checklist-item", function () {
    let collectionID = $(this).attr('id');
    let itemText = $("#" + collectionID).children(":nth-child(2)").text();
    let starVal = $(this).closest('.collection-item').find('.star').text();

    let new_row = "<li class='collection-item valign-wrapper row' id='new_column_" + collectionID + "'>" +
        "<span class='input-field left-align col s9 m10 l10'>" +
        "<label for='station_name'>Checklist Item</label>" +
        "<input type='text' class='validate' id='item_name_" + collectionID + "' value=\"" + encodeURI(itemText) + "\"><input type='hidden' id='starVal' value='" + starVal + "'></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='update-checklist-item' href='#!' id='" + collectionID + "'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='remove-item-update' href='#!' title='remove changes' id='" + collectionID + "'><i class='material-icons prefix red-text'>close</i></a></span></li>";

    $("#" + collectionID).replaceWith(new_row);
    $("#item_name_" + collectionID).focus().val('').val(itemText);
});

// remove item edit
$(document).on("click", "a.remove-item-update", function () {
    let groupID = $(this).closest('.card-panel').find('ul').attr('id');
    let id = $(this).attr('id');
    let itemName = decodeURI($("#" + id).closest('.collection-item').find("input")[0].defaultValue);
    let starVal = $("#" + id).closest('.collection-item').find("input")[1].defaultValue;
    let order = $(this).closest('.collection-item').index();

    let row = "<li class='collection-item' id='" + id + "'>" +
        "<a class='set-required black-text' href='#!'><i class='star material-icons yellow-text text-darken-2'>" + starVal + "</i></a>" +
        "<span class='checklist-name'>" + itemName + "</span><span class='hidden-actions right'>" +
        "<a class='edit-checklist-item black-text' href='#!' id='" + id + "'><i class='material-icons'>edit</i></a> " +
        "<a class='delete-checklist-item black-text' href='#!' id='" + id + "'><i class='material-icons'>delete</i></a></span></li>";

    // this throws the updated row back in the right order
    if (order === 0) {
        $(".collection#" + groupID).prepend(row);
    } else {
        $(".collection#" + groupID + " > li:nth-child(" + order + ")").after(row);
    }

    $('#new_column_' + id).remove();

});

// push item edit to DB
$(document).on("click", "a.update-checklist-item", function () {

    let groupID = $(this).closest('.card-panel').find('ul').attr('id');
    let order = $(this).closest('.collection-item').index();
    let id = $(this).attr('id');
    let itemName = $("#" + id).closest('.collection-item').find("input")[0].value;
    let origName = $("#" + id).closest('.collection-item').find("input")[0].defaultValue;
    let data = {'itemName': itemName, 'itemID': id};

    // save a query if the names are the same
    if (itemName !== "" && itemName !== origName) {
        $.post("../db/scripts/admin/edit_checklist_item.php", data, function (response) {
            // nothing to see here
        });
    }

    if (itemName !== "") {
        let row = "<li class='collection-item' id='" + id + "'>" +
            "<a class='set-required black-text' href='#!'><i class='star material-icons yellow-text text-darken-2'>star_border</i></a>" +
            "<span class='checklist-name'>" + itemName + "</span><span class='hidden-actions right'>" +
            "<a class='edit-checklist-item black-text' href='#!' id='" + id + "'><i class='material-icons'>edit</i></a> " +
            "<a class='delete-checklist-item black-text' href='#!' id='" + id + "'><i class='material-icons'>delete</i></a></span></li>";

        // this throws the updated row back in the right order
        if (order === 0) {
            $("ul#" + groupID).prepend(row);
        } else {
            $("#" + groupID + " > li:nth-child(" + order + ")").after(row);
        }

        $('#new_column_' + id).remove();
    }
});

// edit group name
$(document).on("click", "a.edit-checklist-group", function () {
    let groupID = $(this).attr('id');
    let currrentTitle = $(this).closest('.card-title_' + groupID).find('.header').text();

    let input_row = "<div class='card-title_" + groupID + " row valign-wrapper' id='new_column'>" +
        "<span class='input-field left-align col s9 m10 l10'>" +
        "<label for='group_name'>Group Name</label>" +
        "<input type='text' class='validate' id='item_name_" + groupID + "' value='" + currrentTitle + "'></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='update-group-name' href='#!' id='" + groupID + "'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='remove-group-update' href='#!' title='remove changes' id='" + groupID + "'><i class='material-icons prefix red-text'>close</i></a></span></div>";


    $(".card-title_" + groupID).replaceWith(input_row);
    $("#item_name_" + groupID).focus().val('').val(currrentTitle);
});

// push group edit to DB
$(document).on("click", "a.update-group-name", function () {
    let id = $(this).attr('id');
    let groupName = $("#item_name_" + id).val();
    let origName = $("#item_name_" + id).defaultValue;
    let data = {'groupName': groupName, 'groupID': id};

    if (groupName !== "" && groupName !== origName) {
        $.post("../db/scripts/admin/edit_station_group.php", data, function (response) {
            // nothing to see here
        });
    }

    if (groupName !== "") {
        let row = "<div class='card-title_" + id + "'><span class='header'>" + groupName + "</span><span class='right'>" +
            "<a class='edit-checklist-group black-text' href='#!' id='" + id + "'><i class='material-icons'>edit</i></a> " +
            "<a class='delete-checklist-group black-text' href='#!' id='" + id + "'><i class='material-icons'>delete</i></a>" +
            "</span></div>";
        $(".card-title_" + id).replaceWith(row);
    }
});

// remove group edit
$(document).on("click", "a.remove-group-update", function () {
    let id = $(this).attr('id');
    let groupName = $("#item_name_" + id).prop("defaultValue");

    let row = "<div class='card-title_" + id + "'><span class='header'>" + groupName + "</span><span class='right'>" +
        "<a class='edit-checklist-group black-text' href='#!' id='" + id + "'><i class='material-icons'>edit</i></a> " +
        "<a class='delete-checklist-group black-text' href='#!' id='" + id + "'><i class='material-icons'>delete</i></a>" +
        "</span></div>";
    $(".card-title_" + id).replaceWith(row);
});


// todo update to recent edits

// add group
$(document).on("click", "a.create-group", function () {
    let data = {'stationID': getUrlParameter('id')};
    $.post("../db/scripts/admin/create_group.php", data, function (response) {
        let ids = response.split(',');

        // <a class='set-required black-text' href='#!'><i class='star material-icons yellow-text text-darken-2'>star_border</i></a>
        //                             <span class="checklist-name"><?php echo $station->getItemName($checklistID); ?></span>

        let card = "<div class='row card-panel' id='g_" + ids[0] + "'><div class='card-title_g_" + ids[0] + "'><span class='header'>new grouping</span>" +
            "<span class='right'><a class='edit-checklist-group black-text' href='#!' id='g_" + ids[0] + "'><i class='material-icons'>edit</i></a> " +
            "<a class='delete-checklist-group black-text' href='#' id='g_" + ids[0] + "'><i class='material-icons'>delete</i></a></span></div>" +
            "<ul class='card-content collection' id='g_" + ids[0] + "'>" +
            "<li class='collection-item' id='c_" + ids[1] + "'><a class='set-required black-text' href='#!'>" +
            "<i class='star material-icons yellow-text text-darken-2'>star_border</i></a><span class='checklist-name'>example item</span><span class='hidden-actions right'>" +
            "<a class='edit-checklist-item black-text' href='#!' id='c_" + ids[1] + "'><i class='material-icons'>edit</i></a>" +
            "<a class='delete-checklist-item black-text' href='#!' id='c_" + ids[1] + "'><i class='material-icons'>delete</i></a></span></li></ul>" +
            "<div class='right-align'><a class='btn-floating btn waves-effect waves-light yellow darken-3 create-checklist-item'><i class='material-icons'>add</i></a>" +
            "</div></div>";

        $(".bottom-button").before(card);
    });
});

// delete group confirmation
$(document).on("click", "a.delete-checklist-group", function () {
    $('.modal').modal('open');
    let groupID = $(this).closest('.delete-checklist-group').attr('id');
    $('.modal-footer').attr('id', groupID);
});

// proceed with removal
$(document).on("click", "a#delete-group-confirmed", function () {
    let groupID = $(this).parent().attr('id');
    let data = {'groupID': groupID};

    $.post("../db/scripts/admin/delete_station_group.php", data, function () {
        $('#' + groupID).remove();
    });
});

// toggle item required
$(document).on("click", "a.set-required", function () {
    let star = $(this).find('.star');
    let checklistID = $(this).closest('.collection-item').attr('id');
    let required = star.text() === "star" ? 1 : 0;
    let data = {'id': checklistID, 'currentReq': required};

    $.post("../db/scripts/admin/update_checklist_req.php", data, function (response) {
        star.text(response);
    });
});

// edit station name
$(document).on("click", "a.edit-station-name", function () {
    let currTitle = $('#station-name').text();

    let titleInput = "<div class='col s12 center-alig station-title' id='max-failed'><div class='input-field inline'>" +
        "<input type='text' class='s3 m3 l3' id='station-name' value='" + currTitle + "'>" +
        "</div><a class='update-station-title' href='#!'><i class='material-icons prefix green-text'>done</i></a>" +
        "<a class='remove-station-title-update' href='#!' title='remove changes'><i class='material-icons prefix red-text'>close</i></a></div>";
    $('.station-title').replaceWith(titleInput);
});

// update station name
$(document).on("click", "a.update-station-title", function () {
    let inputfield = $('#station-name')[0];
    let newName = inputfield.value;
    let origName = inputfield.defaultValue;
    let stationID = getUrlParameter('id');
    let data = {'id': stationID, 'name': newName};

    if (newName !== origName) { // saves a query
        $.post("../db/scripts/admin/update_station_name.php", data, function () {
            // nothing to see here
        });
    }

    setStationTitle(newName);
});

$(document).on("click", "a.remove-station-title-update", function () {
    let origTotal = $('#station-name')[0].defaultValue;
    setStationTitle(origTotal);
});

function setStationTitle(name) {
    let title = "<div class='station-title'><span class='header h4' id='station-name'>" + name + "</span>" +
        "<a class='edit-station-name black-text' href='#!'><i class='material-icons large-edit-pencil'>edit</i></a></div>";
    $('.station-title').replaceWith(title);
}


// edit max failed
$(document).on("click", "a.edit-max-failed", function () {
    let currMax = $("#num-max").text();
    let totalMax = $('li').length;

    let newMaxInput = "<div class='col s12 center-align' id='max-failed'>max failed: <div class='input-field inline'>" +
        "<input type='number' class='s2 m2 l2' id='max-number' min='0' max='" + totalMax + "' value='" + currMax + "'>" +
        "</div><a class='update-max-failed' href='#!'><i class='material-icons prefix green-text'>done</i></a>" +
        "<a class='remove-max-failed-update' href='#!' title='remove changes'><i class='material-icons prefix red-text'>close</i></a></div>";

    $('#max-failed').replaceWith(newMaxInput);
});

// update max failed
$(document).on("click", "a.update-max-failed", function () {
    let inputfield = $('#max-number')[0];
    let newTotal = inputfield.value;
    let origTotal = inputfield.defaultValue;
    let stationID = getUrlParameter('id');
    let data = {'id': stationID, 'max': newTotal};

    if (newTotal !== origTotal) { // saves a query
        $.post("../db/scripts/admin/update_max_failed.php", data, function () {
            // nothing to see here
        });
    }

    setMaxFailed(newTotal);
});

$(document).on("click", "a.remove-max-failed-update", function () {
    let origTotal = $('#max-number')[0].defaultValue;
    setMaxFailed(origTotal);
});


$(document).on("click", "a.edit-general-info", function () {
    let id = $(this).closest('.card-panel').attr('id');
    let current = $(this).closest('.card-panel').find('.general-info').text();
    let textarea = "<div class='input-field'><input type='hidden' id='default_" + id + "' value='" + current + "'>" +
        "<textarea id='area_" + id + "' class='materialize-textarea'>" + current + "</textarea></div>";
    let actions = "<a class='update-general-info' href='#!'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='remove-info-update' href='#!' title='remove changes'><i class='material-icons prefix red-text'>close</i></a>";

    $(this).closest('.card-panel').find('.general-info').html(textarea);
    $(this).closest('.card-panel').find('.right').html(actions);
    $('#area_' + id).trigger('autoresize');
});


// only diff is the "done" link
$(document).on("click", "a.edit-packet-info", function () {
    let id = $(this).closest('.card-panel').attr('id');
    let current = $(this).closest('.card-panel').find('.general-info').text();
    let textarea = "<div class='input-field'><input type='hidden' id='default_" + id + "' value='" + current + "'>" +
        "<textarea id='area_" + id + "' class='materialize-textarea'>" + current + "</textarea></div>";
    let actions = "<a class='update-packet-info' href='#!'><i class='material-icons prefix green-text'>done</i></a> " +
        "<a class='remove-info-update' href='#!' title='remove changes'><i class='material-icons prefix red-text'>close</i></a>";

    $(this).closest('.card-panel').find('.general-info').html(textarea);
    $(this).closest('.card-panel').find('.right').html(actions);
    $('#area_' + id).trigger('autoresize');
});


$(document).on("click", "a.remove-info-update", function () {
    let parent = $(this).closest('.card-panel');
    let id = parent.attr('id');
    let right = "<a class='edit-general-info black-text' href='#!'><i class='material-icons'>edit</i></a>";
    let origText = parent.find('#default_' + id).val();
    origText = linkify(origText);

    parent.find('.right').html(right);
    parent.find('.general-info').html(origText);
});


$(document).on("click", "a.update-general-info", function () {
    let parent = $(this).closest('.card-panel');
    let id = parent.attr('id');
    let right = "<a class='edit-general-info black-text' href='#!'><i class='material-icons'>edit</i></a>";
    let origText = parent.find('#default_' + id).val();
    let newText = parent.find('#area_' + id).val();

    if (origText !== newText) {
        let data = {'id': id, 'text': newText};
        $.post("../db/scripts/admin/update_gen_info.php", data, function (response) {
            // nothing to see here
        });
    }

    newText = linkify(newText);

    parent.find('.right').html(right);
    parent.find('.general-info').html(newText);
});

$(document).on("click", "a.update-packet-info", function () {
    let parent = $(this).closest('.card-panel');
    let id = parent.attr('id');
    let right = "<a class='edit-packet-info black-text' href='#!'><i class='material-icons'>edit</i></a>";
    let origText = parent.find('#default_' + id).val();
    let newText = parent.find('#area_' + id).val();

    if (origText !== newText) {
        let data = {'id': id, 'text': newText};
        $.post("../db/scripts/admin/update_packet_info.php", data, function (response) {
            // nothing here
        });
    }

    newText = linkify(newText);

    parent.find('.right').html(right);
    parent.find('.general-info').html(newText);
});


function setMaxFailed(num) {
    let failblock = "<div class='center-align' id='max-failed'>max failed: <span id='num-max'>" + num + "</span> " +
        "<a class='edit-max-failed black-text' href='#!'><i class='material-icons edit-pencil'>edit</i></a></div>";
    $('#max-failed').replaceWith(failblock);
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

function linkify(inputText) {
    let replacedText, replacePattern1, replacePattern2, replacePattern3;

    //URLs starting with http://, https://, or ftp://
    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

    //Change email addresses to mailto:: links.
    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
    replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

    return replacedText;
}

// todo after checklist edit, restore proper star


/*
 * OVERVIEW INFORMATION - ATTEMPTS
 */

$(document).on("click", "a.station-select", function () {
    let stationName = $(this).find('.station-name').text();
    $('.dropdown-name').text(stationName);
    generateStation($(this).attr('id'));
});

function generateStation(stationID) {
    let uid = $('.target-name').attr('id');
    let data = {'id': stationID, 'u': uid};
    $.get("../db/scripts/admin/get_attempts.php", data, function (data) {
        if (data === "no data") {
            let card = "<div class='card-panel row'><h5 class='card-title'>No attempts made</h5><div>no attempts have been recorded for this station</div></div>";
            $('.station-info').html(card);
            return;
        }
        let d = jQuery.parseJSON(data);
        $('.station-info').html("");

        d.forEach(function (attempt) {
            attempt.forEach(function (group) {

                let cards = "";
                if ($("#" + group.id).length === 0) {
                    cards += "<div class='card-panel row' id='" + group.id + "'>";
                    cards += "<h5 class='card-title'>" + group.groupName + "</h5>";
                    cards += "<ul class='card-content collection'>";
                    cards += "</ul></div>";

                    $('.station-info').append(cards);
                }

                group.items.forEach(function (a) {
                    if ($('#' + a.id).length === 0) {
                        let item = "<li class='collection-item' id='" + a.id + "'>" + a.itemName + "<span class='right'></span></li>";
                        $('#' + group.id + " > ul").append(item);
                    }

                    let buttons;
                    if (a.attempts.result !== "passed") { // todo fix who evaluated
                        buttons = "<i class='red-text text-darken-1 material-icons' title='Evaluated by " + a.attempts.evaluator + "\n" + a.time+ "'>remove_circle</i>";
                    } else {
                        buttons = "<i class='green-text text-darken-1 material-icons' title='Evaluated by " + a.attempts.evaluator + "\n" + a.time+ "'>check_circle</i>";
                    }
                    $('#' + a.id + ' > .right').append(buttons);
                });
            });
        });
    });
}