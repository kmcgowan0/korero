<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form medium-7 medium-offset-2 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Create an Account') ?></legend>
        <?php
        echo $this->Form->control('firstname', ['label' => 'First Name']);
        echo $this->Form->control('lastname', ['label' => 'Last Name']);
            echo $this->Form->control('email');
        echo $this->Form->control('password');
        echo $this->Form->hidden('location', ['type' => 'text', 'id' => 'location-coords']);

        echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1920, 'maxYear' => date('Y')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    <p id="my-location"></p>
    <div class="large-9 medium-8 columns">

        <button class="location-button">Get my location</button>

    </div>
</div>
