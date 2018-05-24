<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->assign('title', 'All Users');
?>
<div class="users index small-12 columns content full-user-list">

    <div class="row">
        <h3><?= __('Users') ?></h3>

        <p>Sort by: <?= $this->Html->link(__('Distance from me'), ['distance'], ['class' => 'red-button']) ?>
            <?= $this->Html->link(__('Number of mutual interests'), ['interests'], ['class' => 'red-button']) ?></p>
    </div>
    <div class="user-list-container">
    <?php
    $user_locations = array();
    $count = 0;
    foreach ($distinct_user_array as $user):

        if ($user->id != $authUser['id']) {

            $user_locations[$user->id] = $user->location;

            $related_interests = [];

            foreach ($user_matching_data as $matching_datum) {
                //if the matching data matches the id of the related user
                //add that interest to an array for this user
                if ($matching_datum['UsersInterests']->user_id == $user->id) {

                    array_push($related_interests, $matching_datum['Interests']);
                }
            }

            $related_users_var[$user->id] = $related_interests;
            //add each related interest to a string
            $related_interest_str = array();
            foreach ($related_interests as $related_interest) {
                $related_interest_str[] = $related_interest->name;
            }
            $interest_count = count($related_interest_str);

            if ($count % 2 == 0) {
                $background = 'grey';
            } else {
                $background = 'white';
            }
            ?>
            <div class="single-user row <?php echo $background; ?>-area">
                <div class="small-12 medium-2 columns">
                    <?php if ($user->upload) :
                        $profile_img = $user->upload;
                    else :
                        $profile_img = 'placeholder.png';
                    endif; ?>
                    <div class="profile-picture profile-picture-medium hide-for-medium-only"
                         style="background-image: url(/img/<?php echo $profile_img; ?>);"></div>
                    <div class="profile-picture profile-picture-extra-medium show-for-medium-only"
                         style="background-image: url(/img/<?php echo $profile_img; ?>);"></div>
                </div>
                <div class="small-12 medium-10 columns user-detail">
                    <h4><?= h($user->firstname) ?>, <?= h($user->age) ?></h4>
                    <p><?= h($user->coded_location) ?></p>
                    <?php if ($user->blocked == true) { ?>
                        <span class="blocked">(Blocked)</span><br>
                        <?= $this->Html->link(__('Unblock'), ['action' => 'unblock-user', $user->id]) ?>
                    <?php } elseif ($user->hidden == true) { ?>
                        <span class="blocked">(Hidden from connections view)</span><br>
                        <?= $this->Html->link(__('Unhide'), ['action' => 'unhide-user', $user->id]) ?>
                    <?php } ?>
                    <p class="margin-bottom">You both like: <?php echo implode(", ", $related_interest_str); ?></p>
                    <a href="#" class="red-button">View profile</a>
                    <a href="#" class="red-button">Send message</a>
                </div>
            </div>
        <?php
            $count++; } ?>

        <?php

    endforeach; ?>
    </div>
    <div class="row">
        <button id="load-more" type="button" class="red-button">Load more</button>
    </div>
</div>
<script>
    var userLocations = <?php echo json_encode($user_locations); ?>;

</script>
