<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form medium-7 medium-offset-2 columns content">
    <?= $this->Form->create($user, ['id' => 'add-user-form']) ?>
    <fieldset>
        <legend><?= __('Create an Account') ?></legend>
        <?php
        echo $this->Form->control('firstname', ['label' => 'First Name']);
        echo $this->Form->control('lastname', ['label' => 'Last Name']);
        echo $this->Form->control('email');
        echo $this->Form->control('password');
        echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1920, 'maxYear' => date('Y')]);
        echo $this->Form->hidden('location', ['type' => 'text', 'id' => 'location-coords']); ?>
        <?php
        echo $this->Form->control('coded_location', ['type' => 'text', 'id' => 'my-location', 'label' => 'Location']);
        ?>
        <button class="location-button" type="button">Get my location</button>

    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>
