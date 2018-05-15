$(document).ready(function () {

    bindFunc();
    
    getWindowSize();

    messageNotifications();

//     scrollBottom();

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
        bindFunc();
    });
    $('.reveal-link').on('click', function () {

        var liveMessageId = $(this).data('id');
        connectionMessages(liveMessageId);

        $('#related-user-'+liveMessageId).css('border', '2px solid #000');
        $('#notifications-'+liveMessageId).html('');
        messageNotifications();
        $('#message-form' + liveMessageId).submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                url = $form.attr('url');
            var body = $('#message-body' + liveMessageId).val();
            var posting = $.post(url, {body: body, recipient: liveMessageId});
            
            posting.done(function (data) {
                $(".message-form" + liveMessageId)[0].reset();
                connectionMessages(liveMessageId);
            });
        });
    });

    $('.location-button').on('click', function () {
	    
        $('.location-button').addClass('spinning');
        getLocation();

    });

    $('#profile-picture').on('change', function () {
        if (typeof (FileReader) != "undefined") {
            var image_holder = $(".profile-preview");
            var reader = new FileReader();
            reader.onload = function (e) {
                image_holder.attr('src', e.target.result);
            };
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
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
        scrollBottom();
    });

    $('#my-new-interest').on('click', function () {
        
        
        
    });

    $('#add-user-form').submit(function (event) {
        var form = this;
        event.preventDefault();
        if ($('#location-coords').val() == '') {
            var address = $('#my-location').val();
            geocodeTown(address);
        }
        form.submit();
    });

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
    refreshMessages(messageId);


});

function search(term) {
    $.get({
        url: '/interests/search',
        data: {term: term},
        success: function (data) {
            $('#results').html(data);
        }
    });
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
            messageNotifications();
        }
    });
}

function connectionMessages(messageId) {
    $.get({
        url: '/messages/connection-messages/' + messageId,
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
    var messages    = $('#messages');
    console.log(messages);
    var height = messages[0].scrollHeight;
    messages.scrollTop(height);
    $('#messages').animate({scrollTop: 15000},'fast');
}

function getWindowSize() {
    var width = $(document).width();
    var height = $('.container').height() / 2;
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
       
        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var pos = lat + ',' + lng;
            geocodeLatLng(geocoder, lat, lng, '#my-location');
            $('#location-coords').val(pos);
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
            window.alert('Geocoder failed due to: ' + status);
        }
    });
}

function geocodeTown(address) {
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode( { 'address' : address }, function( results, status ) {
        if( status == google.maps.GeocoderStatus.OK ) {
            //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
            var lat = results[0].geometry.location.lat();
            var lng = results[0].geometry.location.lng();
            $('#location-coords').val(lat+','+lng);
        } else {
            alert( 'Geocode was not successful for the following reason: ' + status );
        }
    } );
}