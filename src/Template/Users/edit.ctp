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
        <?php
        echo '<label for="upload">Profile picture</label>';
        echo $this->Form->control('upload', array('type' => 'file'));
        ?>
    </fieldset>


    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?php else : ?>

<p>Not allowed bro</p>

<?php endif; ?>
<div class="large-9 medium-8 columns">

<button class="location-button">Get my location</button>

</div>