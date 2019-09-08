$(document).ready(function () {

    $('.collapsible').collapsible();

});

// show edit tools on hover
$(document).on({
    mouseover: function () {
        $(this).find('a:last-child.hidden-actions').css("visibility", "visible");
    },
    touchstart: function () {
        let pencil = $(this).find('a:last-child.hidden-actions');
        if (pencil.css("visibility") === "visible") {
            pencil.css("visibility", "hidden");
        } else {
            pencil.css("visibility", "visible");
        }
    },
    mouseout: function () {
        $(this).find('a:last-child.hidden-actions').css("visibility", "hidden");
    }
}, '.collection-item');

// the name search -- DUPLICATE (evaluate.js)
$('#name_search').on('input', function () {
    let input = $('#name_search').val().toUpperCase();

    $(".collapsible-header").each(function () {
        let text = $(this).find('.member').text().toUpperCase();
        let username = $(this).attr('id').toUpperCase(); // allows search by username AND name

        console.log(text, username);

        if (text.indexOf(input) < 0 && username.indexOf(input) < 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });

    hideEmptyCollections();
});

// todo optimize jquery :(
function hideEmptyCollections() {
    $(".card-panel").each(function (i) {
        if (i > 0) {
            let populated = $(this).find(".collapsible").children();
            let awakeChild = 0;

            populated.each(function () {
                // use display because :visible checks if it takes any page space
                if ($(this).find('.collapsible-header').css('display') !== 'none') {
                    awakeChild++;
                    return false;
                }
            });

            if (awakeChild === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        }
    });
}


// input for name update
$(document).on("click", "a.change-name", function () {
    let parent = $(this).closest('.collection-item');
    let currentName = parent.find('.member').text();
    let memid = parent.attr('id');

    let new_row = "<li class='collection-item valign-wrapper row' id='" + memid + "'>" +
        "<span class='input-field left col s9 m10 l10'>" +
        "<input type='text' class='validate' id='u_" + memid + "'>" +
        "<p class='hide'>" + currentName + "</p></span>" +
        "<span class='col s3 m2 l2 right-align'><a class='update-member-name material-icons prefix green-text' href='#!'>done</a> " +
        "<a class='remove-item-update material-icons prefix red-text' href='#!' title='remove changes'>close</a></span></li>";

    parent.replaceWith(new_row);
    let input = $('#u_' + memid);
    input.focus().val('').val(currentName);
});


$(document).on("click", "a.update-member-name", function () {
    let parent = $(this).closest('.collection-item');
    let memid = parent.attr('id');
    let origName = parent.find('.hide').text();
    let newName = parent.find('input').val();

    if (origName !== newName) {
        let data = {'id': memid, 'name': newName};
        $.post("../db/scripts/admin/update_member_name.php", data, function (r) {
            // nothing should be here
        });
    }

    replaceMemberItem(parent, memid, newName);
});


$(document).on("click", "a.remove-item-update", function () {
    let parent = $(this).closest('.collection-item');
    let memid = parent.attr('id');
    let origName = parent.find('.hide').text();

    replaceMemberItem(parent, memid, origName);
});

function replaceMemberItem(parent, id, name) {
    let nameRow = "<li class='collection-item' id='" + id + "'><span class='member'>" + name + "</span>" +
        "<a class='right hidden-actions black-text material-icons change-name' href='#!'>edit</a></li>";
    parent.replaceWith(nameRow);
}