<?php $this->assign('title', 'Create an account'); ?>
<div class="container login-container dark-theme">
    <div class="row">
        <div class="columns small-12 medium-6 medium-offset-3 text-center large-4 large-offset-4">
            <img src="/img/logo-home.png" class="logo-home">
            <p class="intro-text">
                Korero means conversation. Connect and talk to new people in your area based on similar
                interests. </p>
        </div>
    </div>
    <div class="row">
        <div class="users form columns small-12 medium-6 medium-offset-3 large-4 large-offset-4">
            <?= $this->Form->create($user, ['id' => 'add-user-form']) ?>
            <fieldset>
                <?php
                echo $this->Form->control('firstname', ['label' => false, 'placeholder' => 'First name']);
                echo $this->Form->control('lastname', ['label' => false, 'placeholder' => 'Last name']);
                echo $this->Form->control('email', ['label' => false, 'placeholder' => 'Email']);
                echo $this->Form->control('password', ['label' => false, 'placeholder' => 'Password']);
                echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1920, 'maxYear' => date('Y'), 'label' => 'Date of birth', 'class' => 'dob']);
                echo $this->Form->hidden('location', ['type' => 'text', 'id' => 'location-coords']); ?>
                <button class="location-button" type="button">Get my location</button>
                <h6 class="hidden">Current location: <span class="my-coded-location"></span></h6>
                <?php
                echo $this->Form->hidden('coded_location', ['type' => 'text', 'class' => 'my-coded-location']);
                ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
