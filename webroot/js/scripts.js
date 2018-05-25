$(document).ready(function () {

    bindFunc();

    getWindowSize();

    messageNotifications();

    if ($("div.connections").length > 0) {
        connectionMessageNotifications();
    }

    if ($("div.full-user-list").length > 0) {
        var size_li = $(".full-user-list .single-user").length;
        var x = 10;
        $('.full-user-list .single-user:lt(' + x + ')').show();
        $('#load-more').click(function () {
            x = (x + 20 <= size_li) ? x + 5 : size_li;
            $('.full-user-list .single-user:lt(' + x + ')').show();
            if (x == size_li) {
                $('#load-more').hide();
            }
        });
    }

    refreshInterests();

    $('#search').on('keyup', function () {
        var term = $(this).val();
        search(term);
    });

    $('#results').on('click', '.selectable', function () {
        var name = $(this).text();
        var id = $(this).data('id');
        var added = [];
        $("#selected-form input").each(function () {
            added.push(parseFloat($(this).val()));
        });
        if (jQuery.inArray(id, added) == -1) {
            $('#selected').append('<div class="columns small-12 medium-6 large-4 selected-items">' +
                '<a class="remove" id="' + id + '">x</a> <p class="new-interest">' + name + '</p>' +
                '</div>' +
                '');
            $('#selected-form').append('<input type="hidden" id="' + id + '" class="' + name + '" name="interests[_ids][]" value="' + id + '">');
        }
        bindFunc();
    });

    $('#new-interest-form').submit(function (event) {
        event.preventDefault();
        var name = $('#search').val();
        var $form = $(this),
            url = $form.attr('action');
        var posting = $.post(url, {name: name});
        posting.done(function (data) {
            $("#new-interest-form")[0].reset();
            refreshInterests();
            $('#selected').append('<div class="columns small-12 medium-6 large-4 selected-items">' +
                '<a class="remove">x</a> <p class="new-interest">' + name + '</p>' +
                '</div>' +
                '');
        });


    });

    var changeArray = [];
    changeArray['old'] = 0;
    changeArray['new'] = 0;

    $('#notifications').bind("DOMSubtreeModified", function () {
        var notifications = $('#notifications').html();
        console.log(parseFloat(notifications));
        changeArray['new'] = notifications;
        if (changeArray['new'] > changeArray['old']) {
            notifyMe();
            changeArray['old'] = changeArray['new'];
        }
    });


    $('.reveal-link').on('click', function () {
        var liveMessageId = $(this).data('id');
        markRead(liveMessageId);

        scrollBottom();
        connectionMessages(liveMessageId);
        $('#message-body' + liveMessageId).focus(function () {
            markRead(liveMessageId);
        });
        $('#related-user-' + liveMessageId).css('border', '2px solid #000');
        $('#notifications-' + liveMessageId).html('');
        $('#message-form' + liveMessageId).submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                url = $form.attr('action');
            var body = $('#message-body' + liveMessageId).val();
            if (body != '') {
                var posting = $.post(url, {body: body, recipient: liveMessageId});
                $(".message-form" + liveMessageId)[0].reset();
                posting.done(function (data) {
                    $(".message-form" + liveMessageId)[0].reset();
                    scrollBottom();
                });
            }
        });
        changeArray['old'] = parseFloat($('#notifications').html()) || 0;

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
        if (typeof (FileReader) != "undefined") {
            var image_holder = $(".profile-preview");
            var reader = new FileReader();
            reader.onload = function (e) {
                image_holder.css({'background-image': 'url(' + e.target.result + ')'})
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
                scrollBottom();
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

    Notification.requestPermission().then(function (result) {
    });

});

function notifyMe() {

    var title = "You have new messages!";
    var options = {
        icon: "/img/icon.png"
    }

    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
    }

    // Let's check whether notification permissions have already been granted
    else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification(title, options);
    }

    // Otherwise, we need to ask the user for permission
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
                var notification = new Notification(title, options);
            }
        });
    }
    setTimeout(notification.close.bind(notification), 4000);

    // Finally, if the user has denied notifications and you
    // want to be respectful there is no need to bother them any more.
}


function search(term) {
    $.get({
        url: '/interests/search',
        data: {term: term},
        success: function (data) {
            $('#results').html(data);
        }
    });
}

var interval = 100000;

function refreshMessages(messageId) {

    $.get({
        url: '/messages/instant-messages/' + messageId,
        success: function (data) {
            $('#messages').html(data);
        },
        complete: function () {
            // Schedule the next
            setTimeout(refreshMessages(messageId), 100000);
        }
    });

}

function refreshInterests() {

    $.get({
        url: '/users/refresh-interests/',
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
            $(ids).each(function (i, val) {
                $('#selected-form').append('<input type="hidden" id="' + val + '" name="interests[_ids][]" value="' + val + '">');
            })
        }
    });

}

function formChanged(form) {
    form.find('input[type="text"]').each(function (elem) {
        if (elem.defaultValue != elem.value) {
            return true;
        }
    });
    return false;
}

function connectionMessages(messageId) {
    var connectionRequests = $.get({
        url: '/messages/connection-messages/' + messageId,
        success: function (data) {
            $('#messages' + messageId).html(data);
        },
        complete: function () {
            // Schedule the next
            setTimeout(connectionMessages(messageId), 15000);
        }
    });
}

function scrollBottom() {
    var messages = $('.messages-list');
    var height = messages[0].scrollHeight;
    messages.scrollTop(height);
    $('.messages-list').animate({scrollTop: 15000}, 'fast');
}

function getWindowSize() {
    var width = $(document).width();
    var height = $('.container').height() / 4;
    $('.related-container').css({'width': width, 'height': height})
}

function bindFunc() {
    $('.remove').click(function () {
        var id = $(this).attr('id');
        $(this).parent().remove();
        $('#' + id).remove();
        var hideInterest = '[data-id=' + id + ']';
        $(hideInterest).css('display', 'none');
    });
}

function connectionMessageNotifications() {
    $.get({
        url: '/messages/messages-notifications/',
        success: function (data) {
            $('.unread-messages-script').html(data);

            $.each(unreadMessages, function (i, value) {
                $('#related-user-' + i).css('border', 'solid #d33c44 4px');
                $('#notifications-' + i).html(value);
            });
            messageNotifications();

        },
        complete: function () {
            // Schedule the next
            setTimeout(connectionMessageNotifications, 5000);
        }
    });

}

function messageNotifications() {
    $.get({
        url: '/messages/unread-messages/',
        success: function (data) {
            $('#notifications').html(data);
        }
    })
}

function markRead(messageId) {
    $.get({
        url: '/messages/mark-read/' + messageId
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