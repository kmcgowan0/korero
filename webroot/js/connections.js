$(document).ready(function () {
    var mainUser = $('.main-user');
    var offset = mainUser.offset();
    var width = mainUser.width();
    var height = mainUser.height();
    var mainX = offset.left + width / 2;
    var mainY = offset.top + height / 2 - 60;

    var relatedUsersCoords = [];

    jQuery.each(relatedUsers, function (key, val) {
        relatedUsersCoords.push(coordinates(key, val));
    });

    jQuery.each(relatedUsersCoords, function (x, y) {

        //draw line from previous dot to this dot
        x1 = mainX;
        y1 = mainY;
        x2 = y[0];
        y2 = y[1];
        //count number of interests
        height = y[2].length;

        var m = (y2 - y1) / (x2 - x1); //slope of the segment
        var angle = (Math.atan(m)) * 180 / (Math.PI); //angle of the line
        var d = Math.sqrt(((x2 - x1) * (x2 - x1)) + ((y2 - y1) * (y2 - y1))); //length of the segment
        var transform;

        // the (css) transform angle depends on the direction of movement of the line
        if (x2 >= x1) {
            transform = (360 + angle) % 360;
        } else {
            transform = 180 + angle;
        }

        // add the (currently invisible) line to the page

        jQuery.each(y[2], function (i) {
            var id = 'line_' + new Date().getTime();
            var line = "<div id='" + id + "'class='line' title='" + this['name'] + "'>&nbsp;</div>";
            // var color = '#' + Math.random().toString(16).slice(2, 8).toUpperCase();
            var color = this['colour'];
            var name = this['name'];

            $('#canvas').append(line);

            var left = x1;
            var top = y1;
            if (transform < 45) {
                top = y1  + (2 * 30);
                left = x1;
            }
            if (transform > 180) {
                top = y1  + (i * 5);
                left = x1  + (i * 1);
            }
            if (transform > 90) {
                top = y1  + (i * 5);
                left = x1  + (i * 10);
            }
            if (transform < 90) {
                top = y1;
                left = x1  + (i * 10);
            }

            //rotate the line
            $('#' + id).css({
                'left': left - (i*5),
                'top': top - (i*4),
                'width': '0px',
                'height': '4px',
                'z-index': '10',
                'background-color': '#' + color,
                'position': 'absolute',
                'transform': 'rotate(' + transform + 'deg)',
                'transform-origin': '0px 0px',
                '-ms-transform': 'rotate(' + transform + 'deg)',
                '-ms-transform-origin': '0px 0px',
                '-moz-transform': 'rotate(' + transform + 'deg)',
                '-moz-transform-origin': '0px 0px',
                '-webkit-transform': 'rotate(' + transform + 'deg)',
                '-webkit-transform-origin': '0px 0px',
                '-o-transform': 'rotate(' + transform + 'deg)',
                '-o-transform-origin': '0px 0px'
            });

            // 'draw' the line
            $('#' + id).animate({
                width: d
            }, 600, "linear", function () {
            });
            return i < 3;
        });

        //extra line
        console.log('length ' + y[2].length);
        var extras = y[2].length - 4;
        if (extras > 0) {
            console.log('extra ' + extras);
            var others;
            if (extras == 1) {
                others = extras + ' other';
            } else {
                others = extras + ' others';
            }
            var id = 'line_' + new Date().getTime();
            var line = "<div id='" + id + "'class='line' title='" + others + "'>&nbsp;</div>";
            // var color = '#' + Math.random().toString(16).slice(2, 8).toUpperCase();
            var color = '000';
            $('#canvas').append(line);

            var left = x1;
            var top = y1;

            //rotate the line
            $('#' + id).css({
                'left': left - (6),
                'top': top,
                'width': '0px',
                'height': '4px',
                'z-index': '10',
                'background-color': '#' + color,
                'position': 'absolute',
                'transform': 'rotate(' + transform + 'deg)',
                'transform-origin': '0px 0px',
                '-ms-transform': 'rotate(' + transform + 'deg)',
                '-ms-transform-origin': '0px 0px',
                '-moz-transform': 'rotate(' + transform + 'deg)',
                '-moz-transform-origin': '0px 0px',
                '-webkit-transform': 'rotate(' + transform + 'deg)',
                '-webkit-transform-origin': '0px 0px',
                '-o-transform': 'rotate(' + transform + 'deg)',
                '-o-transform-origin': '0px 0px'
            });

            // 'draw' the line
            $('#' + id).animate({
                width: d
            }, 600, "linear", function () {
            });
        }

    });

});

$( function() {
    $( document ).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
            }
        },
        track: true
    });
} );

function coordinates(key, val) {

    var relatedUser = $('#related-user-' + key);
    var relatedOffset = relatedUser.offset();
    var relatedWidth = relatedUser.width();
    var relatedHeight = relatedUser.height();
    var relatedX = relatedOffset.left + relatedWidth / 2;
    var relatedY = relatedOffset.top + relatedHeight / 2 - 60;
    var coords = [relatedX, relatedY, val];

    return coords;
}