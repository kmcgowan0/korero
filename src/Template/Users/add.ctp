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
        echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1920, 'maxYear' => date('Y')]);
        echo $this->Form->control('location', ['type' => 'text', 'id' => 'location-coords']);
        ?>
        <label for="my-location-input">
            <input id="my-location-input" type="text" name="input">
        </label>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    <p id="my-location"></p>
    <div class="large-9 medium-8 columns">
        <button class="location-button">Find my location</button>

    </div>
</div>
