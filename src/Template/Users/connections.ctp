<?php if ($user->upload) {
    $profile_img = $user->upload;
} else {
    $profile_img = 'placeholder.png';
}

?>

<div class="container <?php echo $user->theme; ?>-theme connections">
    <div class="row">

        <div class="radius-form columns small-12 medium-8">
            <p>Currently showing users within <?php echo $user->radius; ?> miles of you.</p>
            <?= $this->Form->create($user) ?>
            <?= $this->Form->control('radius', ['label' => false, 'placeholder' => 'Update radius', 'class' => 'radius-input']) ?>
            <?= $this->Form->button(__('Update radius')) ?>
            <?= $this->Form->end() ?>
        </div>

        <div class="radius-form columns small-12 medium-4">

        </div>
    </div>

    <div class="related-container">
        <a href="/users/view/<?php echo $user->id; ?>">
            <div class="main-user profile-picture" id="user-<?php echo $user->id; ?>"
                 style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>
        </a>
        <?php if (count($users_in_radius)) :
            //related users var to pass to js
            $related_users_var = array();
//start position for rotating related users
            $position = 0; ?>


            <?php
        foreach ($users_in_radius as $related_user):
            //if the related user isn't the current user
        if ($related_user->id != $user->id) :
            ?>

            <?php
            $related_interests = [];

            foreach ($user_matching_data as $matching_datum) {
                //if the matching data matches the id of the related user
                //add that interest to an array for this user
                if ($matching_datum['UsersInterests']->user_id == $related_user->id) {
                    array_push($related_interests, $matching_datum['Interests']);
                } ?>
                <?php
            }

            //count number of related interests

            $interest_count = count($related_interests);

            //create associative array where user id: interest
            $related_users_var[$related_user->id] = $related_interests;
            //add each related interest to a string
            $related_interest_str = array();
            foreach ($related_interests as $related_interest) {
                $related_interest_str[] = $related_interest->name;
            }

            $notification_count = 0;
            foreach ($unread_messages as $unread_message) {
                if ($unread_message['sender'] == $related_user->id) {
                    $notification_count++;
                }
            }

            if ($notification_count > 0) {
                $border = 'solid #d33c44 4px';
            } else {
                $border = 'solid #000 2px';
            }

