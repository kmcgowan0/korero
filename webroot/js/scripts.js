$(document).ready(function () {

    bindFunc();

    getWindowSize();

    messageNotifications();

    refreshInterests();

    $('#search').on('keyup', function () {
        var term = $(this).val();
        search(term);
    });

    $('#results').on('click', '.selectable', function () {
        var name = $(this).text();
        var id = $(this).data('id');
        var added = [];
        $("#selected-form input").each(function()
            added.push(parseFloat($(this).val()));
        });
        if (jQuery.inArray(id, added) == -1) {
            $('#selected').append('<div class="columns medium-6">' +
                '<p>' + name + '</p>' +
                '<button class="remove" id="' + id + '">Remove</button>' +
                '</div>' +
                '');
            $('#selected-form').append('<input type="hidden" id="' + id + '" name="interests[_ids][]" value="' + id + '">');
        }
        bindFunc();
    });

    $('.reveal-link').on('click', function () {

        var liveMessageId = $(this).data('id');
        connectionMessages(liveMessageId);

        $('#related-user-' + liveMessageId).css('border', '2px solid #000');
        $('#notifications-' + liveMessageId).html('');
        messageNotifications();
        $('#message-form' + liveMessageId).submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                url = $form.attr('action');
            var body = $('#message-body' + liveMessageId).val();
            if (body != '') {
                var posting = $.post(url, {body: body, recipient: liveMessageId});
                posting.done(function (data) {
                    $(".message-form" + liveMessageId)[0].reset();
                    connectionMessages(liveMessageId);
                });
            }
        });
    });

    $('.location-button').on('click', function () {

        $('.location-button').addClass('spinning');
        $('h6.hidden').css({'display': 'block'});
        getLocation('.my-coded-location');
        $('#location-coords').on('change', function () {
            $('.location-button').removeClass('spinning');
        });
    });


    $('#profile-picture').on('change', function () {
        console.log('ok');
        if (typeof (FileReader) != "undefined") {
            var image_holder = $(".profile-preview");
            var reader = new FileReader();
            reader.onload = function (e) {
                image_holder.css({'background-image': 'url(' + e.target.result + ')'})
                console.log(e.target.result);
            };
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
    });

    $('[data-reveal]').on('closed.zf.reveal', function () {

        var liveMessageId = null;
    });

    $("#message-form").unbind('submit').bind('submit', function (event) {
        event.preventDefault();
            var $form = $(this),
                url = $form.attr('action');
            var body = $('#body').val();

            if (body != '') {
                var posting = $.post(url, {body: body});
                $("#message-form")[0].reset();
                posting.done(function (data) {
                    $("#message-form")[0].reset();
                    refreshMessages(messageId);

                });
            }
        scrollBottom();
    });


    $('#add-user-form').submit(function (event) {
        var form = this;
        var geocoder = new google.maps.Geocoder;
        event.preventDefault();
        if ($('#location-coords').val() != "") {
            var input = $('#location-coords').val();
            var latlng = input.split(',');
            var lat = parseFloat(latlng[0]);
            var lng = parseFloat(latlng[1]);
            geocodeLatLng(geocoder, lat, lng, 'my-coded-location')
        }
        form.submit();
    });

    $('#new-interest-form').submit(function (event) {
        console.log(this);
        event.preventDefault();
        var name = $('#search').val();
        var $form = $(this),
            url = $form.attr('action');
        var posting = $.post(url, {name: name});
        posting.done(function (data) {
            $("#new-interest-form")[0].reset();
            refreshInterests();
            $('#selected').append('<div>' +
                '<p>' + name + '</p>' +
                '<button class="remove">Remove</button>' +
                '</div>' +
                '');
        });

    });

    if ($("div#messages").length > 0) {
        refreshMessages(messageId);
        scrollBottom();
    }

    $('#profile-upload').on('change', function () {
        if (typeof (FileReader) != "undefined") {
            var image_holder = $("#upload-image");
            var reader = new FileReader();
            reader.onload = function (e) {
                image_holder.attr('src', e.target.result);
            };
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
    });

    // var messageId = $('#messages-id').val();


});

