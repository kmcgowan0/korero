$(document).ready(function () {
    var mainUser = $('.main-user');
    var offset = mainUser.offset();
    var width = mainUser.width();
    var height = mainUser.height();
    var mainX = offset.left + width / 2;
    var mainY = offset.top + height / 2;

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

            //rotate the line
            $('#' + id).css({
                'left': x1 + (i * 5),
                'top': y1 + (i * 5),
                'width': '0px',
                'height': '4px',
                'z-index': '10',
                'background-color': '#' + color,
                'position': 'fixed',
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
            }, 400, "linear", function () {
            });

        });

    });

});

function coordinates(key, val) {

    var relatedUser = $('#related-user-' + key);
    var relatedOffset = relatedUser.offset();
    var relatedWidth = relatedUser.width();
    var relatedHeight = relatedUser.height();
    var relatedX = relatedOffset.left + relatedWidth / 2;
    var relatedY = relatedOffset.top + relatedHeight / 2;
    var coords = [relatedX, relatedY, val];

    return coords;
}