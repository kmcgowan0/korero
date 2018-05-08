$(document).ready(function () {

    bindFunc();

    messageNotifications();


    $('#search').on('keyup', function () {
        var term = $(this).val();
        search(term);
    });

    $('#results').on('click', '.selectable', function () {
        var name = $(this).text();
        var id = $(this).data('id');
        $('#selected').append('<div>' +
            '<p class="columns medium-6">' + name + '</p>' +
            '<button class="remove" id="' + id + '">Remove</button>' +
            '</div>' +
            '');
        $('#selected-form').append('<input type="hidden" id="' + id + '" name="interests[_ids][]" value="' + id + '">');
        bindFunc()
    });
    $('.reveal-link').on('click', function () {
        var liveMessageId = $(this).data('id');
        connectionMessages(liveMessageId);

        $('.message-form' + liveMessageId).submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                url = $form.attr('action');
            var body = $('#message-body' + liveMessageId).val();
            var posting = $.post(url, {body: body, recipient: liveMessageId});
            posting.done(function (data) {
                $(".message-form" + liveMessageId)[0].reset();
                connectionMessages(liveMessageId);
            });
        });
    });

    $('.location-button').on('click', function () {
        getLocation();
    });

        $('[data-reveal]').on('closed.zf.reveal', function () {
        var liveMessageId = null;
    });

    $("#message-form").submit(function (event) {
        event.preventDefault();
        var $form = $(this),
            url = $form.attr('action');

        var posting = $.post(url, {body: $('#body').val()});

        posting.done(function (data) {
            $("#message-form")[0].reset();
            refreshMessages(messageId);

        });
    });

    // var messageId = $('#messages-id').val();
    refreshMessages(messageId);


});

function search(term) {
    $.get({
        url: '/interests/search',
        data: {term: term},
        success: function (data) {
            $('#results').html(data);
        }
    })
}

var interval = 1000;

function refreshMessages(messageId) {

    $.get({
        url: '/messages/instant-messages/' + messageId,
        success: function (data) {
            $('#messages').html(data);
        },
        complete: function () {
            // Schedule the next
            setTimeout(refreshMessages(messageId), interval);
        }
    })
}

function connectionMessages(messageId) {
    $.get({
        url: '/messages/connection-messages/' + messageId,
        success: function (data) {
            $('#messages' + messageId).html(data);
        },
        complete: function () {
            // Schedule the next
            setTimeout(refreshMessages(messageId), interval);
        }
    })
}

function bindFunc() {
    $('.remove').click(function () {
        var id = $(this).attr('id');
        $(this).parent().remove();
        $('#' + id).remove();
    });
}

//ajax request to unread messages to get notifications
function messageNotifications() {
    $.get({
        url: '/messages/unread-messages/',
        success: function (data) {
            $('#notifications').html(data);
        }
        // complete: function () {
        //     // Schedule the next
        //     // setTimeout(messageNotifications, interval);
        // }
    })
}

function getLocation() {

    var geocoder = new google.maps.Geocoder;
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var pos = lat + ',' + lng;
            console.log(pos);
            geocodeLatLng(geocoder, lat, lng);
            console.log(geocodeLatLng(geocoder, lat, lng));
            $('#location-coords').val(pos);
        })
    } else {
        // Browser doesn't support Geolocation
        alert('get a better broswer. we can\'t find you')
    }
}


function geocodeLatLng(geocoder, lat, lng) {
    var latlng = {lat: lat, lng: lng};
    console.log(latlng);
    geocoder.geocode({'location': latlng}, function (results, status) {
        if (status === 'OK') {
            if (results[0]) {
                $('#my-location').html(results[0].address_components[3].long_name);
                console.log(results[0]);
            } else {
                window.alert('No results found');
            }
        } else {
            window.alert('Geocoder failed due to: ' + status);
        }
    });
}