xhrPool = [];

function search(term) {
    $.get({
        url: '/interests/search',
        data: {term: term},
        success: function (data) {
            $('#results').html(data);
        }
    });
}

var interval = 10000;

function refreshMessages(messageId) {

    $.get({
        url: '/messages/instant-messages/' + messageId,
        beforeSend: function (jqXHR, settings) {
            xhrPool.push(jqXHR);
        },
        success: function (data) {
            $('#messages').html(data);
        },
        complete: function () {
            // Schedule the next
            setTimeout(refreshMessages(messageId), interval);
            messageNotifications();
        }
    });

}

function refreshInterests() {

    $.get({
        url: '/users/refresh-interests/',
        beforeSend: function (jqXHR, settings) {
            xhrPool.push(jqXHR);
        },
        success: function (data) {
            $('#interests-list').html(data);
        },
        complete: function () {
            // Schedule the next
            var ids = [];
            $('.current-interests-list').each(function () {
                var id = $(this).data('id');
                ids.push(id);
            });
            console.log(ids);
            $(ids).each(function (i, val) {
                console.log(val);
                $('#selected-form').append('<input type="hidden" id="' + val + '" name="interests[_ids][]" value="' + val + '">');
            })
        }
    });
}

function connectionMessages(messageId) {
    var connectionRequests = $.get({
        url: '/messages/connection-messages/' + messageId,
        beforeSend: function (jqXHR, settings) {
            xhrPool.push(jqXHR);
        },
        success: function (data) {
            $('#messages' + messageId).html(data);
        },
        complete: function () {
            // Schedule the next
            setTimeout(connectionMessages(messageId), interval);
            messageNotifications();
        }
    });
}

function addInterests() {
    console.log('button click');
    var newInterest = $('#search').val();
    $.get({
        url: '/interests/add',
        data: {name: newInterest},
        success: function (data) {
            console.log(data);
        }
    });
}

function scrollBottom() {
    var messages = $('#messages');
    console.log(messages);
    var height = messages[0].scrollHeight;
    messages.scrollTop(height);
    $('#messages').animate({scrollTop: 15000}, 'fast');
}

function getWindowSize() {
    var width = $(document).width();
    var height = $('.container').height() / 4;
    console.log('width: ' + width + ' height: ' + height);
    $('.related-container').css({'width': width, 'height': height})
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
        beforeSend: function (jqXHR, settings) {
            xhrPool.push(jqXHR);
        },
        success: function (data) {
            $('#notifications').html(data);
        }
        // complete: function () {
        //     // Schedule the next
        //     // setTimeout(messageNotifications, interval);
        // }
    })
}

function getLocation(output) {

    var geocoder = new google.maps.Geocoder;

    // Try HTML5 geolocation.
    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var pos = lat + ',' + lng;
            geocodeLatLng(geocoder, lat, lng, output);
            $('#location-coords').val(pos).trigger('change');
        })
    } else {
        // Browser doesn't support Geolocation
        alert('get a better broswer. we can\'t find you')
    }
}


function geocodeLatLng(geocoder, lat, lng, output) {
    var latlng = {lat: lat, lng: lng};
    console.log(latlng);
    geocoder.geocode({'location': latlng}, function (results, status) {
        if (status === 'OK') {
            if (results[0]) {
                $(output).html(results[0].address_components[3].long_name);
                $(output).val(results[0].address_components[3].long_name);
            } else {
                window.alert('No results found');
            }
        } else {
        }
    });
}

function geocodeTown(address) {
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'address': address}, function (results, status) {
        if (status === 'OK') {
            //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
            var lat = results[0].geometry.location.lat();
            var lng = results[0].geometry.location.lng();
            $('#location-coords').val(lat + ',' + lng);
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}