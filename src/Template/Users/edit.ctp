<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php if ($my_profile) : ?>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user, ['enctype'=>'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
        echo $this->Form->control('email');
        echo $this->Form->control('firstname');
        echo $this->Form->control('lastname');
        echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1950, 'maxYear' => date('Y')]);
        echo $this->Form->hidden('location', ['type' => 'text', 'id' => 'location-coords']); ?>
        <button class="location-button" type="button">Get my location</button>
        <?php
        echo '<label for="upload">Profile picture</label>';
        echo $this->Form->control('upload', ['type' => 'file', 'value' => $user['upload'], 'id' => 'profile-picture']);
        ?>
        <button id="remove-profile-image" type="button">Remove profile picture</button>
        <?php if ($user->upload) :
            $profile_img = $user->upload;
        else :
            $profile_img = 'placeholder.png';
        endif; ?>
        <div class="profile-picture-large profile-picture"
             style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>
    </fieldset>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?php else : ?>

<p>Not allowed bro</p>

<?php endif; ?>
<p id="my-location"></p>