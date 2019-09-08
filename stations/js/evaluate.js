$(document).ready(function () {
    $('.dropdown-trigger').dropdown();
    $('.modal').modal();
});

// the name search
$('#name_search').on('input', function () {
    let input = $('#name_search').val().toUpperCase();

    $(".collection a").each(function () {
        let text = $(this).text().toUpperCase();
        if (text.indexOf(input) < 0) {
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
            let populated = $(this).find(".collection").children();
            let awakeChild = 0;

            populated.each(function () {
                // use display because :visible checks if it takes any page space
                if ($(this).css('display') !== 'none') {
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

$(document).on("click", "a.station-select", function () {
    let stationName = $(this).find('.station-name').text();
    $('.dropdown-name').text(stationName);
    generateStation($(this).attr('id'));
});

function generateStation(stationID) {
    let data = {'id': stationID};
    $.get("../db/scripts/evaluator/get_station.php", data, function (data) {
        let d = jQuery.parseJSON(data);
        let cards = "";

        for (let group in d) {
            cards += "<div class='card-panel row'>";
            cards += "<h5 class='card-title'>" + group + "</h5>";
            cards += "<ul class='card-content collection'>";

            if (d.hasOwnProperty(group)) { // some kind of check that I should have
                for (let item in d[group]) {
                    // item = checklistID
                    // d[group][item] = checklist item
                    if (d[group].hasOwnProperty(item)) {
                        cards += "<li class='collection-item' id='c_" + item + "'>" + d[group][item] + "" +
                            "<span class='right'><a class='failed-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a> " +
                            "<a class='passed-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a></span></li>";
                    }
                }
            }
            cards += "</ul></div>";
        }

        cards += "<div class='row'><a class='submit-attempt waves-effect waves-light btn green darken-2 col s12 m12 l12'>submit attempt</a></div>";
        $('.station-info').html(cards);
        $('.station-info').attr('id', 's_' + stationID);
    });
}

$(document).on("click", "a.passed-item", function () {
    let buttons = "";
    let grandpa = $(this).closest('.collection-item');
    let markElement = $(this).find('i');

    if (markElement.text() === "check_circle_outline") {
        buttons = "<a class='failed-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a> " +
            "<a class='passed-item green-text text-darken-1' href='#!'><i class='material-icons'>check_circle</i></a>";
        grandpa.attr('class', 'collection-item passed');
    } else {
        buttons = "<a class='failed-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a> " +
            "<a class='passed-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item');
    }

    $(this).closest('.right').html(buttons);
});

$(document).on("click", "a.failed-item", function () {
    let buttons = "";
    let grandpa = $(this).closest('.collection-item');
    let markElement = $(this).find('i');

    if (markElement.text() === "remove_circle_outline") {
        buttons = "<a class='failed-item red-text text-darken-1' href='#!'><i class='material-icons'>remove_circle</i></a> " +
            "<a class='passed-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item failed');
    } else {
        buttons = "<a class='failed-item black-text' href='#!'><i class='material-icons'>remove_circle_outline</i></a> " +
            "<a class='passed-item black-text' href='#!'><i class='material-icons'>check_circle_outline</i></a>";
        grandpa.attr('class', 'collection-item');
    }

    $(this).closest('.right').html(buttons);
});

$(document).on("click", "a.submit-attempt", function () {
    let passed = $('.collection-item.passed');
    let failed = $('.collection-item.failed');

    if ($('.collection-item').length !== passed.length + failed.length) {
        $('.modal').modal('open');
    } else {
        let passedItems = [];
        let failedItems = [];

        passed.each(function () {
            passedItems.push(this.id);
        });

        failed.each(function () {
            failedItems.push(this.id);
        });

        let data = {
            'memberID': $('.target-name').attr('id'),
            'stationID': $('.station-info').attr('id'),
            'passedChecks': passedItems,
            'failedChecks': failedItems
        };

        $.post("../db/scripts/evaluator/submit_attempt.php", data, function (response) {
            let message = "Congrats! They passed";
            if (response === "failed") {
                message = "Unfortunately, they failed";
            }
            Materialize.toast(message, 4000);
        });
    }
});