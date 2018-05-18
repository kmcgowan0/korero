<?php if ($user->upload) {
    $profile_img = $user->upload;
} else {
    $profile_img = 'placeholder.png';
} ?>

<div class="container <?php echo $user->theme; ?>-theme">
    <div class="row">

    </div>
    <div class="row">
        <div class="radius-form columns small-12 medium-8">
            <h4>People who like <?php echo $term; ?></h4>

        </div>
        <div class="radius-form columns small-12 medium-4">
            <p>Currently showing users within <?php echo $user->radius; ?> miles of you.</p>
            <?= $this->Form->create($user) ?>
            <?= $this->Form->control('radius', ['label' => false, 'placeholder' => 'Update radius', 'class' => 'radius-input']) ?>
            <?= $this->Form->button(__('Update radius')) ?>
            <?= $this->Form->end() ?>
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
                        foreach ($matching_datum as $a_match) {
                            if ($a_match['_matchingData']['UsersInterests']->user_id == $related_user->id) {

                                array_push($related_interests, $a_match['_matchingData']['Interests']);
                            }
                        }

                    }

                    foreach ($search_result as $result) {
                        if (!in_array($result, $related_interests)) {
                            array_push($related_interests, $result);
                        }
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
//profile picture
                    if ($related_user->upload) :
                        $related_profile_img = $related_user->upload;
                    else :
                        $related_profile_img = 'placeholder.png';
                    endif;

                    $distance = $related_user->distance;
                    if ($distance < 2) {
                        $distance_from_center = 5.5;
                    } elseif ($distance < 5) {
                        $distance_from_center = 10;
                    } elseif ($distance < 50) {
                        $distance_from_center = 11;
                    } elseif ($distance < 100) {
                        $distance_from_center = 12;
                    } elseif ($distance > 1000) {
                        $distance_from_center = 14;
                    } elseif ($distance <= 1000) {
                        $distance_from_center = 16;
                    }

                    ?>
                    <!--                //link to click to show modal-->
                    <a href="#" data-open="modal-<?php echo $related_user->id; ?>"
                       data-id="<?php echo $related_user->id; ?>" id="user-<?php echo $related_user->id; ?>"
                       class="reveal-link">
                        <div class="related-user profile-picture" id="related-user-<?php echo $related_user->id; ?>"
                             style="border: solid #000 2px; background-image: url(/img/<?php echo $related_profile_img; ?>); transform: rotate(<?php echo $position; ?>deg) translate(<?php echo $distance_from_center; ?>em) rotate(-<?php echo $position; ?>deg);">
                        </div>
                        <div class="hover-overlay" id="related-user-<?php echo $related_user->id; ?>"
                             style="transform: rotate(<?php echo $position; ?>deg) translate(<?php echo $distance_from_center; ?>em) rotate(-<?php echo $position; ?>deg);">
                            <p><?= h($related_user->firstname) ?></p>

                        </div>
                    </a>

                    <!--                modal what which is revealed-->
                    <div class="reveal" id="modal-<?php echo $related_user->id; ?>" data-reveal>
                        <div class="row search-profile">
                            <a href="/users/view/<?php echo $related_user->id; ?>"
                               class="small-12 columns align-center">
                                <div class="profile-picture-medium profile-picture"
                                     style="background-image: url(/img/<?php echo $related_profile_img; ?>)"></div>
                            </a>
                            <div class="small-12 columns">
                                <a href="/users/view/<?php echo $related_user->id; ?>">
                                    <h4><?= h($related_user->firstname) ?>, <?= h($related_user->age) ?></h4>
                                </a>
                                <?php if (round($related_user->distance) == 0) {
                                    $distance = 'less than 1';
                                } else {
                                    $distance = round($related_user->distance);
                                } ?>
                                <p>Town<br>(<?php echo $distance; ?> miles from you)</p>
                                <p><?= h($related_user->firstname) ?>
                                    likes <?php echo implode(", ", $related_interest_str); ?></p>
                            </div>

                        </div>
                    <div class="row">
                        <div class="small-12 text-center">
                            <?php if ($related_user->accept_messages == 1 && count($related_interest_str) == 1) { ?>
                                <p>You can message <?php echo $related_user->firstname; ?>, but doing so will allow them to message you.</p>
                                <?= $this->Html->link(__('Message ' . $related_user->firstname), ['controller' => 'Messages', 'action' => 'view', $related_user->id], ['class' => 'button']) ?>
                            <?php } else if (count($related_interest_str) > 1) { ?>
                                <?= $this->Html->link(__('Message ' . $related_user->firstname), ['controller' => 'Messages', 'action' => 'view', $related_user->id], ['class' => 'button']) ?>
                            <?php } ?>
                        </div>
                    </div>

                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                <?php endif;
                $position = $position + $space_allocated;
            endforeach; ?>


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

    <div class="row">
        <?php if ($user->theme == 'dark') {
            $other = 'light';
        } else {
            $other = 'dark';
        } ?>
        <div class="radius-form columns small-12 medium-8">
            <a href="/users/switch-theme">Switch to <?php echo $other; ?> theme</a>
        </div>

        <div class="radius-form columns small-12 medium-4">
            <div class="location">
                <p>We think you're in <span id="my-location"></span>. If we're wrong, please <a
                            href="/users/edit/<?php echo $user->id; ?>">update
                        your location here</a></p>
            </div>
        </div>
    </div>
</div>
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