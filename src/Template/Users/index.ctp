<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="users index large-9 medium-8 columns content full-user-list">
    <h3><?= __('Users') ?></h3>

    <p>Sort by: <?= $this->Html->link(__('Distance from me'), ['distance'], ['class' => 'red-button']) ?>
        <?= $this->Html->link(__('Number of mutual interests'), ['interests'], ['class' => 'red-button']) ?></p>
    <table cellpadding="0" cellspacing="0" id="user-table">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Name</th>
            <th scope="col">Location</th>
            <th scope="col">You both like</th>
            <th scope="col" class="actions"></th>
        </tr>
        </thead>
        <tbody id="all-users">

        <?php
        $user_locations = array();
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

                ?>
                <tr class="data-item">
                    <td class="thin-column"><?php if ($user->upload) :
                            $profile_img = $user->upload;
                        else :
                            $profile_img = 'placeholder.png';
                        endif; ?>
                        <div class="profile-picture-small profile-picture"
                             style="background-image: url(/img/<?php echo $profile_img; ?>)">

                        </div>
                    </td>
                    <td><?= h($user->firstname) ?>, <?= h($user->age) ?><br>
                        <?php if ($user->blocked == true) { ?>
                            <span class="blocked">(Blocked)</span><br>
                            <?= $this->Html->link(__('Unblock'), ['action' => 'unblock-user', $user->id]) ?>
                        <?php } elseif ($user->hidden == true) { ?>
                            <span class="blocked">(Hidden from connections view)</span><br>
                            <?= $this->Html->link(__('Unhide'), ['action' => 'unhide-user', $user->id]) ?>
                        <?php } ?></td>
                    <td><?= h($user->coded_location) ?></td>
                    <td><?php echo implode(", ", $related_interest_str); ?>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(__('View profile'), ['action' => 'view', $user->id]) ?>
                        <br>
                        <?= $this->Html->link(__('Send a message'), ['controller' => 'messages', 'action' => 'view', $user->id]) ?>
                    </td>
                </tr>
            <?php } ?>

        <?php
        endforeach; ?>
        </tbody>
    </table>
    <button id="load-more" type="button" class="red-button">Load more</button>
</div>
<script>
    var userLocations = <?php echo json_encode($user_locations); ?>;

</script>
