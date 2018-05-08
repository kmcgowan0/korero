<!--//get profile picture-->
<?php if ($user->upload) :
    $profile_img = $user->upload;
else :
    $profile_img = 'placeholder.png';
endif; ?>
<div class="main-user profile-picture" id="user-<?php echo $user->id; ?>"
     style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>
<div class="row">

<div class="radius-form columns small-12 medium-8">
    <p>Currently showing users within <?php echo $user->radius; ?> miles of you.</p>
    <?= $this->Form->create($user) ?>
    <?= $this->Form->control('radius', ['label' => false, 'placeholder' => 'Update radius', 'class' => 'radius-input']) ?>
    <?= $this->Form->button(__('Update radius')) ?>
    <?= $this->Form->end() ?>
</div>

        <div class="radius-form columns small-12 medium-4">
            <div class="location">
                <p>We think you're in <span id="my-location"></span>. If we're wrong, please <a href="/users/edit/<?php echo $user->id; ?>">update
                        your location here</a></p>
            </div>
        </div>
</div>

<?php if (count($users_in_radius)) :
    //related users var to pass to js
    $related_users_var = array();
//start position for rotating related users
    $position = 0; ?>

    <div class="related-container">

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
//profile picture
                if ($related_user->upload) :
                    $related_profile_img = $related_user->upload;
                else :
                    $related_profile_img = 'placeholder.png';
                endif; ?>
                <!--                //link to click to show modal-->
                <a href="#" data-open="modal-<?php echo $related_user->id; ?>"
                   data-id="<?php echo $related_user->id; ?>" id="user-<?php echo $related_user->id; ?>"
                   class="reveal-link">
                    <div class="related-user profile-picture" id="related-user-<?php echo $related_user->id; ?>"
                         style="border: solid #000 2px; background-image: url(/img/<?php echo $related_profile_img; ?>); transform: rotate(<?php echo $position; ?>deg) translate(15em) rotate(-<?php echo $position; ?>deg);">
                        <p><?= h($related_user->firstname) ?></p>
                    </div>
                </a>

                <!--                modal what which is revealed-->
                <div class="reveal" id="modal-<?php echo $related_user->id; ?>" data-reveal>
                    <div class="profile-info row">
                        <a href="/users/view/<?php echo $related_user->id; ?>">
                            <div class="profile-picture-small profile-picture small-2 columns"
                                 style="background-image: url(/img/<?php echo $related_profile_img; ?>)"></div>
                        </a>
                        <div class="small-10 medium-8 columns">
                            <a href="/users/view/<?php echo $related_user->id; ?>">
                                <h4><?= h($related_user->firstname) ?></h4>
                            </a>
                            <p>You both like <?php echo implode(", ", $related_interest_str); ?></p>
                        </div>
                        <div class="small-10 medium-2 columns">
                            <?= $this->Html->link(__('Full Conversation'), ['controller' => 'messages', 'action' => 'view', $related_user->id]) ?>
                        </div>
                    </div>

                    <div id="messages<?php echo $related_user->id; ?>"></div>
                    <div class="messages-in-view">
                        <?= $this->Form->create($message, ['data-id' => $related_user->id, 'class' => 'message-form' . $related_user->id]) ?>
                        <fieldset>
                            <?php
                            echo $this->Form->input('body', ['type' => 'text', 'label' => false, 'id' => 'message-body' . $related_user->id]);
                            echo $this->Form->hidden('recipient', ['value' => $related_user->id, 'id' => 'message-recipient']);
                            ?>
                        </fieldset>
                        <?= $this->Form->button(__('Send', ['type' => 'button'])) ?>
                        <?= $this->Form->end() ?>
                    </div>
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


            <?php endif;
            $position = $position + $space_allocated;
        endforeach; ?>

    </div>
    <div id="canvas"></div>
    <script>
        var relatedUsers = <?php echo json_encode($related_users_var); ?>;
        var x = document.getElementById("demo");
        console.log(x);

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
                console.log(navigator.geolocation.getCurrentPosition(showPosition));
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            x.innerHTML = "Latitude: " + position.coords.latitude +
                "<br>Longitude: " + position.coords.longitude;
            console.log(position);
        }
    </script>

<?php else : ?>
    <div class="container">
        <p>You have no mutual interests with anyone around you.</p>
        <p>Try adding some, or updating your radius.</p>
    </div>
<?php endif; ?>