//profile picture
            if ($related_user->upload) :
                $related_profile_img = $related_user->upload;
            else :
                $related_profile_img = 'placeholder.png';
            endif; ?>
           <?php
            if ($width > 768) {
               $distance = $related_user->distance;

               if ($related_user->radius <= 5) {
                   if ($distance < 0.1) {
                       $distance_from_center = 9;
                   } elseif ($distance < 0.5) {
                       $distance_from_center = 10;
                   } elseif ($distance < 1) {
                       $distance_from_center = 11;
                   } elseif ($distance < 2) {
                       $distance_from_center = 12;
                   } elseif ($distance < 3) {
                       $distance_from_center = 14;
                   } elseif ($distance <= 5) {
                       $distance_from_center = 16;
                   }
               } else if ($related_user->radius <= 10) {
                   if ($distance < 0.5) {
                       $distance_from_center = 9;
                   } elseif ($distance < 1) {
                       $distance_from_center = 11;
                   } elseif ($distance < 2) {
                       $distance_from_center = 12;
                   } elseif ($distance < 5) {
                       $distance_from_center = 14;
                   } elseif ($distance > 7) {
                       $distance_from_center = 16;
                   }
               } else if ($related_user->radius < 500) {
                   if ($distance < 2) {
                       $distance_from_center = 8;
                   } elseif ($distance < 5) {
                       $distance_from_center = 10;
                   } elseif ($distance < 20) {
                       $distance_from_center = 11;
                   } elseif ($distance < 40) {
                       $distance_from_center = 12;
                   } elseif ($distance < 70) {
                       $distance_from_center = 14;
                   } elseif ($distance <= 100) {
                       $distance_from_center = 16;
                   }
               } else if ($related_user->radius >= 500) {
                   if ($distance < 2) {
                       $distance_from_center = 8;
                   } elseif ($distance < 10) {
                       $distance_from_center = 10;
                   } elseif ($distance < 50) {
                       $distance_from_center = 11;
                   } elseif ($distance < 100) {
                       $distance_from_center = 12;
                   } elseif ($distance < 1000) {
                       $distance_from_center = 14;
                   } elseif ($distance >= 1000) {
                       $distance_from_center = 16;
                   }
               }
           } else {
               $distance_from_center = 8;
           } ?>

            <!--                //link to click to show modal-->
            <a href="#" data-open="modal-<?php echo $related_user->id; ?>"
               data-id="<?php echo $related_user->id; ?>" id="user-<?php echo $related_user->id; ?>"
               class="reveal-link">
                <div class="related-user profile-picture" id="related-user-<?php echo $related_user->id; ?>"
                     style="border: <?php echo $border; ?>; background-image: url(/img/<?php echo $related_profile_img; ?>); transform: rotate(<?php echo $position; ?>deg) translate(<?php echo $distance_from_center; ?>em) rotate(-<?php echo $position; ?>deg);">

                </div>
                <div class="hover-overlay" id="related-user-<?php echo $related_user->id; ?>"
                     style="transform: rotate(<?php echo $position; ?>deg) translate(<?php echo $distance_from_center; ?>em) rotate(-<?php echo $position; ?>deg);">
                    <p><?= h($related_user->firstname) ?> <?php if ($notification_count > 0) { ?>
                            <span id="notifications-<?php echo $related_user->id; ?>"><?= $notification_count; ?></span>
                        <?php } ?></p>

                </div>
            </a>

            <!--                modal what which is revealed-->
            <div class="reveal" id="modal-<?php echo $related_user->id; ?>" data-reveal>
                <div class="profile-info row">
                    <a href="/users/view/<?php echo $related_user->id; ?>">
                        <div class="profile-picture-small profile-picture small-2 columns"
                             style="background-image: url(/img/<?php echo $related_profile_img; ?>)"></div>
                    </a>
                    <div class="small-10 medium-7 columns">
                        <a href="/users/view/<?php echo $related_user->id; ?>">
                            <h4><?= h($related_user->firstname) ?></h4>
                        </a>
                        <p>You both like <?php echo implode(", ", $related_interest_str); ?></p>
                    </div>
                    <div class="small-10 medium-3 columns">
                        <?= $this->Html->link(__('All Messages'), ['controller' => 'messages', 'action' => 'view', $related_user->id]) ?>
                        <?= $this->Html->link(__('Hide from connections'), ['action' => 'hide-user', $related_user->id]) ?>
                    </div>
                </div>

                <div id="messages<?php echo $related_user->id; ?>" class="messages-list"></div>
                <div class="messages-in-view">
                    <?= $this->Form->create($message, ['data-id' => $related_user->id, 'class' => 'message-form' . $related_user->id, 'id' => 'message-form' . $related_user->id, 'url' => ['action' => 'send-message']]) ?>
                    <fieldset>
                        <?php
                        echo $this->Form->input('body', ['type' => 'text', 'label' => false, 'id' => 'message-body' . $related_user->id, 'autocomplete' => 'off', 'value' => '']);
                        echo $this->Form->hidden('recipient', ['value' => $related_user->id, 'id' => 'message-recipient']);
                        ?>
                    </fieldset>
                    <?= $this->Form->button(__('Send')) ?>
                    <?= $this->Form->end() ?>
                </div>
                <button class="close-button" data-close aria-label="Close modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


        <?php endif;
        $position = $position + $space_allocated;
        endforeach; ?>

        <?php
        if ($list_of_users->count() > 10) { ?>
            <a href="/users" class="reveal-link">
                <div class="related-user profile-picture extra-user"
                     style="border: solid #000 2px; background: #000; transform: rotate(0deg) translate(25em) rotate(-0deg);">
                    <p class="see-all">See all</p>
                </div>
            </a>
        <?php } ?>

            <script>
                var relatedUsers = <?php echo json_encode($related_users_var); ?>;
            </script>

        <?php else : ?>
            <div class="container">
                <p>You have no mutual interests with anyone around you.</p>
                <p>Try adding some, or updating your radius.</p>
            </div>
        <?php endif; ?>
    </div>
    <div id="canvas"></div>

    <div class="row connections-options">
        <?php if ($user->theme == 'dark') {
            $other = 'light';
        } else {
            $other = 'dark';
        } ?>
        <div class="radius-form columns small-12 medium-6 large-3">
            <div class="location">
                <p class="small-text">We think you're in <span id="my-location"></span>. If we're wrong, please <a
                            href="/users/edit/<?php echo $user->id; ?>" class="underline-hover underline">update
                        your location here</a></p>
            </div>
        </div>

        <div class="radius-form columns small-12 medium-6 large-9 medium-text-right small-text-left">
            <a href="/users/switch-theme" class="small-text">Switch to <?php echo $other; ?> theme</a>

        </div>
    </div>
</div>
<div class="unread-messages-script"></div>
<?php list($lat, $long) = explode(',', $user->location); ?>
<script>
    var lat_connections = parseFloat(<?php echo json_encode($lat); ?>);
    var lng_connections = parseFloat(<?php echo json_encode($long); ?>);
    $(document).ready(function () {
        var geocoder = new google.maps.Geocoder;
        console.log('lat ' + lat_connections);
        console.log(lng_connections);
        geocodeLatLng(geocoder, lat_connections, lng_connections, '#my-location');
    });
</script>