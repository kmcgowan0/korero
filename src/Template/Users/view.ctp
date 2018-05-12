<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="container">
    <div class="large-3 medium-4 columns">
        <?php if ($user->upload) :
            $profile_img = $user->upload;
        else :
            $profile_img = 'placeholder.png';
        endif; ?>
        <div class="profile-picture-large profile-picture"
             style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>
    </div>
    <div class="users view large-9 medium-8 columns content">
        <h3><?= h($user->firstname) ?>, <?= $user_age ?></h3>
        <?php if ($allowed_user && $my_profile == false) { ?>
            <p><span id="my-location"></span> (<?= $distance ?> miles from you)</p>
            <?php if (!empty($user->interests)):
                $interests_string = array();
                foreach ($mutual_interest_array as $interests):
                    array_push($interests_string, $interests);
                endforeach; ?>
                <div class="related">
                    <h4>You both like <?php echo implode(", ", $interests_string); ?></h4>
                    <h6><?= $this->Html->link(__('Message ' . $user->firstname), ['controller' => 'Messages', 'action' => 'view', $user->id]) ?></h6>
                </div>
            <?php endif; ?>
        <?php } ?>
        <?php if ($my_profile == true) { ?>
            <h6><?= $this->Html->link(__('Reset Password'), ['action' => 'password-reset', $user->id]) ?></h6>
            <h6><?= $this->Html->link(__('Edit Account'), ['action' => 'edit', $user->id]) ?></h6>
            <h6><?= $this->Html->link(__('Edit Interests'), ['action' => 'edit-interests', $user->id]) ?></h6>
            <h6><?= $this->Html->link(__('Edit Profile picture'), ['action' => 'edit-profile-picture', $user->id]) ?></h6>
            <h6><?= $this->Html->link(__('Remove Profile picture'), ['action' => 'remove-profile-picture']) ?></h6>
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
                    <th scope="row"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
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
        geocodeLatLng(geocoder, lat_connections, lng_connections);
    });
</script>