<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="container view-profile">
    <div class="large-3 medium-4 columns">
        <?php if ($user->upload) :
            $profile_img = $user->upload;
        else :
            $profile_img = 'placeholder.png';
        endif; ?>
        <?php if ($distance < 1) {
            $distance_string = 'less than 1 mile';
        } else if ($distance == 1) {
            $distance_string = '1 mile';
        } else {
            $distance_string = $distance . ' miles';
        } ?>
        <div class="profile-picture-large profile-picture"
             style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>
    </div>
    <div class="users view large-9 medium-8 columns content">
        <h3><?= h($user->firstname) ?>, <?= $user_age ?></h3>
        <!--        If the user has been blocked by the logged in user-->
        <?php if ($blocked_user) { ?>
            <p>You have blocked <?= h($user->firstname) ?>. This means you can't' see each other's profiles, and you can
                no longer message each other.</p>
            <h6> <?= $this->Form->postLink(__('Unblock this user'), ['action' => 'unblock-user', $user->id]) ?></h6> <?php
            //if the user has blocked the logged in user
        } else if ($blocked_by) { ?>
            <p>You have blocked by <?= h($user->firstname) ?>. This means you can't' see each other's profiles, and you
                can no longer message each other.</p>
        <?php } else { ?>
            <?php if ($allowed_user && $my_profile == false) { ?>
                <p><span id="my-location"></span> (<?= $distance_string ?> from you)</p>
                <?php if (!empty($user->interests)):
                    $interests_string = array();
                    foreach ($mutual_interest_array as $interests):
                        array_push($interests_string, $interests);
                    endforeach; ?>
                    <div class="related">
                        <h4>You both like <?php echo implode(", ", $interests_string); ?></h4>
                        <h6><?= $this->Html->link(__('Message ' . $user->firstname), ['controller' => 'Messages', 'action' => 'view', $user->id]) ?></h6>
                        <?php if ($hidden_user) { ?>
                            <p><?php echo $user->firstname; ?> is currently hidden on the connections page.</p>
                            <h6><?= $this->Html->link(__('Show ' . $user->firstname . ' on connections page '), ['action' => 'unhide-user', $user->id]) ?></h6>
                        <?php } else { ?>
                            <h6><?= $this->Html->link(__('Hide ' . $user->firstname . ' from connections page '), ['action' => 'hide-user', $user->id]) ?></h6>
                        <?php } ?>
                        <h6> <?= $this->Form->postLink(
                                __('Block this user'),
                                ['action' => 'block-user', $user->id],
                                ['confirm' => __('Are you sure? You will no longer be able to view each other\'s profiles or send each other messages')]
                            )
                            ?></h6>

                    </div>
                <?php endif; ?>
            <?php } else if ($my_profile == false) { ?>
                <p>You and <?= h($user->firstname) ?> have no mutual interests.</p>
                <?php if ($user->accept_messages == 1) { ?>
                    <p>You can still send <?= h($user->firstname) ?> messages.</p>
                    <h6><?= $this->Html->link(__('Message ' . $user->firstname), ['controller' => 'Messages', 'action' => 'view', $user->id]) ?></h6>
                <?php }
            }
            //give option to message if have no mututal interests
        }
        ?>
        <?php if ($my_profile == true) { ?>
            <ul class="profile-links">
                <li><?= $this->Html->link(__('Reset Password'), ['action' => 'password-reset', $user->id]) ?></li>
                <li><?= $this->Html->link(__('Edit Account'), ['action' => 'edit', $user->id]) ?></li>
                <li><?= $this->Html->link(__('Edit Interests'), ['action' => 'edit-interests', $user->id]) ?></li>
                <li><?= $this->Html->link(__('Edit Profile picture'), ['action' => 'edit-profile-picture', $user->id]) ?></li>
                <li><?= $this->Html->link(__('Remove Profile picture'), ['action' => 'remove-profile-picture']) ?></li>
            </ul>
            <table class="vertical-table">
                <tr>
                    <th scope="row"><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('First Name') ?></th>
                    <td><?= h($user->firstname) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Last Name') ?></th>
                    <td><?= h($user->lastname) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Age') ?></th>
                    <td><?= $user_age ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Location') ?></th>
                    <td><span id="my-location"></span></td>
                </tr>
            </table>
            <?php if (!empty($user->interests)): ?>
                <div class="related">
                    <h4><?= __('Interests') ?></h4>
                    <ul class="interests">
                        <?php foreach ($mutual_interest_array as $interests): ?>
                            <li><?= h($interests) ?></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            <?php else : ?>
                <?php if ($my_profile == true) { ?>
                    <div class="related">
                        <p>It doesn't look like you have any interests currently listed.</p>
                        <h6><?= $this->Html->link(__('Add some'), ['action' => 'edit-interests', $user->id]) ?></h6>

                    </div>
                <?php } ?>
            <?php endif; ?>
        <?php } ?>
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